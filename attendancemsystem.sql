-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2025 at 11:08 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendancemsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `schedule_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `schedule_id`, `name`, `date`, `time_in`, `time_out`) VALUES
(9, 8, 59, 'Jack  Redondo', '2024-12-18', '15:45:28', '17:21:23'),
(10, 5, 54, 'gerald  Onquit', '2024-12-18', '15:45:41', '15:45:49'),
(11, 7, 58, 'Marianne Monroy', '2024-12-18', '17:28:05', NULL),
(41, 8, 54, 'Jack  Redondo', '2024-12-19', '18:18:25', '18:18:48'),
(72, 16, 59, 'Argel Aries Andrade', '2025-01-06', '11:19:42', NULL),
(73, 4, 54, 'Argel Aries Andrade', '2025-01-06', '11:24:49', '11:26:27'),
(75, 9, 54, 'Jack Arnel  Redondo', '2025-01-06', '11:29:55', '11:32:59');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year_section` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `day`, `start_time`, `end_time`, `instructor_id`, `course`, `year_section`) VALUES
(54, 'Monday', '11:00:00', '12:00:00', 16, 'Capstone 1', '4B'),
(59, 'Thursday', '10:00:00', '12:00:00', 19, 'Systtem Administration', '4B'),
(60, 'Friday', '10:00:00', '11:00:00', 18, 'xvz', '1A'),
(63, 'Monday', '14:30:00', '15:30:00', 17, 'Database', '1F'),
(65, 'Monday', '09:30:00', '11:00:00', 9, 'Mobile Tech', '1F');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `year` varchar(50) NOT NULL,
  `section` varchar(10) NOT NULL,
  `rfid` varchar(50) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `middle_name`, `year`, `section`, `rfid`, `email`, `password`) VALUES
(4, 'Argel Aries', 'Andrade', 'C', '4', 'B', '7D5BC2B', 'arandrade@my.cspc.edu.ph', 'f470b9d061e464c1d60f0057b710c0c6'),
(5, 'gerald ', 'Onquit', 'B', '4', 'B', 'FD2E692B', 'geonquit@my.cspc.edu.ph', '380891959a0754c24a7ad3525c2d1e77'),
(6, 'Lerma', 'Neverio', 'V', '4', 'B', 'FD90802C', 'lerma@mail.com', 'bbabc268e57d356fddce2f3aa14262f6'),
(7, 'Marianne', 'Monroy', 'O', '4', 'B', '9D2CBC2B', 'marianne@mail.com', '5d1d88238cb3222b1798127b285b827c'),
(9, 'Jack Arnel ', 'Redondo', 'D', '4', 'B', '9D6DE22B', 'jack@mail.com', '4ff9fc6e4e5d5f590c4f2134a8cc96d1'),
(12, 'Melanie', 'Cereno', '', '4', 'A', '', 'mecereno@my.cspc.edu.ph', ''),
(16, 'Christine', 'Periabras', 'po', '4', 'B', NULL, 'chperiabras@my.cspc.edu.ph', NULL),
(19, 'Student', '1', '2', '3', 'D', NULL, 'student@mycspc.edu.ph', NULL),
(20, 'Gege', 'Onquit', '', '1', 'B', NULL, 'gege@gmail.com', NULL),
(21, 'sfsd', 'dsf', 'dsf', '2', 'D', NULL, 'agagaga@my.cspc.edu.ph', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`) VALUES
(1, 'Admin', 'Admin', 'admin@mail.com', 'D00F5D5217896FB7FD601412CB890830');

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `Id` int(10) NOT NULL,
  `admissionNo` varchar(255) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(10) NOT NULL,
  `sessionTermId` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  `dateTimeTaken` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblattendance`
--

INSERT INTO `tblattendance` (`Id`, `admissionNo`, `classId`, `classArmId`, `sessionTermId`, `status`, `dateTimeTaken`) VALUES
(162, 'ASDFLKJ', '1', '2', '1', '1', '2020-11-01'),
(163, 'HSKSDD', '1', '2', '1', '1', '2020-11-01'),
(164, 'JSLDKJ', '1', '2', '1', '1', '2020-11-01'),
(172, 'HSKDS9EE', '1', '4', '1', '1', '2020-11-01'),
(171, 'JKADA', '1', '4', '1', '0', '2020-11-01'),
(170, 'JSFSKDJ', '1', '4', '1', '1', '2020-11-01'),
(173, 'ASDFLKJ', '1', '2', '1', '1', '2020-11-19'),
(174, 'HSKSDD', '1', '2', '1', '1', '2020-11-19'),
(175, 'JSLDKJ', '1', '2', '1', '1', '2020-11-19'),
(176, 'JSFSKDJ', '1', '4', '1', '0', '2021-07-15'),
(177, 'JKADA', '1', '4', '1', '0', '2021-07-15'),
(178, 'HSKDS9EE', '1', '4', '1', '0', '2021-07-15'),
(179, 'ASDFLKJ', '1', '2', '1', '0', '2021-09-27'),
(180, 'HSKSDD', '1', '2', '1', '1', '2021-09-27'),
(181, 'JSLDKJ', '1', '2', '1', '1', '2021-09-27'),
(182, 'ASDFLKJ', '1', '2', '1', '0', '2021-10-06'),
(183, 'HSKSDD', '1', '2', '1', '0', '2021-10-06'),
(184, 'JSLDKJ', '1', '2', '1', '1', '2021-10-06'),
(185, 'ASDFLKJ', '1', '2', '1', '0', '2021-10-07'),
(186, 'HSKSDD', '1', '2', '1', '0', '2021-10-07'),
(187, 'JSLDKJ', '1', '2', '1', '0', '2021-10-07'),
(188, 'AMS110', '4', '6', '1', '0', '2021-10-07'),
(189, 'AMS133', '4', '6', '1', '0', '2021-10-07'),
(190, 'AMS135', '4', '6', '1', '0', '2021-10-07'),
(191, 'AMS144', '4', '6', '1', '0', '2021-10-07'),
(192, 'AMS148', '4', '6', '1', '0', '2021-10-07'),
(193, 'AMS151', '4', '6', '1', '0', '2021-10-07'),
(194, 'AMS159', '4', '6', '1', '0', '2021-10-07'),
(195, 'AMS161', '4', '6', '1', '0', '2021-10-07');

-- --------------------------------------------------------

--
-- Table structure for table `tblclass`
--

CREATE TABLE `tblclass` (
  `Id` int(10) NOT NULL,
  `className` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclass`
--

INSERT INTO `tblclass` (`Id`, `className`) VALUES
(1, 'Seven'),
(3, 'Eight'),
(4, 'Nine'),
(5, 'test');

-- --------------------------------------------------------

--
-- Table structure for table `tblclassarms`
--

CREATE TABLE `tblclassarms` (
  `Id` int(10) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmName` varchar(255) NOT NULL,
  `isAssigned` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclassarms`
--

INSERT INTO `tblclassarms` (`Id`, `classId`, `classArmName`, `isAssigned`) VALUES
(2, '1', 'S1', '0'),
(4, '1', 'S2', '0'),
(5, '3', 'E1', '0'),
(6, '4', 'N1', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tblclassteacher`
--

CREATE TABLE `tblclassteacher` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNo` varchar(50) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL,
  `pin` varchar(11) DEFAULT NULL,
  `fingerprint` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclassteacher`
--

INSERT INTO `tblclassteacher` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`, `phoneNo`, `classId`, `classArmId`, `dateCreated`, `pin`, `fingerprint`) VALUES
(8, 'Jack ', 'Redondo', 'jack@email.com', '4ff9fc6e4e5d5f590c4f2134a8cc96d1', '09989765565', '', '', '2024-12-18', '222222', 0),
(10, 'Rose Ann', 'Awa', 'rose@gmail.com', 'fcdc7b4207660a1372d0cd5491ad856e', '09989765565', '', '', '2024-12-18', '555555', 2),
(9, 'Gelmark', 'Bolival', 'gelmark@email.com', '6a91d501d5c2c8be2f553fb0a0546431', '09999897877', '', '', '2024-12-18', '444444', 3),
(7, 'sample', 'sample', 'sample@ins.com', '5e8ff9bf55ba3508199d22e984129be6', '9099999', '', '', '2024-12-17', '111111', 7),
(16, 'Argel Aries', 'Andrade', 'arandrade@my.cspc.edu.ph', 'f470b9d061e464c1d60f0057b710c0c6', '09989765565', '', '', '2025-01-06', '000000', 1),
(17, 'jack arnel', 'redond', 'jaredondo@mail.com', '4ff9fc6e4e5d5f590c4f2134a8cc96d1', '09989765565', '', '', '2025-01-06', '111111', 5),
(18, 'jack', 'redondo', 'jar@mail.com', '4ff9fc6e4e5d5f590c4f2134a8cc96d1', '09989765565', '', '', '2025-01-06', '111111', 4),
(19, 'jack ARNEL', 'Redondo', 'jackred@mail.com', '4ff9fc6e4e5d5f590c4f2134a8cc96d1', '09989765565', '', '', '2025-01-06', '222222', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tblsessionterm`
--

CREATE TABLE `tblsessionterm` (
  `Id` int(10) NOT NULL,
  `sessionName` varchar(50) NOT NULL,
  `termId` varchar(50) NOT NULL,
  `isActive` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblsessionterm`
--

INSERT INTO `tblsessionterm` (`Id`, `sessionName`, `termId`, `isActive`, `dateCreated`) VALUES
(1, '2019/2020', '1', '1', '2020-10-31'),
(3, '2019/2020', '2', '0', '2020-10-31');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `otherName` varchar(255) NOT NULL,
  `admissionNumber` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`Id`, `firstName`, `lastName`, `otherName`, `admissionNumber`, `password`, `classId`, `classArmId`, `dateCreated`) VALUES
(3, 'Samuel', 'Rosella', 'none', 'AMS007', '12345', '1', '2', '2020-10-31'),
(4, 'Milagros', 'Lawson', 'none', 'AMS011', '12345', '1', '2', '2020-10-31'),
(5, 'Luis', 'Ayo', 'none', 'AMS012', '12345', '1', '4', '2020-10-31'),
(6, 'Sandra', 'Salgado', 'none', 'AMS015', '12345', '1', '4', '2020-10-31'),
(7, 'Smith', 'Mack', 'Mack', 'AMS017', '12345', '1', '4', '2020-10-31'),
(8, 'Juliana', 'Debiie', 'none', 'AMS019', '12345', '3', '5', '2020-10-31'),
(9, 'Richard', 'Grimmer', 'none', 'AMS021', '12345', '3', '5', '2020-10-31'),
(10, 'Jon', 'Boller', 'none', 'AMS110', '12345', '4', '6', '2021-10-07'),
(11, 'Aida', 'Hawley', 'none', 'AMS133', '12345', '4', '6', '2021-10-07'),
(12, 'Miguel', 'Bush', 'none', 'AMS135', '12345', '4', '6', '2021-10-07'),
(13, 'Sergio', 'Hammons', 'none', 'AMS144', '12345', '4', '6', '2021-10-07'),
(14, 'Lyn', 'Rogers', 'none', 'AMS148', '12345', '4', '6', '2021-10-07'),
(15, 'James', 'Dominick', 'none', 'AMS151', '12345', '4', '6', '2021-10-07'),
(16, 'Ethel', 'Quin', 'none', 'AMS159', '12345', '4', '6', '2021-10-07'),
(17, 'Roland', 'Estrada', 'none', 'AMS161', '12345', '4', '6', '2021-10-07');

-- --------------------------------------------------------

--
-- Table structure for table `tblterm`
--

CREATE TABLE `tblterm` (
  `Id` int(10) NOT NULL,
  `termName` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblterm`
--

INSERT INTO `tblterm` (`Id`, `termName`) VALUES
(1, 'First'),
(2, 'Second'),
(3, 'Third');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `rfid` (`rfid`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclass`
--
ALTER TABLE `tblclass`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclassteacher`
--
ALTER TABLE `tblclassteacher`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblterm`
--
ALTER TABLE `tblterm`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `tblclass`
--
ALTER TABLE `tblclass`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblclassteacher`
--
ALTER TABLE `tblclassteacher`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tblterm`
--
ALTER TABLE `tblterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
