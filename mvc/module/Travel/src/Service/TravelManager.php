<?php
namespace Travel\Service;

//use Travel\Entity\Travel;
use Zend\Db\TableGateway\TableGateway;
use Application\Service\ArticleTagsManager;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */

class TravelManager extends ArticleTagsManager
{
    /**
     * Limit travels on page
     * @var int
     */
    private $page_limit = 2;

    /**
     * Article type id
     * @var int
     */
    private $article_type_id = 1;

    /**
     * @var Zend\Db\Adapter\Adapter
     */
    protected $dbAdapter;

    private $translator;

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

    public function getUploadPath() {
        return $this->uploadPath;
    }

//    /**
//     * Get site languages
//     *
//     * @return mixed
//     */
//    public function getLangs()
//    {
//        $res = new TableGateway('lang', $this->dbAdapter);
//        $sql = $res->getSql()->select();
//        $langs = $res->selectWith($sql)->toArray();
//
//        return $langs;
//    }

    /**
     * Get pages of travels
     * @return float
     */
    public function getTravelsPages($admin = 0)
    {
        if (!empty($admin)) {
            $this->page_limit = 30;
        }

        $select = "SELECT COUNT(*) as count FROM articles WHERE type_id = ".$this->article_type_id;
        $travels = $this->dbAdapter->query($select, 'execute')->current();

        $pages = ceil($travels['count'] / $this->page_limit);

        return (empty($pages)?1:$pages);
    }

    /**
     * Get travels list
     * @param int $page
     * @return null|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function getTravelsList($page = 1, $admin = 0)
    {
        $res = new TableGateway('articles', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->join('articles_txt', 'articles_txt.article_id = articles.article_id', ['lang_id', 'title', 'subtitle', 'announce', 'text']);
        $select->join('user', 'user.id = articles.user_id', ['photo']);
        $select->join('user_txt', '(user_txt.user_id = user.id and user_txt.lang_id = articles_txt.lang_id)', ['full_name']);
        $select->join('lang', 'lang.lang_id = articles_txt.lang_id', []);
        $select->where(['articles.type_id' => $this->article_type_id]);
        if (empty($admin)) {
            $select->where(['articles.status' => 1]);
        } else {
            $this->page_limit = 30;
        }
        $select->where(['lang.locale' => $_SESSION['locale']]);
        $select->limit($this->page_limit);
        $select->offset(($page > 1?($page * $this->page_limit - $this->page_limit):0));
        $select->order('articles.date DESC, articles.article_id DESC');
        $travels = $res->selectWith($select)->toArray();

        if (sizeof($travels) > 0) {
            foreach ($travels as $key => $travel) {
                $travels[$key]['tags'] = $this->getArticleTags($travel['article_id'], $_SESSION['locale']);
            }
        }

        return $travels;
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function getTravelsMiniPostsList($limit = 5)
    {
        $select = "SELECT article_id FROM articles WHERE type_id = ".$this->article_type_id." AND status = 1";
        $travel_ids_tmp = $this->dbAdapter->query($select, 'execute')->toArray();
        if (sizeof($travel_ids_tmp) > 0) {
            foreach ($travel_ids_tmp as $travel) {
                $travel_ids[] = $travel['article_id'];
            }
            $rand_keys = array_rand($travel_ids, (sizeof($travel_ids) <= $limit?sizeof($travel_ids) - 1:$limit));

            foreach ($rand_keys as $key) {
                $rand_ids[] = $travel_ids[$key];
            }

            $res = new TableGateway('articles', $this->dbAdapter);
            $sql = $res->getSql();
            $select = $sql->select();
            $select->join('articles_txt', 'articles_txt.article_id = articles.article_id', ['lang_id', 'title', 'subtitle', 'announce', 'text']);
            $select->join('user', 'user.id = articles.user_id', ['photo']);
            $select->join('user_txt', '(user_txt.user_id = user.id and user_txt.lang_id = articles_txt.lang_id)', ['full_name']);
            $select->join('lang', 'lang.lang_id = articles_txt.lang_id', []);
            $select->where(['articles.status' => 1]);
            $select->where(['lang.locale' => $_SESSION['locale']]);
            $select->where('articles.article_id IN ('.implode(', ', $rand_ids).')');
            $select->order('articles.date DESC, articles.article_id DESC');
            $travels = $res->selectWith($select)->toArray();

            return $travels;
        }
    }

    /**
     * Get travel by URL
     * @param string $url
     * @return null|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function getTravelByUrl($url = '')
    {
        $res = new TableGateway('articles', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->join('articles_txt', 'articles_txt.article_id = articles.article_id', ['lang_id', 'title', 'subtitle', 'announce', 'text']);
        $select->join('user', 'user.id = articles.user_id', ['photo']);
        $select->join('user_txt', '(user_txt.user_id = user.id and user_txt.lang_id = articles_txt.lang_id)', ['full_name']);
        $select->join('lang', 'lang.lang_id = articles_txt.lang_id', []);
        $select->where(['articles.type_id' => $this->article_type_id]);
        $select->where(['articles.url' => $url]);
        if(!isset($_GET['preview'])) {
            $select->where(['articles.status' => 1]);
        }
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
        $res = new TableGateway('articles', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->join('user', 'user.id = articles.user_id', ['photo']);
        $select->join('user_txt', '(user_txt.user_id = user.id)', ['full_name']);
        $select->join('lang', 'lang.lang_id = user_txt.lang_id', []);
        $select->where(['articles.article_id' => $travel_id]);
        $select->where(['lang.locale' => $_SESSION['locale']]);
        $select->limit(1);
        $travel = $res->selectWith($select)->toArray()[0];

        foreach ($langs as $lang){
            $travel['txt'][$lang['locale']] = $this->getTravelByIdAndLocale($travel['article_id'], $lang['locale']);
        }

        return $travel;
    }

    public function getTravelByIdAndLocale($travel_id = 0, $locale = '')
    {
        $res = new TableGateway('articles_txt', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->join('lang', 'lang.lang_id = articles_txt.lang_id', []);
        $select->where(['articles_txt.article_id' => $travel_id]);
        $select->where(['lang.locale' => $locale]);
        $select->limit(1);
        $travel = $res->selectWith($select)->toArray()[0];

        return $travel;
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

    public function getTravelImagesSize($travel_id = 0)
    {
        $size_sum = 0;

        $dir = $this->uploadPath.$travel_id.'/files';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                $col = 0;
                while (($file = readdir($dh)) !== false) {
                    if($file != '.' and $file != '..' and is_file($dir.'/'.$file)){
                        $size_sum += filesize($dir.'/'.$file);
                    }
                }
                closedir($dh);
            }
        }

        return $this->fileSizeConvert($size_sum);
    }

    public function generate_preview($dir, $name = '', $prefix = '', $x = 0, $y = 0, $size, $quality){
        if($name == ''){
            return false;
        }
        if($size === false){
            return false;
        }

        $icfunc = "imagecreatefromjpeg";

        if(!function_exists($icfunc)){
            return false;
        }

        $isrc = $icfunc($dir.'/'.$name);

        $x_ratio = $x / $size[0];
        $y_ratio = $y / $size[1];
        $ratio = max($x_ratio, $y_ratio);
        $use_x_ratio = ($x_ratio == $ratio);
        $n_w   = $use_x_ratio  ? $x  : floor($size[0] * $ratio);
        $n_h  = !$use_x_ratio ? $y : floor($size[1] * $ratio);

        $idest = imagecreatetruecolor($x, $y);
        $color = imagecolorallocate($idest, 255, 255, 255);
        imagefill($idest, 0, 0, $color);
        imagecopyresized($idest, $isrc, 0, 0, 0, 0, $n_w, $n_h, $size[0], $size[1]);

        unlink($dir.'/'.$prefix.'_'.$name);
        imagejpeg($idest, $dir.'/'.$prefix.'_'.$name, $quality);
        imagedestroy($isrc);
        imagedestroy($idest);
    }

    public function editTravel($travel, $data){
        $update_data = [
            'url' => $data['url'],
            'date' => date('Y-m-d', strtotime($data['date'])),
            'status' => $data['status'],
            'image' => ((isset($data['image']['name']) and $data['image']['name'] != '')?$data['image']['name']:$travel['image'])
        ];

        if (isset($data['image']['name']) and $data['image']['name'] != '') {
            if(!move_uploaded_file($data['image']['tmp_name'], $this->uploadPath.$travel['article_id'].'/'.$data['image']['name'])) {
                throw new \Exception($this->translator->translate('Can\'t upload image!'));
            } else {
                // Optimization image
                \Tinify\setKey("0dl-M4GFe_MUu_cCC9WphHJFM84Js2WA");
                $source = \Tinify\fromFile($this->uploadPath.$travel['article_id'].'/'.$data['image']['name']);
                $source->toFile($this->uploadPath.$travel['article_id'].'/'.$data['image']['name']);

                // Generate previews
                $size = getimagesize($this->uploadPath.$travel['article_id'].'/'.$data['image']['name']);
                $this->generate_preview($this->uploadPath.$travel['article_id'], $data['image']['name'], 'medium', 300, 175, $size, 100);
                $this->generate_preview($this->uploadPath.$travel['article_id'], $data['image']['name'], 'small', 64, 64, $size, 100);
            }
        }

        $connection = $this->dbAdapter->getDriver()->getConnection();
        $connection->beginTransaction();

        try {
            $res = new TableGateway('articles', $this->dbAdapter);
            $sql = $res->getSql();
            $update = $sql->update();
            $update->table('articles');
            $update->set($update_data);
            $update->where(array('article_id' => $travel['article_id']));
            $statement = $sql->prepareStatementForSqlObject($update);
            $statement->execute($sql);

            $langs = $_SESSION['langs'];
            foreach ($langs as $lang) {
                $travel['txt'][$lang['locale']] = $this->getTravelByIdAndLocale($travel['article_id'], $lang['locale']);

                $update_data = [
                    'title' => trim($data[$lang['locale']]['title']),
                    'subtitle' => trim($data[$lang['locale']]['subtitle']),
                    'announce' => trim($data[$lang['locale']]['announce']),
                    'text' => trim($data[$lang['locale']]['text']),
//                    'text' => str_replace("\r\n", '<br />', trim($data[$lang['locale']]['text'])),
                ];

                $res = new TableGateway('articles_txt', $this->dbAdapter);
                $sql = $res->getSql();
                $update = $sql->update();
                $update->table('articles_txt');
                $update->set($update_data);
                $update->where(array('article_id' => $travel['article_id']));
                $update->where(array('lang_id' => $lang['lang_id']));
                $statement = $sql->prepareStatementForSqlObject($update);
                $statement->execute($sql);

                // Update tags
                if (isset($data['tags'][$lang['locale']]) and sizeof($data['tags'][$lang['locale']]) > 0 and trim($data['tags'][$lang['locale']]) != '') {
                    $tags = explode(',', $data['tags'][$lang['locale']]);

                    if ($this->checkArticleTags($travel['article_id'], $lang['lang_id'], $tags) == true) {
                        $this->deleteArticleTagsByArticleIdAndLangId($travel['article_id'], $lang['lang_id']);
                        foreach ($tags as $tag) {
                            $this->setArticleTag($travel['article_id'], $lang['lang_id'], $tag);
                        }
                    }
                } elseif (isset($data['tags'][$lang['locale']]) and trim($data['tags'][$lang['locale']]) == '') {
                    $this->deleteArticleTagsByArticleIdAndLangId($travel['article_id'], $lang['lang_id']);
                }
            }

            $connection->commit();
        }  catch (Exception $e) {
            $connection->rollback();
        }
    }

    public function createTravel($user_id, $data){
        // Travel data
        $create_data = [
            'user_id' => $user_id,
            'url' => $data['url'],
            'image' => '',
            'date' => date('Y-m-d', strtotime($data['date'])),
            'status' => 0
        ];

        // Begin transaction
        $connection = $this->dbAdapter->getDriver()->getConnection();
        $connection->beginTransaction();

        try {
            // Try to insert travel data
            $res = new TableGateway('articles', $this->dbAdapter);
            $res->insert($create_data);

            // Get travel_id
            $travel_id = $res->getLastInsertValue();

            // Try to insert travel_txt data
            if(!empty($travel_id)) {
                $langs = $_SESSION['langs'];
                foreach ($langs as $lang) {
                    $create_txt_data = [
                        'article_id' => $travel_id,
                        'lang_id' => $lang['lang_id'],
                        'title' => '',
                        'subtitle' => '',
                        'announce' => '',
                        'text' => '',
                    ];

                    $res = new TableGateway('articles_txt', $this->dbAdapter);
                    $res->insert($create_txt_data);
                }
            } else {
                // Rollback transaction
                $connection->rollback();

                // Return false;
                return false;
            }

            // Commmit transaction
            $connection->commit();

            // Return travel_id
            return $travel_id;

        }  catch (Exception $e) {
            // Rollback transaction
            $connection->rollback();

            // Return false;
            return false;
        }
    }

    public function deleteTravel($travel_id = 0)
    {
        // Begin transaction
        $connection = $this->dbAdapter->getDriver()->getConnection();
        $connection->beginTransaction();

        $res = new TableGateway('articles', $this->dbAdapter);
        $res->delete(['article_id' => $travel_id]);

        // Commmit transaction
        $connection->commit();

        return;
    }

    public function fileSizeConvert($bytes)
    {
        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

        foreach($arBytes as $arItem) {
            if($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

    public function pr($arr = [])
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
}
