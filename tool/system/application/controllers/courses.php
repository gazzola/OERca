<?php
/**
 * Controller for login Page
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Courses extends Controller {

	public function __construct()
	{
		parent::Controller();	
		$this->load->model('school');
		$this->load->model('curriculum');
		$this->load->model('subject');
		$this->load->model('course');
		$this->load->model('material');
		$this->load->model('coobject');
		$this->load->model('ocw_user');

    $this->load->model('dbmetadata');
	}
	
	public function index()
	{
		$courses =  $this->course->new_get_courses(getUserProperty('id'));
		$data = array('title'=>'Courses','courses'=>$courses);
   		$this->layout->buildPage('courses', $data);
	}
	
	/**
	*	mbleed faceted search, get courses via param set
	**/
	public function faceted_search_courses($uid, $school=0, $year=0, $dscribe2=0, $dscribe=0)
	{		
		$courses =  $this->course->faceted_search_get_courses($uid, $school, $year, $dscribe2, $dscribe);
		$data = array('title'=>'Courses2	','courses'=>$courses);
   		$this->layout->buildPage('courses', $data);
	}
	
	/**
	 * validation check to make sure a school was selected
	 *
	 */
	public function school_check($sid)
	{
		if ($sid == 0) {
			$this->validation->set_message('school_check', 'Please select a School');
			return FALSE;			
		}
		return TRUE;
	}

	/**
	 * validation check to make sure a curriculum was selected
	 *
	 */
	public function curriculum_check($cid)
	{
		if ($cid == 0) {
			$this->validation->set_message('curriculum_check', 'Please select a Curriculum');
			return FALSE;			
		}
		return TRUE;
	}
	
	/**
	 * validation check to force a valid course length value and
	 * prevent "-1" which is the default form field selection from
	 * being selected as a value
	 */
   public function course_length_check($course_length) {
     if ($course_length == -1) {
       $this->validation->set_message('course_length_check', 'Please select Length');
       return FALSE;
     }
     return TRUE;
   }
   
   
	/**
	 * return curriculum and subject information for a given school
	 * to populate the curriculum and subject select boxes with only
	 * the curriculum and subjects for the selected school
	 *
	 * @access  public
	 * @param   school_id
	 * @return  array with: result indication, error_message (if any),
	 *					array of curriculum information (if any),
	 *					array of subject information (if any)
	 */
	public function return_values_for_school($sid)
	{
		$value = array();
		if ($sid == 0) {
			$value['success'] = false;
			$value['error_message'] = "Invalid school!";
			$value['curriculum_data'] = null;
			$value['subject_data'] = null;
		} else {
			$curr_list = $this->curriculum->get_curriculum_id_list($sid);
			$subj_list = $this->subject->get_subj_list($sid);
			$value['success'] = true;
			$value['curriculum_data'] = $curr_list;
			$value['subject_data'] = $subj_list;
		}
		$this->ocw_utils->send_response($value, 'json');
		exit;
	}

	/**
	 * verify/sanitize info and then add the new course
	 *
	 * @access  public
	 * @param   void
	 * @return  void
	 */
	public function add_new_course($view='view')
	{
			$this->load->helper(array('form','url'));
			$this->load->library('validation');

		  if ($view == 'create') {
						// common code to set up form validation information
				    include "courses_form_rules.php";
						include "courses_form_fields.php";
				    
						if ($this->validation->run() == FALSE) {
					   	flashMsg($this->validation->error_string);
					  	redirect("courses/add_new_course", 'location');
				    } else {
					
						// common code to validate POST information and copy to $data
						include "courses_form_validation.php";
	
						if ($validation_error == 0) {
							// create the course
							$new_course = NULL;
							$new_course = $this->course->new_course($data);
							if ($new_course)
								$msg = "Successfully created course '" . $new_course['title'] . "'";
							else
								$msg = "Error creating course";
						}
						flashMsg($msg);
					  redirect("courses/add_new_course", 'location');
					}
				} else {
	    		$coursedetails = array (
						"description" => NULL,
						"highlights" => NULL,
						"keywords" => NULL,
						"school_id" => NULL,
						"curriculum_list" => NULL,
						"subject_id" => NULL,
						"level" => NULL,
						"cnum" => NULL,
						"length" => NULL,
						"term" => NULL,
						"year" => NULL,
						"director" => NULL,
						"creator" => NULL,
						"instructor_id" => NULL,
						"language" => NULL,
						"collaborators" => NULL,
						"cid" => NULL,
						"title" => NULL,
						"start_date" => "(yyyy-mm-dd)",
						"end_date" => "(yyyy-mm-dd)"
						);
			    $missing_menu_val = "-- select --";
			    $school_id[0] = $missing_menu_val;
			    $subj_id[0] = $missing_menu_val;
					$curriculum_list[0] = $missing_menu_val;

          $list = $this->school->get_school_list();
          if ($list != NULL)
            $school_id += $list;
          $list = $this->subject->get_subj_list();
          if ($list != NULL)
            $subj_id += $list;
          $list = $this->curriculum->get_curriculum_id_list();
          if ($list != NULL)
            $curriculum_list += $list;

					// only get instructor details if an instructor is defined for the course
			    $instdetails = array(
			      "name" => NULL,
			      "title" => NULL,
			      "info" => NULL,
			      "uri" => NULL,
			      "imagefile" => NULL
			      );

			    // TODO: consider combining enum fetches into a single DB call since
			    //      DB queries are expensive operations

			    // get the enum values for the pulldowns
			    $courselevel = NULL;
			    $clevelsindb = $this->dbmetadata->get_enum_vals('ocw_courses', 'level');
					$courselevel[-1] = $missing_menu_val;
			    foreach ($clevelsindb as $levelval) {
			      $courselevel[$levelval] = $levelval;
			    }

			    $courselength = NULL;
			    $clengthindb = $this->dbmetadata->get_enum_vals('ocw_courses', 'length');
					$courselength[-1] = $missing_menu_val;
			    foreach ($clengthindb as $lengthval) {
			      $courselength[$lengthval] = $lengthval;
			    }
					
			    $term = NULL;
			    $termnamesindb = $this->dbmetadata->get_enum_vals('ocw_courses', 'term');
					$term[-1] = $missing_menu_val;
			    foreach ($termnamesindb as $termname) {
			      $term[$termname] = $termname;
			    }

			    $curryear = mdate('%Y');

			    $year = array(
			      ($curryear + 2) => ($curryear + 2),
			      ($curryear + 1) => ($curryear + 1),
			      ($curryear) => ($curryear),
			      ($curryear - 1) => ($curryear - 1),
			      ($curryear - 2) => ($curryear - 2),
			      ($curryear - 3) => ($curryear - 3),
			      ($curryear - 4) => ($curryear - 4),
			      ($curryear - 5) => ($curryear - 5)
			      );

					$start_date = 0;
					$end_date = 0;

			    // form field attributes
			    $coursedescbox = array(
			      'name' => 'description',
			      'id' => 'description',
			      'wrap' => 'virtual',
						'style'=> 'width:270px;height:100px;',
						'value' => $coursedetails['description'],
						'tabindex' => 18,
			      );

			    $coursehighlightbox = array(
			      'name' => 'highlights',
			      'id' => 'highlights',
			      'wrap' => 'virtual',
						'style'=> 'width:270px;height:100px;',
						'value' => $coursedetails['highlights'],
						'tabindex' => 17,
			      );

			    $keywordbox = array(
			      'name' => 'keywords',
			      'id' => 'keywords',
			      'wrap' => 'virtual',
						'style'=> 'width:270px;height:100px;',
						'value' => $coursedetails['keywords'],
						'tabindex' => 19,
			      );

			    $titlebox = array(
			      'name' => 'title',
			      'id' => 'title',
			      'wrap' => 'virtual',
						'style'=> 'width:270px;height:100px;', 
			      'value' => $instdetails['title'],
						'tabindex' => 19,
			      );

			    $inst_infobox = array(
			      'name' => 'info',
			      'id' => 'info',
			      'wrap' => 'virtual',
						'style'=> 'width:270px;height:100px;', 
			      'value' => $instdetails['info']
			      );

			    if ($coursedetails['year'] != 0000) {
						$curryear = $coursedetails['year'];
			    }

			    $data = array(
						'cid' => NULL, 
						'title' => 'Courses',
			      'courselevel' => $courselevel,
			      'courselength' => $courselength,
			      'coursedescbox' => $coursedescbox,
			      'coursehighlightbox' => $coursehighlightbox,
			      'keywordbox' => $keywordbox,
			      'term' => $term,
			      'curryear' => $curryear,
			      'year' => $year,
						'start_date' => $start_date,
						'end_date' => $end_date,
			      'school_id' => $school_id,
						'curriculum_list' => $curriculum_list,
			      'subj_id' => $subj_id,
			      'coursedetails' => $coursedetails,
			      'titlebox' => $titlebox,
			      'inst_infobox' => $inst_infobox,
			      'instdetails' => $instdetails
			      );

	    		$this->load->view(property('app_views_path').'/courses/_add_course.php', $data);
					
				}
}	


	/**
	 * verify/sanitize and then add the course info
	 *
	 * @access  public
	 * @param   int course id
	 * @return  void
	 */
	public function edit_course_info($cid,$view='view')
	{ 
		if ($view=='edit') {
					// common code to set up form validation information
 					include 'courses_form_rules.php';
 					include 'courses_form_fields.php';

			    if ($this->validation->run() == FALSE) {
			      $role = getUserProperty('role');
				    flashMsg($this->validation->error_string);
					  redirect("courses/edit_course_info/$cid", 'location');
			    } else {
						// common code to validate POST information and copy to $data
						include "courses_form_validation.php";

						if ($validation_error == 0) {
							// now update the course info
 							$this->course->update_course($cid, $data);
 							$msg = "Saved course information.";
						}
						flashMsg($msg);
					  redirect("courses/edit_course_info/$cid", 'location');
			    }
		} else {
				$coursedetails = $this->course->get_course($cid);
				$school_id = $this->school->get_school_list();
   			$subj_id = $this->subject->get_subj_list($coursedetails['school_id']);
				$curriculum_list = $this->curriculum->get_curriculum_id_list($coursedetails['school_id']);

		   // only get instructor details if an instructor is defined for the course
		    $instdetails = array(
		      "name" => NULL,
		      "title" => NULL,
		      "info" => NULL,
		      "uri" => NULL,
		      "imagefile" => NULL
		      );
		    if (isset($coursedetails['instructor_id'])) {
		       $instdetails = $this->ocw_user->profile($coursedetails['instructor_id']);
		    }
		   
		    $missing_menu_val = "-- select --";
		    $school_id[0] = $missing_menu_val;
		    $subj_id[0] = $missing_menu_val;
				$curriculum_list[0] = $missing_menu_val;
				
		    // TODO: consider combining enum fetches into a single DB call since
		    //      DB queries are expensive operations
		
		    // get the enum values for the pulldowns
		    $courselevel = NULL;
		    $clevelsindb = $this->dbmetadata->get_enum_vals('ocw_courses', 'level');
		    foreach ($clevelsindb as $levelval) {
		      $courselevel[$levelval] = $levelval;
		    }
		
		    $courselength = NULL;
		    $clengthindb = $this->dbmetadata->get_enum_vals('ocw_courses', 'length');
		    foreach ($clengthindb as $lengthval) {
		      $courselength[$lengthval] = $lengthval;
		    }
				
		    $term = NULL;
		    $termnamesindb = $this->dbmetadata->get_enum_vals('ocw_courses', 'term');
		    foreach ($termnamesindb as $termname) {
		      $term[$termname] = $termname;
		    }

		    $curryear = mdate('%Y');
		
		    $year = array(
		      ($curryear + 2) => ($curryear + 2),
		      ($curryear + 1) => ($curryear + 1),
		      ($curryear) => ($curryear),
		      ($curryear - 1) => ($curryear - 1),
		      ($curryear - 2) => ($curryear - 2),
		      ($curryear - 3) => ($curryear - 3),
		      ($curryear - 4) => ($curryear - 4),
		      ($curryear - 5) => ($curryear - 5)
		      );
		
		    // form field attributes
		    $coursedescbox = array(
		      'name' => 'description',
		      'id' => 'description',
		      'wrap' => 'virtual',
					'style'=> 'width:270px;height:100px;', 
		      'value' => $coursedetails['description']
		      );
		
		    $coursehighlightbox = array(
		      'name' => 'highlights',
		      'id' => 'highlights',
		      'wrap' => 'virtual',
					'style'=> 'width:270px;height:100px;', 
		      'value' => $coursedetails['highlights']
		      );
		
		    $keywordbox = array(
		      'name' => 'keywords',
		      'id' => 'keywords',
		      'wrap' => 'virtual',
					'style'=> 'width:270px;height:100px;', 
		      'value' => $coursedetails['keywords']
		      );

		    $titlebox = array(
		      'name' => 'title',
		      'id' => 'title',
		      'wrap' => 'virtual',
					'style'=> 'width:270px;height:100px;', 
		      'value' => $instdetails['title']
		      );
		
		    $inst_infobox = array(
		      'name' => 'info',
		      'id' => 'info',
		      'wrap' => 'virtual',
					'style'=> 'width:270px;height:100px;', 
		      'value' => $instdetails['info']
		      );
		
		    if ($coursedetails['year'] != 0000) {
		      $curryear = $coursedetails['year'];
		    }
		
		    $data = array('title'=>'Courses',
		      'cid'=>$cid,
		      'courselevel' => $courselevel,
		      'courselength' => $courselength,
		      'coursedescbox' => $coursedescbox,
		      'coursehighlightbox' => $coursehighlightbox,
		      'keywordbox' => $keywordbox,
		      'term' => $term,
		      'curryear' => $curryear,
		      'year' => $year,
		      'school_id' => $school_id,
					'curriculum_list' => $curriculum_list,
		      'subj_id' => $subj_id,
		      'coursedetails' => $coursedetails,
		      'titlebox' => $titlebox,
		      'inst_infobox' => $inst_infobox,
		      'instdetails' => $instdetails
		      );

    		$this->load->view(property('app_views_path').'/courses/_edit_course_info.php', $data);
		}
	}

	/**
	 * remove a course and all the associated information along with it
	 *
	 * @access  public
	 * @param   int course id
	 * @return  boolean
	 */
	public function remove_course($cid)
	{
		if (getUserProperty('role') != 'admin') {
			flashMsg("Only administrators are permitted to remove a course!");
			return false;
		}
		
		// XXX Should make them verify again!
		$this->course->remove_course($cid);
	}
	
}
?>
