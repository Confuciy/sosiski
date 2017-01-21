<?php
namespace Travel\Service;

use Travel\Entity\Travel;
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
     * This method updates data of an existing user.
     */
//    public function updateUser($user, $data)
//    {
//        // Do not allow to change user email if another user with such email already exits.
//        if($user['email'] != $data['email'] && $this->checkUserExists($data['email'])) {
//            throw new \Exception("Another user with email address " . $data['email'] . " already exists");
//        }
//
//        $update_data = [
//            'email' => $data['email'],
//            'full_name' => $data['full_name'],
//            'status' => $data['status'],
//        ];
//
//        $res = new TableGateway('user', $this->dbAdapter);
//        $sql = $res->getSql();
//        $update = $sql->update();
//        $update->table('user');
//        $update->set($update_data);
//        $update->where(array('id' => $user['id']));
//        $statement = $sql->prepareStatementForSqlObject($update);
//        $statement->execute($sql);
//
//        return true;
//    }
//
//    public function setUserPassword($user, $password)
//    {
//        $update_data = [
//            'password' => $password,
//        ];
//
//        $res = new TableGateway('user', $this->dbAdapter);
//        $sql = $res->getSql();
//        $update = $sql->update();
//        $update->table('user');
//        $update->set($update_data);
//        $update->where(array('id' => $user['id']));
//        $statement = $sql->prepareStatementForSqlObject($update);
//        $statement->execute($sql);
//
//        return true;
//    }
//
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

//    public function getUserByEmail($email)
//    {
//        $select = "SELECT `user`.* FROM `user` WHERE LOWER(`email`) = '".trim(mb_strtolower($email, 'UTF-8'))."' LIMIT 1";
//        $user = $this->dbAdapter->query($select, 'execute')->current();
//
//        return $user;
//    }
//
//    public function getUserById($id)
//    {
//        $res = new TableGateway('user', $this->dbAdapter);
//        $sql = $res->getSql();
//        $select = $sql->select();
//        $select->where(['id' => $id]);
//        $select->limit(1);
//        $user = $res->selectWith($select)->current();
//
//        return $user;
//    }
}
