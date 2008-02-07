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
