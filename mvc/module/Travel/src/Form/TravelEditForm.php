<?php
namespace Travel\Form;

use Zend\Form\Form;
//use Zend\Form\Fieldset;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Date;
use Zend\Validator\NotEmpty;
use Travel\Validator\TravelExistsValidator;
use Travel\Validator\TravelImageValidator;

class TravelEditForm extends Form
{
    private $scenario = '';

    private $dbAdapter = null;

    private $travel = null;

    private $translator = null;

    private $uploadPath = null;

    /**
     * Constructor.
     */
    public function __construct($scenario = '', $dbAdapter = null, $travel = null, $translator = null, $uploadPath = null)
    {

        // Define form name
        parent::__construct('travel-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->dbAdapter = $dbAdapter;
        $this->travel = $travel;
        $this->translator = $translator;
        $this->uploadPath = $uploadPath;

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
        // Add "url" field
        $this->add([
            'type'  => 'text',
            'name' => 'url',
            'options' => [
                'label' => $this->translator->translate('Url'),
            ],
        ]);

        // Add "date" field
        $this->add([
            'type'  => 'text',
            'name' => 'date',
            'options' => [
                'label' => $this->translator->translate('Date'),
            ],
        ]);

        if ($this->scenario == 'edit') {
            // Add "image" field
            $file = new Element\File('image-file');
            $file->setLabel($this->translator->translate('Image'))->setAttribute('name', 'image')->setAttribute('type', 'file');
            $this->add($file);

            // Add "status" field
            $this->add([
                'type' => 'select',
                'name' => 'status',
                'options' => [
                    'label' => $this->translator->translate('Status'),
                    'value_options' => [
                        1 => $this->translator->translate('Active'),
                        0 => $this->translator->translate('Not Active'),
                    ]
                ],
            ]);
        }

        // Add the Submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => ($this->scenario == 'edit'?$this->translator->translate('Save'):$this->translator->translate('Create'))
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

        if ($this->scenario == 'create') {
            // Add input for "url" field
            $inputFilter->add([
                'name' => 'url',
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                    [
                        'name' => TravelExistsValidator::class,
                        'options' => [
                            'dbAdapter' => $this->dbAdapter,
                            'travel' => $this->travel
                        ],
                    ],
                ],
            ]);
        }
        if ($this->scenario == 'edit') {
            // Add input for "url" field
            $inputFilter->add([
                'name' => 'url',
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                ],
            ]);
        }

        // Add input for "date" field
        $inputFilter->add([
            'name' => 'date',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Date',
                    'options' => [
                        'format' => 'd.m.Y',
                        //'locale' => 'de',
                        'messages' => [
                            Date::INVALID => $this->translator->translate('Date Not Set!'),
                            Date::INVALID_DATE => $this->translator->translate('Invalid Date!'),
                            Date::FALSEFORMAT => $this->translator->translate('Invalid Format!'),
                        ],
                    ],
                ],
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => $this->translator->translate('Please, Enter Date'),
                        ],
                    ],
                ],
            ],
        ]);

        if ($this->scenario == 'edit') {
            // Add input for "image" field
            $inputFilter->add([
                'name' => 'image',
                'required' => false,
                'validators' => [
                    [
                        'name' => TravelImageValidator::class,
                        'options' => [
                            'minSize' => '64',
                            'maxSize' => '1024',
                            //'newFileName' => 'photo_'.time(),
                            'uploadPath' => $this->uploadPath . $this->travel['travel_id'] . '/',
                            'travel_id' => $this->travel['travel_id'],
                            'dbAdapter' => $this->dbAdapter,
                        ],
                    ],
                ],
            ]);

            // Add input for "status" field
            $inputFilter->add([
                'name' => 'status',
                'required' => true,
                'filters' => [
                    ['name' => 'ToInt'],
                ],
                'validators' => [
                    ['name' => 'InArray', 'options' => ['haystack' => [1, 0]]]
                ],
            ]);
        }
    }
}
