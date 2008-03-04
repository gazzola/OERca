<?php
/**
 * Controller for manage Page
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Manage extends Controller 
{
	public function __construct()
	{
		parent::Controller();	
	}
	
	public function index()
	{
		$this->freakauth_light->check();

    $this->load->model('ocw_user');

    if (getUserProperty('role') == 'dscribe2') {
        redirect('dscribe2/home/', 'location');

    } else {
        $courses = $this->ocw_user->get_courses(getUserProperty('id'));
        $data = array('title'=>'Home',
									    'courses'=>$courses,
											'sysrole'=>getUserProperty('role'));
        $this->layout->buildPage('manage', $data);
    }
	}
}
?>
