<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\Between;

class PayAmountForm extends Form
{
    private $translator = null;

    public function __construct($translator = null)
    {
        $this->translator = $translator;

        // Define form name
        parent::__construct('pay-amount-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        // Add "amount" field
        $this->add([
            'type' => 'text',
            'name' => 'pay_amount',
            'attributes' => [
                'style' => 'width: 200px;',
                'placeholder' => $this->translator->translate('Pay Amount'),
            ],
            'options' => [
                'label' => $this->translator->translate('Pay Amount'),
            ],
        ]);

        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => $this->translator->translate('Pay')
            ],
        ]);
    }

    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'pay_amount',
            'required' => true,
            'filters' => [
                ['name' => 'Digits']
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => $this->translator->translate('Please, Enter Pay Amount'),
                        ],
                    ],
                ],
                [
                    'name' => 'Between',
                    'options' => [
                        'min' => 1,
                        'max' => 10000,
                        'messages' => [
                            Between::NOT_BETWEEN => $this->translator->translate('The input is not between 1 and 10000, inclusively'),
                        ],
                    ],
                ],
            ],
        ]);
    }
}