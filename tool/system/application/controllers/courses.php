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
	 * verify/sanitize the course info form
	 *
	 * @access  public
	 * @param   int course id
	 * @return  void
	 */
	public function check_course_info($cid)
	{
    $errmsg = '';
    
    $rules = array(
      'id' => "required"
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
	
	
	/**
	 * add/edit course information
	 *
	 * @access  public
	 * @param   int course id
	 * @return  void
	 */
	public function edit_course_info($cid)
	{
	  $this->ocw_utils->dump($_POST);
	  $this->ocw_utils->dump($_FILES);
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
