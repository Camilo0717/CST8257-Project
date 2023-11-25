<?php

class User{
    private $UserId;
    private $Name;
    
    public function __construct($UserId, $Name) {
        $this->UserId = $UserId;
        $this->Name = $Name;
    }
    
    public function getUserId() {
        return $this->UserId;
    }

    public function getName() {
        return $this->Name;
    }

    public function setUserId($UserId): void {
        $this->UserId = $UserId;
    }

    public function setName($Name): void {
        $this->Name = $Name;
    }

}