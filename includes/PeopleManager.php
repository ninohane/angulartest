<?php

require_once 'ConnectionManager.php';

class PeopleManager {

    private $cm;
    
    public function __construct() {
        $this->cm = ConnectionManager::getInstance();
    }

    public function getPeople() {
        $query = "SELECT * FROM people";
        $this->cm->query($query);
        return $this->cm->fetchAll();
    }

    public function addPerson($name, $surname, $age, $email) {
        $query = "INSERT INTO people(name, surname, age, email) VALUES('$name', '$surname', '$age', '$email')";
        return $this->cm->query($query);
    }

    public function deletePerson($id) {
        $query = "DELETE FROM people WHERE id='$id'";
        return $this->cm->query($query);
    }
}

?>