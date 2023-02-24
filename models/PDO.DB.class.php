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

/********************************CLASS FUNCTIONS*************************************/
    // NOTE: Since "class" is a reserved word, the PHP class to interact with the database table "class" is called "ClassTable"

    public function getAllClassObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM class", "ClassTable");

        if (count($data) > 0) {

            $outputTable = "<tr>
                            <th>Class ID</th>
                            <th>Class Name</th>
                            <th>Class Professor</th>
                            <th>School ID</th>
            </tr>\n";

            foreach ($data as $class) {

                // TODO: Code below needs to be refactored later to display school name instead of currently displayed school ID
                // Should update class object and replace the schoolId with the name of the school
                $classSchoolID = $class->getClassSchoolID();
                $schoolObject = $this->getAllObjects("SELECT schoolName FROM school WHERE schoolId = $classSchoolID", "School");
            
                foreach ($schoolObject as $school) {

                    $classSchoolName = $school->getSchoolName();
                    $class->setClassSchoolID($classSchoolName);

                } // Ends school foreach

                $outputTable .= $class->getTableData();

            } // Ends class foreach

        } else {
            $outputTable = "<h2>No classes exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllClassObjectsAsTable


/********************************CLASSENTRY FUNCTIONS*************************************/
    public function getAllClassEntryObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM classEntry", "ClassEntry");

        if (count($data) > 0) {

            $outputTable = "<tr>
                            <th>Student ID</th>
                            <th>Class ID</th>
            </tr>\n";
    
            foreach ($data as $classEntry) {

                $outputTable .= $classEntry->getTableData();

            } // Ends classEntry foreach
    
        } else {
            $outputTable = "<h2>No classEntry records exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllClassEntryObjectsAsTable

/********************************FILE FUNCTIONS*************************************/
    public function getAllFileObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM file", "File");

        if (count($data) > 0) {

            $outputTable = "<tr>
                            <th>File ID</th>
                            <th>File Name</th>
                            <th>File Time Created</th>
                            <th>File Time Edited</th>
                            <th>File Location</th>
                            <th>Student ID</th>
            </tr>\n";
    
            foreach ($data as $file) {

                $fileStudentID = $file->getFileStudentID();
                $studentObject = $this->getAllObjects("SELECT * FROM student WHERE studentId = $fileStudentID", "Student");
             
                foreach ($studentObject as $student) {

                    $fileStudentUsername = $student->getStudentUsername();
                    $file->setFileStudentID($fileStudentUsername);

                } // Ends student foreach

                $outputTable .= $file->getTableData();

            } // Ends file foreach
    
        } else {
            $outputTable = "<h2>No files exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllFileObjectsAsTable

/********************************LOG FUNCTIONS*************************************/
    public function getAllLogObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM log", "Log");

        if (count($data) > 0) {

            $outputTable = "<tr>
                            <th>Log ID</th>
                            <th>Log Time Created</th>
                            <th>Log Time Edited</th>
                            <th>Login Attempt ID</th>
                            <th>Student ID</th>
            </tr>\n";
    
            foreach ($data as $log) {

                $logStudentID = $log->getLogStudentID();
                $studentObject = $this->getAllObjects("SELECT * FROM student WHERE studentId = $logStudentID", "Student");
             
                foreach ($studentObject as $student) {

                    $logStudentUsername = $student->getStudentUsername();
                    $log->setLogStudentID($logStudentUsername);

                } // Ends student foreach

                $outputTable .= $log->getTableData();

            } // Ends log foreach
    
        } else {
            $outputTable = "<h2>No logs exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllLogObjectsAsTable

/********************************LOGINATTEMPT FUNCTIONS*************************************/
    
    public function getAllLoginAttemptObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM loginAttempt", "LoginAttempt");

        if (count($data) > 0) {

            $outputTable = "<tr>
                            <th>Login Attempt ID</th>
                            <th>Login Attempt Username</th>
                            <th>Login Attempt Password</th>
                            <th>Login Attempt Time Entered</th>
                            <th>Login Attempt Success</th>
                            <th>Student ID</th>
            </tr>\n";
    
            foreach ($data as $loginAttempt) {

                $outputTable .= $loginAttempt->getTableData();

            } // Ends loginAttempt foreach
    
        } else {
            $outputTable = "<h2>No login attempt records exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllLoginAttemptObjectsAsTable

/********************************SCHOOL FUNCTIONS*************************************/
    
    public function getAllSchoolObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM school", "School");

        if (count($data) > 0) {

            $outputTable = "<tr>
                            <th>School ID</th>
                            <th>School Name</th>
            </tr>\n";
    
            foreach ($data as $school) {

                $outputTable .= $school->getTableData();

            } // Ends school foreach
    
        } else {
            $outputTable = "<h2>No schools exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllSchoolObjectsAsTable

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

                $studentSchoolID = $student->getStudentSchoolID();
                $schoolObject = $this->getAllObjects("SELECT * FROM school WHERE schoolId = $studentSchoolID", "School");
             
                foreach ($schoolObject as $school) {

                    $studentSchoolName = $school->getSchoolName();
                    $student->setStudentSchoolID($studentSchoolName);

                } // Ends school foreach

                $outputTable .= $student->getTableData();

            } // Ends student foreach
    
        } else {
            $outputTable = "<h2>No students exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllStudentObjectsAsTable

/********************************USER FUNCTIONS*************************************/
    
    public function getAllUserObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM user", "User");

        if (count($data) > 0) {

            $outputTable = "<tr>
                            <th>User ID</th>
                            <th>User First Name</th>
                            <th>User Last Name</th>
                            <th>User Email</th>
                            <th>User Username</th>
                            <th>User Password</th>
                            <th>User Classification</th>
                            <th>School ID</th>
            </tr>\n";
    
            foreach ($data as $user) {

                $userSchoolID = $user->getUserSchoolID();
                $schoolObject = $this->getAllObjects("SELECT * FROM school WHERE schoolId = $userSchoolID", "School");
             
                foreach ($schoolObject as $school) {

                    $userSchoolName = $school->getSchoolName();
                    $user->setUserSchoolID($userSchoolName);

                } // Ends school foreach

                $outputTable .= $user->getTableData();

            } // Ends user foreach
    
        } else {
            $outputTable = "<h2>No users exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllUserObjectsAsTable

} // Ends DB class