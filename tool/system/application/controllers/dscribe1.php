<?php
/**
 * Controller for dScribe section
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Dscribe1 extends Controller 
{
	public function __construct()
	{
		parent::Controller();

		$this->freakauth_light->check('dscribe1');

		$this->load->model('ocw_user');
		$this->load->model('material');
	}

	public function index()
	{
		$this->home();
	}

	/**
     * Display dScribe dashboard 
     *
     * @access  public
     * @return  void
     */
	public function home()
	{
    $data = array('title' => 'Progress', 
                  'role' => getUserProperty('role'),
                  'name' => getUserProperty('name'));

    $data['id'] = getUserProperty('id');
    $data['courses'] = $this->ocw_user->get_courses_simple($data['id']);
		if (is_array($data['courses'])) {
      			foreach ($data['courses'] as $key => &$value) {
        				$value['num']['total'] = $this->material->get_co_count($value['id']);
        				$value['num']['done'] = $this->material->get_done_count($value['id']);
        				$value['num']['ask'] = $this->material->get_ask_count($value['id']);
        				$value['num']['rem'] = $this->material->get_rem_count($value['id']);
      			}
		}
   	$this->layout->buildpage('dscribe1/index', $data);
	}

  /**
     * Display dScribe2 course dashboard 
     *
     * @access  public
     * @param string task 
     * @param int  course id 
     * @return  void
     */
  public function courses()
  {
      $this->data['title'] = 'dScribe1 &raquo; Manage Courses';
      $this->data['courses'] = $this->course->get_courses();
      $this->layout->buildPage('dscribe1/courses', $this->data);
	}
}
?>
