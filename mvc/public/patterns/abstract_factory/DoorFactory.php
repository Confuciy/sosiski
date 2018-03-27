<?php
interface DoorFactory {
    public function makeDoor() : Door;
    public function makeFittingExpert() : DoorFittingExpert;
}