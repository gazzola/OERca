-- phpMyAdmin SQL Dump
-- version 2.10.3deb1ubuntu0.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Nov 21, 2008 at 12:47 PM
-- Server version: 5.0.45
-- PHP Version: 5.2.3-1ubuntu6.3

SET FOREIGN_KEY_CHECKS=0;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `oertest`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_acl`
-- 

CREATE TABLE IF NOT EXISTS `ocw_acl` (
  `user_id` int(11) NOT NULL default '0',
  `course_id` bigint(20) NOT NULL default '0',
  `role` enum('instructor','dscribe1','dscribe2') NOT NULL default 'dscribe1',
  PRIMARY KEY  (`user_id`,`course_id`,`role`),
  KEY `user_id` (`user_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_ci_sessions`
-- 

CREATE TABLE IF NOT EXISTS `ocw_ci_sessions` (
  `session_id` varchar(40) NOT NULL default '0',
  `ip_address` varchar(16) NOT NULL default '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL default '0',
  `session_data` text,
  PRIMARY KEY  (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_claims_commission`
-- 

CREATE TABLE IF NOT EXISTS `ocw_claims_commission` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `user_id` int(11) default NULL,
  `rationale` longtext NOT NULL,
  `comments` text NOT NULL,
  `have_replacement` enum('yes','no','pending') NOT NULL default 'pending',
  `recommend_commission` enum('yes','no') NOT NULL default 'no',
  `status` enum('new','request sent','in progress','done') NOT NULL default 'new',
  `action` enum('None','Permission','Search','Retain: Permission','Retain: Public Domain','Retain: Copyright Analysis','Create','Commission','Fair Use','Remove and Annotate') character set utf8 collate utf8_unicode_ci NOT NULL default 'None',
  `created_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_claims_fairuse`
-- 

CREATE TABLE IF NOT EXISTS `ocw_claims_fairuse` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `user_id` int(11) default NULL,
  `rationale` longtext NOT NULL,
  `additional_rationale` text,
  `comments` text NOT NULL,
  `warrant_review` enum('yes','no','pending') NOT NULL default 'pending',
  `action` enum('None','Permission','Search','Retain: Permission','Retain: Public Domain','Retain: Copyright Analysis','Create','Commission','Fair Use','Remove and Annotate') character set utf8 collate utf8_unicode_ci NOT NULL default 'None',
  `status` enum('new','request sent','in progress','ip review','done') NOT NULL default 'new',
  `approved` enum('yes','no','pending') NOT NULL default 'pending',
  `created_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_claims_permission`
-- 

CREATE TABLE IF NOT EXISTS `ocw_claims_permission` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `user_id` int(11) default NULL,
  `contact_name` varchar(255) NOT NULL,
  `contact_line1` varchar(255) NOT NULL,
  `contact_line2` varchar(255) NOT NULL,
  `contact_city` varchar(255) NOT NULL,
  `contact_state` varchar(255) NOT NULL,
  `contact_country` varchar(255) NOT NULL,
  `contact_postalcode` varchar(255) NOT NULL,
  `contact_phone` varchar(255) NOT NULL,
  `contact_fax` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `comments` text,
  `status` enum('new','request sent','in progress','done') NOT NULL default 'new',
  `info_sufficient` enum('yes','no','pending') NOT NULL default 'pending',
  `action` enum('None','Permission','Search','Retain: Permission','Retain: Public Domain','Retain: Copyright Analysis','Create','Commission','Fair Use','Remove and Annotate') character set utf8 collate utf8_unicode_ci NOT NULL default 'None',
  `letter_sent` enum('yes','no') NOT NULL default 'no',
  `response_received` enum('yes','no') NOT NULL default 'no',
  `approved` enum('yes','no','pending') NOT NULL default 'pending',
  `created_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_claims_retain`
-- 

CREATE TABLE IF NOT EXISTS `ocw_claims_retain` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `user_id` int(11) default NULL,
  `rationale` longtext NOT NULL,
  `comments` text NOT NULL,
  `accept_rationale` enum('yes','no','unsure','pending') NOT NULL default 'pending',
  `status` enum('new','request sent','in progress','ip review','done') NOT NULL default 'new',
  `action` enum('None','Permission','Search','Retain: Permission','Retain: Public Domain','Retain: Copyright Analysis','Create','Commission','Fair Use','Remove and Annotate') character set utf8 collate utf8_unicode_ci NOT NULL default 'None',
  `approved` enum('yes','no') NOT NULL default 'no',
  `created_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_copyright_contactinfo`
-- 

CREATE TABLE IF NOT EXISTS `ocw_copyright_contactinfo` (
  `id` bigint(20) NOT NULL,
  `copyright_holder_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `copyright_holder_id` (`copyright_holder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_copyright_holders`
-- 

CREATE TABLE IF NOT EXISTS `ocw_copyright_holders` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `friend` enum('1','0') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_corecomp`
-- 

CREATE TABLE IF NOT EXISTS `ocw_corecomp` (
  `id` bigint(20) NOT NULL auto_increment,
  `corecomp` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_courses`
-- 

CREATE TABLE IF NOT EXISTS `ocw_courses` (
  `id` bigint(20) NOT NULL auto_increment,
  `number` int(10) unsigned default NULL,
  `title` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `curriculum_id` bigint(20) default NULL,
  `director` varchar(70) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `instructor_id` int(10) unsigned default NULL,
  `collaborators` text NOT NULL,
  `level` enum('Undergraduate','Masters','PhD','M1','M2','M3','M4') NOT NULL,
  `length` enum('1 week','2 weeks','3 weeks','4 weeks','5 weeks','6 weeks','7 weeks','8 weeks','9 weeks','10 weeks','11 weeks','12 weeks','13 weeks','14 weeks') NOT NULL,
  `term` enum('Fall','Winter','Spring','Summer') NOT NULL,
  `year` year(4) NOT NULL,
  `copyright_holder_id` int(11) default NULL,
  `language` varchar(255) NOT NULL default 'English',
  `school_id` int(10) unsigned default NULL,
  `subject_id` int(10) unsigned default NULL,
  `curricular_info` text NOT NULL,
  `lifecycle_version` varchar(255) NOT NULL,
  `imagefile` varchar(255) NOT NULL,
  `highlights` text NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `curriculum_id` (`curriculum_id`),
  KEY `instructor_id` (`instructor_id`),
  KEY `copyright_holder_id` (`copyright_holder_id`),
  KEY `school_id` (`school_id`),
  KEY `subject_id` (`subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_course_files`
-- 

CREATE TABLE IF NOT EXISTS `ocw_course_files` (
  `id` bigint(20) NOT NULL auto_increment,
  `course_id` bigint(20) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `modified_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `filename` (`filename`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_curriculums`
-- 

CREATE TABLE IF NOT EXISTS `ocw_curriculums` (
  `id` bigint(20) NOT NULL auto_increment,
  `school_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `school_id` (`school_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_dscribe2_dscribe1`
-- 

CREATE TABLE IF NOT EXISTS `ocw_dscribe2_dscribe1` (
  `id` bigint(20) NOT NULL auto_increment,
  `dscribe2_id` int(11) NOT NULL,
  `dscribe1_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `dscribe2_id` (`dscribe2_id`),
  KEY `dscribe1_id` (`dscribe1_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_instructors`
-- 

CREATE TABLE IF NOT EXISTS `ocw_instructors` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `info` text NOT NULL,
  `uri` varchar(255) default NULL,
  `imagefile` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_materials`
-- 

CREATE TABLE IF NOT EXISTS `ocw_materials` (
  `id` bigint(20) NOT NULL auto_increment,
  `course_id` bigint(20) NOT NULL,
  `category` varchar(255) NOT NULL default 'Materials',
  `name` varchar(255) NOT NULL,
  `ctools_url` varchar(255) NOT NULL,
  `author` varchar(255) default NULL,
  `collaborators` text NOT NULL,
  `tag_id` bigint(20) NOT NULL default '0',
  `mimetype_id` tinyint(4) NOT NULL default '0',
  `in_ocw` enum('1','0') NOT NULL default '0',
  `embedded_co` enum('0','1') NOT NULL default '0',
  `nodetype` enum('child','parent') NOT NULL default 'child',
  `parent` bigint(20) NOT NULL default '0',
  `order` int(11) NOT NULL default '0',
  `modified` enum('1','0') NOT NULL default '0',
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `course_id` (`course_id`),
  KEY `mimetype_id` (`mimetype_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_materials_corecomp`
-- 

CREATE TABLE IF NOT EXISTS `ocw_materials_corecomp` (
  `id` bigint(20) NOT NULL auto_increment,
  `material_id` bigint(20) NOT NULL,
  `corecomp_id` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `material_id` (`material_id`),
  KEY `corecomp_id` (`corecomp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_material_categories`
-- 

CREATE TABLE IF NOT EXISTS `ocw_material_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_material_comments`
-- 

CREATE TABLE IF NOT EXISTS `ocw_material_comments` (
  `id` bigint(20) NOT NULL auto_increment,
  `material_id` bigint(20) NOT NULL default '0',
  `user_id` int(11) NOT NULL,
  `comments` longtext NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `material_id` (`material_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_material_files`
-- 

CREATE TABLE IF NOT EXISTS `ocw_material_files` (
  `id` bigint(20) NOT NULL auto_increment,
  `material_id` bigint(20) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `user_id` int(11) default NULL,
  `cleared` enum('yes','no') NOT NULL default 'no',
  `modified_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `filename` (`filename`),
  KEY `material_id` (`material_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

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
-- Table structure for table `ocw_objects`
-- 

CREATE TABLE IF NOT EXISTS `ocw_objects` (
  `id` bigint(20) NOT NULL auto_increment,
  `material_id` bigint(20) NOT NULL default '0',
  `subtype_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `author` varchar(255) default NULL,
  `contributor` varchar(255) default NULL,
  `instructor_owns` enum('yes','no','pending') NOT NULL default 'pending',
  `other_copyholder` varchar(255) NOT NULL,
  `is_unique` enum('yes','no','pending') NOT NULL default 'pending',
  `citation` longtext NOT NULL,
  `tags` longtext NOT NULL,
  `ask` enum('yes','no') NOT NULL,
  `ask_status` enum('new','in progress','done') NOT NULL default 'new',
  `ask_dscribe2` enum('yes','no') NOT NULL default 'no',
  `ask_dscribe2_status` enum('new','in progress','done') NOT NULL default 'new',
  `action_type` enum('Permission','Search','Retain: Permission','Retain: Public Domain','Retain: Copyright Analysis','Create','Commission','Fair Use','Remove and Annotate') character set utf8 collate utf8_unicode_ci default NULL,
  `action_taken` enum('Permission','Search','Retain: Permission','Retain: Public Domain','Retain: Copyright Analysis','Create','Commission','Fair Use','Remove and Annotate') character set utf8 collate utf8_unicode_ci default NULL,
  `status` varchar(255) NOT NULL,
  `done` enum('1','0') NOT NULL default '0',
  `time` bigint(20) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `material_id` (`material_id`),
  KEY `subtype_id` (`subtype_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_comments`
-- 

CREATE TABLE IF NOT EXISTS `ocw_object_comments` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL default '0',
  `user_id` int(11) NOT NULL,
  `comments` longtext NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_copyright`
-- 

CREATE TABLE IF NOT EXISTS `ocw_object_copyright` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `status` enum('unknown','copyrighted','public domain') NOT NULL,
  `holder` varchar(255) NOT NULL,
  `notice` text NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_files`
-- 

CREATE TABLE IF NOT EXISTS `ocw_object_files` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `modified_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `fname` (`object_id`,`filename`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_log`
-- 

CREATE TABLE IF NOT EXISTS `ocw_object_log` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL default '0',
  `user_id` int(11) NOT NULL,
  `log` longtext NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_questions`
-- 

CREATE TABLE IF NOT EXISTS `ocw_object_questions` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `question` longtext NOT NULL,
  `answer` longtext NOT NULL,
  `status` enum('new','in progress','done') NOT NULL default 'new',
  `user_id` int(11) NOT NULL,
  `role` enum('instructor','dscribe2') default NULL,
  `category` enum('general','fair use','permission','commission','retain') NOT NULL default 'general',
  `created_on` datetime NOT NULL,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_replacements`
-- 

CREATE TABLE IF NOT EXISTS `ocw_object_replacements` (
  `id` bigint(20) NOT NULL auto_increment,
  `material_id` bigint(20) NOT NULL,
  `object_id` bigint(20) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `author` varchar(255) default NULL,
  `contributor` varchar(255) default NULL,
  `citation` longtext NOT NULL,
  `tags` longtext NOT NULL,
  `ask` enum('yes','no') NOT NULL,
  `ask_status` enum('new','in progress','done') NOT NULL default 'new',
  `suitable` enum('yes','no','pending') NOT NULL default 'pending',
  `unsuitable_reason` longtext NOT NULL,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `material_id` (`material_id`),
  KEY `object_id` (`object_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_replacement_comments`
-- 

CREATE TABLE IF NOT EXISTS `ocw_object_replacement_comments` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL default '0',
  `user_id` int(11) NOT NULL,
  `comments` longtext NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_replacement_copyright`
-- 

CREATE TABLE IF NOT EXISTS `ocw_object_replacement_copyright` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `status` enum('unknown','copyrighted','public domain') NOT NULL,
  `holder` varchar(255) NOT NULL,
  `notice` text NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_replacement_log`
-- 

CREATE TABLE IF NOT EXISTS `ocw_object_replacement_log` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL default '0',
  `user_id` int(11) NOT NULL,
  `log` longtext NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_object_replacement_questions`
-- 

CREATE TABLE IF NOT EXISTS `ocw_object_replacement_questions` (
  `id` bigint(20) NOT NULL auto_increment,
  `object_id` bigint(20) NOT NULL,
  `question` longtext NOT NULL,
  `answer` longtext NOT NULL,
  `status` enum('new','in progress','done') NOT NULL default 'new',
  `user_id` int(11) NOT NULL,
  `role` enum('instructor','dscribe2') default NULL,
  `category` enum('general','fair use','permission','commission','retain') NOT NULL default 'general',
  `created_on` datetime NOT NULL,
  `modified_by` int(11) default NULL,
  `modified_on` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `object_id` (`object_id`),
  KEY `user_id` (`user_id`),
  KEY `modified_by` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

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
-- Table structure for table `ocw_postoffice`
-- 

CREATE TABLE IF NOT EXISTS `ocw_postoffice` (
  `id` bigint(20) NOT NULL auto_increment,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `msg_type` enum('dscribe1_to_dscribe2','dscribe1_to_instructor','dscribe2_to_dscribe1','instructor_to_dscribe1') NOT NULL,
  `sent` enum('yes','no') NOT NULL default 'no',
  `course_id` bigint(20) NOT NULL,
  `material_id` bigint(20) NOT NULL,
  `object_id` bigint(20) NOT NULL,
  `object_type` enum('original','replacement') NOT NULL,
  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `modified_on` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `from_id` (`from_id`),
  KEY `to_id` (`to_id`),
  KEY `sent` (`sent`),
  KEY `course_id` (`course_id`),
  KEY `material_id` (`material_id`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_schools`
-- 

CREATE TABLE IF NOT EXISTS `ocw_schools` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_subjects`
-- 

CREATE TABLE IF NOT EXISTS `ocw_subjects` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `subj_code` varchar(15) NOT NULL,
  `subj_desc` varchar(255) NOT NULL,
  `school_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `school_id` (`school_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_tags`
-- 

CREATE TABLE IF NOT EXISTS `ocw_tags` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_users`
-- 

CREATE TABLE IF NOT EXISTS `ocw_users` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `ocw_user_profiles`
-- 

CREATE TABLE IF NOT EXISTS `ocw_user_profiles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `info` text NOT NULL,
  `uri` varchar(255) default NULL,
  `imagefile` blob NOT NULL,
  `imagetype` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

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
  ADD CONSTRAINT `ocw_object_replacement_questions_ibfk_8` FOREIGN KEY (`modified_by`) REFERENCES `ocw_users` (`id`),
  ADD CONSTRAINT `ocw_object_replacement_questions_ibfk_9` FOREIGN KEY (`object_id`) REFERENCES `ocw_object_replacements` (`id`);

-- 
-- Constraints for table `ocw_object_subtypes`
-- 
ALTER TABLE `ocw_object_subtypes`
  ADD CONSTRAINT `ocw_object_subtypes_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `ocw_object_types` (`id`);

-- 
-- Constraints for table `ocw_postoffice`
-- 
ALTER TABLE `ocw_postoffice`
  ADD CONSTRAINT `ocw_postoffice_ibfk_1` FOREIGN KEY (`from_id`) REFERENCES `ocw_users` (`id`),
  ADD CONSTRAINT `ocw_postoffice_ibfk_2` FOREIGN KEY (`to_id`) REFERENCES `ocw_users` (`id`),
  ADD CONSTRAINT `ocw_postoffice_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `ocw_courses` (`id`),
  ADD CONSTRAINT `ocw_postoffice_ibfk_4` FOREIGN KEY (`material_id`) REFERENCES `ocw_materials` (`id`),
  ADD CONSTRAINT `ocw_postoffice_ibfk_5` FOREIGN KEY (`object_id`) REFERENCES `ocw_objects` (`id`);

-- 
-- Constraints for table `ocw_subjects`
-- 
ALTER TABLE `ocw_subjects`
  ADD CONSTRAINT `ocw_subjects_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `ocw_schools` (`id`);

-- 
-- Constraints for table `ocw_user_profiles`
-- 
ALTER TABLE `ocw_user_profiles`
  ADD CONSTRAINT `ocw_user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `ocw_users` (`id`);

SET FOREIGN_KEY_CHECKS=1;
