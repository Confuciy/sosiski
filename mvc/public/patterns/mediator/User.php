<?php
class User {
    protected $name;
    protected $chatRoom;

    public function __construct(string $name, ChatRoom $chatRoom) {
        $this->name = $name;
        $this->chatRoom = $chatRoom;
    }

    public function getName() {
        return $this->name;
    }

    public function send($message) {
        $this->chatRoom->showMessage($this, $message);
    }
}