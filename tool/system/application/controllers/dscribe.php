<?php
/**
 * Controller for dScribe section
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Dscribe extends Controller {

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
       		$this->layout->buildPage('dscribe/index', $this->data);
		}
	}

	/**
     * Display dScribe management page 
     *
     * @access  public
     * @param   int	course id		
     * @param   string	task		
     * @param   int	dscribe id		
     * @return  void
     */
	public function dscribes($cid, $task='', $did='') 
	{
		// make sure user is an instructor
		$this->_isValidUser($cid); 

		if (isset($_POST['task'])) {
			$task = $_POST['task'];
        	$name = $_POST['name'];
        	$uname = $_POST['username'];
        	$email = $_POST['email'];
        	$level = $_POST['level'];
		}

		// add new dScribe
        if ($task=='add_dscribe') {
            $error = '';

            if(($u = $this->ocw_user->exists($email)) !== false) {
                if ($this->ocw_user->is_dscribe($u['id'], $cid)) {
                    $error = preg_replace('/{NAME}/',
								  getUserProperty($u['id']),
								  $this->lang->line('ocw_ins_error_exists')); 
                    $error = preg_replace('/{EMAIL}/', $email, $error); 
					flashMsg($error);
       				redirect('instructor/dscribes/'.$cid, 'location');
                }
            } else {
                $u = $this->ocw_user->add_user($name, $uname, $email);
            }

            $this->course->add_user($u['id'], $cid, $level);
            
        } elseif ($task == 'remove') {
            $this->course->remove_user($did, $cid);
        }

		$this->data['dscribes'] = $this->course->dscribes($cid); 
		$this->data['title'] = $this->lang->line('ocw_ins_pagetitle_manage'); 
       	$this->layout->buildPage('instructor/manage_dscribes', $this->data);
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

		if ($task == 'update_material') {
			$data = array($field=>$val);
			$this->material->update($mid, $data);
			$this->ocw_utils->send_response('success');
			exit;

		} elseif ($task == 'edit_material') {
			$materials = $this->material->materials($cid, $mid, true);
			$this->data['material'] = $materials[0];
			$this->data['tags'] = $this->tag->tags(); 
			$this->data['filetypes'] = $this->file_type->filetypes(); 
			$this->data['categories'] = $this->material->categories(); 
			$this->data['title'] = $this->lang->line('ocw_ds_pagetitle_editmat'); 
       		$this->layout->buildPage('dscribe/edit_material', $this->data);

		} elseif ($task == 'add_material_comment') {
			$this->material->add_comment($mid, $this->uid, $_POST);
			$this->ocw_utils->send_response('success');
			exit;

		} elseif ($task == 'update_ip') {
			$data = array('material_id'=>$mid, $_POST['field']=>$_POST['val'], 'modified_by'=>$this->uid);
			$this->ipobject->update($_POST['oid'], $data);
			$this->ocw_utils->send_response('success');
			exit;

		} elseif ($task == 'add_ip') {
			$this->ipobject->add($mid, $this->uid, $_POST);
			$this->ocw_utils->send_response('success');
			exit;

		} elseif ($task == 'add_ip_comment') {
			$this->ipobject->add_comment($_POST['ipobject_id'], $this->uid, $_POST);
			$this->ocw_utils->send_response('success');
			exit;

		} elseif ($task == 'remove_ip') {
			$this->ipobject->remove($val);
       		redirect("dscribe/materials/$cid/view_ip/$mid", 'location');

		} elseif ($task == 'view_ip') {
			$materials = $this->material->materials($cid, $mid, true);
			$this->data['material'] = $materials[0];
			$this->data['filetypes'] = $this->file_type->filetypes(); 
			$this->data['ip_types'] = $this->ipobject->ip_types(); 
			$this->data['ip_uses'] = $this->ipobject->ip_uses(); 
			$this->data['ipobjects'] = $this->ipobject->ipobjects($mid); 
			$this->data['title'] = $this->lang->line('ocw_ds_pagetitle_viewip'); 
       		$this->layout->buildPage('dscribe/manage_ipobjects', $this->data);

		} elseif ($task == 'edit_ip') {
			$materials = $this->material->materials($cid, $mid, true);
			$this->data['material'] = $materials[0];
			$this->data['filetypes'] = $this->file_type->filetypes(); 
			$this->data['ip_types'] = $this->ipobject->ip_types(); 
			$this->data['ip_uses'] = $this->ipobject->ip_uses(); 
			$ipobject = $this->ipobject->ipobjects($mid,$field); 
			$this->data['ipobject'] = $ipobject[0];
			$this->data['title'] = $this->lang->line('ocw_ds_pagetitle_editip'); 
       		$this->layout->buildPage('dscribe/edit_ipobject', $this->data);

		} else {
			$this->data['tags'] = $this->tag->tags(); 
			$this->data['materials'] = $this->material->materials($cid,'',true,true); 
			$this->data['title'] = $this->lang->line('ocw_ds_pagetitle_material'); 
       		$this->layout->buildPage('dscribe/manage_materials', $this->data);
		}
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
       	$this->layout->buildPage('dscribe/set_profiles', $this->data);
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

		if (isset($_POST['task'])) {
			$task = $_POST['task'];
        	$name = $_POST['copyholder'];
		}

		if ($task == 'update_copy') {
			if ($name<>'') {
				$data = array('director'=>$name);
				$this->course->update_course($cid, $data);
				$this->data['course'] = $this->course->details($cid);
			} else {
				flashMsg($this->lang->line('ocw_ds_error_nocopy'));
       			redirect('dscribe/copyright/'.$cid, 'location');
			}
		}

		$this->data['title'] = $this->lang->line('ocw_ds_pagetitle_copy'); 
       	$this->layout->buildPage('dscribe/set_copyright', $this->data);
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
       	$this->layout->buildPage('dscribe/edit_tags', $this->data);
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
       	$this->layout->buildPage('dscribe/review_course', $this->data);
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
			$this->coursedetails = $this->course->details($cid);
			$this->data['course'] = $this->coursedetails;
			$this->data['cname'] = $this->coursedetails['number'].' '.$this->coursedetails['title'];
			return true;

		} else {
			$msg = preg_replace('/{NAME}/',getUserProperty('name'), 
					$this->lang->line('ocw_ds_error_noaccess')); 
			flashMsg($msg);
       		redirect('home/', 'location');
		}
		return false;
	} 
}
?>
