-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: localhost    Database: u107823177_Team1_SQL_DB
-- ------------------------------------------------------
-- Server version	8.0.32-0ubuntu0.20.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity`
--

DROP TABLE IF EXISTS `activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity` (
  `activityId` int NOT NULL AUTO_INCREMENT,
  `activityUserId` int NOT NULL,
  `activityLogId` int DEFAULT NULL,
  `activityStudentId` int DEFAULT NULL,
  `activityDatetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`activityId`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity`
--

LOCK TABLES `activity` WRITE;
/*!40000 ALTER TABLE `activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `class` (
  `classId` int NOT NULL AUTO_INCREMENT COMMENT 'Class ID',
  `className` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Class name ',
  `classProfessor` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Class professor',
  `schoolId` int NOT NULL COMMENT 'School ID linked from school table',
  PRIMARY KEY (`classId`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class`
--

LOCK TABLES `class` WRITE;
/*!40000 ALTER TABLE `class` DISABLE KEYS */;
/*!40000 ALTER TABLE `class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classEntry`
--

DROP TABLE IF EXISTS `classEntry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classEntry` (
  `studentId` int NOT NULL COMMENT 'Student ID from student table',
  `classId` int NOT NULL COMMENT 'Class ID from class table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Table connecting students to a specific class';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classEntry`
--

LOCK TABLES `classEntry` WRITE;
/*!40000 ALTER TABLE `classEntry` DISABLE KEYS */;
/*!40000 ALTER TABLE `classEntry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file`
--

DROP TABLE IF EXISTS `file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file` (
  `fileId` int NOT NULL AUTO_INCREMENT COMMENT 'File ID',
  `fileName` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'File name',
  `fileTimeCreated` datetime NOT NULL COMMENT 'Time the file was created',
  `fileTimeEdited` datetime NOT NULL COMMENT 'Time the file was edited',
  `fileLocation` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Location of the file',
  `studentId` int NOT NULL COMMENT 'Student ID of the student linked to the file',
  PRIMARY KEY (`fileId`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Table of file info';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file`
--

LOCK TABLES `file` WRITE;
/*!40000 ALTER TABLE `file` DISABLE KEYS */;
/*!40000 ALTER TABLE `file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log` (
  `logId` int NOT NULL AUTO_INCREMENT COMMENT 'Log ID',
  `logType` int NOT NULL,
  `logTimeCreated` datetime NOT NULL COMMENT 'Time the log was created',
  `loginAttemptId` int DEFAULT NULL COMMENT 'Login attempt ID associated with the log (if any)',
  `studentId` int NOT NULL COMMENT 'Student the log file is associated with',
  PRIMARY KEY (`logId`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Table of log info';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loginAttempt`
--

DROP TABLE IF EXISTS `loginAttempt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loginAttempt` (
  `loginAttemptId` int NOT NULL AUTO_INCREMENT COMMENT 'Login attempt ID',
  `loginAttemptUsername` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Username attached to login attempt',
  `loginAttemptTimeEntered` datetime NOT NULL COMMENT 'Time that the login attempt occurred',
  `loginAttemptSuccess` tinyint(1) NOT NULL COMMENT 'Boolean representing if the login attempt was successful or not',
  `studentId` int NOT NULL COMMENT 'Student ID linked to login attempt',
  PRIMARY KEY (`loginAttemptId`)
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Table of login attempts';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loginAttempt`
--

LOCK TABLES `loginAttempt` WRITE;
/*!40000 ALTER TABLE `loginAttempt` DISABLE KEYS */;
/*!40000 ALTER TABLE `loginAttempt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school`
--

DROP TABLE IF EXISTS `school`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `school` (
  `schoolId` int NOT NULL AUTO_INCREMENT COMMENT 'School ID',
  `schoolName` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'School name',
  PRIMARY KEY (`schoolId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Table of each school''s information';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `school`
--

LOCK TABLES `school` WRITE;
/*!40000 ALTER TABLE `school` DISABLE KEYS */;
/*!40000 ALTER TABLE `school` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student` (
  `studentId` int NOT NULL AUTO_INCREMENT COMMENT 'Student''s ID',
  `studentFirstName` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Student''s first name',
  `studentMiddleInitial` char(1) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Student''s middle initial',
  `studentLastName` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Student''s last name',
  `studentUsername` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Student''s RIT username',
  `schoolId` int NOT NULL COMMENT 'School ID of the school that the student is linked to',
  PRIMARY KEY (`studentId`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Table of students';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `userId` int NOT NULL AUTO_INCREMENT COMMENT 'User ID',
  `userFirstName` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User''s first name',
  `userLastName` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User''s last name',
  `userEmail` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User''s email address',
  `userUsername` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User''s RIT username',
  `userPassword` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User''s password',
  `userClassification` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'User''s classification',
  `schoolId` int NOT NULL COMMENT 'School ID linked from school table',
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Table of all user specific information';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-04-14 15:00:21
