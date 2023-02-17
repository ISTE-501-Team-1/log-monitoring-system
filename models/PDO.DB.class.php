<?php 

class DB {

    private $dbh;

    /********************************GENERAL FUNCTIONS*************************************/
    function __construct() {

        require_once 'pdo-config.php';

        try {

            $this->dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Message displayed on successful connection
            // echo("Connected to $dbname at $host successfully.");

        } catch(PDOException $pe) {
            
            // Message displayed on failed connection
            die("Could not connect to the database $dbname :" . $pe->getMessage());
        
        } // Ends try catch

    } // Ends __construct

    function getAllObjects($stmtInput, $classInput) {

        $data = array();

        try {

            require_once "./controllers/DB.Controller.class.php";

            $stmt = $this->dbh->prepare($stmtInput);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS,$classInput);

            while ($row=$stmt->fetch()) {
                $data[] = $row;
            } // Ends while

            return $data;

        } catch(PDOException $pe) {
            echo $pe->getMessage();
            return array();
        } // Ends try catch

    } // Ends getAllObjects

/********************************STUDENT FUNCTIONS*************************************/
    public function getAllStudentObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM student", "Student");

        if (count($data) > 0) {

            $outputTable = "<tr>
                            <th>Student ID</th>
                            <th>Student First Name</th>
                            <th>Student Middle Initial</th>
                            <th>Student Last Name</th>
                            <th>Student Username</th>
                            <th>Student School</th>
            </tr>\n";
    
            foreach ($data as $student) {

                // TODO: Code below needs to be refactored later to display school name instead of currently displayed school ID
                // $studentSchoolID = $student->getStudentSchool();
                // $schoolObject = $this->getAllObjects("SELECT * FROM school WHERE schoolId = $studentSchoolID", "School");
             
                // foreach ($schoolObject as $school) {

                //     $studentSchoolName = $school->getSchoolName();
                //     $student->setStudentSchool($studentSchoolName);

                // } // Ends school foreach

                $outputTable .= $student->getTableData();

            } // Ends student foreach
    
        } else {
            $outputTable = "<h2>No students exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllStudentObjectsAsTable

    public function getAllStudentObjectsAsTableView() {

        $data = $this->getAllObjects("SELECT * FROM student", "Student");

        if (count($data) > 0) {

            $outputTable = "<tr>
                            <th>Student ID</th>
                            <th>Student First Name</th>
                            <th>Student Middle Initial</th>
                            <th>Student Last Name</th>
                            <th>Student Username</th>
                            <th>Student School</th>
            </tr>\n";
    
            foreach ($data as $student) {

                $studentSchoolID = $student->getStudentSchool();
                $schoolObject = $this->getAllObjects("SELECT * FROM school WHERE schoolId = $studentSchoolID", "School");
             
                foreach ($schoolObject as $school) {

                    $studentSchoolName = $school->getSchoolName();
                    $student->setStudentSchool($studentSchoolName);

                } // Ends school foreach

                $outputTable .= $student->getTableData();

            } // Ends student foreach
    
        } else {
            $outputTable = "<h2>No students exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllStudentObjectsAsTableView

} // Ends DB class