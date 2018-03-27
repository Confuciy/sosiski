<?php
class RemoteControl {

    public function submit(CommandInterface $command) {
        $command->execute();
    }
}