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
        
        $this->load->library('FAL_front', 'fal_front');
        
        //$this->_container = $this->config->item('FAL_template_dir').'template/container';
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
