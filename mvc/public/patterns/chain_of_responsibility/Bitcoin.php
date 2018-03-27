<?php
class Bitcoin extends Account {
    protected $balance;

    public function __construct(float $balance) {
        $this->balance = $balance;
    }
}