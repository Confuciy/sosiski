<?php
class TeaMaker {
    protected $availableTea = [];

    public function make($preference) {
        if (empty($this->availableTea[$preference])) {
            $this->availableTea[$preference] = new KarakTea($preference);
        }

        return $this->availableTea[$preference];
    }
}