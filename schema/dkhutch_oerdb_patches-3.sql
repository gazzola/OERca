ALTER TABLE `ocw_claims_commission` CHANGE `action` `action` ENUM( 'None', 'Permission', 'Search', 'Fair Use', 'Re-Create', 'Retain: Instructor Created', 'Retain: Public Domain', 'Retain: No Copyright', 'Remove & Annotate' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'None'; 

 ALTER TABLE `ocw_claims_fairuse` CHANGE `action` `action` ENUM( 'None', 'Permission', 'Search', 'Re-Create', 'Retain: Instructor Created', 'Retain: Public Domain', 'Retain: No Copyright', 'Commission', 'Remove & Annotate' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'None'; 

 ALTER TABLE `ocw_claims_permission` CHANGE `action` `action` ENUM( 'None', 'Search', 'Fair Use', 'Re-Create', 'Retain: Instructor Created', 'Retain: Public Domain', 'Retain: No Copyright', 'Commission', 'Remove & Annotate' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'None'; 

 ALTER TABLE `ocw_claims_retain` CHANGE `action` `action` ENUM( 'None', 'Permission', 'Search', 'Fair Use', 'Re-Create', 'Retain: Instructor Created', 'Retain: Public Domain', 'Commission', 'Remove & Annotate' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'None'; 

