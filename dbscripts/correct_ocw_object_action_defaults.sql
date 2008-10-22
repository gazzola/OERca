/*  This script changes the database schema to correct a bug 
    introduced by the update_action_items_1.sql script. The
    update_action_items_1.sql script incorrectly set the 
    default value of the action on newly added objects to 
    None where they should have been NULL.
*/

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
     default NULL collate utf8_unicode_ci;

UPDATE `ocw_objects` SET 
       `action_type`= NULL WHERE 
       `action_type`='';

UPDATE `ocw_objects` SET 
       `action_type`= NULL WHERE 
       `action_type`='None';

ALTER TABLE `ocw_objects` MODIFY COLUMN `action_type` 
      ENUM (
     'Permission',
     'Search',
     'Retain: Permission',
     'Retain: Public Domain',
     'Retain: Copyright Analysis',
     'Create',
     'Commission',
     'Fair Use',
     'Remove and Annotate' ) 
     default NULL collate utf8_unicode_ci;
     
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
    default NULL collate utf8_unicode_ci;

UPDATE `ocw_objects` SET 
      `action_taken`= NULL WHERE 
      `action_taken`='';

UPDATE `ocw_objects` SET 
      `action_taken`= NULL WHERE 
      `action_taken`='None';

ALTER TABLE `ocw_objects` MODIFY COLUMN `action_taken` 
     ENUM (
    'Permission',
    'Search',
    'Retain: Permission',
    'Retain: Public Domain',
    'Retain: Copyright Analysis',
    'Create',
    'Commission',
    'Fair Use',
    'Remove and Annotate' ) 
    default NULL collate utf8_unicode_ci;