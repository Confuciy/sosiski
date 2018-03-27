<?php
class DefaultCase implements WritingState {
    public function write(string $words) {
        echo $words;
    }
}