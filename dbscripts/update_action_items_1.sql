/*  this script changes the database schema to reflect the
    changes made to the code in SVN r53972. The changes are
    related to the ENUM values assigned to action, action_type
    and action_taken columns. It also updates the relevant
    records so that they have the new values instead of the
    old, deleted ENUM values. */

/*  variables may have been used to reduce repeated code
    but chances of making a mistake are increased when
    trying to do the clever stuff.
    TODO: consider making this cleaner */

ALTER TABLE `ocw_claims_commission` MODIFY COLUMN `action` 
      ENUM ( 'None',
     'Permission',
     'Search',
     'Retain: Permission',
     'Retain: Public Domain',
     'Retain: No Copyright',
     'Retain: Copyright Analysis',
     'Re-Create',
     'Create',
     'Commission',
     'Fair Use',
     'Remove and Annotate' ) 
     NOT NULL default 'NONE' collate utf8_unicode_ci;

UPDATE `ocw_claims_commission` SET 
       `action`='Retain: Copyright Analysis' WHERE 
       `action`='Retain: No Copyright';

UPDATE `ocw_claims_commission` SET
       `action`='Create' WHERE
       `action`='Re-Create';

ALTER TABLE `ocw_claims_commission` MODIFY COLUMN `action` 
      ENUM ( 'None',
     'Permission',
     'Search',
     'Retain: Permission',
     'Retain: Public Domain',
     'Retain: Copyright Analysis',
     'Create',
     'Commission',
     'Fair Use',
     'Remove and Annotate' ) 
     NOT NULL default 'NONE' collate utf8_unicode_ci;


ALTER TABLE `ocw_claims_fairuse` MODIFY COLUMN `action` 
      ENUM ( 'None',
     'Permission',
     'Search',
     'Retain: Permission',
     'Retain: Public Domain',
     'Retain: No Copyright',
     'Retain: Copyright Analysis',
     'Re-Create',
     'Create',
     'Commission',
     'Fair Use',
     'Remove and Annotate' ) 
     NOT NULL default 'NONE' collate utf8_unicode_ci;

UPDATE `ocw_claims_fairuse` SET 
       `action`='Retain: Copyright Analysis' WHERE 
       `action`='Retain: No Copyright';

UPDATE `ocw_claims_fairuse` SET 
       `action`='Create' WHERE 
       `action`='Re-Create';

ALTER TABLE `ocw_claims_fairuse` MODIFY COLUMN `action` 
      ENUM ( 'None',
     'Permission',
     'Search',
     'Retain: Permission',
     'Retain: Public Domain',
     'Retain: Copyright Analysis',
     'Create',
     'Commission',
     'Fair Use',
     'Remove and Annotate' ) 
     NOT NULL default 'NONE' collate utf8_unicode_ci;


ALTER TABLE `ocw_claims_permission` MODIFY COLUMN `action` 
      ENUM ( 'None',
     'Permission',
     'Search',
     'Retain: Permission',
     'Retain: Public Domain',
     'Retain: No Copyright',
     'Retain: Copyright Analysis',
     'Re-Create',
     'Create',
     'Commission',
     'Fair Use',
     'Remove and Annotate' ) 
     NOT NULL default 'NONE' collate utf8_unicode_ci;

UPDATE `ocw_claims_permission` SET 
       `action`='Retain: Copyright Analysis' WHERE 
       `action`='Retain: No Copyright';

UPDATE `ocw_claims_permission` SET 
       `action`='Create' WHERE 
       `action`='Re-Create';

ALTER TABLE `ocw_claims_permission` MODIFY COLUMN `action` 
      ENUM ( 'None',
     'Permission',
     'Search',
     'Retain: Permission',
     'Retain: Public Domain',
     'Retain: Copyright Analysis',
     'Create',
     'Commission',
     'Fair Use',
     'Remove and Annotate' ) 
     NOT NULL default 'NONE' collate utf8_unicode_ci;


ALTER TABLE `ocw_claims_retain` MODIFY COLUMN `action` 
      ENUM ( 'None',
     'Permission',
     'Search',
     'Retain: Permission',
     'Retain: Public Domain',
     'Retain: No Copyright',
     'Retain: Copyright Analysis',
     'Re-Create',
     'Create',
     'Commission',
     'Fair Use',
     'Remove and Annotate' ) 
     NOT NULL default 'NONE' collate utf8_unicode_ci;

UPDATE `ocw_claims_retain` SET 
       `action`='Retain: Copyright Analysis' WHERE 
       `action`='Retain: No Copyright';

UPDATE `ocw_claims_retain` SET 
       `action`='Create' WHERE 
       `action`='Re-Create';

ALTER TABLE `ocw_claims_retain` MODIFY COLUMN `action` 
      ENUM ( 'None',
     'Permission',
     'Search',
     'Retain: Permission',
     'Retain: Public Domain',
     'Retain: Copyright Analysis',
     'Create',
     'Commission',
     'Fair Use',
     'Remove and Annotate' ) 
     NOT NULL default 'NONE' collate utf8_unicode_ci;


ALTER TABLE `ocw_objects` MODIFY COLUMN `action_type` 
      ENUM ( 'None',
     'Permission',
     'Search',
     'Retain: Permission',
     'Retain: Public Domain',
     'Retain: No Copyright',
     'Retain: Copyright Analysis',
     'Re-Create',
     'Create',
     'Commission',
     'Fair Use',
     'Remove and Annotate' ) 
     NOT NULL default 'NONE' collate utf8_unicode_ci;

UPDATE `ocw_objects` SET 
       `action_type`='Retain: Copyright Analysis' WHERE 
       `action_type`='Retain: No Copyright';

UPDATE `ocw_objects` SET 
       `action_type`='Create' WHERE 
       `action_type`='Re-Create';

ALTER TABLE `ocw_objects` MODIFY COLUMN `action_type` 
      ENUM ( 'None',
     'Permission',
     'Search',
     'Retain: Permission',
     'Retain: Public Domain',
     'Retain: Copyright Analysis',
     'Create',
     'Commission',
     'Fair Use',
     'Remove and Annotate' ) 
     NOT NULL default 'NONE' collate utf8_unicode_ci;


ALTER TABLE `ocw_objects` MODIFY COLUMN `action_taken` 
      ENUM ( 'None',
     'Permission',
     'Search',
     'Retain: Permission',
     'Retain: Public Domain',
     'Retain: No Copyright',
     'Retain: Copyright Analysis',
     'Re-Create',
     'Create',
     'Commission',
     'Fair Use',
     'Remove and Annotate' ) 
     NOT NULL default 'NONE' collate utf8_unicode_ci;

UPDATE `ocw_objects` SET 
       `action_taken`='Retain: Copyright Analysis' WHERE 
       `action_taken`='Retain: No Copyright';

UPDATE `ocw_objects` SET 
       `action_taken`='Create' WHERE 
       `action_taken`='Re-Create';

ALTER TABLE `ocw_objects` MODIFY COLUMN `action_taken` 
      ENUM ( 'None',
     'Permission',
     'Search',
     'Retain: Permission',
     'Retain: Public Domain',
     'Retain: Copyright Analysis',
     'Create',
     'Commission',
     'Fair Use',
     'Remove and Annotate' ) 
     NOT NULL default 'NONE' collate utf8_unicode_ci;

