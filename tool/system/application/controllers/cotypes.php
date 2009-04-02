<?php
/**
 * Controller for Content Object types List
 *
 * @package	OER Tool		
 * @author  Michael Bleed <mbleed@umich.edu>
 * @date    april 2nd 2009
 */

class cotypes extends Controller {

	/**
	 * Default constructor
	 */
	public function __construct()
	{
		parent::Controller();	
	}
	
	
	/**
	 * Loads a view that lists content objects by type
	 */
	public function index()
	{
		$data = array();
		$this->freakauth_light->check();
    	$this->load->view('cotypes');
	}
}
?>
