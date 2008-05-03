ALTER TABLE `ocw_object_questions` ADD `status` ENUM( 'new', 'in progress', 'done' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new' AFTER `answer` ;

ALTER TABLE `ocw_object_replacement_questions` ADD `status` ENUM( 'new', 'in progress', 'done' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new' AFTER `answer` ;

ALTER TABLE `ocw_claims_commission` ADD `status` ENUM( 'new', 'in progress', 'done' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new' AFTER `comments` ;

 ALTER TABLE `ocw_claims_fairuse` ADD `status` ENUM( 'new', 'in progress', 'ip review', 'done' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new'  AFTER `comments`;

ALTER TABLE `ocw_claims_permission` ADD `status` ENUM( 'new', 'in progress', 'done' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new' AFTER `contact_email` ;

ALTER TABLE `ocw_claims_retain` ADD `status` ENUM( 'new', 'in progress', 'done' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new' AFTER `comments` ;


ALTER TABLE `ocw_claims_fairuse` ADD `warrant_review` ENUM( 'yes', 'no', 'pending' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending' AFTER `comments` ;

ALTER TABLE `ocw_claims_fairuse` ADD `additional_rationale` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `rationale` ;

ALTER TABLE `ocw_claims_fairuse` ADD `action` ENUM( 'None', 'Get permission', 'Commission', 'Claim no copyright' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'None' AFTER `warrant_review`;

 ALTER TABLE `ocw_claims_permission` CHANGE `info_sufficient` `info_sufficient` ENUM( 'yes', 'no', 'pending' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending';

ALTER TABLE `ocw_claims_permission` ADD `comments` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `contact_email` ;  

ALTER TABLE `ocw_claims_permission` ADD `action` ENUM( 'None', 'Claim fair use', 'Commission', 'Claim no copyright' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'None' AFTER `info_sufficient`;

 ALTER TABLE `ocw_claims_retain` CHANGE `escalate` `action` ENUM( 'None', 'Claim fair use', 'Get permission', 'Commission' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'None';

ALTER TABLE `ocw_claims_retain` ADD `accept_rationale` ENUM( 'yes', 'no', 'unsure', 'pending' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending' AFTER `comments` ;

ALTER TABLE `ocw_claims_retain` CHANGE `status` `status` ENUM( 'new', 'in progress', 'ip review', 'done' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new';

ALTER TABLE `ocw_claims_commission` ADD `have_replacement` ENUM( 'yes', 'no', 'pending' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending' AFTER `comments` ,
ADD `recommend_commission` ENUM( 'yes', 'no' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no' AFTER `have_replacement` ;

ALTER TABLE `ocw_claims_commission` CHANGE `action_taken` `action` ENUM( 'None', 'Find it', 'Recreate it', 'Remove it', 'Claim fair use', 'Get Permission', 'Claim no copyright' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'None' ;
