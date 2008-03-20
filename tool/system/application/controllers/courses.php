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
		$this->load->model('course');
		$this->load->model('material');
		$this->load->model('coobject');
	}
	
	public function index()
	{
		$courses =  $this->course->get_courses();
		$data = array('title'=>'Courses','courses'=>$courses, 'breadcrumb'=>$this->breadcrumb());
       	$this->layout->buildPage('courses/index', $data);
	}
	
	
	/**
	 * verify/sanitize and then add the course info
	 *
	 * @access  public
	 * @param   int course id
	 * @return  void
	 */
	public function edit_course_info($cid)
	{ 
    $rules = array(
      'school_id' => "integer|maxlength[3]|xss_clean",
      'subj_id' => "integer|maxlength[4]|xss_clean",
      'cnum' => "integer|maxlength[4]|xss_clean",
      'title' => "maxlength[255]|xss_clean",
      'courselevel' => "alpha|maxlength[15]|xss_clean",
      'courselength' => "integer|maxlength[2]|xss_clean",
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
      'title' => "Course Title",
      'courselevel' => "Course Level",
      'courselength' => "Course Length",
      'term' => "Term",
      'year' => "Year",
      'director' => "Director",
      'creator' => "Creator",
      'collaborators' => "Copyright Holder",
      'language' => "Language",
      'highlights' => "Highlights",
      'description' => "Description",
      'keywords' => "Keywords"
      );
    
    $this->validation->set_rules($rules);
    $this->validation->set_fields($fields);
    if ($this->validation->run() == FALSE)
    {
      $role = getUserProperty('role');
	    flashMsg($this->validation->error_string);
			redirect("materials/home/$cid/$role/editcourse", 'location');
    } else {
      // TODO: check to see if
      
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
      if ($_POST['collaborators']) {
        $data['collaborator'] = $_POST['collaborators'];
      } else $data['collaborator'] = '';
      if ($_POST['language']) {
        $data['language'] = $_POST['language'];
      }
      if ($_POST['highlights']) {
        $data['highlight'] = $_POST['highlights'];
      } else $data['highlight'] = '';
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
			$msg = "Edited course information.";
			flashMsg($msg);
      redirect("materials/home/$cid", "location");
    }
	}
	

	public function breadcrumb($section='default')
	{
		$breadcrumb = array();

		if ($section == 'default') {
			$breadcrumb[] = array('url'=>site_url(), 'name'=>'Home');
			$breadcrumb[] = array('url'=>'', 'name'=>'Courses');
		}
		return $breadcrumb;
	}
}
?>
