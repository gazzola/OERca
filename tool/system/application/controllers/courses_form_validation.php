<?php
		// For edit, cid is sent back as a hidden field.
		// For create, it doesn't exist yet.
		if (isset($_POST['cid'])) {
			$cid = $_POST['cid'];
		}
		
		if ($_POST['cnum']) {
			$data['number'] = $_POST['cnum'];
		} else $data['number'] = NULL;
		if ($_POST['title']) {
			$data['title'] = $_POST['title'];
		} else $data['title'] = '';
		if ($_POST['director']) {
			$data['director'] = $_POST['director'];
		} else $data['director'] = '';
		if ($_POST['creator']) {
			$data['creator'] = $_POST['creator'];
		} else $data['creator'] = '';
		
		/*
		// check if an instructor has been assigned to the course
		$has_inst = $this->course->get_course_by_number($cid, 'instructor_id');
		// update the instructor name if specified
		if ($_POST['creator']) {
			if ($has_inst['instructor_id']) {
				$inst_data['name'] = $_POST['creator'];
				$this->instructors->update_inst($hasinst['instructor_id'], $inst_data);
			}
			else if (!$has_inst['instructor_id']) {
				$this->instructors->add_inst_to_course($_POST['creator'], $cid);
			}
		}
		*/

		if ($_POST['collaborators']) {
			$data['collaborators'] = $_POST['collaborators'];
		} else $data['collaborators'] = '';
		if ($_POST['language']) {
			$data['language'] = $_POST['language'];
		}
		if ($_POST['highlights']) {
			$data['highlights'] = $_POST['highlights'];
		} else $data['highlights'] = '';
		if ($_POST['description']) {
			$data['description'] = $_POST['description'];
		} else $data['description'] = '';
		if ($_POST['keywords']) {
			$data['keywords'] = $_POST['keywords'];
		} else $data['keywords'] = '';

		$data['school_id'] = $_POST['school_id'];
		$data['curriculum_id'] = $_POST['curriculum_id'];
		if (isset($_POST['subj_id'])) {
			$data['subject_id'] = $_POST['subj_id'];
		} else $data['subject_id'] = NULL;
		$data['level'] = $_POST['courselevel'];
		$data['length'] = $_POST['courselength'];
		$data['term'] = $_POST['term'];
		$data['year'] = $_POST['year'];
		$data['start_date'] = $_POST['start_date'];
		$data['end_date'] = $_POST['end_date'];

		// Verify that curriculum_id and subject_id belong to the selected school
		$validation_error = 0;
		$s = $this->school->get_school($data['school_id']);
		
		$curr = $this->curriculum->get_curriculum($data['curriculum_id']);
		if ($curr['school_id'] != $s['id']) {
			$msg = "Curriculum " . $curr['name'] . " does not belong to school " . $s['name'];
			$validation_error = 1;
		}

		$subj = $this->subject->get_subject($data['subject_id']);
		if (isset($subj['school_id']) && $subj['school_id'] != $s['id']) {
			$msg = "Subject " . $subj['subj_code'] . ":" . $subj['subj_desc'] . " does not belong to school " . $s['name'];
			$validation_error = 1;
		}

?>