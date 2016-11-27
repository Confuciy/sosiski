<?php
namespace User\Validator;

use Zend\Validator\AbstractValidator;
#use Zend\Db\TableGateway\TableGateway;
#use Zend\Db\Sql\Select;

/**
 * This validator class is designed for checking if there is an existing user
 * with such an email.
 */
class UserExistsValidator extends AbstractValidator
{
    /**
     * Available validator options.
     * @var array
     */
    protected $options = array(
        'dbAdapter' => null,
        'user' => null
    );

    // Validation failure message IDs.
    const NOT_SCALAR = 'notScalar';
    const USER_EXISTS = 'userExists';

    /**
     * Validation failure messages.
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_SCALAR => "The email must be a scalar value",
        self::USER_EXISTS => "Another user with such an email already exists"
    );

    /**
     * Constructor.
     */
    public function __construct($options = null)
    {
        // Set filter options (if provided).
        if (is_array($options)) {
            if (isset($options['dbAdapter']))
                $this->options['dbAdapter'] = $options['dbAdapter'];
            if (isset($options['user']))
                $this->options['user'] = $options['user'];
        }

        // Call the parent class constructor
        parent::__construct($options);
    }

    /**
     * Check if user exists.
     */
    public function isValid($value)
    {
        if (!is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false;
        }

        $res = new TableGateway('WEB_USERS', $this->options['dbAdapter']);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->where(['USERS_EMAIL' => $value]);
        $select->limit(1);
        $user = $res->selectWith($select)->current();

        if ($this->options['user'] == null) {
            $isValid = ($user == null);
        } else {
            if ($user->USERS_EMAIL != $value && $user != null)
                $isValid = false;
            else
                $isValid = true;
        }

        // If there were an error, set error message.
        if (!$isValid) {
            $this->error(self::USER_EXISTS);
        }

        // Return validation result.
        return $isValid;
    }
}
