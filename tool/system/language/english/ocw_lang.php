<?php
# Global
$lang['ocw_dscribe']	= 'dScribe';
$lang['ocw_instructor']	= 'Instructor';
$lang['ocw_remove']	= 'remove';
$lang['ocw_add']	= 'Add';
$lang['ocw_update']	= 'Update';

# Home
$lang['ocw_home_signin']	= 'Sign in as an {INSTRUCTOR} or {DSCRIBE}';

# --- Instructor Section -- #
# page titles
$lang['ocw_ins_pagetitle_managemat'] = $lang['ocw_instructor'].' &raquo; Manage Materials';
$lang['ocw_ins_pagetitle_manage'] = $lang['ocw_instructor'].' &raquo; Manage dScribes';
$lang['ocw_ins_pagetitle_material'] = $lang['ocw_instructor'].' &raquo; Pick course materials';
$lang['ocw_ins_pagetitle_review'] = $lang['ocw_instructor'].' &raquo; Review';

# menubar text
$lang['ocw_ins_menu_home']	= 'Home';
$lang['ocw_ins_menu_managematerials']	= 'Manage Materials';
$lang['ocw_ins_menu_materials']	= 'Select Course Materials for OCW';
$lang['ocw_ins_menu_manage']	= 'Manage dScribes';
$lang['ocw_ins_menu_review']	= 'Review for Export';

# instructor>error
$lang['ocw_ins_error_notinstructor'] = 'Cannot access Instructor dashbard. {NAME} is not an instructor for this course.';
$lang['ocw_ins_error_exists'] = '{NAME} ({EMAIL}) is already a dScribe for this course.';

# instructor>home
$lang['ocw_ins_home_dscribetext']	= 'Add and Remove dScribes for this course';
$lang['ocw_ins_home_materialstext']	= 'Select course materials for inclusion in OCW'; 
$lang['ocw_ins_home_reviewtext']	= 'Review materials preapred for OCW';

# instructor>dscribes
$lang['ocw_ins_dscribes_intro']	= 'As the instructor for this course, you may act as your own dScribe, or you may want to assign this role to one of your students or other dScribe.';
$lang['ocw_ins_dscribes_addadscribe']	= 'Add a dScribe';
$lang['ocw_ins_dscribes_name']	= 'Name';
$lang['ocw_ins_dscribes_username']	= 'Username';
$lang['ocw_ins_dscribes_email']	= 'Email';
$lang['ocw_ins_dscribes_name']	= 'Name';
$lang['ocw_ins_dscribes_level']	= $lang['ocw_dscribe'].' level';
$lang['ocw_ins_dscribes_currentds']	= 'Current dScribes';

# --- dScribe Section -- #

# page titles
$lang['ocw_ds_pagetitle_material'] = $lang['ocw_dscribe'].' &raquo; Course Materials';
$lang['ocw_ds_pagetitle_profile'] = $lang['ocw_dscribe'].' &raquo; Course & Instructor Profiles';
$lang['ocw_ds_pagetitle_copy'] = $lang['ocw_dscribe'].' &raquo; Set Default Copyright';
$lang['ocw_ds_pagetitle_tag'] = $lang['ocw_dscribe'].' &raquo; Edit Tags';
$lang['ocw_ds_pagetitle_review'] = $lang['ocw_dscribe'].' &raquo; Review';
$lang['ocw_ds_pagetitle_export'] = $lang['ocw_dscribe'].' &raquo; Export';
$lang['ocw_ds_pagetitle_viewip'] = $lang['ocw_instructor'].' &raquo; Manage IP Objects';
$lang['ocw_ds_pagetitle_editip'] = $lang['ocw_instructor'].' &raquo; Edit IP Object';
$lang['ocw_ds_pagetitle_editmat'] = $lang['ocw_instructor'].' &raquo; Edit Material';

# menubar text
$lang['ocw_ds_menu_home']	= 'Home';
$lang['ocw_ds_menu_materials']	= 'Prep Course Materials';
$lang['ocw_ds_menu_profiles']	= 'Set Profiles';
$lang['ocw_ds_menu_copyright']	= 'Set Copyright';
$lang['ocw_ds_menu_tags']	= 'Edit Tags';
$lang['ocw_ds_menu_review']	= 'Review for Export';
$lang['ocw_ds_menu_export']	= 'Export';


# dscribe>error
$lang['ocw_ds_error_notaccess'] = 'Cannot access dScribe dashbard. {NAME} is not an instructor or dScribe for this course.';
$lang['ocw_ds_error_nocopy'] = 'Please specify a name for the default copyright holder';

# dscribe>home
$lang['ocw_ds_home_materialstext']	= 'Verify copyright and integrity of materials'; 
$lang['ocw_ds_home_profilestext']	= 'Manage course and instructor profiles';
$lang['ocw_ds_home_copytext']	= 'Set default copyright holder';
$lang['ocw_ds_home_reviewtext']	= 'Review materials preapred for OCW';

# dscribe>copy
$lang['ocw_ds_copy_header']	= 'Set default copyright holder';
$lang['ocw_ds_copy_introtext']	= 'Materials included in OCW are licensed as follows: attribution, non-commercial and share alike. Find out more about this license <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/">here.</a>';

# describe>export
$lang['ocw_ds_export_instruction'] = 'If you are satisfied with your choices and category selections, please click on the "Export" button down below to get a IMS-CP zip file. You can then upload the file in eduCommons and create a OCW site.';