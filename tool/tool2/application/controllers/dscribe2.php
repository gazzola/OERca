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

		$this->freakauth_light->check('dscribe2');

		$this->load->model('course');
		$this->load->model('material');
		$this->load->model('ocw_user');
		$this->load->model('mimetype');
	
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
		$this->data['title'] = 'dScribe2'; 
		$this->data['breadcrumb'] = $this->breadcrumb(); 
    	$this->layout->buildPage('dscribe2/index', $this->data);
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
				$d = array('title'=>$_POST['val'],'description'=>'');
				$curr_id = $this->course->new_curriculum($d);
				$_POST['field'] = 'curriculum_id';
				$_POST['val'] = $curr_id;
			}
			if ($_POST['field']=='new_sequence_id') {
				$d = array('name'=>$_POST['val']);
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
			$this->data['course'] = $this->course->get_course($cid);
			$this->data['sequences'] = $this->course->sequences();
			$this->data['curriculum'] = $this->course->curriculums();
			$this->data['title'] = 'dScribe2 &raquo; Manage Courses &raquo; Edit'; 
       		$this->layout->buildPage('dscribe2/edit_course', $this->data);

		} elseif ($task =='review') {
       		$this->layout->buildPage('dscribe2/review', $this->data);

		} elseif ($task =='remove') {
			flashMsg('The course would be removed at this time, but this is only a demo');
       		redirect('dscribe2/courses/', 'location');

		} else {
			$this->data['title'] = 'dScribe2 &raquo; Manage Courses'; 
			$this->data['courses'] = $this->course->get_courses();
			$this->data['breadcrumb'] = $this->breadcrumb('courses'); 
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
		$this->data['breadcrumb'] = $this->breadcrumb('dscribes'); 
     	$this->layout->buildPage('dscribe2/dscribes', $this->data);
	}

	public function breadcrumb($section='default')
	{
		$breadcrumb = array();

		$breadcrumb[] = array('url'=>site_url(), 'name'=>'Home');
		$breadcrumb[] = array('url'=>site_url('dscribe2'), 'name'=>'dScribe2 Interface');

		if ($section == 'courses') {
			$breadcrumb[] = array('url'=>'', 'name'=>'Manage Courses');
		} elseif($section == 'dscribes') {
			$breadcrumb[] = array('url'=>'', 'name'=>'Manage dScribes');
		}
		return $breadcrumb;
	}
}
?>
