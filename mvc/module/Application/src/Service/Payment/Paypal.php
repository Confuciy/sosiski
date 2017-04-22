<?php
namespace Application\Service\Payment;

class Paypal extends Account
{
    protected $balance;

    public function __construct($translator, float $balance)
    {
        $this->balance = $balance;
        parent::setTranslator($translator);
    }
}