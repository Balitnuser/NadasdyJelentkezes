<?php

class Applicant {
    public $id;
    public $name;
    public $school;
    public $majors;
    public $dorm;
    public $senior;
    public function __construct($id, $name, $school, $majors, $dorm, $senior) {
        $this->id = $id;
        $this->name = $name;
        $this->school = $school;
        $this->majors = $majors;
        $this->dorm = $dorm;
        $this->senior = $senior;
    }

}
