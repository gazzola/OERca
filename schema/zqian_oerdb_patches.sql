ALTER TABLE `ocw_objects` CHANGE `action_type` `action_type` ENUM( 'Permission','Search','Fair Use', 'Re-Create','Retain: Instructor Created', 'Retain: Public Domain', 'Retain: No Copyright', 'Commission', 'Remove & Annotate' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Search'; 


ALTER TABLE `ocw_object_replacement_questions`DROP FOREIGN KEY ocw_object_replacement_questions_ibfk_1;

ALTER TABLE `ocw_object_replacement_questions`ADD CONSTRAINT `ocw_object_replacement_questions_ibfk_1` FOREIGN KEY (`object_id`) REFERENCES `ocw_object_replacements` (`object_id`);
