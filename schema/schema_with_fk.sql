-- phpMyAdmin SQL Dump
-- version 2.10.3deb1ubuntu0.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jun 03, 2008 at 04:41 PM
-- Server version: 5.0.45
-- PHP Version: 5.2.3-1ubuntu6.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `ocw`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_acl`
-- 

DROP TABLE IF EXISTS `ocw_acl`;
CREATE TABLE `ocw_acl` (
  `user_id` int(11) NOT NULL default '0',
  `course_id` bigint(20) NOT NULL default '0',
  `role` enum('instructor','dscribe1','dscribe2') collate utf8_unicode_ci NOT NULL default 'dscribe1',
  PRIMARY KEY  (`user_id`,`course_id`,`role`),
  KEY `user_id` (`user_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_ci_sessions`
-- 

DROP TABLE IF EXISTS `ocw_ci_sessions`;
CREATE TABLE `ocw_ci_sessions` (
  `session_id` varchar(40) NOT NULL default '0',
  `ip_address` varchar(16) NOT NULL default '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL default '0',
  `session_data` text,
  PRIMARY KEY  (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_claims_commission`
-- 

DROP TABLE IF EXISTS `ocw_claims_commission`;
CREATE TABLE `ocw_claims_commission` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `user_id` int(11) default NULL,
  `rationale` longtext collate utf8_unicode_ci NOT NULL,
  `comments` text collate utf8_unicode_ci NOT NULL,
  `have_replacement` enum('yes','no','pending') collate utf8_unicode_ci NOT NULL default 'pending',
  `recommend_commission` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  `status` enum('new','in progress','done') collate utf8_unicode_ci NOT NULL default 'new',
  `action` enum('None','Permission','Search','Fair Use','Re-Create','Retain: Instructor Created','Retain: Public Domain','Retain: No Copyright','Remove & Annotate') collate utf8_unicode_ci NOT NULL default 'None',
  `created_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_claims_fairuse`
-- 

DROP TABLE IF EXISTS `ocw_claims_fairuse`;
CREATE TABLE `ocw_claims_fairuse` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `user_id` int(11) default NULL,
  `rationale` longtext collate utf8_unicode_ci NOT NULL,
  `additional_rationale` text collate utf8_unicode_ci,
  `comments` text collate utf8_unicode_ci NOT NULL,
  `warrant_review` enum('yes','no','pending') collate utf8_unicode_ci NOT NULL default 'pending',
  `action` enum('None','Permission','Search','Re-Create','Retain: Instructor Created','Retain: Public Domain','Retain: No Copyright','Commission','Remove & Annotate') collate utf8_unicode_ci NOT NULL default 'None',
  `status` enum('new','in progress','ip review','done') collate utf8_unicode_ci NOT NULL default 'new',
  `approved` enum('yes','no','pending') collate utf8_unicode_ci NOT NULL default 'pending',
  `created_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_claims_permission`
-- 

DROP TABLE IF EXISTS `ocw_claims_permission`;
CREATE TABLE `ocw_claims_permission` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `user_id` int(11) default NULL,
  `contact_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `contact_line1` varchar(255) collate utf8_unicode_ci NOT NULL,
  `contact_line2` varchar(255) collate utf8_unicode_ci NOT NULL,
  `contact_city` varchar(255) collate utf8_unicode_ci NOT NULL,
  `contact_state` varchar(255) collate utf8_unicode_ci NOT NULL,
  `contact_country` varchar(255) collate utf8_unicode_ci NOT NULL,
  `contact_postalcode` varchar(255) collate utf8_unicode_ci NOT NULL,
  `contact_phone` varchar(255) collate utf8_unicode_ci NOT NULL,
  `contact_fax` varchar(255) collate utf8_unicode_ci NOT NULL,
  `contact_email` varchar(255) collate utf8_unicode_ci NOT NULL,
  `comments` text collate utf8_unicode_ci,
  `status` enum('new','in progress','done') collate utf8_unicode_ci NOT NULL default 'new',
  `info_sufficient` enum('yes','no','pending') collate utf8_unicode_ci NOT NULL default 'pending',
  `action` enum('None','Search','Fair Use','Re-Create','Retain: Instructor Created','Retain: Public Domain','Retain: No Copyright','Commission','Remove & Annotate') collate utf8_unicode_ci NOT NULL default 'None',
  `letter_sent` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  `response_received` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  `approved` enum('yes','no','pending') collate utf8_unicode_ci NOT NULL default 'pending',
  `created_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_claims_retain`
-- 

DROP TABLE IF EXISTS `ocw_claims_retain`;
CREATE TABLE `ocw_claims_retain` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `user_id` int(11) default NULL,
  `rationale` longtext collate utf8_unicode_ci NOT NULL,
  `comments` text collate utf8_unicode_ci NOT NULL,
  `accept_rationale` enum('yes','no','unsure','pending') collate utf8_unicode_ci NOT NULL default 'pending',
  `status` enum('new','in progress','ip review','done') collate utf8_unicode_ci NOT NULL default 'new',
  `action` enum('None','Permission','Search','Fair Use','Re-Create','Retain: Instructor Created','Retain: Public Domain','Commission','Remove & Annotate') collate utf8_unicode_ci NOT NULL default 'None',
  `approved` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  `created_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_copyright_contactinfo`
-- 

DROP TABLE IF EXISTS `ocw_copyright_contactinfo`;
CREATE TABLE `ocw_copyright_contactinfo` (
  `id` bigint(20) NOT NULL,
  `copyright_holder_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `copyright_holder_id` (`copyright_holder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_copyright_holders`
-- 

DROP TABLE IF EXISTS `ocw_copyright_holders`;
CREATE TABLE `ocw_copyright_holders` (
  `id` int(11) NOT NULL,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `friend` enum('1','0') collate utf8_unicode_ci NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_corecomp`
-- 

DROP TABLE IF EXISTS `ocw_corecomp`;
CREATE TABLE `ocw_corecomp` (
  `id` bigint(20) NOT NULL auto_increment,
  `corecomp` varchar(255) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_courses`
-- 

DROP TABLE IF EXISTS `ocw_courses`;
CREATE TABLE `ocw_courses` (
  `id` bigint(20) NOT NULL auto_increment,
  `number` int(10) unsigned default NULL,
  `title` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `curriculum_id` bigint(20) default NULL,
  `director` varchar(70) character set utf8 collate utf8_unicode_ci NOT NULL,
  `creator` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `instructor_id` int(10) unsigned default NULL,
  `collaborators` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `level` enum('Undergraduate','Masters','PhD','M1','M2','M3','M4') character set utf8 collate utf8_unicode_ci NOT NULL,
  `length` enum('1 week','2 weeks','3 weeks','4 weeks','5 weeks','6 weeks','7 weeks','8 weeks','9 weeks','10 weeks','11 weeks','12 weeks','13 weeks','14 weeks') character set utf8 collate utf8_unicode_ci NOT NULL,
  `term` enum('Fall','Winter','Spring','Summer') character set utf8 collate utf8_unicode_ci NOT NULL,
  `year` year(4) NOT NULL,
  `copyright_holder_id` int(11) default NULL,
  `language` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default 'English',
  `school_id` int(10) unsigned default NULL,
  `subject_id` int(10) unsigned default NULL,
  `curricular_info` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `lifecycle_version` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `imagefile` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `highlights` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `description` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `keywords` text character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `curriculum_id` (`curriculum_id`),
  KEY `instructor_id` (`instructor_id`),
  KEY `copyright_holder_id` (`copyright_holder_id`),
  KEY `school_id` (`school_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_course_files`
-- 

DROP TABLE IF EXISTS `ocw_course_files`;
CREATE TABLE `ocw_course_files` (
  `id` bigint(20) NOT NULL auto_increment,
  `course_id` bigint(20) NOT NULL,
  `filename` varchar(255) collate utf8_unicode_ci NOT NULL,
  `modified_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `filename` (`filename`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_curriculums`
-- 

DROP TABLE IF EXISTS `ocw_curriculums`;
CREATE TABLE `ocw_curriculums` (
  `id` bigint(20) NOT NULL auto_increment,
  `school_id` int(10) unsigned NOT NULL,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `school_id` (`school_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_dscribe2_dscribe1`
-- 

DROP TABLE IF EXISTS `ocw_dscribe2_dscribe1`;
CREATE TABLE `ocw_dscribe2_dscribe1` (
  `id` bigint(20) NOT NULL auto_increment,
  `dscribe2_id` int(11) NOT NULL,
  `dscribe1_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `dscribe2_id` (`dscribe2_id`),
  KEY `dscribe1_id` (`dscribe1_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_instructors`
-- 

DROP TABLE IF EXISTS `ocw_instructors`;
CREATE TABLE `ocw_instructors` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` text collate utf8_unicode_ci NOT NULL,
  `info` text collate utf8_unicode_ci NOT NULL,
  `uri` varchar(255) collate utf8_unicode_ci default NULL,
  `imagefile` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_materials`
-- 

DROP TABLE IF EXISTS `ocw_materials`;
CREATE TABLE `ocw_materials` (
  `id` bigint(20) NOT NULL auto_increment,
  `course_id` bigint(20) NOT NULL,
  `category` varchar(255) collate utf8_unicode_ci NOT NULL default 'Materials',
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `ctools_url` varchar(255) collate utf8_unicode_ci NOT NULL,
  `author` varchar(255) collate utf8_unicode_ci default NULL,
  `collaborators` text collate utf8_unicode_ci NOT NULL,
  `tag_id` bigint(20) NOT NULL default '0',
  `mimetype_id` tinyint(4) NOT NULL default '0',
  `in_ocw` enum('1','0') character set latin1 NOT NULL default '0',
  `embedded_co` enum('0','1') character set latin1 NOT NULL default '0',
  `nodetype` enum('child','parent') character set latin1 NOT NULL default 'child',
  `parent` bigint(20) NOT NULL default '0',
  `order` int(11) NOT NULL default '0',
  `modified` enum('1','0') character set latin1 NOT NULL default '0',
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `course_id` (`course_id`),
  KEY `mimetype_id` (`mimetype_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_materials_corecomp`
-- 

DROP TABLE IF EXISTS `ocw_materials_corecomp`;
CREATE TABLE `ocw_materials_corecomp` (
  `id` bigint(20) NOT NULL auto_increment,
  `material_id` bigint(20) NOT NULL,
  `corecomp_id` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `material_id` (`material_id`),
  KEY `corecomp_id` (`corecomp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_material_categories`
-- 

DROP TABLE IF EXISTS `ocw_material_categories`;
CREATE TABLE `ocw_material_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_material_comments`
-- 

DROP TABLE IF EXISTS `ocw_material_comments`;
CREATE TABLE `ocw_material_comments` (
  `id` bigint(20) NOT NULL auto_increment,
  `material_id` bigint(20) NOT NULL default '0',
  `user_id` int(11) NOT NULL,
  `comments` longtext collate utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `material_id` (`material_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_material_files`
-- 

DROP TABLE IF EXISTS `ocw_material_files`;
CREATE TABLE `ocw_material_files` (
  `id` bigint(20) NOT NULL auto_increment,
  `material_id` bigint(20) NOT NULL,
  `filename` varchar(255) collate utf8_unicode_ci NOT NULL,
  `user_id` int(11) default NULL,
  `cleared` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  `modified_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `filename` (`filename`),
  KEY `material_id` (`material_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_mimetypes`
-- 

DROP TABLE IF EXISTS `ocw_mimetypes`;
CREATE TABLE `ocw_mimetypes` (
  `id` tinyint(4) NOT NULL auto_increment,
  `name` varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL,
  `mimetype` varchar(70) character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_objects`
-- 

DROP TABLE IF EXISTS `ocw_objects`;
CREATE TABLE `ocw_objects` (
  `id` bigint(20) NOT NULL auto_increment,
  `material_id` bigint(20) NOT NULL default '0',
  `subtype_id` bigint(20) NOT NULL,
  `name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `location` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `description` longtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `author` varchar(255) character set utf8 collate utf8_unicode_ci default NULL,
  `contributor` varchar(255) character set utf8 collate utf8_unicode_ci default NULL,
  `instructor_owns` enum('yes','no','pending') character set utf8 collate utf8_unicode_ci NOT NULL default 'pending',
  `other_copyholder` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `is_unique` enum('yes','no','pending') character set utf8 collate utf8_unicode_ci NOT NULL default 'pending',
  `citation` longtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `tags` longtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `ask` enum('yes','no') character set utf8 collate utf8_unicode_ci NOT NULL,
  `ask_status` enum('new','in progress','done') character set utf8 collate utf8_unicode_ci NOT NULL default 'new',
  `ask_dscribe2` enum('yes','no') character set utf8 collate utf8_unicode_ci NOT NULL default 'no',
  `ask_dscribe2_status` enum('new','in progress','done') character set utf8 collate utf8_unicode_ci NOT NULL default 'new',
  `action_type` enum('Permission','Search','Fair Use','Re-Create','Retain: Instructor Created','Retain: Public Domain','Retain: No Copyright','Commission','Remove & Annotate') character set utf8 collate utf8_unicode_ci default NULL,
  `action_taken` enum('Permission','Search','Fair Use','Re-Create','Retain: Instructor Created','Retain: Public Domain','Retain: No Copyright','Commission','Remove & Annotate') character set utf8 collate utf8_unicode_ci default NULL,
  `status` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `done` enum('1','0') character set utf8 collate utf8_unicode_ci NOT NULL default '0',
  `time` bigint(20) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `material_id` (`material_id`),
  KEY `subtype_id` (`subtype_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_comments`
-- 

DROP TABLE IF EXISTS `ocw_object_comments`;
CREATE TABLE `ocw_object_comments` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL default '0',
  `user_id` int(11) NOT NULL,
  `comments` longtext collate utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_copyright`
-- 

DROP TABLE IF EXISTS `ocw_object_copyright`;
CREATE TABLE `ocw_object_copyright` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `status` enum('unknown','copyrighted','public domain') collate utf8_unicode_ci NOT NULL,
  `holder` varchar(255) collate utf8_unicode_ci NOT NULL,
  `notice` text collate utf8_unicode_ci NOT NULL,
  `url` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_files`
-- 

DROP TABLE IF EXISTS `ocw_object_files`;
CREATE TABLE `ocw_object_files` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `filename` varchar(255) collate utf8_unicode_ci NOT NULL,
  `modified_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `fname` (`object_id`,`filename`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_log`
-- 

DROP TABLE IF EXISTS `ocw_object_log`;
CREATE TABLE `ocw_object_log` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL default '0',
  `user_id` int(11) NOT NULL,
  `log` longtext collate utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_questions`
-- 

DROP TABLE IF EXISTS `ocw_object_questions`;
CREATE TABLE `ocw_object_questions` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `question` longtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `answer` longtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `status` enum('new','in progress','done') character set utf8 collate utf8_unicode_ci NOT NULL default 'new',
  `user_id` int(11) NOT NULL,
  `role` enum('instructor','dscribe2') character set utf8 collate utf8_unicode_ci default NULL,
  `category` enum('general','fair use','permission','commission','retain') character set utf8 collate utf8_unicode_ci NOT NULL default 'general',
  `created_on` datetime NOT NULL,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_replacements`
-- 

DROP TABLE IF EXISTS `ocw_object_replacements`;
CREATE TABLE `ocw_object_replacements` (
  `id` bigint(20) NOT NULL auto_increment,
  `material_id` bigint(20) NOT NULL,
  `object_id` bigint(20) NOT NULL default '0',
  `name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `location` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `description` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `author` varchar(255) character set utf8 collate utf8_unicode_ci default NULL,
  `contributor` varchar(255) character set utf8 collate utf8_unicode_ci default NULL,
  `citation` longtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `tags` longtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `ask` enum('yes','no') character set utf8 collate utf8_unicode_ci NOT NULL,
  `ask_status` enum('new','in progress','done') character set utf8 collate utf8_unicode_ci NOT NULL default 'new',
  `suitable` enum('yes','no','pending') character set utf8 collate utf8_unicode_ci NOT NULL default 'pending',
  `unsuitable_reason` longtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `material_id` (`material_id`),
  KEY `object_id` (`object_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_replacement_comments`
-- 

DROP TABLE IF EXISTS `ocw_object_replacement_comments`;
CREATE TABLE `ocw_object_replacement_comments` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL default '0',
  `user_id` int(11) NOT NULL,
  `comments` longtext collate utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_replacement_copyright`
-- 

DROP TABLE IF EXISTS `ocw_object_replacement_copyright`;
CREATE TABLE `ocw_object_replacement_copyright` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `status` enum('unknown','copyrighted','public domain') collate utf8_unicode_ci NOT NULL,
  `holder` varchar(255) collate utf8_unicode_ci NOT NULL,
  `notice` text collate utf8_unicode_ci NOT NULL,
  `url` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_replacement_log`
-- 

DROP TABLE IF EXISTS `ocw_object_replacement_log`;
CREATE TABLE `ocw_object_replacement_log` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL default '0',
  `user_id` int(11) NOT NULL,
  `log` longtext collate utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_replacement_questions`
-- 

DROP TABLE IF EXISTS `ocw_object_replacement_questions`;
CREATE TABLE `ocw_object_replacement_questions` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `question` longtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `answer` longtext character set utf8 collate utf8_unicode_ci NOT NULL,
  `status` enum('new','in progress','done') character set utf8 collate utf8_unicode_ci NOT NULL default 'new',
  `user_id` int(11) NOT NULL,
  `role` enum('instructor','dscribe2') character set utf8 collate utf8_unicode_ci default NULL,
  `category` enum('general','fair use','permission','commission','retain') character set utf8 collate utf8_unicode_ci NOT NULL default 'general',
  `created_on` datetime NOT NULL,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_subtypes`
-- 

DROP TABLE IF EXISTS `ocw_object_subtypes`;
CREATE TABLE `ocw_object_subtypes` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `type_id` int(11) NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_types`
-- 

DROP TABLE IF EXISTS `ocw_object_types`;
CREATE TABLE `ocw_object_types` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `description` text character set utf8 collate utf8_unicode_ci,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_schools`
-- 

DROP TABLE IF EXISTS `ocw_schools`;
CREATE TABLE `ocw_schools` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `description` text character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_subjects`
-- 

DROP TABLE IF EXISTS `ocw_subjects`;
CREATE TABLE `ocw_subjects` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `subj_code` varchar(15) collate utf8_unicode_ci NOT NULL,
  `subj_desc` varchar(255) collate utf8_unicode_ci NOT NULL,
  `school_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `school_id` (`school_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_tags`
-- 

DROP TABLE IF EXISTS `ocw_tags`;
CREATE TABLE `ocw_tags` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Description` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_users`
-- 

DROP TABLE IF EXISTS `ocw_users`;
CREATE TABLE `ocw_users` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  `user_name` varchar(45) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(120) NOT NULL,
  `role` varchar(50) NOT NULL default 'user',
  `banned` tinyint(1) NOT NULL default '0',
  `forgotten_password_code` varchar(50) default NULL,
  `last_visit` datetime default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- 
-- Constraints for dumped tables
-- 

-- 
-- Constraints for table `ocw_acl`
-- 
ALTER TABLE `ocw_acl`
  ADD CONSTRAINT `ocw_acl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`),
  ADD CONSTRAINT `ocw_acl_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `ocw_courses` (`id`);

-- 
-- Constraints for table `ocw_claims_commission`
-- 
ALTER TABLE `ocw_claims_commission`
  ADD CONSTRAINT `ocw_claims_commission_ibfk_4` FOREIGN KEY (`object_id`) REFERENCES `ocw_objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_claims_commission_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_claims_commission_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_claims_fairuse`
-- 
ALTER TABLE `ocw_claims_fairuse`
  ADD CONSTRAINT `ocw_claims_fairuse_ibfk_4` FOREIGN KEY (`object_id`) REFERENCES `ocw_objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_claims_fairuse_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_claims_fairuse_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_claims_permission`
-- 
ALTER TABLE `ocw_claims_permission`
  ADD CONSTRAINT `ocw_claims_permission_ibfk_4` FOREIGN KEY (`object_id`) REFERENCES `ocw_objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_claims_permission_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_claims_permission_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_claims_retain`
-- 
ALTER TABLE `ocw_claims_retain`
  ADD CONSTRAINT `ocw_claims_retain_ibfk_4` FOREIGN KEY (`object_id`) REFERENCES `ocw_objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_claims_retain_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_claims_retain_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_copyright_contactinfo`
-- 
ALTER TABLE `ocw_copyright_contactinfo`
  ADD CONSTRAINT `ocw_copyright_contactinfo_ibfk_1` FOREIGN KEY (`copyright_holder_id`) REFERENCES `ocw_copyright_holders` (`id`);

-- 
-- Constraints for table `ocw_courses`
-- 
ALTER TABLE `ocw_courses`
  ADD CONSTRAINT `ocw_courses_ibfk_1` FOREIGN KEY (`curriculum_id`) REFERENCES `ocw_curriculums` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_courses_ibfk_2` FOREIGN KEY (`instructor_id`) REFERENCES `ocw_instructors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_courses_ibfk_3` FOREIGN KEY (`school_id`) REFERENCES `ocw_schools` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_courses_ibfk_4` FOREIGN KEY (`subject_id`) REFERENCES `ocw_subjects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_courses_ibfk_5` FOREIGN KEY (`copyright_holder_id`) REFERENCES `ocw_copyright_holders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- 
-- Constraints for table `ocw_course_files`
-- 
ALTER TABLE `ocw_course_files`
  ADD CONSTRAINT `ocw_course_files_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `ocw_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `ocw_curriculums`
-- 
ALTER TABLE `ocw_curriculums`
  ADD CONSTRAINT `ocw_curriculums_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `ocw_schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `ocw_dscribe2_dscribe1`
-- 
ALTER TABLE `ocw_dscribe2_dscribe1`
  ADD CONSTRAINT `ocw_dscribe2_dscribe1_ibfk_1` FOREIGN KEY (`dscribe2_id`) REFERENCES `ocw_users` (`id`),
  ADD CONSTRAINT `ocw_dscribe2_dscribe1_ibfk_2` FOREIGN KEY (`dscribe1_id`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_materials`
-- 
ALTER TABLE `ocw_materials`
  ADD CONSTRAINT `ocw_materials_ibfk_16` FOREIGN KEY (`course_id`) REFERENCES `ocw_courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_materials_ibfk_18` FOREIGN KEY (`tag_id`) REFERENCES `ocw_tags` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_materials_ibfk_19` FOREIGN KEY (`mimetype_id`) REFERENCES `ocw_mimetypes` (`id`) ON UPDATE CASCADE;

-- 
-- Constraints for table `ocw_materials_corecomp`
-- 
ALTER TABLE `ocw_materials_corecomp`
  ADD CONSTRAINT `ocw_materials_corecomp_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `ocw_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_materials_corecomp_ibfk_2` FOREIGN KEY (`corecomp_id`) REFERENCES `ocw_corecomp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `ocw_material_comments`
-- 
ALTER TABLE `ocw_material_comments`
  ADD CONSTRAINT `ocw_material_comments_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `ocw_materials` (`id`),
  ADD CONSTRAINT `ocw_material_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_material_files`
-- 
ALTER TABLE `ocw_material_files`
  ADD CONSTRAINT `ocw_material_files_ibfk_3` FOREIGN KEY (`material_id`) REFERENCES `ocw_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_material_files_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_objects`
-- 
ALTER TABLE `ocw_objects`
  ADD CONSTRAINT `ocw_objects_ibfk_4` FOREIGN KEY (`material_id`) REFERENCES `ocw_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `ocw_object_comments`
-- 
ALTER TABLE `ocw_object_comments`
  ADD CONSTRAINT `ocw_object_comments_ibfk_1` FOREIGN KEY (`object_id`) REFERENCES `ocw_objects` (`id`),
  ADD CONSTRAINT `ocw_object_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_object_copyright`
-- 
ALTER TABLE `ocw_object_copyright`
  ADD CONSTRAINT `ocw_object_copyright_ibfk_1` FOREIGN KEY (`object_id`) REFERENCES `ocw_objects` (`id`);

-- 
-- Constraints for table `ocw_object_files`
-- 
ALTER TABLE `ocw_object_files`
  ADD CONSTRAINT `ocw_object_files_ibfk_1` FOREIGN KEY (`object_id`) REFERENCES `ocw_objects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 
-- Constraints for table `ocw_object_log`
-- 
ALTER TABLE `ocw_object_log`
  ADD CONSTRAINT `ocw_object_log_ibfk_1` FOREIGN KEY (`object_id`) REFERENCES `ocw_objects` (`id`),
  ADD CONSTRAINT `ocw_object_log_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_object_questions`
-- 
ALTER TABLE `ocw_object_questions`
  ADD CONSTRAINT `ocw_object_questions_ibfk_4` FOREIGN KEY (`object_id`) REFERENCES `ocw_objects` (`id`),
  ADD CONSTRAINT `ocw_object_questions_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`),
  ADD CONSTRAINT `ocw_object_questions_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `ocw_users` (`id`),
  ADD CONSTRAINT `ocw_object_questions_ibfk_7` FOREIGN KEY (`modified_by`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_object_replacements`
-- 
ALTER TABLE `ocw_object_replacements`
  ADD CONSTRAINT `ocw_object_replacements_ibfk_4` FOREIGN KEY (`material_id`) REFERENCES `ocw_materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ocw_object_replacements_ibfk_7` FOREIGN KEY (`object_id`) REFERENCES `ocw_objects` (`id`),
  ADD CONSTRAINT `ocw_object_replacements_ibfk_8` FOREIGN KEY (`modified_by`) REFERENCES `ocw_users` (`id`),
  ADD CONSTRAINT `ocw_object_replacements_ibfk_9` FOREIGN KEY (`modified_by`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_object_replacement_comments`
-- 
ALTER TABLE `ocw_object_replacement_comments`
  ADD CONSTRAINT `ocw_object_replacement_comments_ibfk_3` FOREIGN KEY (`object_id`) REFERENCES `ocw_object_replacements` (`id`),
  ADD CONSTRAINT `ocw_object_replacement_comments_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_object_replacement_copyright`
-- 
ALTER TABLE `ocw_object_replacement_copyright`
  ADD CONSTRAINT `ocw_object_replacement_copyright_ibfk_1` FOREIGN KEY (`object_id`) REFERENCES `ocw_object_replacements` (`id`);

-- 
-- Constraints for table `ocw_object_replacement_log`
-- 
ALTER TABLE `ocw_object_replacement_log`
  ADD CONSTRAINT `ocw_object_replacement_log_ibfk_1` FOREIGN KEY (`object_id`) REFERENCES `ocw_object_replacements` (`id`),
  ADD CONSTRAINT `ocw_object_replacement_log_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_object_replacement_questions`
-- 
ALTER TABLE `ocw_object_replacement_questions`
  ADD CONSTRAINT `ocw_object_replacement_questions_ibfk_4` FOREIGN KEY (`object_id`) REFERENCES `ocw_object_replacements` (`id`),
  ADD CONSTRAINT `ocw_object_replacement_questions_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`),
  ADD CONSTRAINT `ocw_object_replacement_questions_ibfk_6` FOREIGN KEY (`modified_by`) REFERENCES `ocw_users` (`id`),
  ADD CONSTRAINT `ocw_object_replacement_questions_ibfk_7` FOREIGN KEY (`object_id`) REFERENCES `ocw_object_replacements` (`id`),
  ADD CONSTRAINT `ocw_object_replacement_questions_ibfk_8` FOREIGN KEY (`modified_by`) REFERENCES `ocw_users` (`id`);

-- 
-- Constraints for table `ocw_object_subtypes`
-- 
ALTER TABLE `ocw_object_subtypes`
  ADD CONSTRAINT `ocw_object_subtypes_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `ocw_object_types` (`id`);

-- 
-- Constraints for table `ocw_subjects`
-- 
ALTER TABLE `ocw_subjects`
  ADD CONSTRAINT `ocw_subjects_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `ocw_schools` (`id`);
