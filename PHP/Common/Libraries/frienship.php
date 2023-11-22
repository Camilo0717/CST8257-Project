<?php

class friendship{
    private $FriendRequesterId;
    private $FriendRequesteeId;
    private $Status; // This is StatusCode
    
    public function __construct($FriendRequesterId, $FriendRequesteeId, $Status) {
        $this->FriendRequesterId = $FriendRequesterId;
        $this->FriendRequesteeId = $FriendRequesteeId;
        $this->Status = $Status;
    }
    
    public function getFriendRequesterId() {
        return $this->FriendRequesterId;
    }

    public function getFriendRequesteeId() {
        return $this->FriendRequesteeId;
    }

    public function getStatus() {
        return $this->Status;
    }

    public function setFriendRequesterId($FriendRequesterId): void {
        $this->FriendRequesterId = $FriendRequesterId;
    }

    public function setFriendRequesteeId($FriendRequesteeId): void {
        $this->FriendRequesteeId = $FriendRequesteeId;
    }

    public function setStatus($Status): void {
        $this->Status = $Status;
    }
}