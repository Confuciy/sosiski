<?php
class Lion implements Animal {
    public function roar() {
        echo 'Roaaar!';
    }

    public function accept(AnimalOperation $operation) {
        $operation->visitLion($this);
    }
}