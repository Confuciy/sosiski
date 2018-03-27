<?php
abstract class HiringManager {

    // Factory method
    abstract public function makeInterviewer() : Interviewer;

    public function takeInterview() {
        $interviewer = $this->makeInterviewer();
        $interviewer->askQuestions();
    }
}