-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2021 at 05:10 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evolunteer`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievement`
--

CREATE TABLE `achievement` (
  `ACHIEVEMENT_ID` varchar(15) NOT NULL,
  `ACHIEVEMENT_NAME` varchar(50) NOT NULL,
  `ACHIEVEMENT_DESCRIPTION` varchar(150) DEFAULT NULL,
  `ACHIEVEMENT_CREATED_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `USER_NRIC` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE `group` (
  `GROUP_CODE` varchar(15) NOT NULL,
  `GROUP_NAME` varchar(50) DEFAULT NULL,
  `GROUP_REMARKS` varchar(100) DEFAULT NULL,
  `GROUP_CREATED_BY` varchar(12) NOT NULL,
  `GROUP_CREATED_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`GROUP_CODE`, `GROUP_NAME`, `GROUP_REMARKS`, `GROUP_CREATED_BY`, `GROUP_CREATED_DATE`) VALUES
('ADM', 'ADMINISTRATOR', 'responsible for the upkeep, configuration, and reliable operation of computer systems', '000413140977', '2021-12-06 06:09:08'),
('USER', 'USER', 'A normal user for the system, without any special permissions.', '000413140977', '2021-12-07 08:52:37');

-- --------------------------------------------------------

--
-- Table structure for table `group_application`
--

CREATE TABLE `group_application` (
  `USER_NRIC` varchar(12) NOT NULL,
  `GROUP_CODE` varchar(15) NOT NULL,
  `APP_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `APP_STATUS` varchar(10) NOT NULL,
  `APP_ACCEPT_BY` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `group_matrix`
--

CREATE TABLE `group_matrix` (
  `PROGRAM_ID` int(11) NOT NULL,
  `GROUP_CODE` varchar(15) NOT NULL,
  `CRUD` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `participate`
--

CREATE TABLE `participate` (
  `USER_NRIC` varchar(12) NOT NULL,
  `VP_ID` varchar(25) NOT NULL,
  `PARTICIPATE_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `PARTICIPATE_NOTIFIED` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `suggestions`
--

CREATE TABLE `suggestions` (
  `SUGGESTIONS_ID` varchar(25) NOT NULL,
  `SUGGESTIONS_TITLE` varchar(50) NOT NULL,
  `SUGGESTIONS_DETAILS` longtext NOT NULL,
  `SUGGESTIONS_CREATED_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `USER_NRIC` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `suggestions`
--

INSERT INTO `suggestions` (`SUGGESTIONS_ID`, `SUGGESTIONS_TITLE`, `SUGGESTIONS_DETAILS`, `SUGGESTIONS_CREATED_DATE`, `USER_NRIC`) VALUES
('test', 'This is an example title', '# test HI\r\n\r\n*Hi!!* \r\n\r\nThis _is_ a test message!!\r\n\r\n**test**', '2021-12-20 13:56:18', '000413140977'),
('test2', 'test2', 'test2', '2021-12-10 00:33:18', '000413140977');

-- --------------------------------------------------------

--
-- Table structure for table `suggestions_comment`
--

CREATE TABLE `suggestions_comment` (
  `SC_ID` varchar(15) NOT NULL,
  `USER_NRIC` varchar(12) NOT NULL,
  `SUGGESTIONS_ID` varchar(25) NOT NULL,
  `COMMENT` text NOT NULL,
  `COMMENT_DATE_TIME` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `suggestions_comment`
--

INSERT INTO `suggestions_comment` (`SC_ID`, `USER_NRIC`, `SUGGESTIONS_ID`, `COMMENT`, `COMMENT_DATE_TIME`) VALUES
('61c1c7b2ce129', '000413140977', 'test', 'This is my first comment!!', '2021-12-21 12:25:22'),
('61c43a3f41843', '000413140977', 'test', 'This is my second comment!!!', '2021-12-23 08:58:39');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `USER_NRIC` varchar(12) NOT NULL,
  `USER_USERNAME` varchar(50) NOT NULL,
  `USER_PASSWORD` char(100) NOT NULL,
  `USER_FULL_NAME` varchar(150) NOT NULL,
  `USER_EMAIL` varchar(100) NOT NULL,
  `USER_PHONE_NO` varchar(15) NOT NULL,
  `USER_CREATED_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `USER_LOGIN_COUNT` int(11) NOT NULL,
  `USER_STATUS` char(5) NOT NULL,
  `GROUP_CODE` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`USER_NRIC`, `USER_USERNAME`, `USER_PASSWORD`, `USER_FULL_NAME`, `USER_EMAIL`, `USER_PHONE_NO`, `USER_CREATED_DATE`, `USER_LOGIN_COUNT`, `USER_STATUS`, `GROUP_CODE`) VALUES
('000413140977', 'hyglobalhd', '$argon2i$v=19$m=1024,t=2,p=2$QXNQeXZGWDJKQ3ZYRG1GUA$kdeGqUdw9dPuQbz/wybT6tDJ8xdAJvT27awLdbLSk7E', 'AMIRUL ADLI FAHMI BIN AZAM', 'clossotruth@gmail.com', '01121196596', '2021-12-10 03:38:29', 0, 'A', 'USER');

-- --------------------------------------------------------

--
-- Table structure for table `user_achievement`
--

CREATE TABLE `user_achievement` (
  `USER_NRIC` varchar(12) NOT NULL,
  `ACHIEVEMENT_ID` varchar(15) NOT NULL,
  `RECEIVED_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_program`
--

CREATE TABLE `volunteer_program` (
  `VP_ID` varchar(25) NOT NULL,
  `VP_TITLE` varchar(50) NOT NULL,
  `VP_DETAILS` longtext NOT NULL,
  `VP_START_DATE` date NOT NULL,
  `VP_END_PROGRAM` date NOT NULL,
  `VP_MINIMUM_PARTICIPANT` int(11) NOT NULL,
  `VP_PICKED_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `USER_NRIC` varchar(12) NOT NULL,
  `SUGGESTIONS_ID` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `vote`
--

CREATE TABLE `vote` (
  `USER_NRIC` varchar(12) NOT NULL,
  `SUGGESTIONS_ID` varchar(25) NOT NULL,
  `VOTE_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vote`
--

INSERT INTO `vote` (`USER_NRIC`, `SUGGESTIONS_ID`, `VOTE_DATE`) VALUES
('000413140977', 'test', '2021-12-09 07:53:43');

-- --------------------------------------------------------

--
-- Table structure for table `vp_comment`
--

CREATE TABLE `vp_comment` (
  `USER_NRIC` varchar(12) NOT NULL,
  `VP_ID` varchar(25) NOT NULL,
  `COMMENT` text NOT NULL,
  `COMMENT_DATE_TIME` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `web_program`
--

CREATE TABLE `web_program` (
  `PROGRAM_ID` int(11) NOT NULL,
  `PROGRAM_TITLE` varchar(50) DEFAULT NULL,
  `PROGRAM_LINK` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievement`
--
ALTER TABLE `achievement`
  ADD PRIMARY KEY (`ACHIEVEMENT_ID`),
  ADD KEY `achievement_ibfk_1` (`USER_NRIC`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`GROUP_CODE`);

--
-- Indexes for table `group_application`
--
ALTER TABLE `group_application`
  ADD PRIMARY KEY (`USER_NRIC`,`GROUP_CODE`),
  ADD KEY `GROUP_CODE` (`GROUP_CODE`),
  ADD KEY `APP_ACCEPT_BY` (`APP_ACCEPT_BY`);

--
-- Indexes for table `group_matrix`
--
ALTER TABLE `group_matrix`
  ADD PRIMARY KEY (`PROGRAM_ID`,`GROUP_CODE`),
  ADD KEY `group_matrix_ibfk_1` (`GROUP_CODE`);

--
-- Indexes for table `participate`
--
ALTER TABLE `participate`
  ADD PRIMARY KEY (`USER_NRIC`,`VP_ID`),
  ADD KEY `VP_ID` (`VP_ID`);

--
-- Indexes for table `suggestions`
--
ALTER TABLE `suggestions`
  ADD PRIMARY KEY (`SUGGESTIONS_ID`),
  ADD KEY `suggestions_ibfk_1` (`USER_NRIC`);

--
-- Indexes for table `suggestions_comment`
--
ALTER TABLE `suggestions_comment`
  ADD PRIMARY KEY (`SC_ID`),
  ADD KEY `SUGGESTIONS_ID` (`SUGGESTIONS_ID`),
  ADD KEY `suggestions_comment_ibfk_2` (`USER_NRIC`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`USER_NRIC`),
  ADD KEY `GROUP_CODE` (`GROUP_CODE`);

--
-- Indexes for table `user_achievement`
--
ALTER TABLE `user_achievement`
  ADD PRIMARY KEY (`USER_NRIC`,`ACHIEVEMENT_ID`),
  ADD KEY `ACHIEVEMENT_ID` (`ACHIEVEMENT_ID`);

--
-- Indexes for table `volunteer_program`
--
ALTER TABLE `volunteer_program`
  ADD PRIMARY KEY (`VP_ID`),
  ADD KEY `USER_NRIC` (`USER_NRIC`),
  ADD KEY `volunteer_program_ibfk_2` (`SUGGESTIONS_ID`);

--
-- Indexes for table `vote`
--
ALTER TABLE `vote`
  ADD PRIMARY KEY (`USER_NRIC`,`SUGGESTIONS_ID`),
  ADD KEY `SUGGESTIONS_ID` (`SUGGESTIONS_ID`);

--
-- Indexes for table `vp_comment`
--
ALTER TABLE `vp_comment`
  ADD PRIMARY KEY (`USER_NRIC`,`VP_ID`),
  ADD KEY `VP_ID` (`VP_ID`);

--
-- Indexes for table `web_program`
--
ALTER TABLE `web_program`
  ADD PRIMARY KEY (`PROGRAM_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `achievement`
--
ALTER TABLE `achievement`
  ADD CONSTRAINT `achievement_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `group_application`
--
ALTER TABLE `group_application`
  ADD CONSTRAINT `group_application_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `group_application_ibfk_2` FOREIGN KEY (`GROUP_CODE`) REFERENCES `user` (`USER_NRIC`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `group_application_ibfk_3` FOREIGN KEY (`APP_ACCEPT_BY`) REFERENCES `user` (`USER_NRIC`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `group_matrix`
--
ALTER TABLE `group_matrix`
  ADD CONSTRAINT `PROGRAM_ID` FOREIGN KEY (`PROGRAM_ID`) REFERENCES `web_program` (`PROGRAM_ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `group_matrix_ibfk_1` FOREIGN KEY (`GROUP_CODE`) REFERENCES `group` (`GROUP_CODE`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `participate`
--
ALTER TABLE `participate`
  ADD CONSTRAINT `participate_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `participate_ibfk_2` FOREIGN KEY (`VP_ID`) REFERENCES `volunteer_program` (`VP_ID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `suggestions`
--
ALTER TABLE `suggestions`
  ADD CONSTRAINT `suggestions_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `suggestions_comment`
--
ALTER TABLE `suggestions_comment`
  ADD CONSTRAINT `suggestions_comment_ibfk_1` FOREIGN KEY (`SUGGESTIONS_ID`) REFERENCES `suggestions` (`SUGGESTIONS_ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `suggestions_comment_ibfk_2` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `GROUP_CODE` FOREIGN KEY (`GROUP_CODE`) REFERENCES `group` (`GROUP_CODE`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `user_achievement`
--
ALTER TABLE `user_achievement`
  ADD CONSTRAINT `user_achievement_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `user_achievement_ibfk_2` FOREIGN KEY (`ACHIEVEMENT_ID`) REFERENCES `achievement` (`ACHIEVEMENT_ID`);

--
-- Constraints for table `volunteer_program`
--
ALTER TABLE `volunteer_program`
  ADD CONSTRAINT `volunteer_program_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `volunteer_program_ibfk_2` FOREIGN KEY (`SUGGESTIONS_ID`) REFERENCES `suggestions` (`SUGGESTIONS_ID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `vote_ibfk_2` FOREIGN KEY (`SUGGESTIONS_ID`) REFERENCES `suggestions` (`SUGGESTIONS_ID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `vp_comment`
--
ALTER TABLE `vp_comment`
  ADD CONSTRAINT `vp_comment_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `vp_comment_ibfk_2` FOREIGN KEY (`VP_ID`) REFERENCES `volunteer_program` (`VP_ID`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
