<?php
class IosBuilder extends Builder {
    public function test() {
        echo 'Running ios tests<br />';
    }

    public function lint() {
        echo 'Linting the ios code<br />';
    }

    public function assemble() {
        echo 'Assembling the ios build<br />';
    }

    public function deploy() {
        echo 'Deploying ios build to server<br />';
    }
}