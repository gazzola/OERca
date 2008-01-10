<?php
/**
 * Controller for login Page
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Home extends Controller {

	public function __construct()
	{
		parent::Controller();	
	}
	
	public function index()
	{
		$data = array('title'=>'Home');
		redirect('courses','location');
       	$this->layout->buildPage('home', $data);
	}

	public function breadcrumb($section='default')
	{
		$breadcrumb = array();

		if ($section == 'default') {
			$breadcrumb[] = array('url'=>site_url(), 'name'=>'Home');
		}
		return $breadcrumb;
	}
}
?>
