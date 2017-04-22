<?php
namespace Application\Service;

class PaymentManager
{
    private $bank;
    private $paypal;
    private $bitcoin;
    private $message = [];

    public function __construct($bank, $paypal, $bitcoin)
    {
        $this->bank = $bank;
        $this->paypal = $paypal;
        $this->bitcoin = $bitcoin;

        $this->bank->setNext($paypal);
        $this->paypal->setNext($bitcoin);
    }

    public function pay(float $amountToPay = 0)
    {
        $this->bank->pay($amountToPay);
        if ($this->bank->message != '') {
            $this->message[] = $this->bank->message;
        }
        if ($this->paypal->message != '') {
            $this->message[] = $this->paypal->message;
        }
        if ($this->bitcoin->message != '') {
            $this->message[] = $this->bitcoin->message;
        }

        return $this->message;
    }
}