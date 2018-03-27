<?php
abstract class Builder {

    // Template method
    public final function build() {
        $this->test();
        $this->lint();
        $this->assemble();
        $this->deploy();
    }

    public abstract function test();
    public abstract function lint();
    public abstract function assemble();
    public abstract function deploy();
}