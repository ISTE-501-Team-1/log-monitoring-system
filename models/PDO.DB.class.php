<?php 

class DB {

    private $dbh;

/********************************GENERAL FUNCTIONS*************************************/
    
    function __construct() {

        require '../models/pdo-config.php';

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

            require_once "../controllers/DB.Controller.class.php";

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

/********************************ACTIVITY FUNCTIONS*************************************/

    public function getAllActivityObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM activity", "Activity");

        if (count($data) > 0) {
            
            $outputTable = "<thead><tr>
                            <th>Activity ID</th>
                            <th>Activity User ID</th>
                            <th>Activyty Log ID</th>
                            <th>Activity Student ID</th>
                            <th>Activity Datetime</th>
            </tr></thead>\n";

            foreach ($data as $activity) {

                $outputTable .= $activity->getTableData();

            } // Ends activity foreach

        } else {
            $outputTable = "<h2>No activities exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllActivityObjectsAsTable

    // Returns a table of recently viewed students. userID is the ID of the user you are trying to get the table for, and limitNum is the number limit of row you want to get, or can be set to 0 to get all records.
    public function getActivityRecentStudents($userID, $limitNum) {

        if ($limitNum == 0) {
            $data = $this->getAllObjects("SELECT * FROM activity WHERE activityUserId = $userID AND activityLogId IS NULL ORDER BY activityDatetime DESC", "Activity");
        } else {
            $data = $this->getAllObjects("SELECT * FROM activity WHERE activityUserId = $userID AND activityLogId IS NULL ORDER BY activityDatetime DESC LIMIT $limitNum", "Activity");
        } // Ends limit if

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Student ID</th>
                            <th>Student First Name</th>
                            <th>Student Middle Initial</th>
                            <th>Student Last Name</th>
                            <th>Student Username</th>
                            <th>Student School</th>
            </tr></thead>\n";
    
            foreach ($data as $activity) {

                $activityStudentID = $activity->getActivityStudentID();
                $activityStudentObject = $this->getAllObjects("SELECT * FROM student WHERE studentId = $activityStudentID", "Student");

                foreach ($activityStudentObject as $student) {

                    $studentSchoolID = $student->getStudentSchoolID();
                    $schoolObject = $this->getAllObjects("SELECT * FROM school WHERE schoolId = $studentSchoolID", "School");
                
                    foreach ($schoolObject as $school) {

                        $studentSchoolName = $school->getSchoolName();
                        $student->setStudentSchoolID($studentSchoolName);

                    } // Ends school foreach

                    $outputTable .= $student->getTableLinkingRow();

                } // Ends student foreach
            
            } // Ends activity foreach
    
        } else {
            $outputTable = "<h2>You have not previously viewed any students...</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllActivityRecentStudents

    // Returns a table of recently viewed logs. userID is the ID of the user you are trying to get the table for, and limitNum is the number limit of row you want to get, or can be set to 0 to get all records.
    public function getActivityRecentLogs($userID, $limitNum) {

        if ($limitNum == 0) {
            $data = $this->getAllObjects("SELECT * FROM activity WHERE activityUserId = $userID AND activityStudentId IS NULL ORDER BY activityDatetime DESC", "Activity");
        } else {
            $data = $this->getAllObjects("SELECT * FROM activity WHERE activityUserId = $userID AND activityStudentId IS NULL ORDER BY activityDatetime DESC LIMIT $limitNum", "Activity");
        } // Ends limit if

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Log ID</th>
                            <th>Log Type</th>
                            <th>Log Time Created</th>
                            <th>Login Attempt ID</th>
                            <th>Student Username</th>
            </tr></thead>\n";
    
            foreach ($data as $activity) {

                $activityLogID = $activity->getActivityLogID();
                $activityLogObject = $this->getAllObjects("SELECT * FROM log WHERE logId = $activityLogID", "Log");

                foreach ($activityLogObject as $log) {

                    $logStudentID = $log->getLogStudentID();
                    $studentObject = $this->getAllObjects("SELECT * FROM student WHERE studentId = $logStudentID", "Student");
                
                    foreach ($studentObject as $student) {

                        $logStudentUsername = $student->getStudentUsername();
                        $log->setLogStudentID($logStudentUsername);

                    } // Ends student foreach

                    $outputTable .= $log->getTableLinkingRow();

                } // Ends log foreach
            
            } // Ends activity foreach
    
        } else {
            $outputTable = "<h2>You have not previously viewed any logs...</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllActivityRecentLogs

    // Returns the logs appropriate for the specific user. Used for pagination
    public function getActivityLogObjectsAsTable($userID, $currentPageNumber, $recordsPerPage) {

        $offset = ($currentPageNumber - 1) * $recordsPerPage;

        $data = $this->getAllObjects("SELECT * FROM activity WHERE activityUserId = $userID 
        AND activityStudentId IS NULL ORDER BY activityDatetime DESC 
        LIMIT $offset, $recordsPerPage", "Activity");

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Log ID</th>
                            <th>Log Type</th>
                            <th>Log Time Created</th>
                            <th>Login Attempt ID</th>
                            <th>Student Username</th>
            </tr></thead>\n";
    
            foreach ($data as $activity) {

                $activityLogID = $activity->getActivityLogID();
                $activityLogObject = $this->getAllObjects("SELECT * FROM log WHERE logId = $activityLogID", "Log");

                foreach ($activityLogObject as $log) {

                    $logStudentID = $log->getLogStudentID();
                    $studentObject = $this->getAllObjects("SELECT * FROM student WHERE studentId = $logStudentID", "Student");
                
                    foreach ($studentObject as $student) {

                        $logStudentUsername = $student->getStudentUsername();
                        $log->setLogStudentID($logStudentUsername);

                    } // Ends student foreach

                    $outputTable .= $log->getTableLinkingRow();

                } // Ends log foreach
            
            } // Ends activity foreach
    
        } else {
            $outputTable = "<h3>You have not previously viewed any logs...</h3>";
        }// Ends if

        return $outputTable;

    } // Ends getActivityLogObjectsAsTable

    public function getActivityLogObjectsCount($userID) {

        $data = $this->getAllObjects("SELECT * FROM activity WHERE activityUserId = $userID 
        AND activityStudentId IS NULL", "Activity");

        if (count($data) > 0) {
            return count($data);
        } else {
            return 0;
        } // Ends if

    } // Ends getActivityLogObjectsCount

    // Returns the students appropriate for the specific user. Used for pagination
    public function getActivityStudentObjectsAsTable($userID, $currentPageNumber, $recordsPerPage) {

        $offset = ($currentPageNumber - 1) * $recordsPerPage;

        $data = $this->getAllObjects("SELECT * FROM activity WHERE activityUserId = $userID AND activityLogId IS NULL 
        ORDER BY activityDatetime DESC LIMIT $offset, $recordsPerPage", "Activity"); 

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Student ID</th>
                            <th>Student First Name</th>
                            <th>Student Middle Initial</th>
                            <th>Student Last Name</th>
                            <th>Student Username</th>
                            <th>Student School</th>
            </tr></thead>\n";

            foreach ($data as $activity) {

                $activityStudentID = $activity->getActivityStudentID();
                $activityStudentObject = $this->getAllObjects("SELECT * FROM student WHERE studentId = $activityStudentID", "Student");

                foreach ($activityStudentObject as $student) {

                    $studentSchoolID = $student->getStudentSchoolID();
                    $schoolObject = $this->getAllObjects("SELECT * FROM school WHERE schoolId = $studentSchoolID", "School");
                
                    foreach ($schoolObject as $school) {

                        $studentSchoolName = $school->getSchoolName();
                        $student->setStudentSchoolID($studentSchoolName);

                    } // Ends school foreach

                    $outputTable .= $student->getTableLinkingRow();

                } // Ends student foreach
            
            } // Ends activity foreach

        } else {
            $outputTable = "<h2>You have not previously viewed any students...</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getActivityStudentObjectsAsTable

    public function getActivityStudentObjectsCount($userID) {

        $data = $this->getAllObjects("SELECT * FROM activity WHERE activityUserId = $userID AND activityLogId IS NULL", "Activity");

        if (count($data) > 0) {
            return count($data);
        } else {
            return 0;
        } // Ends if

    } // Ends getActivityStudentObjectsCount

    // Inserts a record with the current user ID and the ID of the student the user was viewing. Updates the record if the user has previously viewed the student
    public function insertActivityViewedStudent($userID, $studentID) {

        require_once("DB.Controller.class.php");

        $Activity = new Activity;
        $Activity->setActivityUserID($userID);
        $Activity->setActivityStudentID($studentID);

        $data = $this->getAllObjects("SELECT * FROM activity WHERE activityUserId = $userID AND activityStudentId = $studentID", "Activity");

        try {

            if (count($data) > 0) {

                $stmt = $this->dbh->prepare("
                    UPDATE activity
                    activityUserId = :activityUserId, activityStudentId = :activityStudentId, activityDatetime = NOW()
                    WHERE activityUserId = :activityUserId AND activityStudentId = :activityStudentId
                ");

                $stmt->execute(array(
                    "activityUserId"=>$Activity->getActivityUserID(),
                    "activityStudentId"=>$Activity->getActivityStudentID()
                ));

            } else {

                $stmt = $this->dbh->prepare("
                    INSERT INTO activity (activityUserId, activityStudentId)
                    VALUES (:activityUserId, :activityStudentId)
                ");

                $stmt->execute(array(
                    "activityUserId"=>$Activity->getActivityUserID(),
                    "activityStudentId"=>$Activity->getActivityStudentID()
                ));

            } // Ends if

        } catch (PDOException $pe) {
            echo $pe->getMessage();
            return -1;
        } // Ends try catch

    } // Ends insertActivityViewedStudent function

    // Inserts a record with the current user ID and the ID of the log the user was viewing
    public function insertActivityViewedLog($userID, $logID) {

        require_once("DB.Controller.class.php");

        $Activity = new Activity;
        $Activity->setActivityUserID($userID);
        $Activity->setActivityLogID($logID);

        $data = $this->getAllObjects("SELECT * FROM activity WHERE activityUserId = $userID AND activityLogId = $logID", "Activity");

        try {

            if (count($data) > 0) {

                $stmt = $this->dbh->prepare("
                    UPDATE activity
                    SET activityUserId = :activityUserId, activityLogId = :activityLogId, activityDatetime = NOW()
                    WHERE activityUserId = :activityUserId AND activityLogId = :activityLogId
                ");

                $stmt->execute(array(
                    "activityUserId"=>$Activity->getActivityUserID(),
                    "activityLogId"=>$Activity->getActivityLogID()
                ));

            } else {

                $stmt = $this->dbh->prepare("
                    INSERT INTO activity (activityUserId, activityLogId)
                    VALUES (:activityUserId, :activityLogId)
                ");

                $stmt->execute(array(
                    "activityUserId"=>$Activity->getActivityUserID(),
                    "activityLogId"=>$Activity->getActivityLogID()
                ));

            } // Ends if

        } catch (PDOException $pe) {
            echo $pe->getMessage();
            return -1;
        } // Ends try catch

    } // Ends insertActivityViewedLog function

/********************************CLASS FUNCTIONS*************************************/
    // NOTE: Since "class" is a reserved word, the PHP class to interact with the database table "class" is called "ClassTable"

    public function getAllClassObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM class", "ClassTable");

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Class ID</th>
                            <th>Class Name</th>
                            <th>Class Professor</th>
                            <th>School ID</th>
            </tr></thead>\n";

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

            $outputTable = "<thead><tr>
                            <th>Student ID</th>
                            <th>Class ID</th>
            </tr></thead>\n";
    
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
            
            $outputTable = "<thead><tr>
                            <th>File ID</th>
                            <th>File Name</th>
                            <th>File Time Created</th>
                            <th>File Time Edited</th>
                            <th>File Location</th>
                            <th>Student ID</th>
            </tr></thead>\n";
    
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

    public function getFileObjectsByStudentAsTable($studentID, $currentPageNumber, $recordsPerPage) {

        $offset = ($currentPageNumber - 1) * $recordsPerPage;

        $data = $this->getAllObjects("SELECT * FROM file WHERE studentId = $studentID LIMIT $offset, $recordsPerPage", "File");

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>File ID</th>
                            <th>File Name</th>
                            <th>File Time Created</th>
                            <th>File Time Edited</th>
                            <th>File Location</th>
                            <th>Student ID</th>
            </tr></thead>\n";
    
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

    } // Ends getFileObjectsByStudentAsTable

    public function getFileObjectsByStudentCount($studentID) {

        $data = $this->getAllObjects("SELECT * FROM file WHERE studentId = $studentID", "File");

        if (count($data) > 0) {
            return count($data);
        } else {
            return 0;
        } // Ends if

    } // Ends getFileObjectsByStudentCount

/********************************LOG FUNCTIONS*************************************/
    
    public function getAllLogObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM log", "Log");

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Log ID</th>
                            <th>Log Type</th>
                            <th>Log Time Created</th>
                            <th>Login Attempt ID</th>
                            <th>Student ID</th>
            </tr></thead>\n";
    
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

    public function getAllLogObjectsAfterDatetime($datetime) {

        $data = array();

        try {

            require_once "../controllers/DB.Controller.class.php";
            $stmt = $this->dbh->prepare("SELECT * FROM log WHERE logTimeCreated >= :timeCreated");
            $stmt->execute(array(":timeCreated" => $dateTime));
            $data = $stmt->fetchAll(PDO::FETCH_CLASS, "loginAttempt");

        } catch(PDOException $pe) {
            echo $pe->getMessage();
            return array();
        } // Ends try catch

        $outputTable = "<thead><tr>
                        <th>Log ID</th>
                        <th>Log Type</th>
                        <th>Log Time Created</th>
                        <th>Login Attempt ID</th>
                        <th>Student ID</th>
        </tr></thead>\n";
            
        if (count($data) > 0) {
            
            foreach ($data as $log) {

                $outputTable .= $log->getTableData();

            } // Ends log foreach
    
        } else {
            $outputTable = "<h2>No logs exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllLogObjectsAsTable

    // Returns the number of logs that were created today
    public function getLogsCreatedTodayCount($userID, $userType) {

        if ($userType == "Admin") { // Gets all logs that were created today

            $data = $this->getAllObjects("SELECT * FROM log WHERE DATE(logTimeCreated) = CURDATE()", "Log");

        } else if ($userType == "Professor") { // Gets all logs for students that are in the classes under the professor

            $data = $this->getAllObjects("SELECT log.* FROM log
            INNER JOIN student ON log.studentId = student.studentId INNER JOIN classEntry ON student.studentId = classEntry.studentId INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID
            WHERE DATE(log.logTimeCreated) = CURDATE()", "Log");

        } else if ($userType == "Support") { // Gets all logs for students that are in the same school under a support

            $data = $this->getAllObjects("SELECT log.* FROM log
            INNER JOIN student ON log.studentId = student.studentId INNER JOIN school ON student.schoolId = school.schoolId INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID
            WHERE DATE(log.logTimeCreated) = CURDATE()", "Log");

        } // Ends if

        if (is_array($data)) {
            return count($data);
        } else {
            return 0;
        } // Ends if

    } // Ends getCountLogsCreatedToday

    // Returns all log objects that were created today
    public function getLogsCreatedTodayAsTable($userID, $userType, $currentPageNumber, $recordsPerPage) {

        $offset = ($currentPageNumber - 1) * $recordsPerPage;

        if ($userType == "Admin") { // Gets all logs that were created today

            $data = $this->getAllObjects("SELECT * FROM log WHERE DATE(logTimeCreated) = CURDATE() LIMIT $offset, $recordsPerPage", "Log");

        } else if ($userType == "Professor") { // Gets all logs for students that are in the classes under the professor

            $data = $this->getAllObjects("SELECT log.* FROM log
            INNER JOIN student ON log.studentId = student.studentId INNER JOIN classEntry ON student.studentId = classEntry.studentId INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID
            WHERE DATE(log.logTimeCreated) = CURDATE() LIMIT $offset, $recordsPerPage", "Log");

        } else if ($userType == "Support") { // Gets all logs for students that are in the same school under a support

            $data = $this->getAllObjects("SELECT log.* FROM log
            INNER JOIN student ON log.studentId = student.studentId INNER JOIN school ON student.schoolId = school.schoolId INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID
            WHERE DATE(log.logTimeCreated) = CURDATE() LIMIT $offset, $recordsPerPage", "Log");

        } // Ends if

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Log ID</th>
                            <th>Log Type</th>
                            <th>Log Time Created</th>
                            <th>Login Attempt ID</th>
                            <th>Student Username</th>
            </tr></thead>\n";
    
            foreach ($data as $log) {

                $logStudentID = $log->getLogStudentID();
                $studentObject = $this->getAllObjects("SELECT * FROM student WHERE studentId = $logStudentID", "Student");
             
                foreach ($studentObject as $student) {

                    $logStudentUsername = $student->getStudentUsername();
                    $log->setLogStudentID($logStudentUsername);

                } // Ends student foreach

                $outputTable .= $log->getTableLinkingRow();

            } // Ends log foreach
    
        } else {
            $outputTable = "<h2>No logs exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getLogsCreatedTodayAsTable

    // Returns the logs appropriate for the specific user. Used for pagination
    public function getLogObjectsByRoleAsTable($userID, $userType, $currentPageNumber, $recordsPerPage) {

        $offset = ($currentPageNumber - 1) * $recordsPerPage;

        if ($userType == "Admin") {

            $data = $this->getAllObjects("SELECT * FROM log LIMIT $offset, $recordsPerPage", "Log");

        } else if ($userType == "Professor") { // Gets all logs for students that are in the classes under the professor

            $data = $this->getAllObjects("SELECT log.* FROM log
            INNER JOIN student ON log.studentId = student.studentId 
            INNER JOIN classEntry ON student.studentId = classEntry.studentId 
            INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID
            LIMIT $offset, $recordsPerPage", 
            "Log");

        } else if ($userType == "Support") { // Gets all logs for students that are in the same school under a support

            $data = $this->getAllObjects("SELECT log.* FROM log
            INNER JOIN student ON log.studentId = student.studentId 
            INNER JOIN school ON student.schoolId = school.schoolId 
            INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID
            LIMIT $offset, $recordsPerPage", 
            "Log");

        } // Ends if

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Log ID</th>
                            <th>Log Type</th>
                            <th>Log Time Created</th>
                            <th>Login Attempt ID</th>
                            <th>Student Username</th>
            </tr></thead>\n";
    
            foreach ($data as $log) {

                $logStudentID = $log->getLogStudentID();
                $studentObject = $this->getAllObjects("SELECT * FROM student WHERE studentId = $logStudentID", "Student");
             
                foreach ($studentObject as $student) {

                    $logStudentUsername = $student->getStudentUsername();
                    $log->setLogStudentID($logStudentUsername);

                } // Ends student foreach

                $outputTable .= $log->getTableLinkingRow();

            } // Ends log foreach
    
        } else {
            $outputTable = "<h2>No logs exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getLogObjectsByRoleAsTable

    public function getLogObjectsByRoleFilteredAsTable($userID, $userType, $currentPageNumber, $recordsPerPage, $sortBy, $filterByUsername, $filterByTime, $filterByType) {

        $offset = ($currentPageNumber - 1) * $recordsPerPage;

        $sortQuery = "";
        if ($sortBy === "type") {
            $sortQuery = "GROUP BY log.logId ORDER BY log.logType";
        } elseif ($sortBy === "student") {
            $sortQuery = "GROUP BY log.logId ORDER BY log.studentId";
        } elseif ($sortBy === "mostRecent") {
            $sortQuery = "GROUP BY log.logId ORDER BY log.logTimeCreated DESC";
        }

        $filterConditions = array();
        if (!empty($filterByUsername)) {
            $filteredStudent = $this->getStudentByUsername($filterByUsername);
            
            if (is_array($filteredStudent)) {
                $filterConditions[] = "log.studentId = $filteredStudent[0]";
            } else {
                return "<h2>No matching records found with that username.</h2>";
            }
        }

        if (!empty($filterByType) && $filterByType != "Any") {
            
            if ($filterByType == "Successful Login") {
                $filterConditions[] = "log.logType = 0";
                $filterConditions[] = "loginAttempt.loginAttemptSuccess = 1";
            } elseif ($filterByType == "Failed Login") {
                $filterConditions[] = "log.logType = 0";
                $filterConditions[] = "loginAttempt.loginAttemptSuccess = 0";
            } elseif ($filterByType == "File Created") {
                $filterConditions[] = "log.logType = 1";
            } elseif ($filterByType == "File Modified") {
                $filterConditions[] = "log.logType = 2";
            }
            
        }

        if ($filterByTime === "Last Day") {
            $filterConditions[] = "log.logTimeCreated >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
        } elseif ($filterByTime === "Last Three Days") {
            $filterConditions[] = "log.logTimeCreated >= DATE_SUB(NOW(), INTERVAL 3 DAY)";
        } elseif ($filterByTime === "Last Week") {
            $filterConditions[] = "log.logTimeCreated >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
        } elseif ($filterByTime === "Last Month") {
            $filterConditions[] = "log.logTimeCreated >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        }

        if ($userType == "Admin") {

            $query = "SELECT * FROM log
            INNER JOIN loginAttempt on log.loginAttemptId = loginAttempt.loginAttemptId
            INNER JOIN student ON log.studentId = student.studentId";

        } elseif ($userType == "Professor") {

            $query = "SELECT log.* FROM log
            INNER JOIN loginAttempt on log.loginAttemptId = loginAttempt.loginAttemptId
            INNER JOIN student ON log.studentId = student.studentId
            INNER JOIN classEntry ON student.studentId = classEntry.studentId
            INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID";

        } elseif ($userType == "Support") {

            $query = "SELECT log.* FROM log
            INNER JOIN loginAttempt on log.loginAttemptId = loginAttempt.loginAttemptId
            INNER JOIN student ON log.studentId = student.studentId
            INNER JOIN school ON student.schoolId = school.schoolId
            INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID";

        } // Ends if
        
        if (!empty($filterConditions)) {
            $query .= " WHERE " . implode(" AND ", $filterConditions);
        }

        if (empty($sortQuery)) {
            $sortQuery = "GROUP BY log.logId";
        }
        
        $query .= " $sortQuery LIMIT $offset, $recordsPerPage";

        $data = $this->getAllObjects($query, "Log");

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Log ID</th>
                            <th>Log Type</th>
                            <th>Log Time Created</th>
                            <th>Login Attempt ID</th>
                            <th>Student Username</th>
            </tr></thead>\n";
    
            foreach ($data as $log) {

                $logStudentID = $log->getLogStudentID();
                $studentObject = $this->getAllObjects("SELECT * FROM student WHERE studentId = $logStudentID", "Student");
             
                foreach ($studentObject as $student) {

                    $logStudentUsername = $student->getStudentUsername();
                    $log->setLogStudentID($logStudentUsername);

                } // Ends student foreach

                $outputTable .= $log->getTableLinkingRow();

            } // Ends log foreach
    
        } else {
            $outputTable = "<h3>No logs found.</h3>";
        }// Ends if

        return $outputTable;

    } // Ends getLogObjectsByRoleFilteredAsTable

    // Returns the number of logs that will be shown for a specific user. Used for pagination math
    public function getLogObjectsByRoleCount($userID, $userType) {

        if ($userType == "Admin") {

            $data = $this->getAllObjects("SELECT * FROM log", "Log");

        } else if ($userType == "Professor") { // Gets all logs for students that are in the classes under the professor

            $data = $this->getAllObjects("SELECT log.* FROM log
            INNER JOIN student ON log.studentId = student.studentId
            INNER JOIN classEntry ON student.studentId = classEntry.studentId
            INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID",
            "Log");

        } else if ($userType == "Support") { // Gets all logs for students that are in the same school under a support

            $data = $this->getAllObjects("SELECT log.* FROM log
            INNER JOIN student ON log.studentId = student.studentId
            INNER JOIN school ON student.schoolId = school.schoolId
            INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID",
            "Log");

        } // Ends if

        if (count($data) > 0) {
            return count($data);
        } else {
            return 0;
        } // Ends if

    } // Ends getLogObjectsByRoleCount

    public function getLogObjectsByRoleFilteredCount($userID, $userType, $sortBy, $filterByUsername, $filterByTime, $filterByType) {

        if ($sortBy === "type") {
            $sortQuery = "GROUP BY log.logId ORDER BY log.logType";
        } elseif ($sortBy === "student") {
            $sortQuery = "GROUP BY log.logId ORDER BY log.studentId";
        } elseif ($sortBy === "mostRecent") {
            $sortQuery = "GROUP BY log.logId ORDER BY log.logTimeCreated DESC";
        }

        $filterConditions = array();
        if (!empty($filterByUsername)) {
            $filteredStudent = $this->getStudentByUsername($filterByUsername);
            
            if (is_array($filteredStudent)) {
                $filterConditions[] = "log.studentId = $filteredStudent[0]";
            } else {
                return "<h2>No matching records found with that username.</h2>";
            }
        }

        if (!empty($filterByType) && $filterByType != "Any") {
            
            if ($filterByType == "Successful Login") {
                $filterConditions[] = "log.logType = 0";
                $filterConditions[] = "loginAttempt.loginAttemptSuccess = 1";
            } elseif ($filterByType == "Failed Login") {
                $filterConditions[] = "log.logType = 0";
                $filterConditions[] = "loginAttempt.loginAttemptSuccess = 0";
            } elseif ($filterByType == "File Created") {
                $filterConditions[] = "log.logType = 1";
            } elseif ($filterByType == "File Modified") {
                $filterConditions[] = "log.logType = 2";
            }
            
        }

        if ($filterByTime === "Last Day") {
            $filterConditions[] = "log.logTimeCreated >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
        } elseif ($filterByTime === "Last Three Days") {
            $filterConditions[] = "log.logTimeCreated >= DATE_SUB(NOW(), INTERVAL 3 DAY)";
        } elseif ($filterByTime === "Last Week") {
            $filterConditions[] = "log.logTimeCreated >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
        } elseif ($filterByTime === "Last Month") {
            $filterConditions[] = "log.logTimeCreated >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        }

        if ($userType == "Admin") {

            $query = "SELECT * FROM log
            INNER JOIN loginAttempt on log.loginAttemptId = loginAttempt.loginAttemptId
            INNER JOIN student ON log.studentId = student.studentId";

        } elseif ($userType == "Professor") {

            $query = "SELECT log.* FROM log
            INNER JOIN loginAttempt on log.loginAttemptId = loginAttempt.loginAttemptId
            INNER JOIN student ON log.studentId = student.studentId
            INNER JOIN classEntry ON student.studentId = classEntry.studentId
            INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID";

        } elseif ($userType == "Support") {

            $query = "SELECT log.* FROM log
            INNER JOIN loginAttempt on log.loginAttemptId = loginAttempt.loginAttemptId
            INNER JOIN student ON log.studentId = student.studentId
            INNER JOIN school ON student.schoolId = school.schoolId
            INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID";

        } // Ends if
        
        if (!empty($filterConditions)) {
            $query .= " WHERE " . implode(" AND ", $filterConditions);
        }

        if (empty($sortQuery)) {
            $sortQuery = "GROUP BY log.logId";
        }
        
        $query .= " $sortQuery";

        $data = $this->getAllObjects($query, "Log");

        if (count($data) > 0) {
            return count($data);
        } else {
            return 0;
        } // Ends if

    } // Ends getLogObjectsByRoleFilteredCount

    // Returns information for one log in an array
    public function getLogByID($logID) {

        $data = $this->getAllObjects("SELECT * FROM log WHERE logId = '$logID'", "Log");

        if (count($data) > 0) {

            $outputLog[] = $data[0]->getLogID();
            $outputLog[] = $data[0]->getLogType();
            $outputLog[] = $data[0]->getLogTimeCreated();
            $outputLog[] = $data[0]->getLogLoginAttemptID();
            $outputLog[] = $data[0]->getLogStudentID();
    
        } elseif (count($data) > 1) {

            $outputLog = "ERROR500";

        } else {

            $outputLog = "ERROR404";

        }// Ends if

        return $outputLog;

    } // Ends getLogByID

    public function getLogLatestByStudentID($studentID) {

        $data = $this->getAllObjects("SELECT * FROM log
        WHERE studentId = '$studentID'
        ORDER BY logTimeCreated DESC
        LIMIT 1", "Log");

        if (count($data) > 0) {

            $outputLog[] = $data[0]->getLogID();
            $outputLog[] = $data[0]->getLogType();
            $outputLog[] = $data[0]->getLogTimeCreated();
            $outputLog[] = $data[0]->getLogLoginAttemptID();
            $outputLog[] = $data[0]->getLogStudentID();
    
        } elseif (count($data) > 1) {

            $outputLog = "ERROR500";

        } else {

            $outputLog = "ERROR404";

        }// Ends if

        return $outputLog;

    } // Ends getLogLatestByStudentID

/********************************LOGINATTEMPT FUNCTIONS*************************************/
    
    public function getAllLoginAttemptObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM loginAttempt", "LoginAttempt");

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Login Attempt ID</th>
                            <th>Login Attempt Username</th>
                            <th>Login Attempt Password</th>
                            <th>Login Attempt Time Entered</th>
                            <th>Login Attempt Success</th>
                            <th>Student ID</th>
            </tr></thead>\n";
    
            foreach ($data as $loginAttempt) {

                $outputTable .= $loginAttempt->getTableData();

            } // Ends loginAttempt foreach
    
        } else {
            $outputTable = "<h2>No login attempt records exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllLoginAttemptObjectsAsTable

    // get all login attempts, successful or failed, from a single student
    public function getAllLoginAttemptObjectsFromStudent($studentID, $failed) {
        $data = array();

        try {

            require_once "../controllers/DB.Controller.class.php";

            $stmt = $this->dbh->prepare("SELECT * FROM loginAttempt WHERE studentID = :id");
            if ($failed) {
                $stmt = $this->dbh->prepare("SELECT * FROM loginAttempt WHERE studentID = :id AND loginAttemptSuccess = 0");
            }
            $stmt->execute(array(":id" => $studentID));
            $data = $stmt->fetchAll(PDO::FETCH_CLASS, "loginAttempt");

        } catch(PDOException $pe) {
            echo $pe->getMessage();
            return array();
        } // Ends try catch

        $outputTable = "<thead><tr>
                        <th>Login Attempt ID</th>
                        <th>Login Attempt Username</th>
                        <th>Login Attempt Password</th>
                        <th>Login Attempt Time Entered</th>
                        <th>Login Attempt Success</th>
                        <th>Student ID</th>
        </tr></thead>\n";

        if(count($data) > 0) {
            foreach ($data as $loginAttempt) {

                $outputTable .= $loginAttempt->getTableData();

            } // Ends loginAttempt foreach
        } else {
            $outputTable = "<h2>No login attempt records exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllLoginAttemptObjectsFromStudent

    // get all login attempts, successful or failed, after a datetime
    public function getAllLoginAttemptObjectsAfterDatetime($datetime, $failed) {
        $data = array();

        try {

            require_once "../controllers/DB.Controller.class.php";
            $stmt = $this->dbh->prepare("SELECT * FROM loginAttempt WHERE loginAttemptTimeEntered >= :timeEntered");
            if ($failed) {
                $stmt = $this->dbh->prepare("SELECT * FROM loginAttempt WHERE loginAttemptTimeEntered >= :timeEntered AND loginAttemptSuccess = 0");
            }
            $stmt->execute(array(":timeEntered" => $dateTime));
            $data = $stmt->fetchAll(PDO::FETCH_CLASS, "loginAttempt");

        } catch(PDOException $pe) {
            echo $pe->getMessage();
            return array();
        } // Ends try catch

        $outputTable = "<thead><tr>
                        <th>Login Attempt ID</th>
                        <th>Login Attempt Username</th>
                        <th>Login Attempt Password</th>
                        <th>Login Attempt Time Entered</th>
                        <th>Login Attempt Success</th>
                        <th>Student ID</th>
        </tr></thead>\n";

        if(count($data) > 0) {
            foreach ($data as $loginAttempt) {

                $outputTable .= $loginAttempt->getTableData();

            } // Ends loginAttempt foreach
        } else {
            $outputTable = "<h2>No login attempt records exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllLoginAttemptObjectsAfterDateTime
    
    // Returns the number of login attempts from today
    public function getLoginAttemptsTodayCount($successType, $userID, $userType) {

        switch ($successType) {

            case "all":

                if ($userType == "Admin") { // Gets all login attempts from today

                    $data = $this->getAllObjects("SELECT * FROM loginAttempt WHERE DATE(loginAttemptTimeEntered) = CURDATE()", "LoginAttempt");
        
                } else if ($userType == "Professor") { // Gets all loginAttempts for students that are in the classes under the professor
        
                    $data = $this->getAllObjects("SELECT loginAttempt.* FROM loginAttempt
                    INNER JOIN student ON loginAttempt.studentId = student.studentId INNER JOIN classEntry ON student.studentId = classEntry.studentId INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID
                    WHERE DATE(loginAttempt.loginAttemptTimeEntered) = CURDATE()", "LoginAttempt");
        
                } else if ($userType == "Support") { // Gets all loginAttempts for students that are in the same school under a support
        
                    $data = $this->getAllObjects("SELECT loginAttempt.* FROM loginAttempt
                    INNER JOIN student ON loginAttempt.studentId = student.studentId INNER JOIN school ON student.schoolId = school.schoolId INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID
                    WHERE DATE(loginAttempt.loginAttemptTimeEntered) = CURDATE()", "LoginAttempt");
        
                } // Ends if
                
                break;

            case "failure":

                if ($userType == "Admin") { // Gets all login attempts from today

                    $data = $this->getAllObjects("SELECT * FROM loginAttempt WHERE DATE(loginAttemptTimeEntered) = CURDATE() AND loginAttemptSuccess = 0", "LoginAttempt");
        
                } else if ($userType == "Professor") { // Gets all loginAttempts for students that are in the classes under the professor
        
                    $data = $this->getAllObjects("SELECT loginAttempt.* FROM loginAttempt
                    INNER JOIN student ON loginAttempt.studentId = student.studentId INNER JOIN classEntry ON student.studentId = classEntry.studentId INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID
                    WHERE DATE(loginAttempt.loginAttemptTimeEntered) = CURDATE() AND loginAttemptSuccess = 0", "LoginAttempt");
        
                } else if ($userType == "Support") { // Gets all loginAttempts for students that are in the same school under a support
        
                    $data = $this->getAllObjects("SELECT loginAttempt.* FROM loginAttempt
                    INNER JOIN student ON loginAttempt.studentId = student.studentId INNER JOIN school ON student.schoolId = school.schoolId INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID
                    WHERE DATE(loginAttempt.loginAttemptTimeEntered) = CURDATE() AND loginAttemptSuccess = 0", "LoginAttempt");
        
                } // Ends if

                break;

            case "success":

                if ($userType == "Admin") { // Gets all login attempts from today

                    $data = $this->getAllObjects("SELECT * FROM loginAttempt WHERE DATE(loginAttemptTimeEntered) = CURDATE() AND loginAttemptSuccess = 1", "LoginAttempt");
        
                } else if ($userType == "Professor") { // Gets all loginAttempts for students that are in the classes under the professor
        
                    $data = $this->getAllObjects("SELECT loginAttempt.* FROM loginAttempt
                    INNER JOIN student ON loginAttempt.studentId = student.studentId INNER JOIN classEntry ON student.studentId = classEntry.studentId INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID
                    WHERE DATE(loginAttempt.loginAttemptTimeEntered) = CURDATE() AND loginAttemptSuccess = 1", "LoginAttempt");
        
                } else if ($userType == "Support") { // Gets all loginAttempts for students that are in the same school under a support
        
                    $data = $this->getAllObjects("SELECT loginAttempt.* FROM loginAttempt
                    INNER JOIN student ON loginAttempt.studentId = student.studentId INNER JOIN school ON student.schoolId = school.schoolId INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID
                    WHERE DATE(loginAttempt.loginAttemptTimeEntered) = CURDATE() AND loginAttemptSuccess = 1", "LoginAttempt");
        
                } // Ends if

                break;

        } // Ends successType switch

        if (is_array($data)) {
            return count($data);
        } else {
            return 0;
        } // Ends if

    } // Ends getCountLoginAttemptsToday

    // Returns the number of login attempts from today
    public function getLoginAttemptsTodayAsTable($successType, $userID, $userType, $currentPageNumber, $recordsPerPage) {

        $offset = ($currentPageNumber - 1) * $recordsPerPage;

        switch ($successType) {

            case "all":

                if ($userType == "Admin") { // Gets all login attempts from today

                    $data = $this->getAllObjects("SELECT * FROM loginAttempt WHERE DATE(loginAttemptTimeEntered) = CURDATE() LIMIT $offset, $recordsPerPage", "LoginAttempt");
        
                } else if ($userType == "Professor") { // Gets all loginAttempts for students that are in the classes under the professor
        
                    $data = $this->getAllObjects("SELECT loginAttempt.* FROM loginAttempt
                    INNER JOIN student ON loginAttempt.studentId = student.studentId INNER JOIN classEntry ON student.studentId = classEntry.studentId INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID
                    WHERE DATE(loginAttempt.loginAttemptTimeEntered) = CURDATE() LIMIT $offset, $recordsPerPage", "LoginAttempt");
        
                } else if ($userType == "Support") { // Gets all loginAttempts for students that are in the same school under a support
        
                    $data = $this->getAllObjects("SELECT loginAttempt.* FROM loginAttempt
                    INNER JOIN student ON loginAttempt.studentId = student.studentId INNER JOIN school ON student.schoolId = school.schoolId INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID
                    WHERE DATE(loginAttempt.loginAttemptTimeEntered) = CURDATE() LIMIT $offset, $recordsPerPage", "LoginAttempt");
        
                } // Ends if
                
                break;

            case "failure":

                if ($userType == "Admin") { // Gets all login attempts from today

                    $data = $this->getAllObjects("SELECT * FROM loginAttempt WHERE DATE(loginAttemptTimeEntered) = CURDATE() AND loginAttemptSuccess = 0 LIMIT $offset, $recordsPerPage", "LoginAttempt");
        
                } else if ($userType == "Professor") { // Gets all loginAttempts for students that are in the classes under the professor
        
                    $data = $this->getAllObjects("SELECT loginAttempt.* FROM loginAttempt
                    INNER JOIN student ON loginAttempt.studentId = student.studentId INNER JOIN classEntry ON student.studentId = classEntry.studentId INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID
                    WHERE DATE(loginAttempt.loginAttemptTimeEntered) = CURDATE() AND loginAttemptSuccess = 0 LIMIT $offset, $recordsPerPage", "LoginAttempt");
        
                } else if ($userType == "Support") { // Gets all loginAttempts for students that are in the same school under a support
        
                    $data = $this->getAllObjects("SELECT loginAttempt.* FROM loginAttempt
                    INNER JOIN student ON loginAttempt.studentId = student.studentId INNER JOIN school ON student.schoolId = school.schoolId INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID
                    WHERE DATE(loginAttempt.loginAttemptTimeEntered) = CURDATE() AND loginAttemptSuccess = 0 LIMIT $offset, $recordsPerPage", "LoginAttempt");
        
                } // Ends if

                break;

            case "success":

                if ($userType == "Admin") { // Gets all login attempts from today

                    $data = $this->getAllObjects("SELECT * FROM loginAttempt WHERE DATE(loginAttemptTimeEntered) = CURDATE() AND loginAttemptSuccess = 1 LIMIT $offset, $recordsPerPage", "LoginAttempt");
        
                } else if ($userType == "Professor") { // Gets all loginAttempts for students that are in the classes under the professor
        
                    $data = $this->getAllObjects("SELECT loginAttempt.* FROM loginAttempt
                    INNER JOIN student ON loginAttempt.studentId = student.studentId INNER JOIN classEntry ON student.studentId = classEntry.studentId INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID
                    WHERE DATE(loginAttempt.loginAttemptTimeEntered) = CURDATE() AND loginAttemptSuccess = 1 LIMIT $offset, $recordsPerPage", "LoginAttempt");
        
                } else if ($userType == "Support") { // Gets all loginAttempts for students that are in the same school under a support
        
                    $data = $this->getAllObjects("SELECT loginAttempt.* FROM loginAttempt
                    INNER JOIN student ON loginAttempt.studentId = student.studentId INNER JOIN school ON student.schoolId = school.schoolId INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID
                    WHERE DATE(loginAttempt.loginAttemptTimeEntered) = CURDATE() AND loginAttemptSuccess = 1 LIMIT $offset, $recordsPerPage", "LoginAttempt");
        
                } // Ends if

                break;

        } // Ends successType switch

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Login Attempt ID</th>
                            <th>Login Attempt Username</th>
                            <th>Login Attempt Password</th>
                            <th>Login Attempt Time Entered</th>
                            <th>Login Attempt Success</th>
                            <th>Student ID</th>
            </tr></thead>\n";
    
            foreach ($data as $loginAttempt) {

                $outputTable .= $loginAttempt->getTableData();

            } // Ends loginAttempt foreach
    
        } else {
            $outputTable = "<h3>There have been no login attempts created today that match your query.</h3>";
        }// Ends if

        return $outputTable;

    } // Ends getLoginAttemptsTodayAsTable

    // Returns information for one login attempt in an array
    public function getLoginAttemptByID($loginAttemptID) {

        $data = $this->getAllObjects("SELECT * FROM loginAttempt WHERE loginAttemptId = '$loginAttemptID'", "LoginAttempt");

        if (count($data) > 0) {

            $outputLoginAttempt[] = $data[0]->getLoginAttemptID();
            $outputLoginAttempt[] = $data[0]->getLoginAttemptUsername();
            $outputLoginAttempt[] = $data[0]->getLoginAttemptPassword();
            $outputLoginAttempt[] = $data[0]->getLoginAttemptTimeEntered();
            $outputLoginAttempt[] = $data[0]->getLoginAttemptSuccess();
            $outputLoginAttempt[] = $data[0]->getLoginAttemptStudentID();
    
        } elseif (count($data) > 1) {

            $outputLoginAttempt = "ERROR500";

        } else {

            $outputLoginAttempt = "ERROR404";

        }// Ends if

        return $outputLoginAttempt;

    } // Ends getLoginAttemptByID

/********************************SCHOOL FUNCTIONS*************************************/
    
    public function getAllSchoolObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM school", "School");

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>School ID</th>
                            <th>School Name</th>
            </tr></thead>\n";
    
            foreach ($data as $school) {

                $outputTable .= $school->getTableData();

            } // Ends school foreach
    
        } else {
            $outputTable = "<h2>No schools exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllSchoolObjectsAsTable

    public function getSchoolByID($schoolID) {

        $data = $this->getAllObjects("SELECT * FROM school WHERE schoolId = '$schoolID'", "School");

        if (count($data) > 0) {

            $outputSchool[] = $data[0]->getSchoolID();
            $outputSchool[] = $data[0]->getSchoolName();
    
        } elseif (count($data) > 1) {

            $outputSchool = "ERROR500";

        } else {

            $outputSchool = "ERROR404";

        }// Ends if

        return $outputSchool;

    } // Ends getSchoolByID

/********************************STUDENT FUNCTIONS*************************************/
    
    public function getAllStudentObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM student", "Student");

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Student ID</th>
                            <th>Student First Name</th>
                            <th>Student Middle Initial</th>
                            <th>Student Last Name</th>
                            <th>Student Username</th>
                            <th>Student School</th>
            </tr></thead>\n";
    
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

    // get all students from a specific school
    public function getAllStudentObjectsFromSchool($datetime) {
        $data = array();

        try {

            require_once "../controllers/DB.Controller.class.php";
            $stmt = $this->dbh->prepare("SELECT * FROM student WHERE schoolID = :school");
            $stmt->execute(array(":timeEntered" => $dateTime));
            $data = $stmt->fetchAll(PDO::FETCH_CLASS, "loginAttempt");

        } catch(PDOException $pe) {
            echo $pe->getMessage();
            return array();
        } // Ends try catch

        $outputTable = "<thead><tr>
                            <th>Student ID</th>
                            <th>Student First Name</th>
                            <th>Student Middle Initial</th>
                            <th>Student Last Name</th>
                            <th>Student Username</th>
                            <th>Student School</th>
            </tr></thead>\n";

        if(count($data) > 0) {
            foreach ($data as $loginAttempt) {

                $outputTable .= $loginAttempt->getTableData();

            } // Ends loginAttempt foreach
        } else {
            $outputTable = "<h2>No login attempt records exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getAllStudentsFromSchool

    // Gets all students for the passed in user
    public function getStudentObjectsByRoleAsTable($userID, $userType, $currentPageNumber, $recordsPerPage) {

        $offset = ($currentPageNumber - 1) * $recordsPerPage;

        if ($userType == "Admin") {

            $data = $this->getAllObjects("SELECT * FROM student LIMIT $offset, $recordsPerPage", "Student");

        } else if ($userType == "Professor") { // Gets all students that are in the classes under the professor

            $data = $this->getAllObjects("SELECT student.* FROM student
            INNER JOIN classEntry ON student.studentId = classEntry.studentId 
            INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID
            LIMIT $offset, $recordsPerPage", "Student");

        } else if ($userType == "Support") { // Gets all students that are in the same school under a support

            $data = $this->getAllObjects("SELECT student.* FROM student
            INNER JOIN school ON student.schoolId = school.schoolId 
            INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID 
            LIMIT $offset, $recordsPerPage", "Student");

        } // Ends if

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Student ID</th>
                            <th>Student First Name</th>
                            <th>Student Middle Initial</th>
                            <th>Student Last Name</th>
                            <th>Student Username</th>
                            <th>Student School</th>
            </tr></thead>\n";
    
            foreach ($data as $student) {

                $studentSchoolID = $student->getStudentSchoolID();
                $schoolObject = $this->getAllObjects("SELECT * FROM school WHERE schoolId = $studentSchoolID", "School");
             
                foreach ($schoolObject as $school) {

                    $studentSchoolName = $school->getSchoolName();
                    $student->setStudentSchoolID($studentSchoolName);

                } // Ends school foreach

                $outputTable .= $student->getTableLinkingRow();

            } // Ends student foreach
    
        } else {
            $outputTable = "<h2>No students exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getStudentObjectsByRoleAsTable

    public function getStudentObjectsByRoleFilteredAsTable($userID, $userType, $currentPageNumber, $recordsPerPage, $sortBy, $filterByUsername, $filterByClass, $filterByLog) {

        $offset = ($currentPageNumber - 1) * $recordsPerPage;

        $sortQuery = "";
        if ($sortBy === "school") {
            $sortQuery = "GROUP BY student.studentId, student.schoolId ORDER BY student.schoolId";
        } elseif ($sortBy === "username") {
            $sortQuery = "GROUP BY student.studentUsername ORDER BY student.studentUsername";
        }

        $filterConditions = array();

        if (!empty($filterByUsername)) {
            $filterConditions[] = "student.studentUsername = \"$filterByUsername\"";
        }
        
        if (!empty($filterByClass)) {
            $filterConditions[] = "class.classId = $filterByClass";
        }

        // if ($filterByLog === "lastDay") {
        //     $filterConditions[] = "class.classId = $filterByClass";
        // } elseif ($filterByLog === "lastDay") {

        // } elseif ($filterByLog === "lastThreeDays") {

        // } elseif ($filterByLog === "lastWeek") {

        // } elseif ($filterByLog === "lastMonth") {

        // }

        if ($userType == "Admin") {

            $query = "SELECT student.* FROM student
            INNER JOIN classEntry ON student.studentId = classEntry.studentId
            INNER JOIN class ON classEntry.classId = class.classId";

        } elseif ($userType == "Professor") { // Gets all students that are in the classes under the professor

            $query = "SELECT student.* FROM student
            INNER JOIN classEntry ON student.studentId = classEntry.studentId
            INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID";

        } elseif ($userType == "Support") { // Gets all students that are in the same school under a support

            $query = "SELECT student.* FROM student
            INNER JOIN school ON student.schoolId = school.schoolId
            INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID
            INNER JOIN classEntry ON student.studentId = classEntry.studentId
            INNER JOIN class ON classEntry.classId = class.classId";

        } // Ends if

        if (!empty($filterConditions)) {
            $query .= " WHERE " . implode(" AND ", $filterConditions);
        }

        if (empty($sortQuery)) {
            $sortQuery = "GROUP BY student.studentId";
        }

        $query .= " $sortQuery LIMIT $offset, $recordsPerPage";
        
        $data = $this->getAllObjects($query, "Student");

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>Student ID</th>
                            <th>Student First Name</th>
                            <th>Student Middle Initial</th>
                            <th>Student Last Name</th>
                            <th>Student Username</th>
                            <th>Student School</th>
            </tr></thead>\n";
    
            foreach ($data as $student) {

                $studentSchoolID = $student->getStudentSchoolID();
                $schoolObject = $this->getAllObjects("SELECT * FROM school WHERE schoolId = $studentSchoolID", "School");
             
                foreach ($schoolObject as $school) {

                    $studentSchoolName = $school->getSchoolName();
                    $student->setStudentSchoolID($studentSchoolName);

                } // Ends school foreach

                $outputTable .= $student->getTableLinkingRow();

            } // Ends student foreach
    
        } else {
            $outputTable = "<h2>No students exist.</h2>";
        }// Ends if

        return $outputTable;

    } // Ends getStudentObjectsByRoleFilteredAsTable

    public function getStudentObjectsByRoleCount($userID, $userType) {

        if ($userType == "Admin") {

            $data = $this->getAllObjects("SELECT * FROM student", "Student");

        } elseif ($userType == "Professor") { // Gets all students that are in the classes under the professor

            $data = $this->getAllObjects("SELECT student.* FROM student
            INNER JOIN classEntry ON student.studentId = classEntry.studentId 
            INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID", 
            "Student");

        } elseif ($userType == "Support") { // Gets all students that are in the same school under a support

            $data = $this->getAllObjects("SELECT student.* FROM student
            INNER JOIN school ON student.schoolId = school.schoolId 
            INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID", 
            "Student");

        } // Ends if

        if (count($data) > 0) {
            return count($data);
        } else {
            return 0;
        } // Ends if

    } // Ends getStudentObjectsByRoleCount

    public function getStudentObjectsByRoleFilteredCount($userID, $userType, $sortBy, $filterByUsername, $filterByClass, $filterByLog) {

        $sortQuery = "";
        if ($sortBy === "school") {
            $sortQuery = "GROUP BY student.studentId, student.schoolId ORDER BY student.schoolId";
        } elseif ($sortBy === "username") {
            $sortQuery = "GROUP BY student.studentUsername ORDER BY student.studentUsername";
        }

        $filterConditions = array();

        if (!empty($filterByUsername)) {
            $filterConditions[] = "student.studentUsername = \"$filterByUsername\"";
        }
        
        if (!empty($filterByClass)) {
            $filterConditions[] = "class.classId = $filterByClass";
        }

        // if ($filterByLog === "lastDay") {
        //     $filterConditions[] = "class.classId = $filterByClass";
        // } elseif ($filterByLog === "lastDay") {

        // } elseif ($filterByLog === "lastThreeDays") {

        // } elseif ($filterByLog === "lastWeek") {

        // } elseif ($filterByLog === "lastMonth") {

        // }

        if ($userType == "Admin") {

            $query = "SELECT student.* FROM student
            INNER JOIN classEntry ON student.studentId = classEntry.studentId
            INNER JOIN class ON classEntry.classId = class.classId";

        } elseif ($userType == "Professor") { // Gets all students that are in the classes under the professor

            $query = "SELECT student.* FROM student
            INNER JOIN classEntry ON student.studentId = classEntry.studentId
            INNER JOIN class ON classEntry.classId = class.classId AND class.classProfessor = $userID";

        } elseif ($userType == "Support") { // Gets all students that are in the same school under a support

            $query = "SELECT student.* FROM student
            INNER JOIN school ON student.schoolId = school.schoolId
            INNER JOIN user ON school.schoolId = user.schoolId AND user.userId = $userID
            INNER JOIN classEntry ON student.studentId = classEntry.studentId
            INNER JOIN class ON classEntry.classId = class.classId";

        } // Ends if

        if (!empty($filterConditions)) {
            $query .= " WHERE " . implode(" AND ", $filterConditions);
        }

        if (empty($sortQuery)) {
            $sortQuery = "GROUP BY student.studentId";
        }

        $query .= " $sortQuery";
        
        $data = $this->getAllObjects($query, "Student");

        if (count($data) > 0) {
            return count($data);
        } else {
            return 0;
        } // Ends if

    } // Ends getStudentObjectsByRoleFilteredAsTable

    public function getStudentByID($studentID) {

        $data = $this->getAllObjects("SELECT * FROM student WHERE studentId = '$studentID'", "Student");

        if (count($data) > 0) {

            $outputStudent[] = $data[0]->getStudentID();
            $outputStudent[] = $data[0]->getStudentFirstName();
            $outputStudent[] = $data[0]->getStudentMiddleInitial();
            $outputStudent[] = $data[0]->getStudentLastName();
            $outputStudent[] = $data[0]->getStudentUsername();
            $outputStudent[] = $data[0]->getStudentSchoolID();
    
        } elseif (count($data) > 1) {

            $outputStudent = "ERROR500";

        } else {

            $outputStudent = "ERROR404";

        }// Ends if

        return $outputStudent;

    } // Ends getStudentByID

    public function getStudentByUsername($studentUsername) {

        $data = $this->getAllObjects("SELECT * FROM student WHERE studentUsername = '$studentUsername'", "Student");

        if (count($data) > 0) {

            $outputStudent[] = $data[0]->getStudentID();
            $outputStudent[] = $data[0]->getStudentFirstName();
            $outputStudent[] = $data[0]->getStudentMiddleInitial();
            $outputStudent[] = $data[0]->getStudentLastName();
            $outputStudent[] = $data[0]->getStudentUsername();
            $outputStudent[] = $data[0]->getStudentSchoolID();
    
        } elseif (count($data) > 1) {

            $outputStudent = "ERROR500";

        } else {

            $outputStudent = "ERROR404";

        }// Ends if

        return $outputStudent;

    } // Ends getStudentByUsername

/********************************USER FUNCTIONS*************************************/
    
    public function getAllUserObjectsAsTable() {

        $data = $this->getAllObjects("SELECT * FROM user", "User");

        if (count($data) > 0) {

            $outputTable = "<thead><tr>
                            <th>User ID</th>
                            <th>User First Name</th>
                            <th>User Last Name</th>
                            <th>User Email</th>
                            <th>User Username</th>
                            <th>User Password</th>
                            <th>User Classification</th>
                            <th>School ID</th>
            </tr></thead>\n";
    
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

    // Returns 404 error if no record is found 
    // Returns 500 error if more than 1 record is found
    // Returns array with all user information if a record is found
    public function getUserByID($userID) {

        $data = $this->getAllObjects("SELECT * FROM user WHERE userId = '$userID'", "User");

        if (count($data) > 0) {

            $outputUser[] = $data[0]->getUserID();
            $outputUser[] = $data[0]->getUserFirstName();
            $outputUser[] = $data[0]->getUserLastName();
            $outputUser[] = $data[0]->getUserEmail();
            $outputUser[] = $data[0]->getUserUsername();
            $outputUser[] = $data[0]->getUserPassword();
            $outputUser[] = $data[0]->getUserClassification();
            $outputUser[] = $data[0]->getUserSchoolID();
    
        } elseif (count($data) > 1) {

            $outputUser = "ERROR500";

        } else {

            $outputUser = "ERROR404";

        }// Ends if

        return $outputUser;

    } // Ends getUserByID

    // NOTE: This is the primary function for verifying login details. 
    // Returns 404 error if no record is found 
    // Returns 500 error if more than 1 record is found
    // Returns array with ID and classification if a record is found
    public function getUserInfoByLogin($inputUsername, $inputPassword) {

        $data = $this->getAllObjects("SELECT * FROM user WHERE userUsername = '$inputUsername' AND userPassword = '$inputPassword'", "User");

        if (count($data) > 0) {

            $outputUser[] = $data[0]->getUserID();
            $outputUser[] = $data[0]->getUserClassification();
    
        } elseif (count($data) > 1) {

            $outputUser = "ERROR500";

        } else {

            $outputUser = "ERROR404";

        }// Ends if

        return $outputUser;

    } // Ends getUserInfoByLogin

    // NOTE: Secondary login verification function used to check if username exists
    // Returns 404 error if no record is found 
    // Returns 500 error if more than 1 record is found
    // Returns user's password if a record is found
    public function getUserInfoByUsername($inputUsername) {

        $data = $this->getAllObjects("SELECT * FROM user WHERE userUsername = '$inputUsername'", "User");

        if (count($data) > 0) {

            $outputUser = $data[0]->getUserPassword();
    
        } elseif (count($data) > 1) {

            $outputUser = "ERROR500";

        } else {

            $outputUser = "ERROR404";

        }// Ends if

        return $outputUser;

    } // Ends getUserInfoByUsername

} // Ends DB class