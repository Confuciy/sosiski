<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="WEB_USERS")
 */
class User
{
    // User status constants.
    const STATUS_ACTIVE       = 1; // Active user.
    const STATUS_RETIRED      = 2; // Retired user.

    /**
     * @ORM\Id
     * @ORM\Column(name="USERS_ID")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="USERS_EMAIL")
     */
    protected $email;

    /**
     * @ORM\Column(name="USERS_SURNAME")
     */
    protected $surname;

    /**
     * @ORM\Column(name="USERS_NAME")
     */
    protected $name;

    /**
     * @ORM\Column(name="USERS_PATRONYMIC")
     */
    protected $patronymic;

    /**
     * @ORM\Column(name="USERS_PASSWORD")
     */
    protected $password;

    /**
     * @ORM\Column(name="USERS_ACTIVE")
     */
    protected $status;

    /**
     * @ORM\Column(name="USERS_REGISTER")
     */
    protected $dateCreated;

//    /**
//     * @ORM\Column(name="pwd_reset_token")
//     */
//    protected $passwordResetToken;
//
//    /**
//     * @ORM\Column(name="pwd_reset_token_creation_date")
//     */
//    protected $passwordResetTokenCreationDate;

    /**
     * Returns user ID.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets user ID.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns email.
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets email.
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns surname.
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Sets surname.
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Returns name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets name.
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns patronymic.
     * @return string
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * Sets patronymic.
     * @param string $patronymic
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;
    }

    /**
     * Returns status.
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns possible statuses as array.
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETIRED => 'Retired'
        ];
    }

    /**
     * Returns user status as string.
     * @return string
     */
    public function getStatusAsString()
    {
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];

        return 'Unknown';
    }

    /**
     * Sets status.
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Returns password.
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets password.
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the date of user creation.
     * @return string
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date when this user was created.
     * @param string $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

//    /**
//     * Returns password reset token.
//     * @return string
//     */
//    public function getResetPasswordToken()
//    {
//        return $this->passwordResetToken;
//    }
//
//    /**
//     * Sets password reset token.
//     * @param string $token
//     */
//    public function setPasswordResetToken($token)
//    {
//        $this->passwordResetToken = $token;
//    }
//
//    /**
//     * Returns password reset token's creation date.
//     * @return string
//     */
//    public function getPasswordResetTokenCreationDate()
//    {
//        return $this->passwordResetTokenCreationDate;
//    }
//
//    /**
//     * Sets password reset token's creation date.
//     * @param string $date
//     */
//    public function setPasswordResetTokenCreationDate($date)
//    {
//        $this->passwordResetTokenCreationDate = $date;
//    }
}
