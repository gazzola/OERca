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
    $errmsg = 'This is bad';
    
    $rules = array(
      'subnum' => "integer|maxlength[4]|xss_clean",
      'cnum' => "integer|maxlength[4]|xss_clean",
      'title' => "maxlength[255]|xss_clean",
      'director' => "maxlength[70]|xss_clean",
      'creator' => "maxlength[255]|xss_clean",
      'collaborator' => "maxlength[65535]|xss_clean",
      'school_id' => "integer|maxlength[3]|xss_clean",
      'courselevel' => "alpha|maxlength[15]|xss_clean",
      'courselength' => "integer|maxlength[2]|xss_clean",
      'term' => "alpha|maxlength[8]|xss_clean",
      'year' => "integer|maxlength[4]|xss_clean",
      'copyright' => "maxlength[255]|xss_clean",
      'language' => "maxlength[255]|xss_clean",
      'school_id' => "integer|maxlength[3]|xss_clean",
      'subj_id' => "integer|maxlength[4]|xss_clean",
      );
    
    $this->validation->set_rules($rules);
    if ($this->validation->run() == FALSE)
    {
      $role = getUserProperty('role');
			flashMsg($errmsg);
			redirect("materials/home/$cid/$role/editcourse", 'location');
    } else {
      $msg = "Edited course information.";
			flashMsg($msg);
			$this->ocw_utils->dump($_POST);
			$this->ocw_utils->dump($_FILES);
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
