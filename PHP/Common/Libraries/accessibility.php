<?php

class accessibility{
    private $AccesibilityCode;
    private $Description;
    
    public function __construct($AccesibilityCode, $Description) {
        $this->AccesibilityCode = $AccesibilityCode;
        $this->Description = $Description;
    }
    
    public function getAccesibilityCode() {
        return $this->AccesibilityCode;
    }

    public function getDescription() {
        return $this->Description;
    }

    public function setAccesibilityCode($AccesibilityCode): void {
        $this->AccesibilityCode = $AccesibilityCode;
    }

    public function setDescription($Description): void {
        $this->Description = $Description;
    }


}

