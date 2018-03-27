<?php
class JobSeeker {
    protected $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function onJobPosted(JobPost $job) {
        // Do something with the job posting
        echo 'Hi ' . $this->name . '!<br />New job posted: '. $job->getTitle() . '<br /><br />';
    }
}