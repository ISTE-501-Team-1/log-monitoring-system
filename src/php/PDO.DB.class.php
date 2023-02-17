<?php

    class DB {

        // attr
        private $dbh;

        //------------------------- CONNECT TO DB ------------------------------

        // constructor
        function __construct() {
            try {
                $this -> dbh = new PDO("mysql:host={$_SERVER['DB_SERVER']};dbname={$_SERVER['DB']}", $_SERVER['DB_USER'], $_SERVER['DB_PASSWORD']);
            } catch(PDOException $pdoe) {
                echo $pdoe -> getMessage();
                die();
            }
        }

        //---------------------------- CREATE -----------------------------------

        // insert into db
        function genericInsert($table, $columns, $values) {
            try {

                // make sure table/columns exists
                if (DB::columnsAreValid($table, $columns)) {

                    // build query string
                    $queryBeginning = "INSERT INTO $table (";
                    $queryEnd = ") VALUES (";

                    for ($i = 0; $i < min(count($columns), count($values)); $i++) {
                        $queryBeginning .= $columns[$i] . ", ";
                        $queryEnd .= ":value" . $i . ", ";
                    }

                    $queryString = rtrim($queryBeginning, ", ") . rtrim($queryEnd, ", ") . ")";

                    // prepare statement
                    $stmt = $this -> dbh -> prepare($queryString);

                    // bind params
                    for ($i = 0; $i < min(count($columns), count($values)); $i++) {
                        $stmt -> bindParam(":value" . $i, $values[$i]);
                    }

                    // execute statement
                    $stmt -> execute();

                } else {

                    //table/columns don't exist
                    throw new Exception("Error with insert, couldn't find table or columns.");
                    return false;
                }

                return true;

            } catch(Exception $e) {
                echo $e -> getMessage();
                return false;
                die();
            }
        }

        //------------------------------ READ -----------------------------------

        // generic fetch statement, get all columns/records from specified table if it exists
        function genericFetch($table) {
            try {
                $data = [];
                
                // make sure table exists
                if (DB::tableIsValid($table)) {

                    // prepare statement
                    $stmt = $this -> dbh -> prepare("SELECT * FROM $table");

                    // execute
                    $stmt -> execute();

                    // fetch result
                    $data = $stmt -> fetchAll(PDO::FETCH_ASSOC);

                } else {

                    //table doesn't exist
                    throw new Exception("Couldn't find table");

                }

                return $data;

            } catch(Exception $e) {
                echo $e -> getMessage();
                return [];
                die();
            }
        }

        //-------------------------------- UPDATE -----------------------------------

        // update rows in db
        function genericUpdate($table, $columns, $values, $condition) {
            try {

                // make sure table and columns exist
                if (DB::columnsAreValid($columns)) {

                    // build query string
                    $queryBeginning = "UPDATE $table SET ";

                    for ($i = 0; $i < min(count($columns), count($values)); $i++) {
                        $queryBeginning .= $columns[$i] . " = :value" . $i . ", ";
                    }

                    $queryString = rtrim($queryBeginning, ", ") . " WHERE " . $condition;

                    // prepare statement
                    $stmt = $this -> dbh -> prepare($queryString);

                    // bind params
                    for ($i = 0; $i < min(count($columns), count($values)); $i++) {
                        $stmt -> bindParam(":value" . $i, $values[$i]);
                    }

                    // execute statement
                    $stmt -> execute();

                } else {

                    //table doesn't exist
                    throw new Exception("Couldn't find table or columns");

                }

                return $stmt -> rowCount();

            } catch(Exception $e) {
                echo $e -> getMessage();
                return -1;
                die();
            }
        }
        
        //-------------------------------- DELETE -----------------------------------

        // delete rows from db
        function genericDelete($table, $condition) {
            try {

                // make sure table exists
                if (DB::tableIsValid($table)) {

                    // build query string
                    $queryString = "DELETE FROM $table WHERE " . $condition;

                    // prepare statement
                    $stmt = $this -> dbh -> prepare($queryString);

                    // execute statement
                    $stmt -> execute();

                } else {

                    //table doesn't exist
                    throw new Exception("Couldn't find table");

                }

                return $stmt -> rowCount();

            } catch(Exception $e) {
                echo $e -> getMessage();
                return -1;
                die();
            }
        }

        //--------------------------------- META ------------------------------------

        // list out tables
        function showTables() {
            try {
                $data = [];
                
                // prepare statement
                $stmt = $this -> dbh -> prepare("SHOW TABLES");

                // execute
                $stmt -> execute();

                // fetch result
                $data = $stmt -> fetchAll();
                
                return $data;

            } catch(Exception $e) {
                echo $e -> getMessage();
                return [];
                die();
            }
        }

        // describe table
        function describe($table) {
            try {
                $data = [];
                
                // make sure table exists
                if (DB::tableIsValid($table)) {

                    // prepare statement
                    $stmt = $this -> dbh -> prepare("DESCRIBE $table");

                    // execute
                    $stmt -> execute();

                    // fetch result
                    $data = $stmt -> fetchAll();

                } else {

                    //table doesn't exist
                    throw new Exception("Couldn't find table");
                    
                }

                return $data;

            } catch(Exception $e) {
                echo $e -> getMessage();
                return [];
                die();
            }
        }

        //-------- VALIDATION (for items that can't be bound as a parameter) ----------

        // validate that table exists
        function tableIsValid($table) {

            // set flag to false by default
            $isValid = false;

            // look through tables, check if the table specified is among them
            foreach (DB::showTables() AS $listing) {

                // check each table
                $isValid = ($listing[0] == $table);
                
                // if you find the table, jump out of the loop
                if ($isValid) break;
            }

            // return the flag
            return $isValid;
        }

        // validate that columns exist 
        function columnsAreValid($table, $columns) {
            
            try {

                // set flag to true by default; if one column fails, the whole thing fails
                $isValid = true;

                // confirm the columns belong to a real table
                if (DB::tableIsValid($table)) {

                    // check each column against the table's described columns
                    foreach ($columns AS $column) {

                        // flag to make sure each column exists
                        $columnExists = false;

                        // find each column among the listed columns, all must be found
                        foreach (DB::describe($table) AS $listed) {

                            // compare the inputed column name to the listed column name
                            $columnExists = ($column == $listed[0]);

                            // when you find the column, jump out of the loop
                            if ($columnExists) break;
                        }

                        // check of the column was found
                        $isValid = $columnExists;

                        // if it wasn't found, validation has failed
                        if (!$isValid) break;
                    }

                    return $isValid;

                } else {

                    //table doesn't exist
                    throw new Exception("Couldn't find table/columns");
                }

                return $isValid;
            } catch(Exception $e) {
                echo $e -> getMessage();
                return [];
                die();
            }
        }
    }

// don't close php tag