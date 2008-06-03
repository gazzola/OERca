<?php
/**
 * Home Class
 *
 * @package OER Tool
 * @author Ali Asad Lotia <lotia@umich.edu>
 * @date 10 February 2008
 * @copyright Copyright (c) 2006, University of Michigan
 */
class Home extends Controller {

 /**
  * The default constructor.
  */
  public function __construct()
  {
    parent::Controller();
    $this->load->model('ocw_user');
    $this->load->model('material');
    $this->load->library('oer_progbar');
    $this->load->library('oer_layout');
    $this->load->library('navtab');
    $this->load->library('oer_manage_nav');
  }
  
  
  /**
    * Users with roles other than dscribe1 are redirected to the 
    * default home pages for their role types. Users with dscribe1
    * roles are presented with visual summaries of the current
    * state of the content clearing process.
    * Progress bars showing how many content objects have been 
    * cleared; are in progress; and have not yet been 
    * started are displayed for each of the dscribe1's assigned
    * courses.
    */
  public function index()
  {
    $this->freakauth_light->check();

    $data = array('title' => 'Progress', 
                  'role' => getUserProperty('role'),
                  'name' => getUserProperty('name'));

    if ($data['role'] == 'dscribe1') {
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
      	// get the navigation tab set
      	$tabset = $this->oer_manage_nav->get_tabset($data['role']);
      	$data['tabs'] = $this->navtab->make_tabs($tabset);
      	$this->oer_layout->buildpage('homedscribe1', $data);
      
    } elseif ($data['role'] == 'dscribe2') {
        redirect('dscribe2/home/', 'location');

    } elseif ($data['role'] == 'instructor') {
        redirect('instructor/home/', 'location');

		} else {
      	$this->layout->buildPage('homeother', $data);
    }
  }

  
  /**
    * Generates a bar chart showing the state of the IP
    * clearance of the content objects in a course.
    *
    * @param    int total number of content objects
    * @param    int number of cleared content objects
    * @param    int number of content objects that have associated 
    *            questions
    * @param    int number of content objects that need to be checked
    * @return   void
    */  
  public function make_bar($total,$done,$ask,$rem)
  {
    $this->oer_progbar->build_prog_bar($total,$done,$ask,$rem);
    $this->oer_progbar->get_prog_bar();
  }


  /**
    * Generates a colored square representing the specifed
    * CO status
    *
    * @param    string the CO status (done, ask, rem)
    * @return   void
    */
  public function make_stat_key($status)
  {
    $this->oer_progbar->build_stat_key($status);
    $this->oer_progbar->get_stat_key();
  }
}
?>
