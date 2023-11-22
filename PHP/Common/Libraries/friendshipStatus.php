<?php

class friendshipStatus{
    private $StatusCode;
    private $Description;
    
    public function __construct($StatusCode, $Description) {
        $this->StatusCode = $StatusCode;
        $this->Description = $Description;
    }
    
    public function getStatusCode() {
        return $this->StatusCode;
    }

    public function getDescription() {
        return $this->Description;
    }

    public function setStatusCode($StatusCode): void {
        $this->StatusCode = $StatusCode;
    }

    public function setDescription($Description): void {
        $this->Description = $Description;
    }

}