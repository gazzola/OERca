<?php
	$rules = array(
		'cnum' => "integer|maxlength[4]|xss_clean",
		'title' => "maxlength[255]|required|xss_clean",
		'start_date' => "required|alpha_dash|xss_clean",
		'end_date' => "required|alpha_dash|xss_clean",
		'curriculum_id' => "integer|required|xss_clean|callback_curriculum_check",
		'director' => "maxlength[70]|xss_clean",
		'creator' => "maxlength[255]|xss_clean",
		'instructor_id' => "integer|maxlength[10]|xss_clean",
		'collaborators' => "maxlength[65535]|xss_clean",
		'courselevel' => "required|alphanum|maxlength[15]|xss_clean",
		'courselength' => "required|alphanum|maxlength[7]|xss_clean|callback_course_length_check",
		'term' => "required|alphanum|maxlength[8]|xss_clean",
		'year' => "required|integer|maxlength[4]|xss_clean",
		'copyright_holder' => "maxlength[255]|xss_clean",
		'language' => "maxlength[255]|xss_clean",
		'school_id' => "required|integer|maxlength[3]|xss_clean|callback_school_check",
		'subj_id' => "integer|maxlength[4]|xss_clean",
		'curricular_info' => "maxlength[200]|xss_clean",
		'lifecycle_version' => "maxlength[255]|xss_clean",
		'highlights' => "maxlength[200]|xss_clean",
		'description' => "maxlength[800]|xss_clean",
		'keywords' => "maxlength[120]|xss_clean"
	);
	$this->validation->set_rules($rules);
?>
