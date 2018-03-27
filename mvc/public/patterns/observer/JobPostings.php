<?php
class JobPostings {
    protected $observers = [];

    protected function notify(JobPost $jobPosting) {
        foreach ($this->observers as $observer) {
            $observer->onJobPosted($jobPosting);
        }
    }

    public function attach(JobSeeker $observer) {
        $this->observers[] = $observer;
    }

    public function addJob(JobPost $jobPosting) {
        $this->notify($jobPosting);
    }
}