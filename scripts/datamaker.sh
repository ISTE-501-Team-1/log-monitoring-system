#!/bin/bash

# script to write some SQL insert statements containing randomly generated data
# generating records for the "log" and "loginAttempt" tables
# items that have to do with a student should use existing student table entries

# function to add leading 0s to output

leadingZeros () {
	output=$(shuf -i $1-$2 -n 1)
	if [[ $output -lt 10 ]]
	then
		output="0${output}"
	fi
	echo $output
}

# number of records to write
numRecords=10

if [[ $# -eq 1  ]]
then
	numRecords=$1
fi

# attributes to log in
user="u107823177_Team1_SQL_User"
pass="71H7KqGKH6"
db="u107823177_Team1_SQL_DB"

# iterate through the following code for each record being added to the two tables
n=0
while [[ $n -lt $numRecords ]]
do
	# random student id to be selected
	id=$(shuf -i 1-107 -n 1)

	# make tempfile to run query
	echo "SELECT studentUsername FROM student WHERE studentId = $id;" > temp.sql

	# log in and run tempfile, capture result
	studentName=$(mysql --user=$user --password=$pass $db < temp.sql | tail -1)

	# make loginAttempt from said student (75% chance for a successful login)
	# loginAttempt fields:
	# loginAttemptUsername (already have it)
	# loginAttemptPassword 
	pw=""
	pwLen=$(shuf -i 5-15 -n 1)
	i=0
	while [[ $i -lt $pwLen ]]
	do
		toAdd=$(source random_letter.sh)
		pw="${pw}${toAdd}"
		((i++))
	done
	# loginAttemptTimeEntered (today's date + random time)
	timeEntered="$(date +%F) $(leadingZeros 1 24):$(leadingZeros 1 60):$(leadingZeros 1 60)"
	# loginAttemptSuccess (just 75% chance to be 1, otherwise 0)
	attemptSuccess=1
	rndm=$(shuf -i 0-20 -n 1)

	if [[ $rndm -lt 5 ]]
	then
		attemptSuccess=0
	fi

	# studentId (we already have it)

	# now write the query to a temp file and run it
	echo "INSERT INTO loginAttempt (loginAttemptUsername, loginAttemptPassword, loginAttemptTimeEntered, loginAttemptSuccess, studentId) VALUES ($studentName, $pw, $timeEntered, $attemptSuccess, $id);" > temp.sql
	mysql --user=$user --password=$pass $db < temp.sql > /dev/null

	# grab the resulting loginAttemptId
	echo "SELECT MAX(loginAttemptId) FROM loginAttempt;" > temp.sql
	attemptId=$(mysql --user=$user --password=$pass $db < temp.sql | tail -1)

	# make log of loginAttempt, enter that query as well
	echo "INSERT INTO log (logTimeCreated, loginAttemptId, studentId) values ($timeEntered, $attemptId, $id);" > temp.sql
	mysql --user=$user --password=$pass $db < temp.sql > /dev/null

	# now that your work is done, remove the temp file
	rm temp.sql
done
