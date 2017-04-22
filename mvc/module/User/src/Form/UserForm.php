<?php
namespace User\Form;

//use User\Validators\File\UserPhotoValidator;
use Zend\Form\Form;
//use Zend\Form\Fieldset;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use User\Validator\UserExistsValidator;
use User\Validator\UserPhotoValidator;

/**
 * This form is used to collect user's email, full name, password and status. The form
 * can work in two scenarios - 'create' and 'update'. In 'create' scenario, user
 * enters password, in 'update' scenario he/she doesn't enter password.
 */
class UserForm extends Form
{
    /**
     * Scenario ('create' or 'update').
     * @var string
     */
    private $scenario;

    private $dbAdapter = null;

    private $user = null;

    /**
     * Constructor.
     */
    public function __construct($scenario = 'create', $dbAdapter = null, $user = null)
    {
        // Define form name
        parent::__construct('user-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->dbAdapter = $dbAdapter;
        $this->user = $user;

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
        // Add "email" field
        $this->add([
            'type'  => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'E-mail',
            ],
        ]);

        // Add "full_name" field
        foreach ($_SESSION['langs'] as $lang) {
            $this->add([
                'type'  => 'text',
                'name' => 'full_name_'.$lang['locale'],
                'options' => [
                    'label' => 'Full Name',
                ],
            ]);
        }

        if ($this->scenario == 'create') {

            // Add "password" field
            $this->add([
                'type'  => 'password',
                'name' => 'password',
                'options' => [
                    'label' => 'Password',
                ],
            ]);

            // Add "confirm_password" field
            $this->add([
                'type'  => 'password',
                'name' => 'confirm_password',
                'options' => [
                    'label' => 'Confirm password',
                ],
            ]);
        }

        // Add "photo" field
        $file = new Element\File('image-file');
        $file->setLabel('Photo')->setAttribute('name', 'photo')->setAttribute('type', 'file');
        $this->add($file);

        // Add "status" field
        $this->add([
            'type'  => 'select',
            'name' => 'status',
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    1 => 'Active',
                    2 => 'Retired',
                ]
            ],
        ]);

        // Add the Submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Register'
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        // Create main input filter
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // Add input for "email" field
        $inputFilter->add([
            'name'     => 'email',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
                [
                    'name' => 'EmailAddress',
                    'options' => [ //\Zend\Validator\EmailAddress::INVALID, //
                        'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                        'useMxCheck'    => false,
                    ],
                ],
                [
                    'name' => UserExistsValidator::class,
                    'options' => [
                        'dbAdapter' => $this->dbAdapter,
                        'user' => $this->user
                    ],
                ],
            ],
        ]);

        // Add input for "full_name" field
        foreach ($_SESSION['langs'] as $lang) {
            $inputFilter->add([
                'name' => 'full_name_'.$lang['locale'],
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 512
                        ],
                    ],
                ],
            ]);
        }

        if ($this->scenario == 'create') {

            // Add input for "password" field
            $inputFilter->add([
                'name'     => 'password',
                'required' => true,
                'filters'  => [
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ],
                    ],
                ],
            ]);

            // Add input for "confirm_password" field
            $inputFilter->add([
                'name'     => 'confirm_password',
                'required' => true,
                'filters'  => [
                ],
                'validators' => [
                    [
                        'name'    => 'Identical',
                        'options' => [
                            'token' => 'password',
                        ],
                    ],
                ],
            ]);
        }

        // Add input for "photo" field
        $inputFilter->add([
            'name' => 'photo',
            'required' => false,
            'validators' => [
                [
                    'name' => UserPhotoValidator::class,
                    'options' => [
                        'minSize' => '64',
                        'maxSize' => '1024',
                        'newFileName' => 'photo_'.time(),
                        'uploadPath' => $_SERVER['DOCUMENT_ROOT'].'/mvc/public/img/users/'.$this->user['id'].'/',
                        'id' => $this->user['id'],
                        'dbAdapter' => $this->dbAdapter,
                    ],
                ],
            ],
        ]);

        // Add input for "status" field
        $inputFilter->add([
            'name'     => 'status',
            'required' => true,
            'filters'  => [
                ['name' => 'ToInt'],
            ],
            'validators' => [
                ['name'=>'InArray', 'options'=>['haystack'=>[1, 2]]]
            ],
        ]);
    }
}
