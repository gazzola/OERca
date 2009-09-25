-- phpMyAdmin SQL Dump
-- version 2.11.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 25, 2009 at 11:25 AM
-- Server version: 5.0.41
-- PHP Version: 5.2.6

SET FOREIGN_KEY_CHECKS=0;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `ocw_tags`
--

CREATE TABLE IF NOT EXISTS `ocw_tags` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ocw_tags`
--

INSERT INTO `ocw_tags` VALUES(1, 'Video lectures', '');
INSERT INTO `ocw_tags` VALUES(2, 'Lecture notes', '');
INSERT INTO `ocw_tags` VALUES(3, 'Projects', '');
INSERT INTO `ocw_tags` VALUES(4, 'Assignments', '');
INSERT INTO `ocw_tags` VALUES(5, 'Discussion group', '');
INSERT INTO `ocw_tags` VALUES(6, 'Exams', '');
INSERT INTO `ocw_tags` VALUES(7, 'Labs', '');
INSERT INTO `ocw_tags` VALUES(8, 'Readings', '');
INSERT INTO `ocw_tags` VALUES(9, 'Schedule', '');
INSERT INTO `ocw_tags` VALUES(10, 'Syllabus', '');
INSERT INTO `ocw_tags` VALUES(11, 'Supplemental media', '');
INSERT INTO `ocw_tags` VALUES(12, 'Learning objectives', '');
INSERT INTO `ocw_tags` VALUES(13, 'Lecture slides', '');
INSERT INTO `ocw_tags` VALUES(14, 'Photo slides', '');
INSERT INTO `ocw_tags` VALUES(15, 'Not tagged', 'No tag has been provided');
INSERT INTO `ocw_tags` VALUES(16, 'Review/Summary', 'review or summary materials for a course or sequence');

SET FOREIGN_KEY_CHECKS=1;
