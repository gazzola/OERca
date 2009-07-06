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
                $this->load->model('course');
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
        // bdr OERDEV-173 let's go count all the CO's the same way as for materials page
      	$materials =  $this->material->materials($value['id'],'',true,true);
				$value['num']['total'] = 0;
				$value['num']['done']  = 0;
				$value['num']['ask']   = 0;
				$value['num']['rem']   = 0;
				if ($materials) {
  				foreach($materials as $category => $cmaterial) {
  				  foreach($cmaterial as $material) {
  					  $value['num']['rem'] += $material['mrem'];
  					  $value['num']['ask'] += $material['mask'];
  					  $value['num']['done'] += $material['mdone'];
  					  //if ($material['mtotal'] != 1000000)						//OERDEV-181 mbleed: removed hardcoded total=1000000 logic
  					  $value['num']['total'] += $material['mtotal'];		
				    }
				  }
			  }
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
  public function courses($school=0, $year=0, $dscribe2=0, $dscribe=0)
  {
      $this->data['title'] = 'dScribe1 &raquo; Manage Courses';
      $uid = getUserProperty('id');
      //$this->data['courses'] = $this->course->get_courses();
      $this->data['courses'] = $this->course->new_get_courses($uid);
      $this->layout->buildPage('dscribe1/courses', $this->data);
	}
}
?>
