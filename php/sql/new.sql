-- phpMyAdmin SQL Dump
-- version 2.9.0.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Apr 18, 2007 at 09:45 AM
-- Server version: 4.0.26
-- PHP Version: 4.4.6
-- 
-- Database: `dhutchmin`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `ocwdemo_accesslevels`
-- 

CREATE TABLE `ocwdemo_accesslevels` (
  `id` tinyint(4) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `ocwdemo_accesslevels`
-- 

INSERT INTO `ocwdemo_accesslevels` (`id`, `name`) VALUES (1, 'admin');
INSERT INTO `ocwdemo_accesslevels` (`id`, `name`) VALUES (2, 'dscribe');
INSERT INTO `ocwdemo_accesslevels` (`id`, `name`) VALUES (3, 'reviewer');

-- --------------------------------------------------------

-- 
-- Table structure for table `ocwdemo_acl`
-- 

CREATE TABLE `ocwdemo_acl` (
  `person_id` bigint(20) NOT NULL default '0',
  `course_id` int(11) NOT NULL default '0',
  `role_id` tinyint(4) NOT NULL default '0',
  `level_id` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`person_id`,`course_id`,`role_id`,`level_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `ocwdemo_acl`
-- 

INSERT INTO `ocwdemo_acl` (`person_id`, `course_id`, `role_id`, `level_id`) VALUES (1, 1, 1, 1);
INSERT INTO `ocwdemo_acl` (`person_id`, `course_id`, `role_id`, `level_id`) VALUES (2, 1, 2, 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `ocwdemo_content`
-- 

CREATE TABLE `ocwdemo_content` (
  `id` bigint(20) NOT NULL auto_increment,
  `material_id` bigint(20) NOT NULL default '0',
  `content` blob NOT NULL,
  `created_on` timestamp(14) NOT NULL,
  `modified_on` timestamp(14) NOT NULL default '00000000000000',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `ocwdemo_content`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ocwdemo_copyrights`
-- 

CREATE TABLE `ocwdemo_copyrights` (
  `id` bigint(20) NOT NULL auto_increment,
  `course_id` bigint(20) NOT NULL default '0',
  `copy_type` enum('default','permission') NOT NULL default 'default',
  `material_id` bigint(20) NOT NULL default '0',
  `copyright` longtext NOT NULL,
  `holder` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `ocwdemo_copyrights`
-- 

INSERT INTO `ocwdemo_copyrights` (`id`, `course_id`, `copy_type`, `material_id`, `copyright`, `holder`) VALUES (1, 1, 'default', 0, 'I own this stuff. Ask to me before you use it', 'John Smith');

-- --------------------------------------------------------

-- 
-- Table structure for table `ocwdemo_courses`
-- 

CREATE TABLE `ocwdemo_courses` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `period` enum('fall','winter','spring','summer') NOT NULL default 'fall',
  `year` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `ocwdemo_courses`
-- 

INSERT INTO `ocwdemo_courses` (`id`, `name`, `period`, `year`) VALUES (1, 'SI 505: Content Management Systems', 'winter', 2007);

-- --------------------------------------------------------

-- 
-- Table structure for table `ocwdemo_filetypes`
-- 

CREATE TABLE `ocwdemo_filetypes` (
  `id` tinyint(4) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `mimetype` varchar(70) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `ocwdemo_filetypes`
-- 

INSERT INTO `ocwdemo_filetypes` (`id`, `name`, `mimetype`) VALUES (1, 'Word', 'application/msword');
INSERT INTO `ocwdemo_filetypes` (`id`, `name`, `mimetype`) VALUES (2, 'Image (jpeg)', 'image/jpeg');
INSERT INTO `ocwdemo_filetypes` (`id`, `name`, `mimetype`) VALUES (3, 'URL', 'text/plain');

-- --------------------------------------------------------

-- 
-- Table structure for table `ocwdemo_ipobjects`
-- 

CREATE TABLE `ocwdemo_ipobjects` (
  `id` bigint(20) NOT NULL default '0',
  `material_id` bigint(20) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `done` enum('1','0') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `ocwdemo_ipobjects`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ocwdemo_materials`
-- 

CREATE TABLE `ocwdemo_materials` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `tag_id` int(11) NOT NULL default '0',
  `filetype_id` int(11) NOT NULL default '0',
  `in_ocw` enum('1','0') NOT NULL default '0',
  `nodetype` enum('child','parent') NOT NULL default 'child',
  `parent` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;

-- 
-- Dumping data for table `ocwdemo_materials`
-- 

INSERT INTO `ocwdemo_materials` (`id`, `name`, `tag_id`, `filetype_id`, `in_ocw`, `nodetype`, `parent`) VALUES (1, 'Drupal Cms', 0, 0, '1', 'parent', 0);
INSERT INTO `ocwdemo_materials` (`id`, `name`, `tag_id`, `filetype_id`, `in_ocw`, `nodetype`, `parent`) VALUES (2, 'Week 1', 0, 0, '1', 'parent', 1);
INSERT INTO `ocwdemo_materials` (`id`, `name`, `tag_id`, `filetype_id`, `in_ocw`, `nodetype`, `parent`) VALUES (3, 'Screencast: First steps', 1, 3, '1', 'child', 2);
INSERT INTO `ocwdemo_materials` (`id`, `name`, `tag_id`, `filetype_id`, `in_ocw`, `nodetype`, `parent`) VALUES (4, 'Sample text', 0, 1, '1', 'child', 2);
INSERT INTO `ocwdemo_materials` (`id`, `name`, `tag_id`, `filetype_id`, `in_ocw`, `nodetype`, `parent`) VALUES (5, 'Week 2', 0, 0, '0', 'parent', 1);
INSERT INTO `ocwdemo_materials` (`id`, `name`, `tag_id`, `filetype_id`, `in_ocw`, `nodetype`, `parent`) VALUES (6, 'Screencast: Second steps', 0, 3, '0', 'child', 5);
INSERT INTO `ocwdemo_materials` (`id`, `name`, `tag_id`, `filetype_id`, `in_ocw`, `nodetype`, `parent`) VALUES (7, 'Sample Text 2', 0, 1, '0', 'child', 5);

-- --------------------------------------------------------

-- 
-- Table structure for table `ocwdemo_people`
-- 

CREATE TABLE `ocwdemo_people` (
  `id` bigint(20) NOT NULL auto_increment,
  `firstname` varchar(20) NOT NULL default '',
  `lastname` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `ocwdemo_people`
-- 

INSERT INTO `ocwdemo_people` (`id`, `firstname`, `lastname`) VALUES (1, 'John', 'Smith');
INSERT INTO `ocwdemo_people` (`id`, `firstname`, `lastname`) VALUES (2, 'Ben', 'Rimaldi');

-- --------------------------------------------------------

-- 
-- Table structure for table `ocwdemo_roles`
-- 

CREATE TABLE `ocwdemo_roles` (
  `id` tinyint(4) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `ocwdemo_roles`
-- 

INSERT INTO `ocwdemo_roles` (`id`, `name`) VALUES (1, 'instructor');
INSERT INTO `ocwdemo_roles` (`id`, `name`) VALUES (2, 'dscribe');

-- --------------------------------------------------------

-- 
-- Table structure for table `ocwdemo_tags`
-- 

CREATE TABLE `ocwdemo_tags` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `ocwdemo_tags`
-- 

INSERT INTO `ocwdemo_tags` (`id`, `name`) VALUES (1, 'Video');
INSERT INTO `ocwdemo_tags` (`id`, `name`) VALUES (2, 'Lecture notes');
INSERT INTO `ocwdemo_tags` (`id`, `name`) VALUES (3, 'Powerpoint Slide');
