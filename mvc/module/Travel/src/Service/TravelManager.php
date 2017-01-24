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

    /**
     * Constructs the service.
     */
    public function __construct($dbAdapter, $translator)
    {
        $this->dbAdapter = $dbAdapter;
        $this->translator = $translator;
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
            $travel['txt'][$lang['locale']] = $this->getTravelByIdAndLocate($travel['travel_id'], $lang['locale']);
        }

        return $travel;
    }

    public function getTravelByIdAndLocate($travel_id = 0, $locate = '')
    {
        $res = new TableGateway('travels_txt', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->join('lang', 'lang.lang_id = travels_txt.lang_id', []);
        $select->where(['travels_txt.travel_id' => $travel_id]);
        $select->where(['lang.locale' => $locate]);
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

        $dir = dirname(__FILE__).'/../../../../public/img/travels/'.$travel_id.'/files';
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

    public function pr($arr = []){
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
}
