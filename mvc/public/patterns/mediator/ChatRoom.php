<?php
class ChatRoom {
    public function showMessage(User $user, string $message) {
        $time = date('M d, y H:i');
        $sender = $user->getName();

        echo $time . ' [' . $sender . ']: ' . $message . '<br />';
    }
}