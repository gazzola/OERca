<?php
/**
 * Controller for postoffice
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Postservice extends Controller {

	public function __construct()
	{
		parent::Controller();
		$this->load->model('postoffice');
	}

	public function index() 
	{ 
		$details = array();

		if (isset($_REQUEST['sorttype'])) {
				list($k,$v) = split(':',$_REQUEST['sorttype']);
				if ($v != 'all') { $details[$k] = $v; }
		}

		$data['select_sorttypes'] = array( 'type:all' => 'all',
      																 'msg_type:dscribe1_to_dscribe2'=>'dscribe1 to dscribe2',
      																 'msg_type:dscribe1_to_instructor'=>'dscribe1 to instructor',
      															   'msg_type:instructor_to_dscribe1'=>'instructor to dscribe1',
      																 'msg_type:dscribe2_to_dscribe1'=>'dscribe2 to dscribe1',
      																 'sent:yes'=>'sent', 'sent:no'=>'pending');

		$data['title'] = 'Notification Center';
	  $data['queue'] = $this->postoffice->queue($details);
		$data['sorttype'] = (isset($_REQUEST['sorttype'])) ? $_REQUEST['sorttype'] : 'all';

    $this->layout->buildPage('postoffice/index', $data);
	}

	public function email_dscribe1s() { $this->postoffice->digest('to_dscribe1'); }
	public function email_dscribe2s() { $this->postoffice->digest('to_dscribe2'); }
	public function email_instructors() { $this->postoffice->digest('to_instructor'); }
}
?>
