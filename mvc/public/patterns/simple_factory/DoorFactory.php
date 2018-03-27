<?php
class DoorFactory {
   public static function makeDoor($width, $height) : Door {
       return new WoodenDoor($width, $height);
   }
}