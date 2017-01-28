<?php
namespace Travel\Service;

use Zend\Db\TableGateway\TableGateway;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class TravelManager
{
    /**
     * Limit travels on page
     * @var int
     */
    private $page_limit = 10;

    /**
     * @var Zend\Db\Adapter\Adapter
     */
    private $dbAdapter;

    private  $translator;

    private $uploadPath = '';

    /**
     * Constructs the service.
     */
    public function __construct($dbAdapter, $translator, $uploadPath)
    {
        $this->dbAdapter = $dbAdapter;
        $this->translator = $translator;
        $this->uploadPath = $uploadPath;
    }

    /**
     * Get pages of travels
     * @return float
     */
    public function getTravelsPages(){
        $select = "SELECT COUNT(*) as count FROM travels";
        $travels = $this->dbAdapter->query($select, 'execute')->current();

        $pages = ceil($travels['count'] / $this->page_limit);

        return (empty($pages)?1:$pages);
    }

    /**
     * Get travels list
     * @param int $page
     * @return null|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function getTravelsList($page = 1)
    {
        $res = new TableGateway('travels', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->join('travels_txt', 'travels_txt.travel_id = travels.travel_id', ['lang_id', 'title', 'subtitle', 'announce', 'text']);
        $select->join('user', 'user.id = travels.user_id', ['full_name', 'photo']);
        $select->join('lang', 'lang.lang_id = travels_txt.lang_id', []);
        $select->where(['travels.status' => 1]);
        $select->where(['lang.locale' => $_SESSION['locale']]);
        $select->limit($this->page_limit, $page);
        $travels = $res->selectWith($select);

        return $travels;
    }

    /**
     * Get travel by URL
     * @param string $url
     * @return null|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function getTravelByUrl($url = '')
    {
        $res = new TableGateway('travels', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->join('travels_txt', 'travels_txt.travel_id = travels.travel_id', ['lang_id', 'title', 'subtitle', 'announce', 'text']);
        $select->join('user', 'user.id = travels.user_id', ['full_name', 'photo']);
        $select->join('lang', 'lang.lang_id = travels_txt.lang_id', []);
        $select->where(['travels.url' => $url]);
        $select->where(['travels.status' => 1]);
        $select->where(['lang.locale' => $_SESSION['locale']]);
        $select->limit(1);
        $travel= $res->selectWith($select)->current();

        return $travel;
    }

    /**
     * Get travel
     * @param int $page
     * @return null|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function getTravelForEdit($travel_id = 0, $langs = [])
    {
        $res = new TableGateway('travels', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->join('user', 'user.id = travels.user_id', ['full_name', 'photo']);
        $select->where(['travels.travel_id' => $travel_id]);
        $select->limit(1);
        $travel = $res->selectWith($select)->toArray()[0];
        foreach ($langs as $lang){
            $travel['txt'][$lang['locale']] = $this->getTravelByIdAndLocale($travel['travel_id'], $lang['locale']);
        }

        return $travel;
    }

    public function getTravelByIdAndLocale($travel_id = 0, $locale = '')
    {
        $res = new TableGateway('travels_txt', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->join('lang', 'lang.lang_id = travels_txt.lang_id', []);
        $select->where(['travels_txt.travel_id' => $travel_id]);
        $select->where(['lang.locale' => $locale]);
        $select->limit(1);
        $travel = $res->selectWith($select)->toArray()[0];

        return $travel;
    }

    public function getLangs()
    {
        $res = new TableGateway('lang', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $langs = $res->selectWith($select)->toArray();

        return $langs;
    }

    public function getTravelImages($travel_id = 0){
        $images = [];

        $dir = $this->uploadPath.$travel_id.'/files';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                $col = 0;
                while (($file = readdir($dh)) !== false) {
                    if($file != '.' and $file != '..' and is_file($dir.'/'.$file)){
                        $images[] = $file;
                    }
                }
                closedir($dh);
            }
        }

        return $images;
    }

    public function updateTravel($travel, $data){
        $update_data = [
            'url' => $data['url'],
            'date' => date('Y-m-d', strtotime($data['date'])),
            'status' => $data['status'],
            'image' => ((isset($data['image']['name']) and $data['image']['name'] != '')?$data['image']['name']:$travel['image'])
        ];

        if (isset($data['image']['name']) and $data['image']['name'] != '') {
            if(!move_uploaded_file($data['image']['tmp_name'], $this->uploadPath.$travel['travel_id'].'/'.$data['image']['name'])) {
                throw new \Exception($this->translator->translate('Can\'t upload image!'));
            } else {
                $dir = $this->uploadPath.$travel['travel_id'];
                if (is_dir($dir)) {
                    if ($dh = opendir($dir)) {
                        $col = 0;
                        while (($file = readdir($dh)) !== false) {
                            if($file != '.' and $file != '..' and is_file($dir.'/'.$file) and $file != $data['image']['name']){
                                unlink($dir.'/'.$file);
                            }
                        }
                        closedir($dh);
                    }
                }
            }
        }

        $connection = $this->dbAdapter->getDriver()->getConnection();
        $connection->beginTransaction();

        try {
            $res = new TableGateway('travels', $this->dbAdapter);
            $sql = $res->getSql();
            $update = $sql->update();
            $update->table('travels');
            $update->set($update_data);
            $update->where(array('travel_id' => $travel['travel_id']));
            $statement = $sql->prepareStatementForSqlObject($update);
            $statement->execute($sql);

            $langs = $this->getLangs();
            foreach ($langs as $lang) {
                $travel['txt'][$lang['locale']] = $this->getTravelByIdAndLocale($travel['travel_id'], $lang['locale']);

                $update_data = [
                    'title' => $data[$lang['locale']]['title'],
                    'subtitle' => $data[$lang['locale']]['subtitle'],
                    'announce' => $data[$lang['locale']]['announce'],
                    'text' => $data[$lang['locale']]['text'],
                ];

                $res = new TableGateway('travels_txt', $this->dbAdapter);
                $sql = $res->getSql();
                $update = $sql->update();
                $update->table('travels_txt');
                $update->set($update_data);
                $update->where(array('travel_id' => $travel['travel_id']));
                $update->where(array('lang_id' => $lang['lang_id']));
                $statement = $sql->prepareStatementForSqlObject($update);
                $statement->execute($sql);
            }

            $connection->commit();
        }  catch (Exception $e) {
            $connection->rollback();
        }
    }

    public function pr($arr = []){
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
}
