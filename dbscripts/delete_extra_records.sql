/* 
 * WARNING!
 * This set of queries will delete most of the contents of the DB
 * other than stuff related to the course IDs specified in the
 * "WHERE (ocw_courses.id <> 21 AND ocw_courses.id <> 22)" line
 * below. You can feel free to change those IDs and/or add more 
 * course IDs in that line. As usual, run at your own risk and all
 * that good stuff.
 *
 * Also, the version of MySQL you are using MUST support subselects or
 * else these queries simply won't work.
 */

START TRANSACTION;

SET foreign_key_checks = 0;

DELETE ocw_courses 
FROM ocw_courses 
WHERE (ocw_courses.id <> 21 AND ocw_courses.id <> 22);

DELETE ocw_course_files
FROM ocw_course_files
WHERE NOT EXISTS(
    SELECT ocw_courses.id FROM ocw_courses WHERE ocw_courses.id =
        ocw_course_files.course_id);
    
DELETE ocw_materials
FROM ocw_materials
WHERE NOT EXISTS(
    SELECT ocw_courses.id FROM ocw_courses WHERE ocw_courses.id =
        ocw_materials.course_id);
    
DELETE ocw_material_comments
FROM ocw_material_comments
WHERE NOT EXISTS (
    SELECT ocw_materials.id FROM ocw_materials WHERE ocw_materials.id =
        ocw_material_comments.material_id);
    
DELETE ocw_material_files
FROM ocw_material_files
WHERE NOT EXISTS (
    SELECT ocw_materials.id FROM ocw_materials WHERE ocw_materials.id =
        ocw_material_files.material_id);
    
DELETE ocw_materials_corecomp
FROM ocw_materials_corecomp
WHERE NOT EXISTS (
    SELECT ocw_materials.id FROM ocw_materials WHERE ocw_materials.id =
        ocw_materials_corecomp.material_id);
    
DELETE ocw_objects
FROM ocw_objects
WHERE NOT EXISTS (
    SELECT ocw_materials.id FROM ocw_materials WHERE ocw_materials.id =
        ocw_objects.material_id);
    
DELETE ocw_object_comments
FROM ocw_object_comments
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id =
        ocw_object_comments.object_id);
    
DELETE ocw_object_copyright
FROM ocw_object_copyright
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id =
        ocw_object_copyright.object_id);
    
DELETE ocw_object_files
FROM ocw_object_files
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id =
        ocw_object_files.object_id);
    
DELETE ocw_object_log
FROM ocw_object_log
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id =
        ocw_object_log.object_id);
    
DELETE ocw_object_questions
FROM ocw_object_questions
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id =
        ocw_object_questions.object_id);

DELETE ocw_object_replacement_comments
FROM ocw_object_replacement_comments
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id = 
        ocw_object_replacement_comments.object_id);

DELETE ocw_object_replacement_copyright
FROM ocw_object_replacement_copyright
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id = 
        ocw_object_replacement_copyright.object_id);

DELETE ocw_object_replacement_log
FROM ocw_object_replacement_log
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id =
        ocw_object_replacement_log.object_id);

DELETE ocw_object_replacement_questions
FROM ocw_object_replacement_questions
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id =
        ocw_object_replacement_questions.object_id);

DELETE ocw_object_replacement_questions
FROM ocw_object_replacement_questions
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id =
        ocw_object_replacement_questions.object_id);

DELETE ocw_object_replacements
FROM ocw_object_replacements
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id =
        ocw_object_replacements.object_id);

DELETE ocw_claims_commission
FROM ocw_claims_commission
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id =
        ocw_claims_commission.object_id);

DELETE ocw_claims_fairuse
FROM ocw_claims_fairuse
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id =
        ocw_claims_fairuse.object_id);

DELETE ocw_claims_permission
FROM ocw_claims_permission
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id =
        ocw_claims_permission.object_id);

DELETE ocw_claims_retain
FROM ocw_claims_retain
WHERE NOT EXISTS (
    SELECT ocw_objects.id FROM ocw_objects WHERE ocw_objects.id =
        ocw_claims_retain.object_id);

DELETE ocw_acl
FROM ocw_acl
WHERE NOT EXISTS (
    SELECT ocw_courses.id FROM ocw_courses WHERE ocw_courses.id =
        ocw_acl.course_id);

SET foreign_key_checks = 1;

COMMIT;
