<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * OER_Manage_Nav Class
 *
 * @package		OER Navtab
 * @subpackage	Libraries
 * @category	 Generation
 * @author	Ali Asad Lotia <lotia@umich.edu>
 *
 * This class complements the more generic navtab class and saves having to 
 * assign and pass the rather complex data structure used to define the
 * tabs and their arguments in the navtab class. The tabs for the various
 * roles are defined here. Any changes to the tabs can be made here and will
 * show up on all pages for that role.
 * 
 */
 class OER_manage_nav
 {
   private $user_role = NULL;
   private $course_id = NULL;
   
   private $CI = NULL;
   
   
   /**
    * Constructor
    *
    * @access   public
    * @return   an instance of this class
    */
   public function __construct()
   {
     $this->CI =& get_instance();
     $this->CI->load->helper('url'); // load the url helper
     return($this);
   }
   
   
   /**
    * Define the dscribe1 tabs
    *
    * @access   private
    * @return   array the tab set for dscribe1 user role
    */
    private function _dscribe1_tabs()
    {
      $tabs = array(
        array(
          "arg" => array("dscribe1/home"),
          "name" => "Home",
          "url" => site_url("dscribe1/home")
          ),
        array(
          "arg" => array("dscribe1/courses"),
          "name" => "Manage Courses",
          "url" => site_url("dscribe1/courses")
          )
        );
      
      return($tabs);
    }
    
    
    /**
      * Define the instructor tabs
      *
      * @access   private
      * @return   array the tab set for instructor user role
      */
      private function _instructor_tabs()
      {
        $tabs = array(
          array(
            "arg" => array("instructor/home"),
            "name" => "Home",
            "url" => site_url("home")
            ),
          array(
            "arg" => array("instructor/materials"),
            "name" => "Select Course Materials",
            "url" => site_url("instructor/materials/{$this->course_id}")
            ),
          array(
            "arg" => array("instructor/courses"),
            "name" => "Manage Courses",
            "url" => site_url("instructor/courses")
            ),
          array(
            "arg" => array("instructor/review"),
            "name" => "Review for Export",
            "url" => site_url("instructor/review/{$this->course_id}")
            ),
          array(
            "arg" => array("dscribe1/index"),
            "name" => "View of dScribe1",
            "url" => site_url("dscribe1/index/{$this->course_id}"),
            )
          );

        return($tabs);
      }
      
      
      /**
        * Define the dscribe2 tabs
        *
        * @access   private
        * @return   array the tab set for dscribe2 user role
        */
        private function _dscribe2_tabs()
        {
          $tabs = array(
            array(
              "arg" => array("dscribe2/home"),
              "name" => "Home",
              "url" => site_url("dscribe2/home")
              ),
            array(
              "arg" => array("dscribe2/courses"),
              "name" => "Manage Courses",
              "url" => site_url("dscribe2/courses")
              ),
            array(
              "arg" => array("dscribe2/dscribes"),
              "name" => "Manage dScribes",
              "url" => site_url("dscribe2/dscribes")
              )
            );

          return($tabs);
        }
        
        
    /**
     * Set the user role
     *
     * @access  public
     * @param   string role
     * @return  void
     */
     public function set_role($role)
     {
       $this->user_role = $role;
     }
     
     
     /**
      * Get the user role
      *
      * @access  public
      * @return  string role
      */
      public function get_role()
      {
        return($this->user_role);
      }
      
      
      /**
       * Set the course id
       *
       * @access  public
       * @param   int role
       * @return  void
       */
       public function set_cid($cid)
       {
         $this->course_id = $cid;
       }


       /**
        * Get the course id
        *
        * @access  public
        * @return  int course id
        */
        public function get_cid()
        {
          return($this->course_id);
        }
      
      
      /**
       * Get the tabset for the passed user role and course id if a view_role 
       * is passed it is used for special cases where an instructor dscribe2
       * may want to get a dscribe1 view.
       * TODO: make different views possible and more generalized
       *
       * @access  public
       * @param   string user role
       * @param   int course id
       * @param   string role for which tabs should be displayed
       * @return  void
       */
       public function get_tabset($role = NULL, $cid = NULL, 
         $view_role = NULL)
       {
         $req_tabset = NULL; // the string used to select the tabset
         
         if ($role) {
          $req_tabset = "_{$role}_tabs";
         } else {
          $req_tabset = "_{$this->user_role}_tabs";
         }
         if ($cid) {
          $this->set_cid($cid);
         }
          
         return($this->$req_tabset());
       }
 }

?>
