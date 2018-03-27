<?php
abstract class Account {

    protected $successor;
    protected $balance;

    public function setNext(Account $account) {
        $this->successor = $account;
    }

    public function pay(float $amountToPay) {
        if ($this->canPay($amountToPay)) {
            echo sprintf('Paid %s using %s<br />' . PHP_EOL, $amountToPay, get_called_class());
        } else if ($this->successor) {
            echo sprintf('Cannot pay using %s. Proceeding ..<br />' . PHP_EOL, get_called_class());
            $this->successor->pay($amountToPay);
        } else {
            throw Exception('None of the accounts have enough balance');
        }
    }

    public function canPay($amount) : bool {
        return $this->balance >= $amount;
    }
}