<?php
/**
 * Controller for dScribe2 section
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Dscribe2 extends Controller {

	private $uid;
	private $data;
	private $coursedetails;

	public function __construct()
	{
		parent::Controller();

		$this->freakauth_light->check();

		$this->load->helper('form');
		$this->load->helper('text');
		$this->load->model('tag');
		$this->load->model('course');
		$this->load->model('material');
		$this->load->model('ipobject');
		$this->load->model('ocw_user');
		$this->load->model('file_type');
	
		$this->uid = getUserProperty('id');	
		$this->data = array();
	}

	public function index() { $this->home(); }

	/**
     * Display dScribe2 dashboard 
     *
     * @access  public
     * @return  void
     */
	public function home()
	{
		if ($this->_isValidUser()) {
			// make sure user is allowed to access this course
			$this->data['title'] = 'dScribe2'; 
       		$this->layout->buildPage('dscribe2/index', $this->data);
		}
	}

	/**
     * Display dScribe2 course dashboard 
     *
     * @access  public
	 8 @param string task 
	 8 @param int  course id 
     * @return  void
     */
	public function courses($task='', $cid='')
	{
		if ($task == 'add') {
			$this->course->new_course($_POST);
			$this->ocw_utils->send_response('success');

		} elseif ($task == 'update') {
			if ($_POST['field']=='new_curriculum_id') {
				$d = array('title'=>$_POST['new_curriculum_id'],'description'=>'');
				$curr_id = $this->course->new_curriculum($d);
				$_POST['field'] = 'curriculum_id';
				$_POST['val'] = $curr_id;
			}
			if ($_POST['field']=='new_sequence_id') {
				$d = array('name'=>$_POST['new_sequence_id']);
				$seq_id = $this->course->new_sequence($d);
				$_POST['field'] = 'sequence_id';
				$_POST['val'] = $seq_id;
			}
			$data = array($_POST['field']=>$_POST['val']);
			$this->course->update_course($cid, $data);
			$this->ocw_utils->send_response('success');
			exit;

		} elseif ($task =='edit') {
			$this->data['cid'] = $cid;
			$this->data['tags'] = $this->tag->tags(); 
			$this->data['course'] = $this->course->details($cid);
			$this->data['filetypes'] = $this->file_type->filetypes(); 
			$this->data['sequences'] = $this->course->sequences();
			$this->data['curriculum'] = $this->course->curriculums();
			$this->data['materials'] = $this->material->materials($cid,'',false,true); 
			$this->data['categories'] = $this->material->categories(); 
			$this->data['title'] = 'dScribe2 &raquo; Manage Courses &raquo; Edit'; 
       		$this->layout->buildPage('dscribe2/edit_course', $this->data);

		} elseif ($task =='review') {
       		$this->layout->buildPage('dscribe2/review', $this->data);

		} elseif ($task =='remove') {
			flashMsg('The course would be removed at this time, but this is only a demo');
       		redirect('dscribe2/courses/', 'location');

		} else {
			$this->data['title'] = 'dScribe2 &raquo; Manage Courses'; 
			$this->data['sequences'] = $this->course->sequences();
			$this->data['curriculum'] = $this->course->curriculums();
			$this->data['courses'] = $this->ocw_user->get_courses(getUserProperty('id'));
     		$this->layout->buildPage('dscribe2/courses', $this->data);
		}
	}

	/**
     * Display dScribe2 dScribe dashboard 
     *
     * @access  public
     * @return  void
     */
	public function dscribes()
	{
		$this->data['title'] = 'dScribe2 &raquo; Manage dScribes'; 
     	$this->layout->buildPage('dscribe2/dscribes', $this->data);
	}


	/**
     * Check to see if a user is an instructor or not 
     *
     * @access  private
     * @param   int	course id		
     * @return  boolean
     */
	private function _isValidUser()
	{
		if (getUserProperty('role')=='dscribe2') {
			return true;

		} else {
			$msg = getUserProperty('name').', you\'re not a dScribe2'; 
			flashMsg($msg);
       		redirect('home/', 'location');
		}
		return false;
	} 
}
?>
