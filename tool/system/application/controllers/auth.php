<?php
/**
 * Auth Controller Class
 *
 * Security controller that provides functionality to handle logins, logout, registration
 * and forgotten password requests.  
 * It also can verify the logged in status of a user and his permissions.
 *
 * The class requires the use of the DB_Session and FreakAuth libraries.
 *
 * @package     FreakAuth_light
 * @subpackage  Controllers
 * @category    Authentication
 * @author      Daniel Vecchiato (danfreak)
 * @copyright   Copyright (c) 2007, 4webby.com
 * @license		http://www.gnu.org/licenses/lgpl.html
 * @link 		http://4webby.com/freakAuth
 * @version 	1.1
 *
 */

class Auth extends Controller
{	
	/**
	 * Initialises the controller
	 *
	 * @return Auth
	 */
    function Auth()
    {
        parent::Controller();
        
		$this->load->model('ocw_user');
		$this->load->model('course');
		$this->CI = $this->freakauth_light;
		if ($_SERVER['QUERY_STRING'] <> '') { // coming from Sakai??
			// get user role, user name and site id
			$role = ($_REQUEST['role']=='maintain') ? 'instructor' : ($_REQUEST['role']=='access' ? 'dscribe1':$_REQUEST['role']);
			$userId = $_REQUEST['user'];
			$username = $_REQUEST['username'];
			$useremail = $_REQUEST['useremail'];
			$site = $_REQUEST['site'];
			$serverurl = $_REQUEST['serverurl'];
			$sakaisession = $_REQUEST['session'];
			$internaluser = $_REQUEST['internaluser'];
			$courseTitle = $_REQUEST['courseTitle'];
			$courseDescription = $_REQUEST['courseDescription'];
			$courseNumber = $_REQUEST['courseNumber'];
			$courseStartDate = $_REQUEST['courseStartDate'];
			$courseEndDate = $_REQUEST['courseEndDate'];
			$courseDirector = $_REQUEST['courseDirector'];
			
			// construct new data to store in session
			$newsessiondata = array (
					'role' => $role,
					'userId' => $userId,
					'username' => $username,
					'useremail' => $useremail,
					'site' => $site,
					'serverurl' => $serverurl,
					'sakaisession' => $sakaisession,
					'internaluser' => $internaluser,
					'courseTitle' => $courseTitle,
					'courseDescription' => $courseDescription,
					'courseNumber' => $courseNumber,
					'courseStartDate' => $courseStartDate,
					'courseEndDate' => $courseEndDate,
					'courseDirector' => $courseDirector
			);
			$this->db_session->set_userdata($newsessiondata);
			
			
			
			if (($userdata=$this->ocw_user->get_user($userId)) == false) {
				$this->ocw_user->add_user($username, $userId, $useremail);
			}
			if (($userdata=$this->ocw_user->get_user($userId)) !== false) {
				 $userdata['sessionid'] = $sakaisession;
				 $userdata['internaluser'] = $internaluser;
				 $userdata['site'] = $site;
				 
				$this->CI->_set_logindata($userdata);
				
				if (($userCourses=$this->ocw_user->get_courses($userdata['id'])) == null)
				{
					
					print "try to add course";
					// if there is no course, add the current one as the first course
					$courseDetails['newc'] = $courseTitle;
					$courseDetails['curriculum'] = 'new';
					$courseDetails['description'] = $courseDescription;
					$courseDetails['sequence']='new';
					$courseDetails['news'] = 'new';
					$courseDetails['cnumber']=$courseNumber;
					$courseDetails['ctitle'] = $courseTitle;
					$courseDetails['sdate'] = $courseStartDate;
					$courseDetails['edate'] = $courseEndDate;
					$courseDetails['class'] = $courseTitle;
					$courseDetails['director'] = $courseDirector;
					$courseDetails['collabs']='';
					$courseDetails['dscribe']=0;

					// add course
					$this->course->new_course($courseDetails);
					// add user role for the course
					if(($u = $this->ocw_user->existsByUserName($userId)) !== false)
					{
						if (($c = $this->course->existsByNumber($courseNumber)) !== false)
						{
							$this->course->add_user($u['id'], $c['id'], $role);		
						}
					}
				}
				
				redirect('home','location');
			}
			else
			{
				exit;
			}
		}

        $this->load->library('FAL_front', 'fal_front');
        $this->_container = 'auth/login/content';
    }
	
    // --------------------------------------------------------------------
	
    /**
     * Displays the login form.
     *
     */
    function index()
    {	    	
    	$this->login();    
    }
    
    // --------------------------------------------------------------------
    
	/**
     * Displays the login form.
     *
     */
    function login()
    {	    	
    	$data['fal'] = $this->fal_front->login();
		$data['title'] = 'Login';
        $this->layout->buildPage($this->_container, $data);
    }

    // --------------------------------------------------------------------
    
    /**
     * Handles the logout action.
     *
     */
    function logout()
    {
        $this->fal_front->logout();
    }
    
	// --------------------------------------------------------------------
	
    /**
     * Handles the post from the registration form.
     *
     */
    
    function register()
    {	        
    	//displays the view
    	$data['fal'] = $this->fal_front->register();
		$data['title'] = 'Register';
        $this->layout->buildPage($this->_container, $data);

    	//$this->output->enable_profiler(TRUE);

    }
    
    // --------------------------------------------------------------------
    
    /**
     * Handles the user activation.
     *
     */
    function activation()
    {	
		$data['fal'] = $this->fal_front->activation();
		$data['title'] = 'Account Activation';
        $this->layout->buildPage($this->_container, $data);
    }
    
	// --------------------------------------------------------------------
	
    /**
     * Handles the post from the forgotten password form.
     *
     */
    function forgotten_password()
    {	
    	$data['heading'] = $this->lang->line('FAL_forgotten_password_label');
    	$data['fal'] = $this->fal_front->forgotten_password();
		$data['title'] = 'Forgot password';
        $this->layout->buildPage($this->_container, $data);
    }
    
	// --------------------------------------------------------------------
	
    /**
     * Displays the forgotten password reset.
     *
     */
    function forgotten_password_reset()
    {	
       $data['heading'] = $this->lang->line('FAL_forgotten_password_label');
       $data['fal'] = $this->fal_front->forgotten_password_reset();
	   $data['title'] = 'Password Reset';
       $this->layout->buildPage($this->_container, $data);
    }

    
    // --------------------------------------------------------------------
    
    /**
     * Function that handles the change password procedure
     * needed to let the user set the password he wants after the
     * forgotten_password_reset() procedure
     *
     */
    function changepassword()
    {
       $data['heading'] = $this->lang->line('FAL_change_password_label');
       $data['fal'] = $this->fal_front->changepassword();
	   $data['title'] = 'Change Password';
       $this->layout->buildPage($this->_container, $data);
    }
}
?>
