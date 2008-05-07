ALTER TABLE `ocw_claims_commission` ADD `modified_by` INT( 11 ) NULL AFTER `created_on` ;
ALTER TABLE `ocw_claims_commission` ADD INDEX ( `modified_by` ) ;
ALTER TABLE `ocw_claims_commission` ADD FOREIGN KEY ( `modified_by` ) REFERENCES `ocw`.`ocw_users` (`id`);

ALTER TABLE `ocw_claims_permission` ADD `modified_by` INT( 11 ) NULL AFTER `created_on` ;
ALTER TABLE `ocw_claims_permission` ADD INDEX ( `modified_by` ) ;
ALTER TABLE `ocw_claims_permission` ADD FOREIGN KEY ( `modified_by` ) REFERENCES `ocw`.`ocw_users` (`id`);

ALTER TABLE `ocw_claims_fairuse` ADD `modified_by` INT( 11 ) NULL AFTER `created_on` ;
ALTER TABLE `ocw_claims_fairuse` ADD INDEX ( `modified_by` ) ;
ALTER TABLE `ocw_claims_fairuse` ADD FOREIGN KEY ( `modified_by` ) REFERENCES `ocw`.`ocw_users` (`id`);

ALTER TABLE `ocw_claims_retain` ADD `modified_by` INT( 11 ) NULL AFTER `created_on` ;
ALTER TABLE `ocw_claims_retain` ADD INDEX ( `modified_by` ) ;
ALTER TABLE `ocw_claims_retain` ADD FOREIGN KEY ( `modified_by` ) REFERENCES `ocw`.`ocw_users` (`id`);

ALTER TABLE `ocw_object_questions` ADD `modified_by` INT( 11 ) NULL AFTER `created_on` ;
ALTER TABLE `ocw_object_questions` ADD INDEX ( `modified_by` ) ;
ALTER TABLE `ocw_object_questions` ADD FOREIGN KEY ( `modified_by` ) REFERENCES `ocw`.`ocw_users` (`id`);

ALTER TABLE `ocw_object_replacement_questions` ADD `modified_by` INT( 11 ) NULL AFTER `created_on` ;
ALTER TABLE `ocw_object_replacement_questions` ADD INDEX ( `modified_by` ) ;
ALTER TABLE `ocw_object_replacement_questions` ADD FOREIGN KEY ( `modified_by` ) REFERENCES `ocw`.`ocw_users` (`id`);
