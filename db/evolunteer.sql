-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2022 at 03:20 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

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
  `ACHIEVEMENT_CREATED_DATE` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `USER_NRIC` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `achievement`
--

INSERT INTO `achievement` (`ACHIEVEMENT_ID`, `ACHIEVEMENT_NAME`, `ACHIEVEMENT_DESCRIPTION`, `ACHIEVEMENT_CREATED_DATE`, `USER_NRIC`) VALUES
('login_1', 'Login 1', 'Login for 10 times', '2021-12-30 08:31:00', '000413140977'),
('login_2', 'Login 2', 'Login for 30 times', '2021-12-30 08:32:22', '000413140977'),
('login_3', 'Login 3', 'Login for 50 times', '2021-12-30 08:33:11', '000413140977'),
('suggestion_1', 'Suggestion 1', 'Create suggestion a total of 10 post', '2021-12-30 08:35:58', '000413140977'),
('suggestion_2', 'Suggestion 2', 'Create suggestion a total of 30 post', '2021-12-30 08:36:47', '000413140977'),
('suggestion_3', 'Suggestion 3', 'Create suggestion a total of 50 post', '2021-12-30 08:37:08', '000413140977'),
('volunteer_1', 'Volunteer 1', 'Participate in volunteer program for a total of 10 times', '2021-12-30 08:42:15', '000413140977'),
('volunteer_2', 'Volunteer 2', 'Participate in volunteer program for a total of 30 times', '2021-12-30 08:43:22', '000413140977'),
('volunteer_3', 'Volunteer 3', 'Participate in volunteer program for a total of 50 times', '2021-12-30 08:42:55', '000413140977'),
('vote_1', 'Vote 1', 'Vote for suggestion for a total of 10 times', '2021-12-30 08:39:46', '000413140977'),
('vote_2', 'Vote 2', 'Vote for suggestion for a total of 30 times', '2021-12-30 08:40:04', '000413140977'),
('vote_3', 'Vote 3', 'Vote for suggestion for a total of 50 times', '2021-12-30 08:40:21', '000413140977');

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE `group` (
  `GROUP_CODE` varchar(15) NOT NULL,
  `GROUP_NAME` varchar(50) DEFAULT NULL,
  `GROUP_REMARKS` varchar(100) DEFAULT NULL,
  `GROUP_CREATED_BY` varchar(12) NOT NULL,
  `GROUP_CREATED_DATE` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`GROUP_CODE`, `GROUP_NAME`, `GROUP_REMARKS`, `GROUP_CREATED_BY`, `GROUP_CREATED_DATE`) VALUES
('ADM', 'ADMINISTRATOR', 'responsible for the upkeep, configuration, and reliable operation of computer systems', '000413140977', '2021-12-06 06:09:08'),
('ORG', 'ORGANIZER', 'People who organize volunteer program events based on the suggestions.', '000413140977', '2022-01-11 07:37:54'),
('USER', 'USER', 'A normal user that have basic access and basic features.', '000413140977', '2021-12-07 08:52:37');

-- --------------------------------------------------------

--
-- Table structure for table `group_application`
--

CREATE TABLE `group_application` (
  `USER_NRIC` varchar(12) NOT NULL,
  `GROUP_CODE` varchar(15) NOT NULL,
  `APP_DATE` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `APP_STATUS` varchar(10) NOT NULL,
  `APP_ACCEPT_BY` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `group_application`
--

INSERT INTO `group_application` (`USER_NRIC`, `GROUP_CODE`, `APP_DATE`, `APP_STATUS`, `APP_ACCEPT_BY`) VALUES
('000413140966', 'ORG', '2022-01-24 06:56:42', 'A', '000413140977'),
('000413140988', 'ORG', '2022-01-19 03:16:10', 'R', '000413140977'),
('000413140999', 'ORG', '2022-01-24 01:37:39', 'A', '000413140977');

-- --------------------------------------------------------

--
-- Table structure for table `participate`
--

CREATE TABLE `participate` (
  `USER_NRIC` varchar(12) NOT NULL,
  `VP_ID` varchar(25) NOT NULL,
  `PARTICIPATE_DATE` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `PARTICIPATE_NOTIFIED` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `participate`
--

INSERT INTO `participate` (`USER_NRIC`, `VP_ID`, `PARTICIPATE_DATE`, `PARTICIPATE_NOTIFIED`) VALUES
('000413140966', '61e76531007603.07660329', '2022-01-24 06:50:17', 1),
('000413140966', '61ee4e925b2d48.12774560', '2022-01-24 07:02:47', 1),
('000413140977', '61cbaf8f4b4766.97973631', '2022-01-17 07:01:55', 1),
('000413140977', '61e76531007603.07660329', '2022-01-19 01:50:16', 1),
('000413140977', 'test', '2022-01-17 01:15:33', 0),
('000413140988', '61cbaf8f4b4766.97973631', '2022-01-17 07:02:58', 1),
('000413140988', '61ee4e925b2d48.12774560', '2022-01-24 07:07:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `suggestions`
--

CREATE TABLE `suggestions` (
  `SUGGESTIONS_ID` varchar(25) NOT NULL,
  `SUGGESTIONS_TITLE` varchar(50) NOT NULL,
  `SUGGESTIONS_DETAILS` longtext NOT NULL,
  `SUGGESTIONS_CREATED_DATE` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `USER_NRIC` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `suggestions`
--

INSERT INTO `suggestions` (`SUGGESTIONS_ID`, `SUGGESTIONS_TITLE`, `SUGGESTIONS_DETAILS`, `SUGGESTIONS_CREATED_DATE`, `USER_NRIC`) VALUES
('61c96bc2f0c286.43081621', 'Help Cleaning Up River', 'Help Cleaning Up River', '2022-01-19 04:12:34', '000413140977'),
('61cbaf7f430107.98886454', 'vp teststs', 'tsetsef saf', '2021-12-29 00:44:47', '000413140977'),
('61e4f199bbc132.31331970', 'Collect trash on beach at Port Dickson', 'I am suggesting that we made a volunteer program for collecting trash at Port Dickson.\r\n\r\nSuggested day taken: 2 and half day.\r\n\r\nSuggested minimum participant: 5 people.\r\n\r\n![example trash](https://images2.minutemediacdn.com/image/upload/c_fill,g_auto,h_1248,w_2220/v1555389948/shape/mentalfloss/beachtrash.jpg?itok=5mAuvQ2X)\r\n\r\n![second example trash](https://d279m997dpfwgl.cloudfront.net/wp/2017/05/0522_henderson-island.jpg)', '2022-01-17 04:44:18', '000413140988'),
('61e760a2d540d4.93292753', 'Help in cleaning up the flood affected aftermath a', 'I am suggesting that we hold a volunteer program on helping places that are affected by flood.\r\n\r\n*Suggested Participant:* 20\r\n*Suggested Period:* 3 Weeks', '2022-01-19 00:55:43', '000413140977'),
('61ee4aea27f250.72286004', 'Example title', '# Test headings\r\n\r\n**strong**\r\n\r\n*italic*\r\n\r\n![image](https://cdn1.theinertia.com/wp-content/uploads/2015/06/RI-Barrel.jpg)', '2022-01-24 06:45:34', '000413140966'),
('test', 'This is an example title', '# test HI\r\n\r\n*Hi!!* \r\n\r\nThis _is_ a test message!!\r\n\r\n**test**v', '2021-12-28 02:07:15', '000413140977'),
('test3', 'test 3 vp', 'test 3 vp', '2021-12-27 05:06:25', '000413140977');

-- --------------------------------------------------------

--
-- Table structure for table `suggestions_comment`
--

CREATE TABLE `suggestions_comment` (
  `SC_ID` varchar(15) NOT NULL,
  `USER_NRIC` varchar(12) NOT NULL,
  `SUGGESTIONS_ID` varchar(25) NOT NULL,
  `COMMENT` text NOT NULL,
  `COMMENT_DATE_TIME` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `suggestions_comment`
--

INSERT INTO `suggestions_comment` (`SC_ID`, `USER_NRIC`, `SUGGESTIONS_ID`, `COMMENT`, `COMMENT_DATE_TIME`) VALUES
('61c1c7b2ce129', '000413140977', 'test', 'This is my first comment!!', '2021-12-21 12:25:22'),
('61c43a3f41843', '000413140977', 'test', 'This is my second comment!!! \"test\" \'test\' \"test \'test test test', '2021-12-28 01:26:57'),
('61e4eeb9bfe1b', '000413140988', 'test', 'test', '2022-01-17 04:21:13'),
('61ee4b806933f', '000413140966', '61ee4aea27f250.72286004', 'Hi!', '2022-01-24 06:47:28'),
('61ee4b9b896d3', '000413140966', '61ee4aea27f250.72286004', 'Hi 2', '2022-01-24 06:47:55'),
('61ee4ba07f3db', '000413140966', '61ee4aea27f250.72286004', 'Hi 3', '2022-01-24 06:48:00'),
('61ee4ba620218', '000413140966', '61ee4aea27f250.72286004', 'Hi 4', '2022-01-24 06:48:06'),
('61ee4baa92032', '000413140966', '61ee4aea27f250.72286004', 'Hello', '2022-01-24 06:48:33'),
('61eea649108d2', '000413140977', 'test', 'hii there', '2022-01-24 13:14:49');

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
  `USER_CREATED_DATE` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `USER_LOGIN_COUNT` int(11) NOT NULL,
  `USER_STATUS` char(5) NOT NULL,
  `GROUP_CODE` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`USER_NRIC`, `USER_USERNAME`, `USER_PASSWORD`, `USER_FULL_NAME`, `USER_EMAIL`, `USER_PHONE_NO`, `USER_CREATED_DATE`, `USER_LOGIN_COUNT`, `USER_STATUS`, `GROUP_CODE`) VALUES
('000413140966', 'amirul12', '$argon2i$v=19$m=65536,t=4,p=1$MlAxVC5ZczVidDJMaVBaUQ$8DOYZhzVZH/2qG69Q9f41938rUliD5dOkXWLhlM4aD0', 'Amirul Adli', 'amirul12@gmail.com', '01121196555', '2022-01-24 08:48:28', 4, 'A', 'ORG'),
('000413140977', 'hyglobalhd', '$argon2i$v=19$m=65536,t=4,p=1$Y1U0VjQwSjZna3RRTWhOaw$+rhePHfjGwL3Q1S63p6Hkw3QWpO4/CfJ6OiuZBMzHvU', 'AMIRUL ADLI FAHMI BIN AZAM', 'amiruladlifahmi@gmail.com', '01121196590', '2022-01-24 13:13:10', 40, 'A', 'ADM'),
('000413140988', 'usertest', '$argon2i$v=19$m=65536,t=4,p=1$OVYxWGRwL2Q0QndvdjZzZw$4DkrcPmnc+w+f5VYxEvDn1+wRnV6KFeYpGbKJw3aZvs', 'Amirul Fahmi', 'test@mail.net', '01121196597', '2022-01-24 07:06:07', 6, 'A', 'USER'),
('000413140999', 'amiruladli', '$argon2i$v=19$m=65536,t=4,p=1$ZW5lQXVRUlRoZE9BNmM2Lg$T+4sIS5pDb60xDYq+BclWppcYar1X3UffmyW4L6l63Q', 'Amirul Adli', 'amirul@gmail.com', '01121196599', '2022-01-24 06:55:16', 2, 'A', 'ORG'),
('000413160955', 'useramirul', '$argon2i$v=19$m=65536,t=4,p=1$QnBBL0VZM3pGdmRJZU1uag$TAVsqBGs3RQ80qjIRZVF0iCIZa00TSsPD7mgLD8O2wU', 'amirul', 'amirul13@gmail.com', '01121196596', '2022-01-24 13:12:03', 0, 'A', 'USER');

-- --------------------------------------------------------

--
-- Table structure for table `user_achievement`
--

CREATE TABLE `user_achievement` (
  `USER_NRIC` varchar(12) NOT NULL,
  `ACHIEVEMENT_ID` varchar(15) NOT NULL,
  `RECEIVED_DATE` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_achievement`
--

INSERT INTO `user_achievement` (`USER_NRIC`, `ACHIEVEMENT_ID`, `RECEIVED_DATE`) VALUES
('000413140977', 'login_1', '2021-12-30 08:57:57'),
('000413140977', 'login_2', '2022-01-18 09:32:41');

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
  `VP_PICKED_DATE` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `USER_NRIC` varchar(12) NOT NULL,
  `SUGGESTIONS_ID` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `volunteer_program`
--

INSERT INTO `volunteer_program` (`VP_ID`, `VP_TITLE`, `VP_DETAILS`, `VP_START_DATE`, `VP_END_PROGRAM`, `VP_MINIMUM_PARTICIPANT`, `VP_PICKED_DATE`, `USER_NRIC`, `SUGGESTIONS_ID`) VALUES
('61cbaf8f4b4766.97973631', 'vp teststs', 'tsetsef saf', '2022-01-18', '2022-01-21', 2, '2022-01-17 06:59:29', '000413140977', '61cbaf7f430107.98886454'),
('61e76531007603.07660329', 'Help in cleaning up the flood affected aftermath', 'I am organizing volunteer program for helping cleaning up the flood affected aftermath\r\n\r\n**For a period of 3 Weeks**\r\n\r\n**Stay Safe**', '2022-01-27', '2022-01-31', 20, '2022-01-19 01:38:57', '000413140977', '61e760a2d540d4.93292753'),
('61ee4e925b2d48.12774560', 'Collect trash on beach at Port Dickson', 'I am suggesting that we made a volunteer program for collecting trash at Port Dickson.\r\n\r\n\r\n**day taken:** 2 and half day.\r\n**minimum participant:** 5 people.\r\n\r\n![example trash](https://images2.minutemediacdn.com/image/upload/c_fill,g_auto,h_1248,w_2220/v1555389948/shape/mentalfloss/beachtrash.jpg?itok=5mAuvQ2X)\r\n\r\n![second example trash](https://d279m997dpfwgl.cloudfront.net/wp/2017/05/0522_henderson-island.jpg)', '2022-02-01', '2022-02-03', 5, '2022-01-24 07:02:17', '000413140966', '61e4f199bbc132.31331970'),
('test', 'setse', 'tsetw', '2021-12-29', '2022-01-03', 3, '2021-12-29 07:00:05', '000413140977', 'test3');

-- --------------------------------------------------------

--
-- Table structure for table `vote`
--

CREATE TABLE `vote` (
  `USER_NRIC` varchar(12) NOT NULL,
  `SUGGESTIONS_ID` varchar(25) NOT NULL,
  `VOTE_DATE` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vote`
--

INSERT INTO `vote` (`USER_NRIC`, `SUGGESTIONS_ID`, `VOTE_DATE`) VALUES
('000413140966', '61e4f199bbc132.31331970', '2022-01-24 06:49:13'),
('000413140966', '61ee4aea27f250.72286004', '2022-01-24 06:45:26'),
('000413140977', '61e4f199bbc132.31331970', '2022-01-17 07:14:05'),
('000413140977', '61e760a2d540d4.93292753', '2022-01-19 00:58:46'),
('000413140977', 'test', '2022-01-24 13:15:04'),
('000413140988', '61e4f199bbc132.31331970', '2022-01-17 04:44:37'),
('000413140988', 'test', '2022-01-17 04:26:05');

-- --------------------------------------------------------

--
-- Table structure for table `vp_comment`
--

CREATE TABLE `vp_comment` (
  `VC_ID` varchar(15) NOT NULL,
  `USER_NRIC` varchar(12) NOT NULL,
  `VP_ID` varchar(25) NOT NULL,
  `COMMENT` text NOT NULL,
  `COMMENT_DATE_TIME` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vp_comment`
--

INSERT INTO `vp_comment` (`VC_ID`, `USER_NRIC`, `VP_ID`, `COMMENT`, `COMMENT_DATE_TIME`) VALUES
('61cd47ae6a138', '000413140977', 'test', 'hi', '2021-12-30 05:46:22'),
('61e4e8be76b5a', '000413140977', '61cbaf8f4b4766.97973631', 'this is a comment *1*', '2022-01-17 03:55:42'),
('61e4e920e4768', '000413140977', '61cbaf8f4b4766.97973631', 'test', '2022-01-17 03:57:20'),
('61e4f003256fb', '000413140988', '61cbaf8f4b4766.97973631', 'er', '2022-01-17 04:28:09'),
('61e76d1fa13a6', '000413140977', '61e76531007603.07660329', 'This is a great program! Let\'s Participate!', '2022-01-19 01:47:40'),
('61ee4bd99a63d', '000413140966', '61e76531007603.07660329', '2', '2022-01-24 06:48:57'),
('61eea6774c16d', '000413140977', '61ee4e925b2d48.12774560', 'hi there too', '2022-01-24 13:15:35');

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
  ADD KEY `GROUP_CODE` (`GROUP_CODE`);

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
  ADD PRIMARY KEY (`VC_ID`),
  ADD KEY `VP_ID` (`VP_ID`),
  ADD KEY `vp_comment_ibfk_1` (`USER_NRIC`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `achievement`
--
ALTER TABLE `achievement`
  ADD CONSTRAINT `achievement_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `group_application`
--
ALTER TABLE `group_application`
  ADD CONSTRAINT `group_application_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_application_ibfk_2` FOREIGN KEY (`GROUP_CODE`) REFERENCES `group` (`GROUP_CODE`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `participate`
--
ALTER TABLE `participate`
  ADD CONSTRAINT `participate_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `participate_ibfk_2` FOREIGN KEY (`VP_ID`) REFERENCES `volunteer_program` (`VP_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `suggestions`
--
ALTER TABLE `suggestions`
  ADD CONSTRAINT `suggestions_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `suggestions_comment`
--
ALTER TABLE `suggestions_comment`
  ADD CONSTRAINT `suggestions_comment_ibfk_1` FOREIGN KEY (`SUGGESTIONS_ID`) REFERENCES `suggestions` (`SUGGESTIONS_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `suggestions_comment_ibfk_2` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `GROUP_CODE` FOREIGN KEY (`GROUP_CODE`) REFERENCES `group` (`GROUP_CODE`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `user_achievement`
--
ALTER TABLE `user_achievement`
  ADD CONSTRAINT `user_achievement_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_achievement_ibfk_2` FOREIGN KEY (`ACHIEVEMENT_ID`) REFERENCES `achievement` (`ACHIEVEMENT_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `volunteer_program`
--
ALTER TABLE `volunteer_program`
  ADD CONSTRAINT `volunteer_program_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `volunteer_program_ibfk_2` FOREIGN KEY (`SUGGESTIONS_ID`) REFERENCES `suggestions` (`SUGGESTIONS_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vote_ibfk_2` FOREIGN KEY (`SUGGESTIONS_ID`) REFERENCES `suggestions` (`SUGGESTIONS_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vp_comment`
--
ALTER TABLE `vp_comment`
  ADD CONSTRAINT `vp_comment_ibfk_1` FOREIGN KEY (`USER_NRIC`) REFERENCES `user` (`USER_NRIC`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vp_comment_ibfk_2` FOREIGN KEY (`VP_ID`) REFERENCES `volunteer_program` (`VP_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
