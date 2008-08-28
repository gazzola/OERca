<?php
	$fields = array(
		'cnum' => "Course Number",
		'title' => "Course Title",
		'start_date' => "Start Date",
		'end_date' => "End Date",
		'curriculum_id' => "Curriculum",
		'director' => "Director",
		'creator' => "Creator",
		'instructor_id' => "Instructor",
		'collaborators' => "Collaborators",
		'courselevel' => "Course Level",
		'courselength' => "Course Length",
		'term' => "Term",
		'year' => "Year",
		'copyright_holder' => "Copyright Holder",
		'language' => "Language",
		'school_id' => "School",
		'subj_id' => "Course Subject",
		'curricular_info' => "Curricular Information",
		'lifecycle_version' => "Lifecycle Version",
		'highlights' => "Highlights",
		'description' => "Description",
		'keywords' => "Keywords"
	);
	$this->validation->set_fields($fields);
?>
