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
		$this->load->model('subject');
		$this->load->model('course');
		$this->load->model('material');
		$this->load->model('coobject');
		$this->load->model('ocw_user');

    $this->load->model('dbmetadata');
	}
	
	public function index()
	{
		$courses =  $this->course->get_courses();
		$data = array('title'=>'Courses','courses'=>$courses);
   	$this->layout->buildPage('courses/index', $data);
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
			    $rules = array(
			      'school_id' => "integer|maxlength[3]|xss_clean",
			      'subj_id' => "integer|maxlength[4]|xss_clean",
			      'cnum' => "integer|maxlength[4]|xss_clean",
			      'title' => "maxlength[255]|xss_clean",
			      'courselevel' => "alphanum|maxlength[15]|xss_clean",
			      'courselength' => "alphanum|maxlength[7]|xss_clean",
			      'term' => "alphanum|maxlength[8]|xss_clean",
			      'year' => "integer|maxlength[4]|xss_clean",
			      'director' => "maxlength[70]|xss_clean",
			      'creator' => "maxlength[255]|xss_clean",
			      'collaborators' => "maxlength[65535]|xss_clean",
			      'copyright_holder' => "maxlength[255]|xss_clean",
			      'language' => "maxlength[255]|xss_clean",
			      'highlights' => "maxlength[200]|xss_clean",
			      'description' => "maxlength[800]|xss_clean",
			      'keywords' => "maxlength[120]|xss_clean"
			      );
			      
			    $fields = array(
			      'school_id' => "School",
			      'subj_id' => "Course Subject",
			      'cnum' => "Course Number",
			      'title' => "Title",
			      'courselevel' => "Level",
			      'courselength' => "Length",
			      'term' => "Term",
			      'year' => "Year",
			      'director' => "Director",
			      'creator' => "Creator",
			      'collaborators' => "Collaborators",
			      'language' => "Language",
			      'highlights' => "Highlights",
			      'description' => "Description",
			      'keywords' => "Keywords"
			      );
			    
			    $this->validation->set_rules($rules);
			    $this->validation->set_fields($fields);
			    if ($this->validation->run() == FALSE) {
			      $role = getUserProperty('role');
				    flashMsg($this->validation->error_string);
					  redirect("courses/edit_course_info/$cid", 'location');
			    } else {
			      if ($_POST['cnum']) {
			        $data['number'] = $_POST['cnum'];
			      } else $data['number'] = NULL;
			      if ($_POST['title']) {
			        $data['title'] = $_POST['title'];
			      } else $data['title'] = '';
			      if ($_POST['director']) {
			        $data['director'] = $_POST['director'];
			      } else $data['director'] = '';
			     
						/* 
			      // check if an instructor has been assigned to the course
			      $has_inst = $this->course->get_course_by_number($cid, 'instructor_id');
			      // update the instructor name if specified
			      if ($_POST['creator']) {
			        if ($has_inst['instructor_id']) {
			          
			          $inst_data['name'] = $_POST['creator'];
			          $this->instructors->update_inst($hasinst['instructor_id'], 
			          $inst_data);
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
			      $data['subject_id'] = $_POST['subj_id'];
			      $data['level'] = $_POST['courselevel'];
			      $data['length'] = $_POST['courselength'];
			      $data['term'] = $_POST['term'];
			      $data['year'] = $_POST['year'];
			      // now update the course info
						$this->course->update_course($cid, $data);
						$msg = "Saved course information.";
						flashMsg($msg);
					  redirect("courses/edit_course_info/$cid", 'location');
			    }
		} else {
				$school_id = $this->school->get_school_list();
   			$subj_id = $this->subject->get_subj_list();
    		$coursedetails = $this->course->get_course($cid);

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
		    // TODO: consider combining enum fetches into a single DB call since
		    //      DB queries are expensive operations
		
		    // get the enum values for the pulldowns
		    $courselevel = NULL;
		    $clevelsindb = $this->dbmetadata->
		      get_enum_vals('ocw_courses', 'level');
		    foreach ($clevelsindb as $levelval) {
		      $courselevel[$levelval] = $levelval;
		    }
		
		    $courselength = NULL;
		    $clengthindb = $this->dbmetadata->
		      get_enum_vals('ocw_courses', 'length');
		    foreach ($clengthindb as $lengthval) {
		      $courselength[$lengthval] = $lengthval;
		    }
		
		    $term = NULL;
		    $termnamesindb = $this->dbmetadata->
		      get_enum_vals('ocw_courses', 'term');
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
		      'subj_id' => $subj_id,
		      'coursedetails' => $coursedetails,
		      'titlebox' => $titlebox,
		      'inst_infobox' => $inst_infobox,
		      'instdetails' => $instdetails
		      );

    		$this->load->view(property('app_views_path').'/courses/_edit_course_info.php', $data);
		}
	}
}
?>
