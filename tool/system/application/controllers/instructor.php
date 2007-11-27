<?php
/**
 * Controller for Instructor section
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Instructor extends Controller {

	private $uid;
	private $data;

	public function __construct()
	{
		parent::Controller();

		$this->freakauth_light->check();

		$this->load->model('ocw_user');
		$this->load->model('course');
		$this->load->model('material');
	
		$this->uid = getUserProperty('id');	
		$this->data = array();
	}

	public function index($cid)
	{
		$this->home($cid);
	}

	/**
     * Display instructor dashboard 
     *
     * @access  public
     * @param   int	course id		
     * @return  void
     */
	public function home($cid)
	{
		// make sure user is an instructor
		if ($this->_isInstructor($cid)) { 
			$this->data['title'] = $this->lang->line('ocw_instructor');
       		$this->layout->buildPage('instructor/index', $this->data);
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
		$this->_isInstructor($cid); 

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

	public function add_material($cid, $materialId) 
	{
		//$this->material->add_material();
		//$this->_isInstructor($cid); 
		$this->data['title'] = $this->lang->line('ocw_ins_pagetitle_material');
		$categories = $this->material->categories();
		$categoriesMaterials = array();
		foreach ($categories as $category)
		{
			$materialList = $this->material->categoryMaterials($cid, '', $in_ocw=false, $as_listing=false, $category);
			$categoriesMaterials[$category] = $materialList;
		}
		$this->data['categories'] = $categories;
		$this->data['categoriesMaterials'] = $categoriesMaterials;
		$this->data['cid']=$cid;
       	$this->layout->buildPage('instructor/pick_materials', $this->data);
	}
	
	/**
     * store the picked materials
     *
     * @access  public	
     * @param   string	task		
     * @return  void
     */
	public function materials_option($task='') 
	{
		$task = $_POST['task'];
		if ($task == 'add')
		{
			$entityIds = $_POST['chooseItem'];
		   	$how_many = count($entityIds);
			echo 'entities chosen: '.$how_many.'<br><br>';
			$cid = $_POST['cid'];
			$user= $_POST['user'];
			$euid = $_POST['euid'];
			$site = $_POST['site'];
			$server = $_POST['server'];
			$sessionid = $_POST['sessionid'];
			$placement = $_POST['placement'];
			$role = $_POST['role'];
			$sign = $_POST['sign'];
			$time = $_POST['time'];
			$url = $_POST['url'];
			echo $url;
			$client = new SoapClient($url."ContentHosting.jws?wsdl");
			for ($i=0; $i<$how_many; $i++) 
			{
				$entityId = $entityIds[$i];
				echo $sessionid.' <br/>'.$entityId.'<br/>';
				print '<pre>'; print_r($client); print '</pre>';
				
				
				
				$resource=$client->getResources($sessionid, $entityId);
				echo 'another';
				$details = array(
								'cid' => $cid,
								'category' => "Resources",
								'name' => $resource.getTitle(),
								'content' => '',
								'author' => $resource.getResourceProperties().getProperty("CHEF:creator"),
								'tag_id' => '',	// empty tag for now
								'filetype_id' => $resource.getContentType(),
								'in_ocw' => 1,
								'embedded_ip' => '',
								'nodetpe' => 'child',
								'order' =>'',
								'modified' => '',
								'created_on' => '',
								'modified_on' => ''
							);
				echo 'another1';
				print '<pre>'; print_r($details); print '</pre>';
				$this->material->add_materials($details);
		    }
			echo "<br><br>";
		}
	}
	
	/**
     * Display pick materials page 
     *
     * @access  public
     * @param   int	course id		
     * @param   string	task		
     * @return  void
     */
	public function materials($cid, $task='') 
	{
		$this->_isInstructor($cid); 
		$this->data['title'] = $this->lang->line('ocw_ins_pagetitle_material');
		$categories = $this->material->categories();
		$categoriesMaterials = array();
		foreach ($categories as $category)
		{
			$materialList = $this->material->categoryMaterials($cid, '', $in_ocw=false, $as_listing=false, $category);
			$categoriesMaterials[$category] = $materialList;
		}
		$this->data['categories'] = $categories;
		$this->data['categoriesMaterials'] = $categoriesMaterials;
		$this->data['cid']=$cid;
       	$this->layout->buildPage('instructor/pick_materials', $this->data);
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
		$this->_isInstructor($cid); 
		$this->data['title'] = $this->lang->line('ocw_ins_pagetitle_review'); 
       	$this->layout->buildPage('instructor/review_course', $this->data);
	}

	/**
     * Check to see if a user is an instructor or not 
     *
     * @access  private
     * @param   int	course id		
     * @return  boolean
     */
	private function _isInstructor($cid)
	{
		if ($this->ocw_user->has_role($this->uid, $cid, 'instructor')) {
			$this->data['cid'] = $cid;
			$this->data['cname'] = $this->course->course_title($cid);
			return true;

		} else {
			$msg = preg_replace('/{NAME}/',getUserProperty('name'), 
					$this->lang->line('ocw_ins_error_notinstructor')); 
			flashMsg($msg);
       		redirect('home/', 'location');
		}
		return false;
	} 
}
?>
