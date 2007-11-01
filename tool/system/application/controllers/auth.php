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
		$this->CI = $this->freakauth_light;

		if ($_SERVER['QUERY_STRING'] <> '') { // coming from Sakai??
			// get user role, user name and site id
			$role = ($_REQUEST['role']=='maintain') ? 'instructor' : $_REQUEST['role'];
			$user = $_REQUEST['user'];
			$site = $_REQUEST['site'];
			$session = $_REQUEST['session'];
			$internaluser = $_REQUEST['internaluser'];

			if (($userdata=$this->ocw_user->get_user($user)) !== false) {
				 $userdata['sessionid'] = $session;
				 $userdata['internaluser'] = $internaluser;
				 $userdata['site'] = $site;
    			$this->CI->_set_logindata($userdata);
				redirect('home','location');
			} else {
				//$this->ocw_utils->dump($_SERVER);
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
