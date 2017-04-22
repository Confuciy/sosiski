<?php
namespace Application\Service\Payment;

abstract class Account
{
    protected $translator;
    protected $successor;
    protected $balance;
    public static $message = '';

    public function setNext(Account $account)
    {
        $this->successor = $account;
    }

    public function pay(float $amountToPay)
    {
        $class = str_replace('Application\Service\Payment\\', '', get_called_class());
        if ($this->canPay($amountToPay)) {
            $this->message = sprintf($this->translator->translate('Paid %s using %s, balance: %s') . PHP_EOL, $amountToPay, $class, $this->getBalance($amountToPay));
        } elseif ($this->successor) {
            $this->successor->pay($amountToPay);
        } else {
            $this->message = $this->translator->translate('None of the accounts have enough balance');
        }
    }

    public function canPay($amount): bool
    {
        return $this->balance >= $amount;
    }

    private function getBalance(float $amountToPay) : float
    {
        return round(($this->balance - $amountToPay), 2);
    }

    protected function setTranslator($translator)
    {
        $this->translator = $translator;
    }
}