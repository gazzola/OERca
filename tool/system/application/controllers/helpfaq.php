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

	public function __construct()
	{
		parent::Controller();	
	}
	
	public function index()
	{
		$data = array();
		$this->freakauth_light->check();
    $this->load->view('helpfaq');
	}
}
?>
