<?php
namespace User\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Zend\Crypt\PublicKey\Rsa;
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
    private $public_key = '/mvc/keys/public_key.pub';
    private $private_key = '/mvc/keys/private_key.pem';
    private $pass_phrase = '@#$@Rgdfbfgh56548())8VVBn!@!##@$%+__)((7""S~!23//';
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
     * Return RSA encode
     * @param $text
     * @return mixed
     */
    public function setRSAencode($text)
    {
        $rsa = Rsa::factory(array(
            'public_key' => $_SERVER['DOCUMENT_ROOT'] . $this->public_key,
            'private_key' => $_SERVER['DOCUMENT_ROOT'] . $this->private_key,
            'pass_phrase' => $this->pass_phrase,
            'binary_output' => false
        ));

        $text = base64_encode($text);
        $text = mb_substr($text, (mb_strlen($text, 'UTF-8') - 11), mb_strlen($text, 'UTF-8'), 'UTF-8') . mb_substr($text, 0, (mb_strlen($text, 'UTF-8') - 11), 'UTF-8');

        return $rsa->encrypt($text);
    }

    /**
     * Return RSA decode
     * @return array
     */
    public function getRSAdecode()
    {
        $rsa = Rsa::factory(array(
            'public_key' => $_SERVER['DOCUMENT_ROOT'] . $this->public_key,
            'private_key' => $_SERVER['DOCUMENT_ROOT'] . $this->private_key,
            'pass_phrase' => $this->pass_phrase,
            'binary_output' => false
        ));

        $decrypt = $rsa->decrypt($_COOKIE['user_hash']);

        return array_combine(['email', 'password'], explode('|', base64_decode(mb_substr($decrypt, 11, mb_strlen($decrypt, 'UTF-8'), 'UTF-8') . mb_substr($decrypt, 0, 11, 'UTF-8'))));
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
        if ($bcrypt->verify($this->password, $passwordHash)) {
            // Great! The password hash matches. Return user identity (email) to be
            // saved in session for later use.
            return new Result(
                Result::SUCCESS,
                $this->email,
                ['Authenticated successfully.']);
        }

        // If password check didn't pass return 'Invalid Credential' failure status.
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']);
    }
}
