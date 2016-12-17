<?php
namespace User\Service;

use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;
use Zend\Db\TableGateway\TableGateway;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class UserManager
{
    /**
     * @var Zend\Db\Adapter\Adapter
     */
    private $dbAdapter;

    /**
     * Constructs the service.
     */
    public function __construct($dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    /**
     * This method adds a new user.
     */
    public function addUser($data)
    {
        /*
        // Do not allow several users with the same email address.
        if($this->checkUserExists($data['email'])) {
            throw new \Exception("User with email address " . $data['$email'] . " already exists");
        }

        // Create new User entity.
        $user = new User();
        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);

        // Encrypt password and store the password in encrypted state.
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($data['password']);
        $user->setPassword($passwordHash);

        $user->setStatus($data['status']);

        $currentDate = date('Y-m-d H:i:s');
        $user->setDateCreated($currentDate);

        // Add the entity to the entity manager.
        $this->entityManager->persist($user);

        // Apply changes to database.
        $this->entityManager->flush();

        return $user;
        */
    }

    /**
     * This method updates data of an existing user.
     */
    public function updateUser($user, $data)
    {
        // Do not allow to change user email if another user with such email already exits.
        if($user['email'] != $data['email'] && $this->checkUserExists($data['email'])) {
            throw new \Exception("Another user with email address " . $data['email'] . " already exists");
        }

        $update_data = [
            'email' => $data['email'],
            'full_name' => $data['full_name'],
            'status' => $data['status'],
        ];
//        if(isset($data['photo']) and $data['photo'] != ''){
//            $update_data = array_merge($update_data, ['photo' => 'photo.jpg']);
//        }

        $res = new TableGateway('user', $this->dbAdapter);
        $sql = $res->getSql();
        $update = $sql->update();
        $update->table('user');
        $update->set($update_data);
        $update->where(array('id' => $user['id']));
        $statement = $sql->prepareStatementForSqlObject($update);
        $statement->execute($sql);

        return true;
    }

    /**
     * This method checks if at least one user presents, and if not, creates
     * 'Admin' user with email 'admin@example.com' and password 'Secur1ty'.
     */
    public function createAdminUserIfNotExists()
    {
//        $user = $this->entityManager->getRepository(User::class)->findOneBy([]);
//        if ($user==null) {
//            $user = new User();
//            $user->setEmail('admin@example.com');
//            $user->setFullName('Admin');
//            $bcrypt = new Bcrypt();
//            $passwordHash = $bcrypt->create('Secur1ty');
//            $user->setPassword($passwordHash);
//            $user->setStatus(User::STATUS_ACTIVE);
//            $user->setDateCreated(date('Y-m-d H:i:s'));
//
//            $this->entityManager->persist($user);
//            $this->entityManager->flush();
//        }
    }

    /**
     * Checks whether an active user with given email address already exists in the database.
     */
    public function checkUserExists($email) {

        $user = $this->getUserByEmail($email);

        return $user !== null;
    }

    /**
     * Checks that the given password is correct.
     */
    public function validatePassword($user, $password)
    {
        $bcrypt = new Bcrypt();
        $passwordHash = $this->getUserPasswordByUserId($user['id']);

        if ($bcrypt->verify($password, $passwordHash)) {
            return true;
        }

        return false;
    }

    /**
     * Generates a password reset token for the user. This token is then stored in database and
     * sent to the user's E-mail address. When the user clicks the link in E-mail message, he is
     * directed to the Set Password page.
     */
    public function generatePasswordResetToken($user)
    {
//        // Generate a token.
//        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);
//        $user->setPasswordResetToken($token);
//
//        $currentDate = date('Y-m-d H:i:s');
//        $user->setPasswordResetTokenCreationDate($currentDate);
//
//        $this->entityManager->flush();
//
//        $subject = 'Password Reset';
//
//        $httpHost = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'localhost';
//        $passwordResetUrl = 'http://' . $httpHost . '/set-password/' . $token;
//
//        $body = 'Please follow the link below to reset your password:\n';
//        $body .= "$passwordResetUrl\n";
//        $body .= "If you haven't asked to reset your password, please ignore this message.\n";
//
//        // Send email to user.
//        mail($user->getEmail(), $subject, $body);
    }

    /**
     * Checks whether the given password reset token is a valid one.
     */
    public function validatePasswordResetToken($passwordResetToken)
    {
//        $user = $this->entityManager->getRepository(User::class)
//            ->findOneByPasswordResetToken($passwordResetToken);
//
//        if($user==null) {
//            return false;
//        }
//
//        $tokenCreationDate = $user->getPasswordResetTokenCreationDate();
//        $tokenCreationDate = strtotime($tokenCreationDate);
//
//        $currentDate = strtotime('now');
//
//        if ($currentDate - $tokenCreationDate > 24*60*60) {
//            return false; // expired
//        }
//
//        return true;
    }

    /**
     * This method sets new password by password reset token.
     */
    public function setNewPasswordByToken($passwordResetToken, $newPassword)
    {
//        if (!$this->validatePasswordResetToken($passwordResetToken)) {
//            return false;
//        }
//
//        $user = $this->entityManager->getRepository(User::class)
//            ->findOneBy(['passwordResetToken'=>$passwordResetToken]);
//
//        if ($user===null) {
//            return false;
//        }
//
//        // Set new password for user
//        $bcrypt = new Bcrypt();
//        $passwordHash = $bcrypt->create($newPassword);
//        $user->setPassword($passwordHash);
//
//        // Remove password reset token
//        $user->setPasswordResetToken(null);
//        $user->setPasswordResetTokenCreationDate(null);
//
//        $this->entityManager->flush();
//
//        return true;
    }

    /**
     * This method is used to change the password for the given user. To change the password,
     * one must know the old password.
     */
    public function changePassword($user, $data)
    {
        $oldPassword = $data['old_password'];

        // Check that old password is correct
        if (!$this->validatePassword($user, $oldPassword)) {
            return false;
        }

        $newPassword = $data['new_password'];

        // Check password length
        if (strlen($newPassword)<6 || strlen($newPassword)>64) {
            return false;
        }

        // Set new password for user
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);
        $this->setUserPassword($user, $passwordHash);

        return true;
    }

    public function setUserPassword($user, $password)
    {
        $update_data = [
            'password' => $password,
        ];

        $res = new TableGateway('user', $this->dbAdapter);
        $sql = $res->getSql();
        $update = $sql->update();
        $update->table('user');
        $update->set($update_data);
        $update->where(array('id' => $user['id']));
        $statement = $sql->prepareStatementForSqlObject($update);
        $statement->execute($sql);

        return true;
    }

    public function getUserPasswordByUserId($id){
        $select = "SELECT `password` FROM `user` WHERE `id` = ".$id;
        $user = $this->dbAdapter->query($select, 'execute')->current();

        return $user['password'];
    }

    public function getUserList()
    {
        $res = new TableGateway('user', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->where(['status' => 1]);
        $select->limit(10);
        $users = $res->selectWith($select);

        return $users;
    }

    public function getUserByEmail($email)
    {
        $select = "SELECT `user`.* FROM `user` WHERE LOWER(`email`) = '".trim(mb_strtolower($email, 'UTF-8'))."' LIMIT 1";
        $user = $this->dbAdapter->query($select, 'execute')->current();

        return $user;
    }

    public function getUserById($id)
    {
        $res = new TableGateway('user', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->where(['id' => $id]);
        $select->limit(1);
        $user = $res->selectWith($select)->current();

        return $user;
    }
}
