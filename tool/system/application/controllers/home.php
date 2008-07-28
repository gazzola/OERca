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

    $role = getUserProperty('role');

    if ($role == 'dscribe1') {
        redirect('dscribe1/home/', 'location');
      
    } elseif ($role == 'dscribe2') {
        redirect('dscribe2/home/', 'location');

    } elseif ($role == 'instructor') {
        redirect('instructor/home/', 'location');

		} else {
        redirect('guest/home/', 'location');
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
