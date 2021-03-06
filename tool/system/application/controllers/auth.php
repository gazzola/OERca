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
	 		
	 		parse_str($_SERVER['QUERY_STRING'], $GET_ARRAY);
	        $sign = preg_replace('/&credentials=.*/', '', $_SERVER['QUERY_STRING']);
	        $url = $GET_ARRAY['serverurl'];
	        
	      // 	print("<pre>");print_r($GET_ARRAY);print("</pre>");
	        $time=$GET_ARRAY['time'];
	        parse_str($GET_ARRAY['credentials'], $credentials);
	        
            $proxy = new SoapClient($url . '/sakai-axis/SakaiSigning.jws?wsdl', array('trace' => 1));
            $result = $proxy->testsign($sign);
            if ($result == 'true') {
                $this->db_session->set_userdata('role', $GET_ARRAY['role']);
                $this->db_session->set_userdata('sakaidata', $GET_ARRAY);
                $this->db_session->set_userdata('credentials', $credentials);
                $jsession = $credentials['JSESSIONID'];
                $pieces = explode('.', $jsession);
                if (count($pieces) == 2) {
                    $this->db_session->set_userdata('session', $pieces[0]);
                    $this->db_session->set_userdata('server', $pieces[1]);
                }
                
                // get user role, user name and site id
				if ($_REQUEST['role']=='maintain' || $_REQUEST['role']=='Instructor')
				{ 
					$role = 'instructor';
				}
				else
				{
					// defaults to dscribe1 for now.
					$role = 'dscribe1';
				}
				/* Zhen's CTools integration code */
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
					log_message('error', "no such user ".$userId);
					$newUserDetails['name'] = $username;
					$newUserDetails['user_name'] = $userId;
					$newUserDetails['email'] = $useremail;
					$newUserDetails['role']=$role;
					$this->ocw_user->add_user($newUserDetails);
					log_message('error', "added user ".$userId);
				}
				log_message('error', "user exist ".$userId);
				if (($userdata=$this->ocw_user->get_user($userId)) !== false) {
					 $userdata['sessionid'] = $sakaisession;
					 $userdata['internaluser'] = $internaluser;
					 $userdata['site'] = $site;
					 
					$this->CI->_set_logindata($userdata);
					
					$course = $this->course->get_course_by_title($courseTitle);
					if ( $course == null)
					{
						log_message('error', "no such course ".$courseTitle);
						// if there is no course, add the current one as the first course
						// if there is no course, add the current one as the first course
						$courseDetails['number']='';
						$courseDetails['title'] = $courseTitle;
						$courseDetails['start_date'] = $courseStartDate;
						$courseDetails['end_date'] = $courseEndDate;
						//$courseDetails['curriculum_id'] = '1';
						$courseDetails['director'] = $courseDirector;
						$courseDetails['collaborators']='';
	
						// add course
						$course=$this->course->new_course($courseDetails);
						log_message('error', "added course ".$courseTitle);
						
						if ($role == 'instructor')
						{
							// if this is an instructor, add this instructor to course
							$this->instructors->add_inst_to_course($username, $course['id']);
						}
					}
					log_message('error', "course exist".$courseTitle);
					
					// add user role for the course
					if(($u = $this->ocw_user->existsByUserName($userId)) != false)
					{
						log_message('error', " exist user ".$userId);
						// whether this user has given role in the course
						if (!$this->ocw_user->has_role($u['id'], $course['id'], $role))
						{
							log_message('error', "user not in class".$u['id'].$course['id'].$role);
							// add user with role into given course
							$userDetails['user_id'] = $u['id'];
							$userDetails['course_id'] = $course['id'];
							$userDetails['role'] = $role; 
							$this->course->add_user($userDetails);
						}
						log_message('error', "user in class ".$u['id'].$course['id'].$role);
					}
					
					$courseId= $course['id'];
					log_message('error', "redirect to "."$role/materials/$courseId");
					redirect("$role/materials/$courseId",'location');
				}
				else
				{
					exit;
				}
            }
        }
 
 
        $this->load->library('FAL_front', 'fal_front');
        $this->_container = 'auth/login/content';
    }
	
	public function breadcrumb($section='default')
	{
		$breadcrumb = array();

		$breadcrumb[] = array('url'=>site_url(), 'name'=>'Home');

		if ($section == 'default') {
			$breadcrumb[] = array('url'=>'', 'name'=>'Login');
		} elseif ($section == 'forgot') {
			$breadcrumb[] = array('url'=>'', 'name'=>'Forgotten Password');
		} elseif ($section == 'forgotrest') {
			$breadcrumb[] = array('url'=>'', 'name'=>'Reset Password');
		} elseif ($section == 'passchange') {
			$breadcrumb[] = array('url'=>'', 'name'=>'Change Password');
		}
		return $breadcrumb;
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
		$data['breadcrumb'] = $this->breadcrumb();
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
		$data['breadcrumb'] = $this->breadcrumb('forgot');
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
	   $data['breadcrumb'] = $this->breadcrumb('forgotreset');
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
	   $data['breadcrumb'] = $this->breadcrumb('passchange');
       $this->layout->buildPage($this->_container, $data);
    }
}
?>
