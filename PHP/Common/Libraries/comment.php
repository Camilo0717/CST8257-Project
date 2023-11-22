<?php

class comment{
    private $CommentId;
    private $AuthorId;
    private $PictureId;
    private $Comment_Text;
    
    public function __construct($CommentId, $AuthorId, $PictureId, $Comment_Text) {
        $this->CommentId = $CommentId;
        $this->AuthorId = $AuthorId;
        $this->PictureId = $PictureId;
        $this->Comment_Text = $Comment_Text;
    }
    
    public function getCommentId() {
        return $this->CommentId;
    }

    public function getAuthorId() {
        return $this->AuthorId;
    }

    public function getPictureId() {
        return $this->PictureId;
    }

    public function getComment_Text() {
        return $this->Comment_Text;
    }

    public function setCommentId($CommentId): void {
        $this->CommentId = $CommentId;
    }

    public function setAuthorId($AuthorId): void {
        $this->AuthorId = $AuthorId;
    }

    public function setPictureId($PictureId): void {
        $this->PictureId = $PictureId;
    }

    public function setComment_Text($Comment_Text): void {
        $this->Comment_Text = $Comment_Text;
    }

}