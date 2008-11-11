<?php
/**
 * Controller for Help/FAQ Page
 *
 * @package	OER Tool		
 * @author  Ali Asad Lotia <lotia@umich.edu>
 * @date    Febrary 28 2008
 * @copyright Copyright (c) 2006, University of Michigan
 */

class HelpFaq extends Controller {

	/**
	 * Default constructor
	 */
	public function __construct()
	{
		parent::Controller();	
	}
	
	
	/**
	 * Loads a view that pops up a window or creates a new tab
	 * (depending on browser settings) with links to help resources
	 */
	public function index()
	{
		$data = array();
		$this->freakauth_light->check();
    $this->load->view('helpfaq');
	}
	
	
	/**
	 * Loads the interactive DHTML recommended action form that
	 * Kathleen Ludewig wrote.
	 */
	public function rad_form()
	{
	  $data = array();
	  $this->freakauth_light->check();
	  $this->load->view('rad_form');
	}
}
?>
