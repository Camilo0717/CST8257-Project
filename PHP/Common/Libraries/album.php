<?php

class album{
    private $AlbumId;
    private $Title;
    private $Description;
    private $OwnerId;
    private $accesibilityCode;
    
    public function __construct($AlbumId, $Title, $Description, $OwnerId, $accesibilityCode) {
        $this->AlbumId = $AlbumId;
        $this->Title = $Title;
        $this->Description = $Description;
        $this->OwnerId = $OwnerId;
        $this->accesibilityCode = $accesibilityCode;
    }

    public function getAlbumId() {
        return $this->AlbumId;
    }

    public function getTitle() {
        return $this->Title;
    }

    public function getDescription() {
        return $this->Description;
    }

    public function getOwnerId() {
        return $this->OwnerId;
    }

    public function getAccesibilityCode() {
        return $this->accesibilityCode;
    }

    public function setAlbumId($AlbumId): void {
        $this->AlbumId = $AlbumId;
    }

    public function setTitle($Title): void {
        $this->Title = $Title;
    }

    public function setDescription($Description): void {
        $this->Description = $Description;
    }

    public function setOwnerId($OwnerId): void {
        $this->OwnerId = $OwnerId;
    }

    public function setAccesibilityCode($accesibilityCode): void {
        $this->accesibilityCode = $accesibilityCode;
    }
}