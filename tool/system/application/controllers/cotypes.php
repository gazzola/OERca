<?php
/**
 * Controller for Content Object types List
 *
 * @package	OER Tool		
 * @author  Michael Bleed <mbleed@umich.edu>
 * @date    april 2nd 2009
 */

class Cotypes extends Controller {

	/**
	 * Default constructor
	 */
	public function __construct()
	{
		parent::Controller();	
		$this->load->model('coobject');
	}
	
	
	/**
	 * Loads a view that lists content objects by type
	 */
	public function index($cotype)
	{
		$cos =  $this->coobject->coobjects_by_type($cotype);
		$count = sizeof($cos);
		$co_types = $this->coobject->coobject_types();
		$data = array('cos'=>$cos,'count'=>$count,'co_types'=>$co_types);
    	$this->load->view('cotypes', $data);
	}
}
?>
