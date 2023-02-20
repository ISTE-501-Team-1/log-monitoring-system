<?php

class Student {

    private $studentId;
    private $studentFirstName;
    private $studentMiddleInitial;
    private $studentLastName;
    private $studentUsername;
    private $schoolId;

    public function getTableData() {

        $returnString =
        "<tr>
            <td>{$this->studentId}</td>
            <td>{$this->studentFirstName}</td>
            <td>{$this->studentMiddleInitial}</td>
            <td>{$this->studentLastName}</td>
            <td>{$this->studentUsername}</td>
            <td>{$this->schoolId}</td>
        ";

        $returnString .= "
        </tr>\n";

        return $returnString;

    } // Ends getTableData function

    // Getters
    public function getStudentID() { return $this->studentId; }
    public function getStudentFirstName() { return $this->studentFirstName; }
    public function getStudentMiddleInitial() { return $this->studentMiddleInitial; }
    public function getStudentLastName() { return $this->studentLastName; }
    public function getStudentUsername() { return $this->studentUsername; }
    public function getStudentSchool() { return $this->schoolId; }

    // Setters
    public function setStudentID($studentId) { $this->studentId = $studentId; }
    public function setStudentFirstName($studentFirstName) { $this->studentFirstName = $studentFirstName; }
    public function setStudentMiddleInitial($studentMiddleInitial) { $this->studentMiddleInitial = $studentMiddleInitial; }
    public function setStudentLastName($studentLastName) { $this->studentLastName = $studentLastName; }
    public function setStudentUsername($studentUsername) { $this->studentUsername = $studentUsername; }
    public function setStudentSchool($schoolId) { $this->schoolId = $schoolId; }

} // Ends Student class

class School {

    private $schoolid;
    private $schoolname;

    public function getTableData() {

        return "<tr>
            <td>{$this->schoolid}</td>
            <td>{$this->schoolname}</td>
        </tr>\n";

    } // Ends getTableData function

    // Getters
    public function getSchoolID() { return $this->schoolid; }
    public function getSchoolName() { return $this->schoolname; }

    // Setters
    public function setSchoolID($schoolid) { $this->schoolid = $schoolid; }
    public function setSchoolName($schoolname) { $this->schoolname = $schoolname; }

} // Ends School class