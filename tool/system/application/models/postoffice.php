<?php
/**
 * Provides access to email information 
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Postoffice extends Model 
{
	public function __construct()
	{
		parent::Model();

    $this->load->model('ocw_user');
    $this->load->model('material');
    $this->load->model('course');
	}
	
	/**
   * send a message from the dscribe1 to the dscribe2
	 *
	 * @access  public
	 * @param		cid 			course id
	 * @param		mid 			material id
	 * @param		oid 			object id
	 * @param		type 			object type (replacement|original)
	 * @return  boolean
	 */
	public function dscribe1_dscribe2_email($cid, $mid, $oid, $type)
	{
     $from_id = getUserProperty('id');
		
		 if ($this->ocw_user->get_user_by_id($from_id) !== false) {	
		 		 $dscribe2 = $this->ocw_user->get_users_by_relationship($from_id,'dscribe2'); 

     		 if ($dscribe2 !== false) {
							foreach ($dscribe2 as $to_id) {
											 $this->add($from_id, $to_id, 'dscribe1_to_dscribe2', $cid, $mid, $oid, $type);
							}
     		 } else {
						return 'Error: Cannot find dscribe2s associated with this dscribe.';
				 }
		 } else {
				return 'Error: Cannot find dscribe1\'s information in the system';
		 }

		return true;
	}

	/**
   * send a message from the dscribe2 to the dscribe1
	 *
	 * @access  public
	 * @param		cid 			course id
	 * @param		mid 			material id
	 * @param		oid 			object id
	 * @param		type 			object type (replacement|original)
	 * @return  void
	 */
	public function dscribe2_dscribe1_email($cid, $mid, $oid, $type) 
	{
     $from_id = getUserProperty('id');
		
		 if ($this->ocw_user->get_user_by_id($from_id) !== false) {	
		 		 $dscribe1 = $this->ocw_user->get_users_by_relationship($from_id,'dscribe1'); 

     		 if ($dscribe1 !== false) {
							foreach ($dscribe1 as $to_id) {
											 $this->add($from_id, $to_id, 'dscribe2_to_dscribe1', $cid, $mid, $oid, $type);
							}
     		 } else {
						return 'Error: Cannot find dscribe1s associated with this dscribe.';
				 }
		 } else {
				return 'Error: Cannot find dscribe2\'s information in the system';
		 }

		 return true;
	}

	/**
   * send a message from the instructor to the dscribe1
	 *
	 * @access  public
	 * @param		cid 			course id
	 * @param		mid 			material id
	 * @param		oid 			object id
	 * @param		type 			object type (replacement|original)
	 * @return  void
	 */
  public function instructor_dscribe1_email($cid, $mid, $oid, $type) 
	{
     $from_id = getUserProperty('id');
		
		 if ($this->ocw_user->get_user_by_id($from_id) !== false) {	
		 		 $dscribe1 = $this->ocw_user->get_users_by_relationship($from_id,'dscribe1',$cid); 

     		 if ($dscribe1 !== false) {
							foreach ($dscribe1 as $to_id) {
											 $this->add($from_id, $to_id, 'instructor_to_dscribe1', $cid, $mid, $oid, $type);
							}
     		 } else {
						return 'Error: Cannot find dscribe1s associated with this dscribe.';
				 }
		 } else {
				return 'Error: Cannot find instructor\'s information in the system';
		 }

		 return true;
	}

	/**
   * Send an email digest out -- meant to be called from cron 
	 *
	 * @access  public
	 * @param		string  type type of digest	(to_dscribe1|to_dscribe2|to_instructor) 
	 * @return  results
	 */
	public function digest($type)
	{
			$from = array('email'=>'nobody@umich.edu', 'name'=>'OER Tool Notifier');
			$subject = '[OER NOTICE] Action Items for '.date('d M, Y');

			if (in_array($type, array('to_dscribe1','to_dscribe2','to_instructor'))) {	
					// get all the unsent mail for this digest type */
					$mail = $this->queue("msg_type LIKE '%$type' AND sent='no'");

					if ($mail != null) {
							$sendlist = array();

							// group emails by who its to, the receiver, the course and material in question
							foreach($mail as $m) {
											if (!in_array($m->to_id, array_keys($sendlist))) { $sendlist[$m->to_id] = array(); }
											if (!in_array($m->from_id, array_keys($sendlist[$m->to_id]))) { 
														$sendlist[$m->to_id][$m->from_id] = array(); 
											}
											if (!in_array($m->course_id, array_keys($sendlist[$m->to_id][$m->from_id]))) { 
														$sendlist[$m->to_id][$m->from_id][$m->course_id] = array(); 
											}
											if (!in_array($m->material_id, array_keys($sendlist[$m->to_id][$m->from_id][$m->course_id]))) { 
														$sendlist[$m->to_id][$m->from_id][$m->course_id][$m->material_id] = array(); 
											}
											array_push($sendlist[$m->to_id][$m->from_id][$m->course_id][$m->material_id], $m);	
							}


							foreach($sendlist as $to_id => $receivers) {
											$to_user = $this->ocw_user->get_user_by_id($to_id); 
											$msg = preg_replace('/{TO_NAME}/',$to_user['name'], $this->template('intro'));	
											$update_candidates = array();

											foreach($receivers as $from_id => $courses) {	
															$from_user = $this->ocw_user->get_user_by_id($from_id); 
														  $msg .= preg_replace('/{FROM_NAME}/', $from_user['name'], $this->template('body1'));	
														  $msg = preg_replace('/{FROM_ROLE}/', $from_user['role'], $msg);	

															foreach($courses as $cid => $materials) {	
																		 $cname = $this->course->course_title($cid);
																		 $msg .= preg_replace('/{CNAME}/', $cname, $this->template('body2'));	

																		 $items = '';
																		 foreach($materials as $mid => $messages) {
																		  			 $mname = $this->material->getMaterialName($mid);

																						 foreach($messages as $m) {
																										 array_push($update_candidates, $m->id);
																										 switch($m->msg_type) {
																												case 'dscribe1_to_instructor': $items .= "\n\t$mname -- {unwrap}".
																																			site_url("materials/askforms/$cid/$mid").'{/unwrap}';
																																			break;
																												case 'dscribe1_to_dscribe2':  $items .= "\n\t$mname -- {unwrap}".
																																			site_url("materials/askforms/$cid/$mid").'{/unwrap}';
																																			break;
																												case 'dscribe2_to_dscribe1':  $items .= "\n\t$mname -- {unwrap}".
		    																															site_url("materials/askforms/$cid/$mid/aitems/dscribe2").'{/unwrap}';
																																			break;
																												case 'instructor_to_dscribe1':  $items .= "\n\t$mname -- {unwrap}".
		    																															site_url("materials/askforms/$cid/$mid/done/instructor").'{/unwrap}';
																																			break;
																										 }
																						 }
																		 }
																		 $msg = preg_replace('/{ITEMS}/', $items, $msg);	
															}	
											}
											$msg .= $this->template('footer');	

											// mark message as sent
											if ($this->send($from, $to_user['email'], $subject, $msg)) {
													foreach($update_candidates as $id) { $this->update_queue($id,array('sent'=>'yes'));}	
											}
							}
					}
			} else {
					return 'Error: cannot determine the type of digest being requested';
			}

			return true;
	}

	/**
   * return a list of the emails in the queue 
	 *
	 * @access  public
	 * @param		array w 	search_criteria	 
	 * @return  results
	 */
	public function queue($w='')
	{
		$emails = array();
		$where = (is_array($w) || $w<>'') ? $w : '1=1';
		$this->db->select('*')->from('postoffice')->where($where);
		$q = $this->db->get();
		if ($q->num_rows() > 0) { foreach ($q->result() as $row) { array_push($emails, $row); } }
		return (sizeof($emails)) ? $emails : null;
	}

	/**
   * update queue 
	 *
	 * @access  public
	 * @return  results
	 */
	public function update_queue($id, $details)
	{
		$time = $this->ocw_utils->get_curr_mysql_time();
		$details['modified_on'] = $time;
		return $this->db->update('postoffice', $details,"id=$id");
	}

	/**
   * add a message to be sent to queue 
	 *
	 * @access  private
	 * @param		from_id   id of sending user	
	 * @param		to_id 	 	id of receiving user	
	 * @param		cid 			course id
	 * @param		mid 			material id
	 * @param		oid 			object id
	 * @param		type 			object type (replacement|original)
	 * @param		msg_type 	message type (dscribe1_to_dscribe2|dscribe1_to_instructor|dscribe2_to_dscribe1|
	 *										instructor_to_dscribe1)
	 * @return  void
	 */
  private function add($from_id, $to_id, $msg_type, $cid, $mid, $oid, $type)
  {
		$time = $this->ocw_utils->get_curr_mysql_time();
		$details = array('from_id'=>$from_id, 
										 'to_id'=>$to_id, 
										 'msg_type'=>$msg_type, 
										 'course_id'=>$cid, 
										 'material_id'=>$mid, 
										 'object_id'=>$oid, 
										 'object_type'=>$type, 
										 'created_at'=>$time, 
										 'modified_on'=>$time);
		$this->db->insert('postoffice', $details);
	}

	/**
   * add a message to be sent to queue 
	 *
	 * @access  private
	 * @param		from_info array of sender's information 
	 * @param		to_email  email to send it to	
	 * @param		subject 	message subject
	 * @param		msg 			message to send	
	 *
	 * @return  boolean
	 */
	private function send($from_info, $to_email, $subject, $msg)
	{
		 $this->email->clear();
     $this->email->from($from_info['email'], $from_info['name']);
     $this->email->to('dkhutch@umich.edu');
     //$this->email->to($to_email);
     $this->email->subject($subject);
     $this->email->message($msg);
     return $this->email->send();
	}

	/** templates for email messages */
	private function template($type)
	{
			$template['intro'] = "Dearest {TO_NAME},\n\nYou have ASK Form items from the following people that need your attention:\n\n";
			$template['body1'] = "{FROM_NAME} ({FROM_ROLE}):\n\n"; 
			$template['body2'] = "  Course: {CNAME}{ITEMS}\n\n\n"; 
		  $template['footer'] = "Ciao!\n\nOER Tool\n\nps: Don't reply to this email -- it will go no where :)";

			return $template[$type];
	}
}
?>
