<?php
/**
 * Controller for managecourses Page
 *
 * @package	OER Tool		
 * @author Ali Asad Lotia <lotia@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */
class ManageCourses extends Controller {
  
  private $data = array("title" => "Manage Courses");
  
  /**
   * Constructor
   *
   * @access  public
   */
  public function __construct()
  {
    parent::Controller();
    // load some libraries which will be used by all functions
    $this->load->model('ocw_user');
    $this->load->library('oer_layout');
    $this->load->library('oer_manage_nav');
    $this->load->library('navtab');
    // fetch values which will be required in several places
    $this->data['id'] = getUserProperty('id');
    $this->data['role'] = getUserProperty('role');
    $this->data['courses'] = $this->ocw_user->get_courses($this->data['id']);
    log_message('debug', "ManageCourses Class Initialized");
  }
  
  
  /**
   * Set some shared values and then call the management function for the 
   * the user role
   * 
   * @access  public
   * @return  void
   */
   public function index()
   {
     $this->freakauth_light->check();
     $managefunc = "manage_" . $this->data['role'];
     $this->$managefunc();
   }
   
   
   /**
    * The dscribe1 management function
    *
    * @access public
    * @return void
    */
   public function manage_dscribe1()
   {
     $this->_manage_default();  
   }
   
   
   /**
    * The dscribe2 management function
    *
    * @access public
    * @return void
    */
   public function manage_dscribe2()
   {
     $this->_manage_default();
   }
   
   
   /**
    * The instructor management function
    *
    * @access public
    * @return void
    */
   public function manage_instructor()
   {
     $this->_manage_default();
   }
   
   
   /**
    * The default management function. If a user has only one course, they
    * are redirected to the manage materials page.
    *
    * @access private
    * @return void
    */
   private function _manage_default()
   {
     if (sizeof($this->_get_first_array_val(
     $this->data['courses'], 2)) == 1) {
       $anchorloc = "materials/home/"; 
       $anchorloc .= $this->_get_first_array_val($this->data['courses'], 4);
       redirect($anchorloc);
     } else {
       $tabset = $this->oer_manage_nav->get_tabset($this->data['role']);
       $this->data['tabs'] = $this->navtab->make_tabs($tabset);
       $this->oer_layout->build_custom_page('manage2', $this->data);
     }
   }
   
   
   /**
    * Return the first value a specified number of levels deep in an array
    *
    * @access private
    * @param  mixed array
    * @param  int the number of levels to descend
    * @return string/array at specified depth
    */
   private function _get_first_array_val($array, $depth)
   {
     while ($depth > 0) {
       $array = array_shift($array);
       $depth--;
     }
     
     return($array);
   }
}
?>