<?php
namespace User\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

/**
 * Adapter used for authenticating user. It takes login and password on input
 * and checks the database if there is a user with such login (email) and password.
 * If such user exists, the service returns its identity (email). The identity
 * is saved to session and can be retrieved later with Identity view helper provided
 * by ZF3.
 */
class AuthAdapter implements AdapterInterface
{
    /**
     * User email.
     * @var string
     */
    private $email;

    /**
     * Password
     * @var string
     */
    private $password;

    /**
     * Password verify type
     * @var int
     */
    private $password_verify_type = 0;

    /**
     * @var Zend\Db\Adapter\Adapter
     */
    private $dbAdapter;


    /**
     * Constructor.
     */
    public function __construct($dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    /**
     * Sets user email.
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Sets password.
     */
    public function setPassword($password)
    {
        $this->password = (string)$password;
    }

    /**
     * Sets password.
     */
    public function setPasswordVerifyType($type)
    {
        $this->password_verify_type = (int)$type;
    }

    /**
     * Get password.
     */
    public function getUser()
    {
        $select = "SELECT `user`.password, `user`.status FROM `user` WHERE LOWER(`email`) = '".trim(mb_strtolower($this->email, 'UTF-8'))."' LIMIT 1";
        $user = $this->dbAdapter->query($select, 'execute')->current();
        //echo $sql->getSqlstringForSqlObject($select); die;

        return $user;
    }

    /**
     * Performs an authentication attempt.
     */
    public function authenticate()
    {
        // Check the database if there is a user with such email.
        $user = $this->getUser();

        // If there is no such user, return 'Identity Not Found' status.
        if ($user == null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Invalid credentials.']);
        }

        // If the user with such email exists, we need to check if it is active or retired.
        // Do not allow retired users to log in.
        if ($user['status'] == 0) {
            return new Result(
                Result::FAILURE,
                null,
                ['User is retired.']);
        }

        $bcrypt = new Bcrypt();
        $passwordHash = $user['password'];

        // Now we need to calculate hash based on user-entered password and compare
        // it with the password hash stored in database.
        if(empty($this->password_verify_type)){
            if ($bcrypt->verify($this->password, $passwordHash)) {
                // Great! The password hash matches. Return user identity (email) to be
                // saved in session for later use.
                return new Result(
                    Result::SUCCESS,
                    $this->email,
                    ['Authenticated successfully.']);
            }
        }
        if(!empty($this->password_verify_type)){
            if ($this->password == $passwordHash) {
                // Great! The password hash matches. Return user identity (email) to be
                // saved in session for later use.
                return new Result(
                    Result::SUCCESS,
                    $this->email,
                    ['Authenticated successfully.']);
            }
        }
        // If password check didn't pass return 'Invalid Credential' failure status.
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']);
    }
}
