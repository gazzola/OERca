-- phpMyAdmin SQL Dump
-- version 2.11.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 25, 2009 at 10:41 AM
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
-- Table structure for table `ocw_mimetypes`
-- 

CREATE TABLE IF NOT EXISTS `ocw_mimetypes` (
  `id` tinyint(4) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `mimetype` varchar(70) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Data for table `ocw_mimetypes`
--

INSERT INTO `ocw_mimetypes` VALUES(1, 'MS Word', 'application/msword');
INSERT INTO `ocw_mimetypes` VALUES(2, 'Image (jpeg)', 'image/jpeg');
INSERT INTO `ocw_mimetypes` VALUES(3, 'URL', 'text/plain');
INSERT INTO `ocw_mimetypes` VALUES(4, 'Powerpoint', 'application/mspowerpoint');
INSERT INTO `ocw_mimetypes` VALUES(6, 'No file type', 'text/plain');
INSERT INTO `ocw_mimetypes` VALUES(7, 'Excerpt', 'text/plain');
INSERT INTO `ocw_mimetypes` VALUES(8, 'Quote', 'text/plain');
INSERT INTO `ocw_mimetypes` VALUES(9, 'Citation', 'text/plain');
INSERT INTO `ocw_mimetypes` VALUES(10, 'Image (gif)', 'image/gif');
INSERT INTO `ocw_mimetypes` VALUES(11, 'Audio (wav)', 'audio/x-wav');
INSERT INTO `ocw_mimetypes` VALUES(12, 'Audio (mp3)', 'audio/mpeg');
INSERT INTO `ocw_mimetypes` VALUES(13, 'Video (mpeg)', 'video/mpeg');
INSERT INTO `ocw_mimetypes` VALUES(14, 'PDF', 'application/pdf');
INSERT INTO `ocw_mimetypes` VALUES(15, 'Audio (midi)', 'audio/midi');
INSERT INTO `ocw_mimetypes` VALUES(16, 'Audio (ra)', 'audio/x-realaudio');
INSERT INTO `ocw_mimetypes` VALUES(17, 'Audio (rm/ram)', 'audio/x-pn-realaudio');
INSERT INTO `ocw_mimetypes` VALUES(18, 'Audio (smil)', 'application/smil');
INSERT INTO `ocw_mimetypes` VALUES(19, 'Audio (tsp)', 'audio/TSP-audio');
INSERT INTO `ocw_mimetypes` VALUES(20, 'Compressed (gz)', 'application/x-gzip');
INSERT INTO `ocw_mimetypes` VALUES(21, 'Compressed (tar)', 'application/x-tar');
INSERT INTO `ocw_mimetypes` VALUES(22, 'Compressed (zip)', 'application/zip');
INSERT INTO `ocw_mimetypes` VALUES(23, 'Image (bitmap)', 'image/x-xbitmap');
INSERT INTO `ocw_mimetypes` VALUES(24, 'Image (png)', 'image/png');
INSERT INTO `ocw_mimetypes` VALUES(25, 'Image (tiff)', 'image/tiff');
INSERT INTO `ocw_mimetypes` VALUES(26, 'Text (rtf)', 'text/rtf');
INSERT INTO `ocw_mimetypes` VALUES(27, 'Text (txt)', 'text/plain');
INSERT INTO `ocw_mimetypes` VALUES(28, 'Video (avi)', 'video/x-msvideo');
INSERT INTO `ocw_mimetypes` VALUES(29, 'Video (quicktime)', 'video/quicktime');
INSERT INTO `ocw_mimetypes` VALUES(30, 'Video (vcd)', 'application/x-cdlink');
INSERT INTO `ocw_mimetypes` VALUES(31, 'Video (vivo)', 'video/vnd.vivo');
INSERT INTO `ocw_mimetypes` VALUES(32, 'Excel', 'application/vnd.ms-excel');
INSERT INTO `ocw_mimetypes` VALUES(33, 'HTML', 'text/html');
INSERT INTO `ocw_mimetypes` VALUES(34, 'CSS', 'text/css');
INSERT INTO `ocw_mimetypes` VALUES(35, 'Postscript', 'application/postscript');
INSERT INTO `ocw_mimetypes` VALUES(36, 'Shockwave', 'application/x-shockwave-flash');
INSERT INTO `ocw_mimetypes` VALUES(37, 'VRML', 'model/vrml');
INSERT INTO `ocw_mimetypes` VALUES(38, 'Win Executable', 'application/octet-stream');
INSERT INTO `ocw_mimetypes` VALUES(39, 'XML', 'text/xml');
INSERT INTO `ocw_mimetypes` VALUES(40, 'Folder', 'folder');
INSERT INTO `ocw_mimetypes` VALUES(41, 'Application Zip File', 'application/x-zip');

SET FOREIGN_KEY_CHECKS=1;

