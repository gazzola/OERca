<?php
/**
 * Controller for Guest section
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Guest extends Controller 
{
	public function __construct()
	{
		parent::Controller();

		$this->load->model('ocw_user');
		$this->load->model('material');
	}

	public function index()
	{
		$this->home();
	}

	/**
     * Display guest dashboard 
     *
     * @access  public
     * @return  void
     */
	public function home()
	{
    $data = array('title' => 'Progress', 
                  'role' => getUserProperty('role'),
                  'name' => getUserProperty('name'));

   	$this->layout->buildpage('guest/index', $data);
	}
}
?>
