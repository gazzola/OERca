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

		$this->freakauth_light->check('instructor');

		$this->load->model('ocw_user');
		$this->load->model('course');
		$this->load->model('material');
		$this->load->model('mimetype');
		$this->load->model('instructors');
		$this->load->model('course');
	
		$this->uid = getUserProperty('id');	
		$this->data = array();
		
		$this->load->helper('file');
		$this->load->helper('download');
	}

	public function index($cid='') { $this->home($cid); }

	/**
     * Display instructor dashboard 
     *
     * @access  public
     * @return  void
     */
	public function home($cid='')
	{
		// make sure user is an instructor
		$this->_isInstructor($cid); 
		$this->data['title'] = $this->lang->line('ocw_instructor');
   	$this->layout->buildPage('instructor/index', $this->data);
	}

	/**
     * Display manage materials page 
     *
     * @access  public
     * @param   int	course id		
     * @return  void
     */
	public function manage_materials($cid, $task='') 
	{
		$this->_isInstructor($cid); 
		$this->data['title'] = $this->lang->line('ocw_ins_pagetitle_managemat'); 
   	$this->layout->buildPage('instructor/manage_materials', $this->data);
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

		if (isset($_POST['task'])) { $task = $_POST['task']; }

		// add new dScribe
   if ($task=='add_dscribe') {
			 $r = $this->ocw_user->add_dscribe($cid, $_POST);
			 if ($r!==true) {
			 		flashMsg($r);
       		redirect('instructor/dscribes/'.$cid, 'location');
			}
   } elseif ($task == 'remove') {
			$this->ocw_user->remove_dscribe($cid, $did);
   }

		$this->data['dscribes'] = $this->ocw_user->dscribes($cid); 
		$this->data['title'] = $this->lang->line('ocw_ins_pagetitle_manage'); 
   	$this->layout->buildPage('instructor/manage_dscribes', $this->data);
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
		if ($task == 'download')
		{
			$entityIds = $_POST['chooseDownloadItem'];
		   	$how_many = count($entityIds);
			for ($i=0; $i<$how_many; $i++) 
			{
				$entityId = $entityIds[$i];
				$name = $this->material->getMaterialName($entityId);
				$data = file_get_contents(getcwd().'/uploads/'.$name); // Read the file's contents
				force_download($name, $data);			
			}
		}
		else if ($task == 'add')
		{
			$entityIds = $_POST['chooseItem'];
		   	$how_many = count($entityIds);
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
			// first decode the session id
			$client = new SoapClient($url."SiteItem.jws?wsdl");
			$session = $client->touchsession($sessionid);
			print_r($session);
			$decodedSessionId = $client->touchsessionid($sessionid);
			
			for ($i=0; $i<$how_many; $i++) 
			{
				
				
				$entityId = $entityIds[$i];
				$entityInfoXML = $client->getResourceInfo($decodedSessionId, $entityId);
				$doc = new DOMDocument();
				$doc->loadXML($entityInfoXML);
				$entities = $doc->getElementsByTagName("resource");
				foreach($entities as $entity)
				{
					$entityIds = $entity->getElementsByTagName("id");
					$entityId = $entityIds->item(0)->nodeValue;
					$entityRelativeIds = $entity->getElementsByTagName("relativeId");
					$entityRelativeId = $entityRelativeIds->item(0)->nodeValue;
					$entityNames = $entity->getElementsByTagName("name");
					$entityName = $entityNames->item(0)->nodeValue;
					$entityUrls = $entity->getElementsByTagName("url");
					$entityUrl = $entityUrls->item(0)->nodeValue;
					$entityTypes = $entity->getElementsByTagName("type");
					$entityType = $entityTypes->item(0)->nodeValue;
					$entityCreators = $entity->getElementsByTagName("creator");
					$entityCreator = $entityCreators->item(0)->nodeValue;
					$entityCreatedOns = $entity->getElementsByTagName("createdOn");
					$entityCreatedOn = $entityCreatedOns->item(0)->nodeValue;
					$entityModifiedOns = $entity->getElementsByTagName("modifiedOn");
					$entityModifiedOn = $entityModifiedOns->item(0)->nodeValue;
					
					$entityTypeId = $this->mimetype->getMimetypeId($entityType);
					
					// use curl to get the resource conent and write to local drive
					$ch = curl_init();
					$filePath=getcwd().'/uploads/'.$entityName;
					$fp = fopen($filePath, "w");
					curl_setopt($ch, CURLOPT_URL, $entityUrl);
					curl_setopt ($ch, CURLOPT_COOKIE, $_SERVER["HTTP_COOKIE"]."; Path=/");
					curl_setopt($ch, CURLOPT_HEADER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_FILE, $fp);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					
					curl_exec($ch);
					curl_close($ch);
					fclose($fp);
					
					$details = array(
									'course_id' => $cid,
									'category' => "Materials",// the category name for resources
									'name' => $entityName,
									'ctools_url' => $entityUrl,
									'author' => $entityCreator,
									'collaborators' => '',
									'tag_id' => '8',	// use this tag for now
									'mimetype_id' => $entityTypeId,
									'in_ocw' => 1,
									'embedded_co' => '',
									'nodetype' => 'child',
									'parent' => '',
									'modified' => '',
									'created_on' => date('Y-m-d h:i:s', $entityCreatedOn),
									'modified_on' => date('Y-m-d h:i:s', $entityModifiedOn)
								);
					$addMaterialId=$this->material->add_material($details);
				}
		    }
		    
			//need to separate out as another function
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
	}
	

	/**
     * Check to see if a user is an instructor or not 
     *
     * @access  private
     * @param   int	course id		
     * @return  boolean
     */
	private function _isInstructor($cid='')
	{
		if ($cid <> '') {
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
		} elseif (getUserProperty('role') == 'instructor') {
				return true;
		}

		return false;
	}
	
	
	/**
	 * Edit instructor info
	 *
	 * @access  public
	 * @param   int cid
	 * @return  void
	 */
	// TODO: reduce the number of DB calls. FIX THE DB SCHEMA!
	public function edit_inst_info($cid)
	{
	  $data = NULL;
	  $inst_id;
	  $courseinfo = $this->course->get_course($cid);
	  $rules = array(
	    'name' => "minlength[1]|maxlength[255]|xssclean",
	    'title' => "maxlength[400]|xssclean",
	    'information' => 'maxlength[800]|xssclean',
	    'uri' => 'maxlength[255]|xssclean'
	    );
	    
	  $fields = array(
	    'name' => "Name",
	    'title' => "Title",
	    'info' => "Information",
	    'uri' => "URI"
	    );
	    
	    $this->validation->set_rules($rules);
	    $this->validation->set_fields($fields);
	    if ($this->validation->run() == FALSE) {
	      $role = getUserProperty('role');
	      flashMsg($this->validation->error_string);
	      redirect("materials/home/$cid/$role/editinst", 'location');
	    } else if (!$courseinfo['instructor_id']) {
	      if ($_POST['name']) {
	        $this->instructors->add_inst_to_course($_POST['name'],$cid);
	        // get course info again now that an instructor_id is defined
	        $courseinfo = $this->course->get_course($cid);
        }
      } else if ($courseinfo['instructor_id']) {
        if ($_POST['name']) {
          $data['name'] = $_POST['name'];
        }
	      if ($_POST['title']) {
	        $data['title'] = $_POST['title'];
        } else $data['title'] = $_POST['title'];
        if ($_POST['info']) {
	        $data['info'] = $_POST['info'];
        } else $data['info'] = '';
        if ($_POST['uri']) {
	        $data['uri'] = $_POST['uri'];
        } else $data['uri'] = '';
        $this->instructors->update_inst($courseinfo['instructor_id'], $data);
        $msg = "Edited the instructor info";
    	  flashMsg($msg);
        redirect("materials/home/$cid", "location");
	    }
	}

  /**
     * Display dScribe1 course dashboard 
     *
     * @access  public
     * @param string task 
     * @param int  course id 
     * @return  void
     */
  public function courses()
  {
		  $data = array();
      $data['title'] = 'Instructor &raquo; Manage Courses';
      $data['courses'] = $this->ocw_user->get_courses(getUserProperty('id'));
      $this->layout->buildPage('instructor/courses', $data);
	}
}
?>
