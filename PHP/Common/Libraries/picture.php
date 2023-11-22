<?php

class picture{
    private $PictureId;
    private $AlbumId;
    private $FileName;
    private $Title;
    private $Description;
    
    public function __construct($PictureId, $AlbumId, $FileName, $Title, $Description) {
        $this->PictureId = $PictureId;
        $this->AlbumId = $AlbumId;
        $this->FileName = $FileName;
        $this->Title = $Title;
        $this->Description = $Description;
    }

    public function getPictureId() {
        return $this->PictureId;
    }

    public function getAlbumId() {
        return $this->AlbumId;
    }

    public function getFileName() {
        return $this->FileName;
    }

    public function getTitle() {
        return $this->Title;
    }

    public function getDescription() {
        return $this->Description;
    }

    public function setPictureId($PictureId): void {
        $this->PictureId = $PictureId;
    }

    public function setAlbumId($AlbumId): void {
        $this->AlbumId = $AlbumId;
    }

    public function setFileName($FileName): void {
        $this->FileName = $FileName;
    }

    public function setTitle($Title): void {
        $this->Title = $Title;
    }

    public function setDescription($Description): void {
        $this->Description = $Description;
    }

}

