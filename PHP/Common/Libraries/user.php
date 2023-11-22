<?php

class User{
    private $UserId;
    private $Name;
    private $Phone;
    private $Password;
    
    public function __construct($UserId, $Name, $Phone, $Password) {
        $this->UserId = $UserId;
        $this->Name = $Name;
        $this->Phone = $Phone;
        $this->Password = $Password;
    }
    
    public function getUserId() {
        return $this->UserId;
    }

    public function getName() {
        return $this->Name;
    }

    public function getPhone() {
        return $this->Phone;
    }

    public function setUserId($UserId): void {
        $this->UserId = $UserId;
    }

    public function setName($Name): void {
        $this->Name = $Name;
    }

    public function setPhone($Phone): void {
        $this->Phone = $Phone;
    }

    public function getPassword() {
        return $this->Password;
    }

    public function setPassword($Password): void {
        $this->Password = $Password;
    }


}