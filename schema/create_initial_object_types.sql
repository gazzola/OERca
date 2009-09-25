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
-- Table structure for table `ocw_object_types`
-- 

CREATE TABLE IF NOT EXISTS `ocw_object_types` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Data for table `ocw_object_types`
--

INSERT INTO `ocw_object_types` VALUES(1, 'Photograph', NULL);
INSERT INTO `ocw_object_types` VALUES(2, 'Graph', NULL);
INSERT INTO `ocw_object_types` VALUES(3, 'Recording', NULL);
INSERT INTO `ocw_object_types` VALUES(4, 'Other', '');
INSERT INTO `ocw_object_types` VALUES(5, 'Illustration', NULL);
INSERT INTO `ocw_object_types` VALUES(6, 'None', 'Not defined');
INSERT INTO `ocw_object_types` VALUES(7, 'Artwork', NULL);
INSERT INTO `ocw_object_types` VALUES(8, 'Chart', NULL);
INSERT INTO `ocw_object_types` VALUES(9, 'Text', NULL);

SET FOREIGN_KEY_CHECKS=1;

