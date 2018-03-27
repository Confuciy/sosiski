<?php
class Dolphin implements Animal {
    public function speak() {
        echo 'Tuut tuttu tuutt!';
    }

    public function accept(AnimalOperation $operation) {
        $operation->visitDolphin($this);
    }
}