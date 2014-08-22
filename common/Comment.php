<?php

final class Comment {
    public static function getCommentByID($commentID){
        $sql = "SELECT * FROM Comments WHERE commentID = :commentID";
        $args = array('commentID' => $commentID);
        $row = Database::executeGetRow($sql, $args);
        if ($row == null) {
            return null;
        }
        return new Comment($row);
    }

    public static function insertComment($comment, $studentID, $creatorID, $createTime) {
        $sql = "INSERT INTO Comments (commentText, studentID, creatorID, createTime) VALUES
                (:comment, :student, :creator, :createTime)";
        $args = array(':comment' => $comment, ':student' => $studentID,
                ':creator' => $creatorID, ':createTime' => date('Y-m-d H:i:s', $createTime));
        return Database::executeInsert($sql, $args);
    }

    public function __construct($row){
        $this->id = $row['commentID'];
        $this->commentText = $row['commentText'];
        $this->studentID = $row['studentID'];
        $this->student = null;
        $this->creatorID = $row['creatorID'];
        $this->creator = null;
        $this->createTime = strtotime($row['createTime']);
    }

    public function getID(){ return $this->id; }
    public function getComment(){ return $this->commentText; }
    public function getStudent(){
        if ($this->student == null) {
            $this->student = User::getUserByID($this->studentID, STUDENT);
        }
        return $this->student;
    }
    public function getCreator() {
        if ($this->creator == null) {
            $this->creator = User::getUserByID($this->creatorID);
        }
        return $this->creator;
    }
    public function getCreateTime() { return $this->createTime; }

    public function toArray($showEvent = true) {
        $data = array(
            'id' => intval($this->id),
            'comment' => $this->commentText,
            'student' => $this->getStudent()->toArray(false)
        );
        if ($showEvent) {
            $data['creator'] = $this->getCreator()->toArray(false);
            $data['createTime'] = date('g:i:sa \o\n Y/m/d', $this->createTime);
        }
        return $data;
    }

    private $id;
    private $commentText;
    private $studentID;
    private $student;
    private $creatorID;
    private $creator;
    private $createTime;
}

