<?php
/**
 * Controller for dScribe section
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Dscribe1 extends Controller {

	private $uid;
	private $data;
	private $coursedetails;

	public function __construct()
	{
		parent::Controller();

		$this->freakauth_light->check('dscribe1');

		$this->load->helper('form');
		$this->load->helper('text');

		$this->load->model('tag');
		$this->load->model('course');
	
		$this->uid = getUserProperty('id');	
		$this->data = array();
	}

	public function index($cid)
	{
		$this->home($cid);
	}

	/**
     * Display dScribe dashboard 
     *
     * @access  public
     * @param   int	course id		
     * @return  void
     */
	public function home($cid)
	{
		if ($this->_isValidUser($cid)) {
			// make sure user is allowed to access this course
			$this->data['title'] = $this->lang->line('ocw_dscribe');
       		$this->layout->buildPage('dscribe1/index', $this->data);
		}
	}

	/**
     * Manage course materials page 
     *
     * @access  public
     * @param   int	course id		
     * @param   string	task		
     * @param   int	 material id		
     * @param   string field name 
     * @param   mixed update values		
     * @return  void
     */
	public function materials($cid, $task='', $mid='', $field='', $val='') 
	{
		$this->_isValidUser($cid); 
		$this->data['title'] = $this->lang->line('ocw_ds_pagetitle_material'); 
       	$this->layout->buildPage('dscribe1/manage_materials', $this->data);
	}

	/**
     * Set course & instructor profiles 
     *
     * @access  public
     * @param   int	course id		
     * @param   string	task		
     * @return  void
     */
	public function profiles($cid, $task='') 
	{
		$this->_isValidUser($cid); 
		$this->data['title'] = $this->lang->line('ocw_ds_pagetitle_profiles'); 
		$courseDetails = $this->course->get_course($cid);
		$this->data['courseTitle'] = $courseDetails['title'];
		$this->data['courseId'] = $courseDetails['number'];
       	$this->layout->buildPage('dscribe1/set_profiles', $this->data);
	}

	/**
     * Set default copyright 
     *
     * @access  public
     * @param   int	course id		
     * @param   string	task		
     * @return  void
     */
	public function copyright($cid, $task='') 
	{
		$this->_isValidUser($cid); 
		$this->data['title'] = $this->lang->line('ocw_ds_pagetitle_copy'); 
       	$this->layout->buildPage('dscribe1/set_copyright', $this->data);
	}


	/**
     * Edit tags
     *
     * @access  public
     * @param   int	course id		
     * @param   string	task		
     * @return  void
     */
	public function tags($cid, $task='') 
	{
		$this->_isValidUser($cid); 
		$this->data['title'] = $this->lang->line('ocw_ds_pagetitle_tag'); 
       	$this->layout->buildPage('dscribe1/edit_tags', $this->data);
	}

	/**
     * Display review page 
     *
     * @access  public
     * @param   int	course id		
     * @param   string	task		
     * @return  void
     */
	public function review($cid, $task='') 
	{
		$this->_isValidUser($cid); 
		$this->data['title'] = $this->lang->line('ocw_ds_pagetitle_review'); 
       	$this->layout->buildPage('dscribe1/review_course', $this->data);
	}
	
	
	/**
     * Display the export page
     *
     * @access  public
     * @param   int	course id		
     * @param   string	task		
     * @return  void
     */
	public function export($cid, $task='') 
	{
		$this->_isValidUser($cid); 
		$this->data['title'] = $this->lang->line('ocw_ds_pagetitle_export'); 
       	$this->layout->buildPage('dscribe1/export', $this->data);
	}

	/**
     * Check to see if a user is an instructor or not 
     *
     * @access  private
     * @param   int	course id		
     * @return  boolean
     */
	private function _isValidUser($cid)
	{
		if ($this->course->has_access($this->uid, $cid)) {
			$this->data['cid'] = $cid;
			$this->coursedetails = $this->course->get_course($cid);
			$this->data['course'] = $this->coursedetails;
			$this->data['cname'] = $this->coursedetails['number'].' '.$this->coursedetails['title'];
			$this->data['breadcrumb'] = $this->breadcrumb();
			
			return true;

		} else {
			$msg = preg_replace('/{NAME}/',getUserProperty('name'), 
					$this->lang->line('ocw_ds_error_noaccess')); 
			flashMsg($msg);
       		redirect('home/', 'location');
		}
		return false;
	} 

	public function breadcrumb($section='default')
	{
		$breadcrumb = array();

		$breadcrumb[] = array('url'=>site_url(), 'name'=>'Home');
		$breadcrumb[] = array('url'=>site_url('home'), 'name'=>'Manage Courses');

		if ($section == 'default') {
			$breadcrumb[] = array('url'=>'', 'name'=> $this->data['cname']);
		}
		return $breadcrumb;
	}
}
?>
