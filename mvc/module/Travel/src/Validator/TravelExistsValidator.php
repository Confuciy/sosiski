<?php
namespace Travel\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Db\TableGateway\TableGateway;

class TravelExistsValidator extends AbstractValidator
{
    /**
     * Available validator options.
     * @var array
     */
    protected $options = array(
        'dbAdapter' => null,
        'travel' => null
    );

    // Validation failure message IDs.
    const TRAVEL_EXISTS = 'travelExists';

    /**
     * Validation failure messages.
     * @var array
     */
    protected $messageTemplates = array(
        self::TRAVEL_EXISTS => "Another travel with such an url already exists"
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
            if (isset($options['travel']))
                $this->options['travel'] = $options['travel'];
        }

        // Call the parent class constructor
        parent::__construct($options);
    }

    /**
     * Check if user exists.
     */
    public function isValid($value)
    {
        $res = new TableGateway('travels', $this->options['dbAdapter']);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->where(['url' => $value]);
        $select->limit(1);
        $travel = $res->selectWith($select)->current();

        if ($this->options['travel'] == null) {
            $isValid = ($travel == null);
        } else {
            if ($travel['url'] != $value && $travel != null)
                $isValid = false;
            else
                $isValid = true;
        }

        // If there were an error, set error message.
        if (!$isValid) {
            $this->error(self::TRAVEL_EXISTS);
        }

        // Return validation result.
        return $isValid;
    }
}
