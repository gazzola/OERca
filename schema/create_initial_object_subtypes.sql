-- phpMyAdmin SQL Dump
-- version 2.11.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 25, 2009 at 10:42 AM
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
-- Table structure for table `ocw_object_subtypes`
-- 

CREATE TABLE IF NOT EXISTS `ocw_object_subtypes` (
  `id` bigint(20) NOT NULL auto_increment,
	`name` varchar(255) NOT NULL,
	`type_id` int(11) NOT NULL,
	`description` text NOT NULL,
	PRIMARY KEY  (`id`),
	KEY `type_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Data for table `ocw_object_subtypes`
--

INSERT INTO `ocw_object_subtypes` VALUES(1, 'Graph', 2, '');
INSERT INTO `ocw_object_subtypes` VALUES(2, 'Chart', 8, '');
INSERT INTO `ocw_object_subtypes` VALUES(3, 'People', 1, '');
INSERT INTO `ocw_object_subtypes` VALUES(4, 'Objects', 1, '');
INSERT INTO `ocw_object_subtypes` VALUES(5, 'Personal Information', 9, '');
INSERT INTO `ocw_object_subtypes` VALUES(6, 'Screenshot', 4, '');
INSERT INTO `ocw_object_subtypes` VALUES(7, 'Sketch or Drawing', 5, '');
INSERT INTO `ocw_object_subtypes` VALUES(9, 'Computer Graphic', 5, '');
INSERT INTO `ocw_object_subtypes` VALUES(11, 'Cartoon', 5, '');
INSERT INTO `ocw_object_subtypes` VALUES(13, 'Chemical Representation', 5, '');
INSERT INTO `ocw_object_subtypes` VALUES(14, 'Scientific Image', 4, '');
INSERT INTO `ocw_object_subtypes` VALUES(15, 'Audio', 3, '');
INSERT INTO `ocw_object_subtypes` VALUES(16, 'Quote', 9, '');
INSERT INTO `ocw_object_subtypes` VALUES(17, 'Artwork', 7, '');
INSERT INTO `ocw_object_subtypes` VALUES(18, 'Trademark', 4, '');
INSERT INTO `ocw_object_subtypes` VALUES(20, 'Video', 3, '');
INSERT INTO `ocw_object_subtypes` VALUES(21, 'Other Media', 4, '');
INSERT INTO `ocw_object_subtypes` VALUES(22, 'None', 6, 'Not defined');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ocw_object_subtypes`
--
ALTER TABLE `ocw_object_subtypes`
  ADD CONSTRAINT `ocw_object_subtypes_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `ocw_object_types` (`id`);

SET FOREIGN_KEY_CHECKS=1;
