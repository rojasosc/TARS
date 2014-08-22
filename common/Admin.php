<?php

final class Admin extends User {
    public function __construct($user_row, $admin_row) {
        parent::__construct($user_row);
    }

    public function toArray($showEvent = false) {
        return parent::toArray($showEvent);
    }

    public function updateProfile($firstName, $lastName) {
        $sql = 'UPDATE Users
                SET firstName = :firstName, lastName = :lastName
                WHERE userID = :id';
        $args = array(':id'=>$this->id, ':firstName'=>$firstName, ':lastName'=>$lastName);
        Database::execute($sql, $args);
    }
}

