<?php
/**
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Coobject extends Model 
{
	public function __construct()
	{
		parent::Model();
		$this->load->model('ocw_user');
		$this->load->model('misc_util');
	}

	/**
     * Get objects for a given material 
     *
     * @access  public
     * @param   int	material id		
     * @param   int	object name 
     * @param   string	action type 
     * @param   string	details 
     * @return  array
     */
	public function coobjects($mid, $oid='', $action_type='', $details='*')
	{
		$objects = array();
		$where = "material_id=$mid";

		$action_type = ($action_type == 'Any') ? '' : $action_type;

		if ($action_type=='AskRCO') {
				$replacements = $this->replacements($mid,'','', $action_type='Ask');
				if ($replacements != null) {
						$where .= " AND id IN (";
						$robj = array();
						foreach($replacements as $o) {
										if (!in_array($o['object_id'],$robj)) { 
												$where .= ((sizeof($robj)) ? ', ':'') . $o['object_id'];
														array_push($robj, $o['object_id']); 
										}
						}
						$where .= ")";
				} 
				$action_type == '';

		} else {
			if ($action_type <> '') { 
				switch ($action_type) {
					case 'Ask': $idx = 'ask'; $ans = 'yes'; break;
					case 'Done': $idx = 'done'; $ans = '1'; break;
					default: $idx = 'action_type'; $ans = $action_type;
				}
				$where .= " AND $idx='$ans'";
			}
		}

		$this->db->select($details)->from('objects')->where($where);
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
					if ($oid <> '') {
							if ($oid == $row['id']) {
									$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
									$row['questions'] = $this->questions($row['id'],'id,user_id,question,answer,role,status,modified_by,modified_on');
									$row['instructor_questions'] = $this->questions($row['id'],'id,user_id,question,answer,role,status,modified_by,modified_on', "original", "instructor");
									$row['dscribe2_questions'] = $this->questions($row['id'],'id,user_id,question,answer,role,status,modified_by,modified_on', "original", "dscribe2");
									$row['copyright'] = $this->copyright($row['id']);
									$row['log'] = $this->logs($row['id'],'user_id,log,modified_on');
									array_push($objects, $row);
							}
					} else {
							$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
							$row['questions'] = $this->questions($row['id'],'id,user_id,question,answer,role,status,modified_by,modified_on');
							$row['instructor_questions'] = $this->questions($row['id'],'id,user_id,question,answer,role,status,modified_by,modified_on', "original", "instructor");
							$row['dscribe2_questions'] = $this->questions($row['id'],'id,user_id,question,answer,role,status,modified_by,modified_on', "original", "dscribe2");
							$row['copyright'] = $this->copyright($row['id']);
							$row['log'] = $this->logs($row['id'],'user_id,log,modified_on');
							array_push($objects, $row);
					}
			}
		} 

		return (sizeof($objects) > 0) ? $objects : null;
	}


	/**
     * Get objects replacements for a given material 
     *
     * @access  public
     * @param   int	material id		
     * @param   int	object id		
     * @param   int	replacement id 
     * @param   string	action type 
     * @param   string	details 
     * @return  array
     */
	public function replacements($mid,$oid='', $rid='', $action_type='', $details='*')
	{
		$objects = array();
		$where = array('material_id' => $mid);
		if ($oid <> '') { $where['object_id'] = $oid; }
		if ($rid <> '') { $where['id'] = $rid; }

		$action_type = ($action_type == 'Any') ? '' : $action_type;
		if ($action_type <> '') { 
			switch ($action_type) {
				case 'Ask': $idx = 'ask'; $ans = 'yes'; break;
				default: $idx = 'action_type'; $ans = $action_type;
			}
			$where[$idx] = $ans; 
		}
		$this->db->select($details)->from('object_replacements')->where($where);
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on','replacement');
				$row['questions'] = $this->questions($row['id'],'id,user_id,question,answer,role,status,modified_by,modified_on','replacement');
				$row['copyright'] = $this->copyright($row['id'],'*','replacement');
				$row['log'] = $this->logs($row['id'],'user_id,log,modified_on','replacement');
				array_push($objects, $row);
			}
		} 

		return (sizeof($objects) > 0) ? $objects : null;
	}


	public function num_objects($mid,	$action_type='')
	{
		$action_type = ($action_type == 'Any') ? '' : $action_type;
		
		if ($action_type == 'AskRCO') {
				$table = 'object_replacements';
				$where['Ask'] = 'yes'; 
		} else {
				if ($action_type <> '') { 
						switch ($action_type) {
								case 'Ask': $idx = 'ask'; $ans = 'yes'; break;
								case 'Done': $idx = 'done'; $ans = '1'; break;
								default: $idx = 'action_type'; $ans = $action_type;
						}
						$where[$idx] = $ans; 
				}
				$table = 'objects';
		}		

		$where['material_id'] = $mid;				
		$this->db->select("COUNT(*) AS c")->from($table)->where($where);		
		$q = $this->db->get();
		$row = $q->result_array();
		return $row[0]['c'];
	}

	/** 
	 * Return information need to fill out dscribe ASK Forms 
   *
   * @access  public
   * @param   int	cid course id 
   * @param   int mid material id 
   * @return  array
	 */
	public function ask_form_info($cid, $mid)
	{
		$info = $general = $fairuse = $permission = $commission = $retain = $done = array();
		$done['general'] = $done['fairuse'] = $done['permission'] = $done['commission'] = $done['retain'] = array();	
		$num_general = $num_fairuse = $num_permission = $num_commission = $num_retain = $num_done = 0;

		/* get the original and replacement objects for this material */		
		$orig_objs = $this->coobjects($mid);
		$repl_objs = $this->replacements($mid);

		if (!is_null($orig_objs)) {
		foreach ($orig_objs as $obj) { 
						/* get general question info for dscribe2 */
						if (!is_null($obj['questions'])) { 
							
								
							// remove all non dscribe2 questions
								foreach ($obj['questions'] as $k => $q) { 
									
								if($q['role']!='dscribe2') { unset($obj['questions'][$k]); }}
								if (count($obj['questions'])) { 
										$notalldone = false;
										$obj['otype'] = 'original';
										foreach ($obj['questions'] as $k => $q) { 
														 		if($q['status']<>'done') { $notalldone = true; }
												 		 		$q['ta_data'] = array('name'=>$obj['otype'].'_'.$obj['id'].'_'.$q['id'],
																									   	'value'=>$q['answer'],
																									   	'class'=>'do_d2_question_update',
																									   	'rows'=>'10', 'cols'=>'60');
												 		 		$q['save_data'] = array('name'=>$obj['otype'].'_status_'.$obj['id'],
																							   	  	  'id'=>'close_'.$obj['id'],
																								        'value'=>'Save for later',
																								        'class'=>'do_d2_question_update');
												 		 		$q['send_data'] = array('name'=>$obj['otype'].'_status_'.$obj['id'],
																											  'value'=>'Send to dScribe', 'class'=>'do_d2_question_update');
											 		 			$obj['questions'][$k] = $q;
										}
										if ($notalldone) { array_push($general, $obj); $num_general++; } 
										else { array_push($done['general'],$obj); $num_done++; }
								}
						} 

						/* get objects with fair use claims */
						if (($c = $this->claim_exists($obj['id'],'fairuse')) !== FALSE) {
								 $added = $notalldone = false;
								 $obj['otype'] = 'original';
								 foreach($c as $k => $cl) {
												 $obj['fairuse'] = $c;
												 if ($cl['status']=='done'||$cl['status']=='ip review') { 
														 if(!$added){array_push($done['fairuse'],$obj); $num_done++; $added=true;} 
												 } else { 
														 $notalldone = true; 
												 		 $cl['yes_data'] = array('name'=>$obj['id'].'_fairuse_'.$cl['id'].'_warrant_review',
																								 	 	 'value'=>'yes',
																								 		 'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 		 'checked'=> (($cl['warrant_review']=='yes') ? true : false));

												 		 $cl['no_data'] = array('name'=>$obj['id'].'_fairuse_'.$cl['id'].'_warrant_review',
																								 		'value'=>'no',
																								 		'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 		'checked'=> (($cl['warrant_review']=='no') ? true : false));

												 		 $cl['additional_ta_data'] = array('name'=>$obj['id'].'_fairuse_'.$cl['id'].'_additional_rationale',
																								 							 'value'=>$cl['additional_rationale'],
																								 							 'class'=>'do_d2_claim_update',
																								 							 'rows'=>'10', 'cols'=>'60');
												 		 
												 		 $cl['comments_ta_data'] = array('name'=>$obj['id'].'_fairuse_'.$cl['id'].'_comments',
																								 						 'value'=>$cl['comments'],
																								 						 'class'=>'do_d2_claim_update',
																								 						 'rows'=>'10', 'cols'=>'60');

												 		 $cl['save_data'] = array('name'=>$obj['id'].'_fairuse_'.$cl['id'].'_status',
																							   	  'id'=>'close_'.$cl['id'],
																								    'value'=>'Save for later',
																								    'class'=>'do_d2_claim_update');

												 		 $cl['send_data'] = array('name'=>$obj['id'].'_fairuse_'.$cl['id'].'_status',
																								  'value'=>'Send to dScribe', 'class'=>'do_d2_claim_update');

												 		 $cl['send_to_ip_data'] = array('name'=>$obj['id'].'_fairuse_'.$cl['id'].'_status',
																								  'value'=>'Send to Legal & Policy Review', 'class'=>'do_d2_claim_update');
												 		 $c[$k] = $cl;
												 		 $obj['fairuse'] = $c;
												 }
								 }	
								 if ($notalldone) {	array_push($fairuse, $obj); $num_fairuse++; }
						}

						/* get objects with permission claims */
						if (($c = $this->claim_exists($obj['id'], 'permission')) !== FALSE) {
								 $added = $notalldone = false;
								 $obj['otype'] = 'original';
								 foreach($c as $k => $cl) {
												 $obj['permission'] = $c;
												 if ($cl['status']=='done') { 
														 if(!$added) { array_push($done['permission'],$obj); $num_done++; $added=true;} 
												 } else { 
														 $notalldone = true; 
												 		 $cl['yes_info_data'] = array('name'=>$obj['id'].'_permission_'.$cl['id'].'_info_sufficient',
																								 	 	 			'value'=>'yes',
																								 		 		  'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 		 			'checked'=> (($cl['info_sufficient']=='yes') ? true : false));

												 		 $cl['no_info_data'] = array('name'=>$obj['id'].'_permission_'.$cl['id'].'_info_sufficient',
																								 				 'value'=>'no',
																								 				 'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 				 'checked'=> (($cl['info_sufficient']=='no') ? true : false));

												 		 $cl['yes_sent_data'] = array('name'=>$obj['id'].'_permission_'.$cl['id'].'_letter_sent',
																								 				  'value'=>'yes',
																								 				  'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 				  'checked'=> (($cl['letter_sent']=='yes') ? true : false));

												 		 $cl['no_sent_data'] = array('name'=>$obj['id'].'_permission_'.$cl['id'].'_letter_sent',
																								 				 'value'=>'no',
																								 				 'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 				 'checked'=> (($cl['letter_sent']=='no') ? true : false));

												 		 $cl['yes_received_data'] = array('name'=>$obj['id'].'_permission_'.$cl['id'].'_response_received',
																								 				  'value'=>'yes',
																								 				  'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 				  'checked'=> (($cl['response_received']=='yes') ? true : false));

												 		 $cl['no_received_data'] = array('name'=>$obj['id'].'_permission_'.$cl['id'].'_response_received',
																								 				 'value'=>'no',
																								 				 'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 				 'checked'=> (($cl['response_received']=='no') ? true : false));

												 		 $cl['yes_approved_data'] = array('name'=>$obj['id'].'_permission_'.$cl['id'].'_approved',
																								 				  'value'=>'yes',
																								 				  'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 				  'checked'=> (($cl['approved']=='yes') ? true : false));

												 		 $cl['no_approved_data'] = array('name'=>$obj['id'].'_permission_'.$cl['id'].'_approved',
																								 				 'value'=>'no',
																								 				 'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 				 'checked'=> (($cl['approved']=='no'||$cl['approved']=='pending') ? true : false));

												 		 $cl['comments_ta_data'] = array('name'=>$obj['id'].'_permission_'.$cl['id'].'_comments',
																								 						 'value'=>$cl['comments'],
																								 						 'class'=>'do_d2_claim_update',
																								 						 'rows'=>'10', 'cols'=>'60');

												 		 $cl['save_data'] = array('name'=>$obj['id'].'_permission_'.$cl['id'].'_status',
																							   	  	'id'=>'close_'.$cl['id'],
																								    	'value'=>'Save for later',
																								    	'class'=>'do_d2_claim_update');

												 		 $cl['send_data'] = array('name'=>$obj['id'].'_permission_'.$cl['id'].'_status',
																								  'value'=>'Send to dScribe', 'class'=>'do_d2_claim_update');

												 		 $c[$k] = $cl;
												 		 $obj['permission'] = $c;
												 }
								 }	
								 if ($notalldone) { array_push($permission, $obj); $num_permission++; }
						}

						/* get objects with commission claims */
						if (($c = $this->claim_exists($obj['id'],'commission')) !== FALSE) {
								 $added = $notalldone = false;
								 $obj['otype'] = 'original';
								 foreach($c as $k => $cl) {
												 $obj['commission'] = $c;
												 if ($cl['status']=='done'|| $cl['status']=='commission review') { 
														 if(!$added) { array_push($done['commission'], $obj); $num_done++; $added=true;} 
												 } else { 
														 $notalldone = true; 

												 		 $cl['yes_repl_data'] = array('name'=>$obj['id'].'_commission_'.$cl['id'].'_have_replacement',
																								 				 'value'=>'yes',
																								 				 'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 				 'checked'=> (($cl['have_replacement']=='yes') ? true : false));

												 		 $cl['no_repl_data'] = array('name'=>$obj['id'].'_commission_'.$cl['id'].'_have_replacement',
																								 				 'value'=>'no',
																								 				 'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 				 'checked'=> (($cl['have_replacement']=='no') ? true : false));

												 		 $cl['yes_comm_data'] = array('name'=>$obj['id'].'_commission_'.$cl['id'].'_recommend_commission',
																								 				 'value'=>'yes',
																								 				 'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 				 'checked'=> (($cl['recommend_commission']=='yes') ? true : false));

												 		 $cl['no_comm_data'] = array('name'=>$obj['id'].'_commission_'.$cl['id'].'_recommend_commission',
																								 				 'value'=>'no',
																								 				 'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 				 'checked'=> (($cl['recommend_commission']=='no') ? true : false));

												 		 $cl['comments_ta_data'] = array('name'=>$obj['id'].'_commission_'.$cl['id'].'_comments',
																								 						 'value'=>$cl['comments'],
																								 						 'class'=>'do_d2_claim_update',
																								 						 'rows'=>'10', 'cols'=>'60');

												 		 $cl['save_data'] = array('name'=>$obj['id'].'_commission_'.$cl['id'].'_status',
																							   	  	'id'=>'close_'.$cl['id'],
																								    	'value'=>'Save for later',
																								    	'class'=>'do_d2_claim_update');

												 		 $cl['send_data'] = array('name'=>$obj['id'].'_commission_'.$cl['id'].'_status',
																								  'value'=>'Send to dScribe', 'class'=>'do_d2_claim_update');

												 		 $cl['send_to_cr_data'] = array('name'=>$obj['id'].'_commission_'.$cl['id'].'_status',
																								  'value'=>'Send to Commission Review', 'class'=>'do_d2_claim_update');
												 		 $c[$k] = $cl;
												 		 $obj['commission'] = $c;
												 }
								 }	
								 if ($notalldone) { array_push($commission, $obj); $num_commission++; }
						}

						/* get objects with retain (no copyright) claims */
						if (($c = $this->claim_exists($obj['id'], 'retain')) !== FALSE) {
								 $added = $notalldone = false;
								 $obj['otype'] = 'original';
								 foreach($c as $k => $cl) {
												 $obj['retain'] = $c;
												 if ($cl['status']=='done'|| $cl['status']=='ip review') { 
														if(!$added) { array_push($done['retain'], $obj); $num_done++; $added=true;} 
	
												} else { 
														$notalldone = true; 

												 		 $cl['yes_rationale_data'] = array('name'=>$obj['id'].'_retain_'.$cl['id'].'_accept_rationale',
																								 	 	 			'value'=>'yes',
																								 		 		  'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 		 			'checked'=> (($cl['accept_rationale']=='yes') ? true : false));

												 		 $cl['no_rationale_data'] = array('name'=>$obj['id'].'_retain_'.$cl['id'].'_accept_rationale',
																								 	 	 			'value'=>'no',
																								 		 		  'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 		 			'checked'=> (($cl['accept_rationale']=='no') ? true : false));

												 		 $cl['unsure_rationale_data'] = array('name'=>$obj['id'].'_retain_'.$cl['id'].'_accept_rationale',
																								 	 	 			'value'=>'unsure',
																								 		 		  'class'=>'do_d2_askform_yesno do_d2_claim_update',
																								 		 			'checked'=> (($cl['accept_rationale']=='unsure') ? true : false));

												 		 $cl['comments_ta_data'] = array('name'=>$obj['id'].'_retain_'.$cl['id'].'_comments',
																								 						 'value'=>$cl['comments'],
																								 						 'class'=>'do_d2_claim_update',
																								 						 'rows'=>'10', 'cols'=>'60');

												 		 $cl['save_data'] = array('name'=>$obj['id'].'_retain_'.$cl['id'].'_status',
																							   	  	'id'=>'close_'.$cl['id'],
																								    	'value'=>'Save for later',
																								    	'class'=>'do_d2_claim_update');

												 		 $cl['send_data'] = array('name'=>$obj['id'].'_retain_'.$cl['id'].'_status',
																								  'value'=>'Send to dScribe', 'class'=>'do_d2_claim_update');

												 		 $cl['send_to_ip_data'] = array('name'=>$obj['id'].'_retain_'.$cl['id'].'_status',
																								  'value'=>'Send to Legal & Policy Review', 'class'=>'do_d2_claim_update');

												 		$c[$k] = $cl;
												 		$obj['retain'] = $c;
												}
								 }	
								 if ($notalldone) { array_push($retain, $obj); $num_retain++;}
						}
		}} 
	
		/* get general question info for replacements as well */
		if (!is_null($repl_objs)) {
		foreach ($repl_objs as $obj) { 
						if (!is_null($obj['questions'])) { 
								// remove all non dscribe2 questions
								foreach ($obj['questions'] as $k => $q) { if($q['role']!='dscribe2') { unset($obj['questions'][$k]); }}
								if (count($obj['questions'])) { 
										$obj['otype'] = 'replacement';
										$notalldone = false;
										foreach ($obj['questions'] as $q) { 
														 		if($q['status']<>'done') { $notalldone = true; } 
												 		 		$q['ta_data'] = array('name'=>$obj['otype'].'_'.$obj['id'].'_'.$q['id'],
																									   	'value'=>$q['answer'],
																									   	'class'=>'do_d2_question_update',
																									   	'rows'=>'10', 'cols'=>'60');
												 		 		$q['save_data'] = array('name'=>$obj['otype'].'_status_'.$obj['id'],
																							   	  	  'id'=>'close_'.$obj['id'],
																								        'value'=>'Save for later',
																								        'class'=>'do_d2_question_update');
												 		 		$q['send_data'] = array('name'=>$obj['otype'].'_status_'.$obj['id'],
																											  'value'=>'Send to dScribe', 'class'=>'do_d2_question_update');
												 		 		$obj['questions'][$k] = $q;
										}
										if ($notalldone) { array_push($general, $obj); $num_general++; } 
										else { array_push($done['general'],$obj); $num_done++; }
								}
						} 
		}} 
	
		/* add information to return array */	
		if ($num_done>0) { $done['all']=$num_done; }
		$info['general'] = ($num_general) ?  $general : null;
		$info['fairuse'] = ($num_fairuse) ?  $fairuse : null;
		$info['permission'] = ($num_permission) ?  $permission : null;
		$info['commission'] = ($num_commission) ?  $commission : null;
		$info['retain'] = ($num_retain) ?  $retain : null;
		$info['aitems'] = ($num_done) ?  $done : null;

    $info['num_avail'] = array('general'=>$num_general, 'fairuse'=>$num_fairuse,
                               'permission'=>$num_permission,'commission'=>$num_commission,
                               'retain'=>$num_retain, 'aitems'=>$num_done);

 		$info['need_input'] = ($num_general+$num_fairuse+$num_permission+$num_commission+$num_retain);

		//$this->ocw_utils->dump($info); exit;
		return $info;
	}


	/** 
	 * Return claims if any 
   *
   * @access  public
   * @param   int			oid object id 
   * @param   string	type claim type (fairuse|commission|permission|retain) 
   * @return  array || boolean FALSE if no claims
	 */
	public function claim_exists($oid, $type)
	{
		$claims = array();
		$table = 'claims_'.$type; 

		$this->db->select('*')->from($table)->where('object_id',$oid)->orderby('modified_on DESC');
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) { array_push($claims, $row); }
		} 

		return (sizeof($claims) > 0) ? $claims : false;
	}
     

	/** 
	 * Update an object claim 
   *
   * @access  public
   * @param   int			oid 		 object id 
   * @param   int			claim_id claim id 
   * @param   string	type 		 claim type (fairuse|commission|permission|retain) 
   * @param   array		data 	   values to update
   * @return  array || boolean FALSE if no claims
	 */
	public function update_object_claim($oid, $claim_id, $type, $data)
	{
		$table = 'claims_'.$type; 
		
		$data['modified_by'] = getUserProperty('id');
		
	  $this->db->update($table, $data, "id=$claim_id AND object_id=$oid");

		if (isset($data['status']) and $data['status']=='done') {
				$d = array('ask_dscribe2_status'=>'done', 'modified_by'=>getUserProperty('id'));
	  		$this->update($oid, $d);
		}
	}


	public function object_stats($mid)
	{
		$stats = array();
		$stats['total'] = $this->num_objects($mid);
		$stats['ask'] = 0;
		$stats['cleared'] = 0;
		
		$q = $this->db->query("SELECT COUNT(*) AS c FROM ocw_objects WHERE material_id=$mid AND ask='yes'");
		$row = $q->result_array();
		$stats['ask'] = $row[0]['c'];
		
		$q = $this->db->query("SELECT COUNT(*) AS c FROM ocw_object_replacements WHERE material_id=$mid AND ask='yes'");
		$row = $q->result_array();
		$stats['ask'] += $row[0]['c'];

		$q = $this->db->query("SELECT COUNT(*) AS c FROM ocw_objects WHERE material_id=$mid AND done='1'");
		$row = $q->result_array();
		$stats['cleared'] = $row[0]['c'];
		
		$q = $this->db->query("SELECT COUNT(*) AS c FROM ocw_object_replacements WHERE material_id=$mid AND ask_status='done'");
		$row = $q->result_array();
		$stats['cleared'] += $row[0]['c'];

		$q = $this->db->query("SELECT action_type, COUNT(*) AS c FROM ocw_objects WHERE material_id=$mid GROUP BY action_type");
		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) { $stats[$row['action_type']] = $row['c']; }
		} 
	
		return $stats;
	}

	/**
     * Get comments  for an ip objects 
     *
     * @access  public
     * @param   int	oid ip object id		
     * @param   string details fields to return	
     * @param   string type either original object or replacement 
     * @return  array
     */
	public function comments($oid, $details='*', $type='original')
	{
		$comments = array();
		$table = ($type == 'original') ? 'object_comments' : 'object_replacement_comments';
		$this->db->select($details)->from($table)->where('object_id',$oid)->orderby('modified_on DESC');
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				array_push($comments, $row);
			}
		} 

		return (sizeof($comments) > 0) ? $comments : null;
	}

	/**
     * Get questions  for an ip objects 
     *
     * @access  public
     * @param   int	oid ip object id		
     * @param   string details fields to return	
     * @param   string type either original object or replacement 
     * @return  array
     */
	public function questions($oid, $details='*', $type='original', $role='')
	{
		$questions = array();
		$table = ($type == 'original') ? 'object_questions' : 'object_replacement_questions';
		if ($role<>'')
		{
			$where = 'object_id="'.$oid.'" and role="'.$role.'"';	
		}else{
			$where = 'object_id="'.$oid.'"';	
		}
		$this->db->select($details)->from($table)->where($where)->orderby('created_on DESC');
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				array_push($questions, $row);
			}
		} 

		return (sizeof($questions) > 0) ? $questions : null;
	}

	/**
     * Get copyright info  for an ip objects 
     *
     * @access  public
     * @param   int	oid ip object id		
     * @param   string details fields to return	
     * @param   string type either original object or replacement 
     * @return  array
     */
	public function copyright($oid, $details='*', $type='original')
	{
		$cp = array();
		$table = ($type == 'original') ? 'object_copyright' : 'object_replacement_copyright';
		$this->db->select($details)->from($table)->where('object_id',$oid);
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				array_push($cp, $row);
			}
		} 

		return (sizeof($cp) > 0) ? $cp[0] : null;
	}


	/**
     * Get log  for an ip objects 
     *
     * @access  public
     * @param   int	oid ip object id		
     * @param   string details fields to return	
     * @param   string type either original object or replacement 
     * @return  array
     */
	public function logs($oid, $details='*', $type='original')
	{
		$log = array();
		$table = ($type == 'original') ? 'object_log' : 'object_replacement_log';
		$this->db->select($details)->from($table)->where('object_id',$oid)->orderby('modified_on DESC');
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				array_push($log, $row);
			}
		} 

		return (sizeof($log) > 0) ? $log : null;
	}

	/**
     * Add an object comment
     *
     * @access  public
     * @param   int object id
     * @param   int user id
     * @param   array data 
     * @param   string type either original object or replacement 
     * @return  void
     */
	public function add_comment($oid, $uid, $data, $type='original')
	{
		$data['object_id'] = $oid;
		$data['user_id'] = $uid;
		$data['created_on'] = date('Y-m-d h:i:s');
		$data['modified_on'] = date('Y-m-d h:i:s');
		$table = ($type == 'original') ? 'object_comments' : 'object_replacement_comments';
		$this->db->insert($table,$data);
	}

	/**
     * Add a question 
     *
     * @access  public
     * @param   int object id
     * @param   int user id
     * @param   array data 
     * @param   string type either original object or replacement 
     * @return  void
     */
	public function add_question($oid, $uid, $data, $type='original')
	{
		$data['object_id'] = $oid;
		$data['user_id'] = $uid;
		$data['created_on'] = date('Y-m-d h:i:s');
		$data['modified_on'] = date('Y-m-d h:i:s');
		$table = ($type == 'original') ? 'object_questions' : 'object_replacement_questions';
		$this->db->insert($table,$data);
	}
	
	/**
     * Add additional question 
     *
     * @access  public
     * @param   int object id
     * @param   int user id
     * @param   array data 
     * @return  void
     */
	public function add_additional_question($oid, $uid, $data)
	{
		$table = 'object_questions';
		// whether there is already a question without an answer
		$role = $data['role'];
		$this->db->where('object_id="'.$oid.'" and role="'.$role.'"');
		$q = $this->db->get($table);
		if ($q->num_rows() > 0) 
		{
			
			// there is already a record for this object
			foreach($q->result_array() as $row) { 
		        $id = $row['id'];
		      }
			// answered, log it and refresh to new question
			$ndata['question'] = $data['question'];
			$data['user_id'] = $uid;
			$ndata['modified_on'] = date('Y-m-d h:i:s');
        	$this->db->where("id=$id");
        	$this->db->update($table, $ndata);
		}
		else
		{
			$data['object_id'] = $oid;
			$data['user_id'] = $uid;
			$data['created_on'] = date('Y-m-d h:i:s');
			$data['modified_on'] = date('Y-m-d h:i:s');
			$table = 'object_questions';
			$this->db->insert($table,$data);
		}
	}

	/**
     * update a question 
     *
     * @access  public
     * @param   int object id
     * @param   int user id
     * @param   array data 
     * @param   string type either original object or replacement 
     * @return  void
     */
	public function update_question($oid, $qid, $data, $type='original')
	{
	  $table = ($type == 'original') ? 'object_questions' : 'object_replacement_questions';
		$data['modified_by'] = getUserProperty('id');
	  $this->db->update($table,$data,"id=$qid AND object_id=$oid");
	}

	/**
     * update an object questions status 
     *
     * @access  public
     * @param   int object id
     * @param   array data 
     * @param   role  (instructor | dscribe2)
     * @param   string type either original object or replacement 
     * @return  void
     */
	public function update_questions_status($oid, $data, $role, $type='original')
	{
	  $table = ($type == 'original') ? 'object_questions' : 'object_replacement_questions';
		$data['modified_by'] = getUserProperty('id');
	  $this->db->update($table,$data,"role='$role' AND object_id=$oid");
	}


	/**
     * Add an object copyright
     *
     * @access  public
     * @param   int object id
     * @param   array data 
     * @param   string type either original object or replacement 
     * @return  void
     */
	public function add_copyright($oid, $data, $type='original')
	{
		$data['object_id'] = $oid;
		$table = ($type == 'original') ? 'object_copyright' : 'object_replacement_copyright';
		$this->db->insert($table,$data);
	}

	 /**
     * update copyright 
     *
     * @access  public
     * @param   int object id
     * @param   array data 
     * @param   string type either original object or replacement 
     * @return  void
     */
	public function update_copyright($oid, $data, $type='original')
	{
	  $table = ($type == 'original') ? 'object_copyright' : 'object_replacement_copyright';
	  $this->db->update($table,$data,"object_id=$oid");
	}

	 /**
     * check to see if a copyright record already exists 
     *
     * @access  public
     * @param   int object id
     * @param   string type either original object or replacement 
     * @return  boolean
     */
	public function copyright_exists($oid,$type='original')
	{
	  $table = ($type == 'original') ? 'ocw_object_copyright' : 'ocw_object_replacement_copyright';
		$q = $this->db->query("SELECT COUNT(*) AS n FROM $table WHERE object_id=$oid"); 
		$row = $q->result_array();
		return ($row[0]['n'] > 0) ? true : false;
	}

	/**
     * Add an object log
     *
     * @access  public
     * @param   int object id
     * @param   int user id
     * @param   array data 
     * @param   string type either original object or replacement 
     * @return  void
     */
	public function add_log($oid, $uid, $data, $type='original')
	{
		$data['object_id'] = $oid;
		$data['user_id'] = $uid;
		$data['created_on'] = date('Y-m-d h:i:s');
		$data['modified_on'] = date('Y-m-d h:i:s');
		$table = ($type == 'original') ? 'object_log' : 'object_replacement_log';
		$this->db->insert($table,$data);
	}

	/**
	 * Add the fairuse rationale for the object
	 */
	public function add_fairuse_rationale($oid, $uid, $data)
	{
		
		$table = "claims_fairuse";
		// whether there is already a record for this object
		$this->db->select("*")->from($table)->where("object_id=$oid");
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			// there is already a record for this object
			foreach($q->result_array() as $row) { 
		        $id = $row['id'];
		      }
		    $ndata['rationale'] = $data['rationale'];
				$ndata['modified_by'] = getUserProperty('id');
			$ndata['modified_on'] = date('Y-m-d h:i:s');
        	$this->db->where("id=$id");
        	$this->db->update($table, $ndata);
		}
		else
		{
			// no record yet, insert one
			$data['object_id'] =$oid;
			$data['user_id'] = $uid;
			$data['created_on'] = date('Y-m-d h:i:s');
			$data['modified_by'] = getUserProperty('id');
			$data['modified_on'] = date('Y-m-d h:i:s');
			$this->db->insert($table, $data);
		}
		
	}
	
	/**
	 * Add the commission rationale for the object
	 */
	public function add_commission_rationale($oid, $uid, $data)
	{
		$table = "claims_commission";
		// whether there is already a record for this object
		$this->db->select("*")->from($table)->where("object_id=$oid");
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			// there is already a record for this object
			foreach($q->result_array() as $row) { 
		        $id = $row['id'];
		      }
		    $ndata['rationale'] = $data['rationale'];
				$ndata['modified_by'] = getUserProperty('id');
			$ndata['modified_on'] = date('Y-m-d h:i:s');
        	$this->db->where("id=$id");
        	$this->db->update($table, $ndata);
		}
		else
		{
			// no record yet, insert one
			$data['object_id'] =$oid;
			$data['user_id'] = $uid;
			$data['created_on'] = date('Y-m-d h:i:s');
			$data['modified_by'] = getUserProperty('id');
			$data['modified_on'] = date('Y-m-d h:i:s');
			$this->db->insert($table, $data);
		}
		
	}
	
	/**
	 * Add the retain rationale for the object
	 */
	public function add_retain_rationale($oid, $uid, $data)
	{
		$table = "claims_retain";
		// whether there is already a record for this object
		$this->db->select("*")->from($table)->where("object_id=$oid");
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			// there is already a record for this object
			foreach($q->result_array() as $row) { 
		        $id = $row['id'];
		      }
		    $ndata['rationale'] = $data['rationale'];
				$ndata['modified_by'] = getUserProperty('id');
			$ndata['modified_on'] = date('Y-m-d h:i:s');
        	$this->db->where("id=$id");
        	$this->db->update($table, $ndata);
		}
		else
		{
			// no record yet, insert one
			$data['object_id'] =$oid;
			$data['user_id'] = $uid;
			$data['created_on'] = date('Y-m-d h:i:s');
			$data['modified_by'] = getUserProperty('id');
			$data['modified_on'] = date('Y-m-d h:i:s');
			$this->db->insert($table, $data);
		}
	}

	public function update_contact($oid, $uid, $data)
	{
		$table = 'claims_permission';
		
		// whether there is already a record for this object
		$this->db->select("*")->from($table)->where("object_id=$oid");
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			// there is already a record for this object
			foreach($q->result_array() as $row) { 
		        $id = $row['id'];
		      }
					$data['modified_by'] = getUserProperty('id');
        	$this->db->where("id=$id");
        	$this->db->update($table, $data);
		}
		else
		{
			// no record yet, insert one
			$data['object_id'] =$oid;
			$data['user_id'] = $uid;
			$data['created_on'] = date('Y-m-d h:i:s');
			$data['modified_by'] = getUserProperty('id');
			$data['modified_on'] = date('Y-m-d h:i:s');
			$this->db->insert($table, $data);
		}
		
	}
	
	/**
	 * get the rationale for the object
	 */
	public function getRationale($oid, $table)
	{
		// get the fairuse retional
		$rationale="";
		$this->db->select("*")->from($table)->where("object_id=$oid");
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			// there is already a record for this object
			foreach($q->result_array() as $row) { 
		        $rationale = $row['rationale'];
		      }
		}
		return $rationale;
	}
	
	/**
	 * get the question for the object
	 */
	public function getQuestion($oid, $role)
	{
		// get the fairuse retional
		$question="";
		$table = "object_questions";
		$this->db->where("object_id", $oid);
		$this->db->where("role", "$role");
		$q = $this->db->get($table);
		if ($q->num_rows() > 0) {
			// there is already a record for this object
			foreach($q->result_array() as $row) { 
		        $question = $row['question'];
		      }
		}
		return $question;
	}
	/**
	 * get the claim permission data
	 */
	public function getClaimsPermission($oid)
	{
		$this->db->select("*")->from('claims_permission')->where("object_id=$oid");
		$q = $this->db->get();
		$permission = $q->row_array();
		return $permission;
	}

	/**
     * Add an object
     *
     * @access  public
     * @param   int course id
     * @param   int material id
     * @param   int user id
     * @param   array data 
     * @return  void
     */
	public function add($cid, $mid, $uid, $data, $files)
	{
		
		// check for slides and get any data embedded in the file
		if (is_array($files['userfile_0'])) {
				$filename = $files['userfile_0']['name'];
				$tmpname = $files['userfile_0']['tmp_name'];
				$data['name'] = $filename;
				$data = $this->prep_data($cid, $mid, $data, $filename, $tmpname);
				if ($data=='slide') { return true; }
		}
		
		$data['done'] = '0';
		$data['material_id'] = $mid;
		$data['modified_by'] = $uid;
		$data['status'] = 'in progress';
		
		// set a default subtype_id if none is specified
		if(!array_key_exists('subtype_id', $data) || !$data['subtype_id']) {
		  $data['subtype_id'] = $this->misc_util->get_id_for_value('ocw_object_subtypes',
		    "name", "None");
		}

		$comment = $data['comment'];
		$question = $data['question'];

		$copy = array('status' => $data['copystatus'],
								  'holder' => $data['copyholder'],
								  'url' => $data['copyurl'],
								  'notice' => $data['copynotice']);
		unset($data['comment']);
		unset($data['co_request']);
		unset($data['question']);
		unset($data['copystatus']);
		unset($data['copyholder']);
		unset($data['copyurl']);
		unset($data['copynotice']);

		// add new object
		$this->db->insert('objects',$data);
		$oid = $this->db->insert_id();

		// add  questions and comments
		if ($question <> '') {
			$this->add_question($oid, getUserProperty('id'), array('question'=>$question));
		}
		if ($comment <> '') {
			$this->add_comment($oid, getUserProperty('id'), array('comments'=>$comment));
		}
		
	 if ($copy['status']<>'' or $copy['holder']<>'' or
			 $copy['notice']<>'' or $copy['url']<>''){
			 $this->add_copyright($oid,$copy);
		}

		// add files
		if (is_array($files['userfile_0'])) {
				$type = $files['userfile_0']['type'];
				$tmpname = $files['userfile_0']['tmp_name'];
				$name = $this->generate_object_name($tmpname);
				$path = $this->prep_path($this->material_path($cid, $mid).'/odir_'.$name);

			  $ext = '';
  			switch (strtolower($type))
        	{
                case 'image/gif':  $ext= '.gif'; break;
                case 'image/tiff':  $ext= '.tiff'; break;
                case 'jpg':
                case 'image/jpeg': $ext= '.jpg'; break;
                case 'image/png':  $ext= '.png'; break;
                default: $ext='.png';
        	}

				// move file to new location
				if (is_uploaded_file($tmpname)) {
						move_uploaded_file($tmpname, $path.'/'.$name.'_grab'.$ext);
				} else {
						@copy($tmpname, $path.'/'.$name.'_grab'.$ext);
						@unlink($tmpname);
				}
				# store new filename
				$this->db->insert('object_files', array('object_id'=>$oid,
																								'filename'=>$name,
																								'modified_on'=>date('Y-m-d h:i:s'),
																								'created_on'=>date('Y-m-d h:i:s')));
		}
	
		return $oid;
	}

	/**
	 * add replacement question
	 */
	public function add_replacement_question($id, $oid, $uid, $data)
	{
		$table = "object_replacement_questions";
		// whether there is already a question without an answer
		$this->db->where('id', $id);
		$role = $data['role'];
		$this->db->where("role", "$role");
		$q = $this->db->get($table);

		if ($q->num_rows() > 0) 
		{
			// there is already a record for this object
			foreach($q->result_array() as $row) { 
		        $id = $row['id'];
		      }
			// answered, log it and refresh to new question
			$ndata['question'] = $data['question'];
			$data['user_id'] = $uid;
			$ndata['modified_on'] = date('Y-m-d h:i:s');
			$ndata['modified_by'] = $uid;
        	$this->db->where("id=$id");
        	$this->db->update($table, $ndata);
		}
		else
		{
			$data['id'] = $id;
			$data['object_id'] = $oid;
			$data['user_id'] = $uid;
			$data['created_on'] = date('Y-m-d h:i:s');
			$data['modified_by'] = $uid;
			$data['modified_on'] = date('Y-m-d h:i:s');
			$table = 'object_replacement_questions';
			$this->db->insert($table,$data);
		}
	}
	
	/**
     * Add a replacement object
     *
     * @access  public
     * @param   int course id
     * @param   int material id
     * @param   int object id
     * @param   array data 
     * @return  void
     */
	public function add_replacement($cid, $mid, $objid, $data, $files)
	{
		// check for slides and get any data embedded in the file
		if (is_array($files['userfile_0'])) {
				$filename = $files['userfile_0']['name'];
				$tmpname = $files['userfile_0']['tmp_name'];
				$data['name'] = $filename;
				$data = $this->prep_data($cid, $mid, $data, $filename, $tmpname);
				if ($data=='slide') { return true; }
		}
					
		$data['material_id'] = $mid;
		$data['object_id'] = $objid;

		$comment = $data['comment'];
		$question = $data['question'];

		$copy = array('status' => $data['copystatus'],
								  'holder' => $data['copyholder'],
								  'url' => $data['copyurl'],
								  'notice' => $data['copynotice']);
		unset($data['comment']);
		unset($data['question']);
		unset($data['copystatus']);
		unset($data['copyholder']);
		unset($data['copyurl']);
		unset($data['copynotice']);

		// add new object
		$this->db->insert('object_replacements',$data);
		$rid = $this->db->insert_id();

		// add  questions and comments
		if ($question <> '') {
			$this->add_question($rid, getUserProperty('id'), array('question'=>$question),'replacement');
		}
		if ($comment <> '') {
			$this->add_comment($rid, getUserProperty('id'), array('comments'=>$comment),'replacement');
		}
		
	 if ($copy['status']<>'' or $copy['holder']<>'' or
			 $copy['notice']<>'' or $copy['url']<>''){
			 $this->add_copyright($rid,$copy,'replacement');
		}

		// add files
		if (is_array($files['userfile_0'])) {
				$type = $files['userfile_0']['type'];
				$tmpname = $files['userfile_0']['tmp_name'];
				$name = $this->object_filename($objid);
				$path = $this->prep_path($this->material_path($cid, $mid).'/odir_'.$name);

				$ext = '';
  			switch (strtolower($type))
       	{
               case 'image/gif':  $ext= '.gif'; break;
               case 'image/tiff':  $ext= '.tiff'; break;
               case 'jpg':
               case 'image/jpeg': $ext= '.jpg'; break;
               case 'image/png':  $ext= '.png'; break;
               default: $ext='.png';
       	}

				// move file to new location
				if (is_uploaded_file($tmpname)) {
						move_uploaded_file($tmpname, $path.'/'.$name.'_rep'.$ext);
				} else {
						copy($tmpname, $path.'/'.$name.'_rep'.$ext);
						@unlink($tmpname);
				}
		}
	
		return $rid;
	}


  // add content objects with data embedded in the file metadata
  public function add_zip($cid, $mid, $uid, $files)
  {
		$fileadded = array(); // 1: file added; 2: file omitted 

    if (is_array($files['userfile']) and $files['userfile']['error']==0) {
        $zipfile = $files['userfile']['tmp_name'];
        $files = $this->ocw_utils->unzip($zipfile, property('app_co_upload_path')); 
				$replace_info = $orig_info = array();

        if ($files !== false && is_array($files)) {
            foreach($files as $newfile) {
		
									if (preg_match('/Slide\d+|\-pres\.\d+/is',$newfile)) { // find slides

											$this->add_slide($cid,$mid,$newfile,$newfile);
											array_push($fileadded, 1);

									} elseif (preg_match('/^(\d+)R?_(.*?)$/',basename($newfile))) {
											$objecttype = (preg_match('/^(\d+)R_(.*?)/',basename($newfile))) ? 'RCO' : 'CO';				
	                    $filedata = array('userfile_0'=>array());
	                    $filedata['userfile_0']['name'] = basename($newfile);
											
											preg_match('/\.(\w+)$/', basename($newfile), $matches);
											$ext = $type = '';
											if (isset($matches[1])) { $ext = $matches[1]; }
											switch (strtolower($ext))
							        	{
							                case 'gif':  $type = 'image/gif'; break;
							                case 'tiff': $type = 'image/tiff'; break;
							                case 'jpg':
							                case 'jpeg': $type = 'image/jpeg'; break;
							                case 'png':  $type = 'image/png'; break;
							                default: $type = 'image/jpeg';
							        	}
	
                      $filedata['userfile_0']['type'] = $type;
                      $filedata['userfile_0']['tmp_name'] = $newfile;

											if (preg_match('/^(\d+)R?_(.*?)$/',basename($newfile))) {
                   				if ($objecttype=='CO') {
		                     		$oid = $this->add($cid, $mid, $uid, array(), $filedata);
														$repfile = preg_replace('/^(\d+)_/',"$1R_",basename($newfile));

														# go through and see if any replacement items are waiting to be inserted
														if (in_array($repfile, array_keys($replace_info))) {
																// replacement exists and has been processed so just add it!
																$filedata = $replace_info[$repfile];
		                     				$rid = $this->add_replacement($cid, $mid, $oid, array(), $filedata);
																unset($replace_info[$repfile]);
												
														} else { # place in queue just in case the replacement comes along later
																$orig_info[basename($newfile)] = $oid;
														}

													} elseif ($objecttype=='RCO') {
														// this is a replacement - we have to make sure the original has been added
														// first before we add this. Otherwise, add it to a queue
														$origfile = preg_replace('/^(\d+)R_/',"$1_",basename($newfile));

														# go through and see if any original item has been inserted alredy 
														if (in_array($origfile, array_keys($orig_info))) {
																// original exists and has been processed so just add replacement 
																$oid = $orig_info[$origfile];
		                     				$rid = $this->add_replacement($cid, $mid, $oid, array(), $filedata);
																unset($orig_info[$origfile]);
												
														} else { # place in queue just in case the original comes along later
																$replace_info[basename($newfile)] = $filedata;
														}
												}		
	                   }
										 array_push($fileadded, 1);

									} else {
										 array_push($fileadded, 2); 
									}
						}		
				} else {
				     // zip file did not contain any files
				}
       
    } else {
			exit('Cannot upload file: an error occurred while uploading file. Please contact administrator.');
    }

		$msg = '';
		if (empty($fileadded)) { $msg = 'No files added: Uploaded file did not contain any files.'; }
  	elseif(in_array(1,$fileadded)) { $msg = 'Content Objects added.'; }
  	elseif(in_array(2,$fileadded)) { $msg = 'No files added: files did not match expected filename format.'; }
		
		return $msg;
 }

  /**
    * remove material based on information given
    * 
	  * TODO: remove materials and related objects from harddisk
    */
  public function remove_object($cid, $mid, $oid)
  {
		# remove material objects and related info
		$this->db->select('id')->from('object_replacements')->where("object_id='$oid'");

		$replacements = $this->db->get();

		if ($replacements->num_rows() > 0) {
				foreach($replacements->result_array() as $row) {
								$this->remove_replacement($cid, $mid, $oid, $row['id']);
				}
		}

		# remove object and it's related info 
		$this->db->delete('object_questions', array('object_id'=>$oid));
		$this->db->delete('object_comments', array('object_id'=>$oid));
		$this->db->delete('object_copyright', array('object_id'=>$oid));
		$this->db->delete('object_log', array('object_id'=>$oid));
		$this->db->delete('objects', array('id'=>$oid));

		# remove object from filesystem
		$path = $this->object_path($cid, $mid, $oid);
		if (!is_null($path)) {
				$this->ocw_utils->remove_dir(property('app_uploads_path').$path);
		}

		return true;
  }

	/* remove a bunch of objects for a given material */
	public function remove_objects($cid, $mid)
	{
		$this->db->select('id')->from('objects')->where("material_id='$mid'");
    $objects = $this->db->get();

    if ($objects->num_rows() > 0) {
        foreach($objects->result_array() as $row) {
                $this->remove_object($cid, $mid, $row['id']);
				}
		}
	}

  /**
    * remove replacement based on information given
    * 
    */
  public function remove_replacement($cid, $mid, $oid, $rid)
  {
			# remove replacement objects and related info 
			$this->db->delete('object_replacement_questions', array('object_id'=>$rid));
			$this->db->delete('object_replacement_comments', array('object_id'=>$rid));
			$this->db->delete('object_replacement_copyright', array('object_id'=>$rid));
			$this->db->delete('object_replacement_log', array('object_id'=>$rid));
			$this->db->delete('object_replacements', array('id'=>$rid));
	   
			$name = $this->object_filename($oid);
			$path = property('app_uploads_path').$this->object_path($cid, $mid, $oid);
	   	$p_imgpath = $path."/{$name}_rep.png";
	   	$j_imgpath = $path."/{$name}_rep.jpg";
	   	$g_imgpath = $path."/{$name}_rep.gif";

			if (file_exists($p_imgpath)) { @unlink($p_imgpath); } 
			elseif (file_exists($j_imgpath)) { @unlink($j_imgpath); }
			elseif (file_exists($g_imgpath)) { @unlink($g_imgpath); }

			return true;
  }

	/**
     * Update objects for a given material 
     *
     * @access  public
     * @param   int	oid object ip id		
     * @param   array	data
     * @return  void
     */
	public function update($oid, $data)
	{
		if(!isset($data['modified_by']) || $data['modified_by']=='') {
			 $data['modified_by'] = getUserProperty('id');
		}
		$this->db->update('objects',$data,"id=$oid");
	}

	/**
     * Update replacement objects for a given material 
     *
     * @access  public
     * @param   int	replacement id		
     * @param   array	data
     * @return  void
     */
	public function update_replacement($oid, $data)
	{
		if(!isset($data['modified_by']) || $data['modified_by']=='') {
			 $data['modified_by'] = getUserProperty('id');
		}
		$this->db->update('object_replacements',$data,"id=$oid");
	}


	/**
     * Update object replacement image
     *
     * @access  public
     * @param   int	oid object ip id		
     * @param   array	data
     * @return  void
     */
	public function update_rep_image($cid, $mid, $oid, $files)
	{
		// check for slides and get any data embedded in the file
		$data = array();
		if (is_array($files['userfile_0'])) {
				$filename = $files['userfile_0']['name'];
				$tmpname = $files['userfile_0']['tmp_name'];
				$data['name'] = $filename;
				$data = $this->prep_data($cid, $mid, array(), $filename, $tmpname);
				if ($data=='slide') { return true; }
		}

		$comment = $data['comment'];
		$question = $data['question'];
		$copy = array('status' => $data['copystatus'],
								  'holder' => $data['copyholder'],
								  'url' => $data['copyurl'],
								  'notice' => $data['copynotice']);
		unset($data['comment']);
		unset($data['question']);
		unset($data['copystatus']);
		unset($data['copyholder']);
		unset($data['copyurl']);
		unset($data['copynotice']);
		
		// don't want to overwrite old values with empty strings
		foreach($data as $k => $v) { if ($v=='') { unset($data[$k]); }}

		// update new object if need be
		if (sizeof($data) > 0) {
				$data['material_id'] = $mid;
				$data['id'] = $oid;
				$this->update_replacement($oid, $data);
		}

		// add  questions and comments
		if ($question <> '') {
			$this->add_question($oid, getUserProperty('id'), array('question'=>$question),'replacement');
		}
		if ($comment <> '') {
			$this->add_comment($oid, getUserProperty('id'), array('comments'=>$comment),'replacement');
		}

	 if ($copy['status']<>'' or $copy['holder']<>'' or
			 $copy['notice']<>'' or $copy['url']<>''){
			 $this->add_copyright($oid,$copy,'replacement');
		}
		
		// add files
		if (is_array($files['userfile_0'])) {
				$type = $files['userfile_0']['type'];
				$tmpname = $files['userfile_0']['tmp_name'];
				$name = $this->object_filename($oid);
				$path = $this->prep_path($this->material_path($cid, $mid).'/odir_'.$name);

				$ext = '';
  			switch (strtolower($type))
        	{
                case 'image/gif':  $ext= '.gif'; break;
                case 'image/tiff':  $ext= '.tiff'; break;
                case 'jpg':
                case 'image/jpeg': $ext= '.jpg'; break;
                case 'image/png':  $ext= '.png'; break;
                default: $ext='.png';
        	}

				// move file to new location
				move_uploaded_file($tmpname, $path.'/'.$name.'_rep'.$ext);
		}
	}


	public function replacement_exists($cid, $mid, $oid) 
	{
		 $name = $this->object_filename($oid);
		 $path = $this->object_path($cid, $mid, $oid);

	   $p_imgurl = property('app_uploads_url').$path.'/'.$name.'_rep.png';
	   $p_imgpath = property('app_uploads_path').$path.'/'.$name.'_rep.png';
	   $j_imgurl = property('app_uploads_url').$path.'/'.$name.'_rep.jpg';
	   $j_imgpath = property('app_uploads_path').$path.'/'.$name.'_rep.jpg';
	   $g_imgurl = property('app_uploads_url').$path.'/'.$name.'_rep.gif';
	   $g_imgpath = property('app_uploads_path').$path.'/'.$name.'_rep.gif';

	   if (is_readable($p_imgpath) || is_readable($j_imgpath) || is_readable($g_imgpath)) {
				 $thumb_found = true;	
	   } else {
				 $thumb_found = false;	
	   }

     return ($thumb_found) ? true : false; 
	}


	/**
     * Get Object types 
     *
     * @access  public
     * @return  array
     */
	public function object_subtypes()
	{
		$types = array();
		$sql = 'SELECT ocw_object_subtypes.*, ocw_object_types.type 
				  FROM ocw_object_subtypes, ocw_object_types
				 WHERE ocw_object_subtypes.type_id = ocw_object_types.id
				 ORDER BY ocw_object_types.type, ocw_object_subtypes.name';
		$q = $this->db->query($sql);

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) { $types[$row['type']][] = $row; }
		} 

		return (sizeof($types) > 0) ? $types : null;
	}

	public function get_subtype_name($id) 
	{
			$name = '';
			$sql = "SELECT name FROM ocw_object_subtypes WHERE id=$id";
			$q = $this->db->query($sql);

			if ($q->num_rows() > 0) {
					foreach($q->result_array() as $row) { $name = $row['name']; }
			} 
			return ($name=='') ? 'Could not find type' : $name;
	}

	//public function prev_next($cid, $mid, $oid)
	//{
	//	$next = '';
	//	$prev = '';
  //
	//	$this->db->select('id, name')->from('objects')->where('material_id',$mid)->orderby('id');
	//	$q = $this->db->get();
	//	$num = $q->num_rows();
	//	$thisnum = $count = 0;
	//
	//	if ($num > 0) {
	//		foreach($q->result_array() as $row) {
	//			$count++;
	//			if ($row['id'] == ($oid - 1)) {
	//				$prev = '<a href="'.site_url("materials/object_info/$cid/$mid/{$row['id']}").'">&laquo;&nbsp;Previous</a>';
	//			}
	//			if ($row['id'] == ($oid + 1)) {
	//				$next = '<a href="'.site_url("materials/object_info/$cid/$mid/{$row['id']}").'">Next&nbsp;&raquo;</a>';
	//			}
	//			if ($row['id'] == $oid) { $thisnum = $count; }
	//		}
	//	}
	//	
	//	$prev = ($prev=='') ? '&laquo;&nbsp;Previous' : $prev;
	//	$next = ($next=='') ? 'Next&nbsp;&raquo;' : $next;
	//	$mid = ($num > 1) ? "$thisnum of $num" : '';
	//	return $prev.'&nbsp;&nbsp;-&nbsp;'.$mid.'&nbsp;-&nbsp;&nbsp;'.$next; 
	//}
	
	/**
    * Generate the previous, next navigation arrows at the bottom of
    * the CO display pages. Arrows are only clickable if in fact that
    * material has previous or next content objects. Otherwise they 
    * are displayed as unclickable text. The relevant results are
    * returned as generated html.
    *
    * @param    int the course id
    * @param    int the material id
    * @param    int the content object id
    *
    * @return   string the html source for the navigation links
    */
  public function prev_next($cid, $mid, $oid)
	{
		$prev_obj = NULL;
		$curr_num = NULL;
		$next_obj = NULL;
		
		$this->db->select('id, name')->
		  from('objects')->where('material_id',$mid)->orderby('id');
		$q = $this->db->get();
		$total_num = $q->num_rows();
	
		/* content object ID's for previous and next items if any and get
		 * the number of the current content object $oid for the current
		 * material $mid nothing happens if no content objects are found */
		if ($total_num > 0) {
		  $q_results = $q->result_array();
		   for ($i = 0; $i < $total_num; $i++) {
		     // $this->ocw_utils->dump($q_results[$i]);
		     if ($q_results[$i]['id'] == $oid) {
		       $curr_num = ($i + 1);
		       if ($i > 0) {
		         $prev_obj = $q_results[$i - 1]['id'];
		       }
		       if ($i < ($total_num - 1)) {
		         $next_obj = $q_results[$i + 1]['id'];
		       }
		     }
		   }
		 } 
		
		/* make buttons active if the respective values are defined,
		 * plain text otherwise */
		$prev_nav = ($prev_obj) ? '<a href="'.site_url("materials/object_info/$cid/$mid/$prev_obj").'">&laquo;&nbsp;Previous</a>' : 
		  '&laquo;&nbsp;Previous';
		$curr_nav = ($curr_num) ? "$curr_num of $total_num" : "";
		$next_nav = ($next_obj) ? '<a href="'.site_url("materials/object_info/$cid/$mid/$next_obj").'">Next&nbsp;&raquo;</a>' :
		  'Next&nbsp;&raquo;';
		
		return $prev_nav.'&nbsp;&nbsp;-&nbsp;'.
		  $curr_nav.'&nbsp;-&nbsp;&nbsp;'.$next_nav;	
	}

	public function prep_path($name, $slide=false)
	{
		$dirs = @split("/", $name);

		$path = property('app_uploads_path').$dirs[0]; // course directory
		$this->oer_filename->mkdir($path);

		$path .= '/'.$dirs[1];  // material directory
		$this->oer_filename->mkdir($path);

		if (!$slide) {
				$path .= '/'.$dirs[2]; // object directory
				$this->oer_filename->mkdir($path);
		}

		return $path;
	}
	
	public function prep_data($cid,$mid,$data,$filename,$pathtofile)
	{
			if (preg_match('/Slide\d+|\-pres\.\d+/i',$filename)) { // find slides
					$this->add_slide($cid,$mid,$filename,$pathtofile);
					return 'slide';
			} else {
					$filedata = $this->get_xmp_data($pathtofile);
					foreach($filedata as $k => $v) { // passed values supercede embedded ones
										if (isset($data[$k])) {
												$data[$k] = ($data[$k]=='') ? $v : $data[$k];
										} else {
												$data[$k] = $v;
										}
					}
			}
			return $data;
	}

	// add a slide
	public function add_slide($cid, $mid, $slidefile,$pathtofile)
	{
			preg_match('/\.(\w+)$/', $slidefile, $matches);
			$ext = $matches[1];

			if (preg_match('/Slide(\d+)\.\w+/i',$slidefile,$matches)) { // powerpoint 
					$loc = intval($matches[1]);

			} elseif (preg_match('/\-pres\.(\d+)\.\w+/',$slidefile,$matches)) { // keynote 
					$loc = intval($matches[1]);

			} else { // return any number found
					$i = preg_match('/(\d+)/',$slidefile,$matches); 
					$loc = intval($matches[1]);
			}

			$path = $this->material_path($cid, $mid);
			if (!is_null($path)) {
					$newpath = $this->prep_path($path, true); 
					$newpath = $newpath."/{$this->material_filename($mid)}_slide_$loc.$ext";
					@copy($pathtofile, $newpath); 
					@chmod($newpath,0777);
					@unlink($pathtofile);
			} else {
					exit('Could not find path to add slide.');
			}
	}

	public function get_xmp_data($newfile)
	{	
		$data = array();
	  $xmp_data = $this->ocw_utils->xmp_data($newfile);
		
		// TODO: need a more dynamic way of getting this hash
    $subtypes = array('2D'=>'1','3D'=>'2','IIllustrative'=>'12',
                      'Cartoon' => '11', 'Comp' => '9', 'Map' => '10',
                      'Medical' => '8', 'PIllustrative' => '4', 'Patient' => '3',
                      'Specimen' => '5', 'Art' => '17', 'Artifact' => '21',
                      'Chemical' => '13', 'Diagram' => '19', 'Equation' => '15',
                      'Gene' => '14', 'Logo' => '18', 'Radiology' => '6',
											 'Microscopy' => '7');

	 	$copy_status = array(''=>'unknown', 'True'=>'copyrighted',
												'False'=>'public domain');

		$act_types = array('Comm'=>'Commission','FU'=>'Fair Use','Perm'=>'Permission','Remove'=>'Remove',
											 'Retain'=>'Retain', 'Search'=>'Search');

		$yesno = array('N'=>'no', 'Y'=>'yes');

		$loc = split('_',basename($newfile));
		$loc = ereg_replace('R','',$loc[0]);

    if (isset($xmp_data['objecttype']) ) {
				# get data from xmp
        $data['ask'] = (isset($xmp_data['ask'])) ? $yesno[$xmp_data['ask']] : 'no'; 
				$data['location'] = $loc;
        $data['question'] = (isset($xmp_data['question'])) ? $xmp_data['question'] : ''; 
        $data['citation'] = (isset($xmp_data['citation'])) ? $xmp_data['citation'] : 'none'; 
        $data['comment'] = (isset($xmp_data['comments'])) ? $xmp_data['comments'] : ''; 
        $data['contributor'] = (isset($xmp_data['contributor'])) ? $xmp_data['contributor'] : ''; 
        $data['description'] = (isset($xmp_data['description'])) ? $xmp_data['description'] : ''; 
        $data['tags'] = (isset($xmp_data['keywords'])) ? $xmp_data['keywords'] : ''; 
        $data['copystatus'] = (isset($xmp_data['copystatus'])) ? $copy_status[$xmp_data['copystatus']] : ''; 
        $data['copyurl'] = (isset($xmp_data['copyurl'])) ? $xmp_data['copyurl'] : ''; 
        $data['copynotice'] = (isset($xmp_data['copynotice'])) ? $xmp_data['copynotice'] : ''; 
        $data['copyholder'] = (isset($xmp_data['copyholder'])) ? $xmp_data['copyholder'] : ''; 
				if ($xmp_data['objecttype']<>'RCO') {
	          $data['subtype_id'] = $subtypes[$xmp_data['subtype']]; 
	          $data['action_type'] = (isset($xmp_data['action'])) ? $act_types[$xmp_data['action']] : ''; 
				}			
		} else {
				$data['ask'] = 'no';
				if (preg_match('/^(\d+)(R)?_/',basename($newfile))) { 
					$data['location'] = $loc; 
				} else {
					$data['location'] = '';
				}
	      $data['citation'] = 'none'; 
	      $data['question'] = ''; 
	      $data['comment'] = ''; 
	      $data['copystatus'] =  ''; 
	      $data['copyurl'] = ''; 
	      $data['copynotice'] = ''; 
	      $data['copyholder'] =  '';
		}
  	
		return $data;
	}

	/* return the path to a material on the file system 
	 *
   * returns path to latest version of material unless
   * all is true and then it returns paths to all versions	
	 */
	public function material_path($cid, $mid, $all=false)
	{
			$path = '';
		
	  	# get course directory name
			$path .= 'cdir_'.$this->course_filename($cid);

			$mat_path = $this->material_filename($mid, $all);

			if (!is_null($mat_path)) {
					if ($all) {
						 	$cpath = $path;
							$path = array();
      				foreach($mat_path as $mp) { 
        							array_push($path, $cpath.'/mdir_'.$mp);
							}
					} else {
							$path .= '/mdir_'.$mat_path;
					}
  		} else {
					return null;
			}
			return $path;
	}

	/* return the path to an object on the file system 
	 *
   * returns path to latest version of material unless
   * all is true and then it returns paths to all versions	
	 */
	public function object_path($cid, $mid, $oid)
	{
			$path = $this->material_path($cid,$mid); 
	
			if (!is_null($path)) {	
					$path .= '/odir_'.$this->object_filename($oid);
			}

			return $path;
	}

	public function course_filename($cid)
	{
			$this->db->select('filename')->from('course_files')->where("course_id=$cid")->order_by('created_on desc')->limit(1);
			$q = $this->db->get();
			if ($q->num_rows() > 0) {
					$r = $q->row();
					return $r->filename;
			} else {
					return null;
			}
	}

	public function material_filename($mid, $all=false)
	{
			$name = '';

			$this->db->select('filename')->from('material_files')->where("material_id=$mid")->order_by('created_on desc');
			if (!$all) { $this->db->limit(1); }

			$q = $this->db->get();

			if ($q->num_rows() > 0) {
					if ($all) {
							$name = array();
      				foreach($q->result_array() as $row) { 
        							array_push($name, $row['filename']);
							}
					} else {
							$r = $q->row();
							$name = $r->filename;
					}
  		} else {
					return null;
			}

			return $name;
	}

	public function object_filename($oid)
	{
			$this->db->select('filename')->from('object_files')->where("object_id=$oid")->order_by('created_on desc')->limit(1);

			$q = $this->db->get();

			if ($q->num_rows() > 0) {
					$r = $q->row();
					return $r->filename;
			} else {
					return null;
			}
	}

  private function object_name_exists($name)
  {
     $this->db->select('filename')->from('object_files')->where("filename='$name'");
     $q = $this->db->get();
     return ($q->num_rows() > 0) ? true : false;
  }

  private function generate_object_name($filename)
  {
   		$digest = '';
      $generate_own = false;
      do {
          if ($generate_own) {
              $digest = $this->oer_filename->random_name($filename);
          } else {
              $digest = $this->oer_filename->file_digest($filename);
          }
          $generate_own = true;
      } while ($this->object_name_exists($digest));

      return $digest;
  }

	public function enum2array($table, $col)
	{
		$array = array();
		$q = $this->db->query("SHOW COLUMNS FROM ocw_$table LIKE '$col'");
		$row = $q->result_array();
		$enum = (is_array($row[0])) 
					? explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",$row[0]['Type'])) : null;
		if (!is_null($enum)) {
				foreach($enum as $val) { $array[$val] = $val; }
		}
		return $array;
	}

	/**
   	 * Print out claim report 
     *
     * @access  public
     * @param   int			course id		
     * @param   int			material id		
     * @param   object	object 
     * @param   string	claim type  (commission | permission | retain | fairuse) 
     * @return  string
     */
  public function claim_report($cid, $mid, $obj, $type)
	{
			$html = '';

			if (($item = $this->claim_exists($obj['id'], $type)) !== FALSE) {
					 $item = $item[0];

					 if ($item['status']=='new' or $item['status']=='in progress') {
							 return 'This claim request is still under review by the dscribe2';
					}

					$uname = ($item['modified_by']<>'') ? $this->ocw_user->username($item['modified_by']) : 'dScribe2';
					$uname = '<b>'.$uname.'</b>';

					if ($type == 'commission') {
							if ($item['have_replacement']=='yes') {
                  $html .= '<br>'.$uname.' provided the dScribe with a replacement with the following action '.
												   'and comments:<br/><br/>Action: <b>'.$item['action'].'</b><br/>';
                
									$html .= ($item['comments']<>'') ? 'Comments: <b>'.$item['comments'].'</b>'
                      														 : 'Comments: <b>no comments</b><br/><br/>';
                	$x = $this->replacement_exists($cid,$mid,$obj['id']);
                	if ($x) {
                    	$html .= '<p>Provided Replacement: </p>'.
															 $this->ocw_utils->create_corep_img($cid,$mid,$obj['id'],$obj['location'],false,true);
                }
            	} elseif ($item['have_replacement']=='no') {
                	$html .= '<br>'.$uname.' could not provide the dScribe with a replacement<br/>';
                	if ($item['recommend_commission']=='yes') {
                   	 	$html .= '<br>'.$uname.' recommends commissioning the content object because: '.
                   						 (($item['comments']<>'') ? $item['comments'] : 'no rationale given');
                	} else {
                    	$html .= '<br>'.$uname.' does not recommend commissioning the content object and '. 
															 'suggests the following:<br/><br/>Action: <b>'.$item['action'].'</b><br/>';
											$html .= ($item['comments']<>'') ? 'Comments: <b>'.$item['comments'].'</b>'
                      														 		 : 'Comments: <b>no comments</b><br/><br/>';
                	}
            	}

            	if($item['status']=='commission review') { 
											$html .= '<br><br>The Commission Review team is reviewing this claim.'; 
							}

				 } elseif ($type=='retain') {
	            if ($item['accept_rationale']=='yes') {
	                $html .= '<br>'.$uname.' accepted dscribe\'s rationale.';
	            } elseif ($item['accept_rationale']=='no') {
	                $html .= '<br>'.$uname.' did not accept dscribe\'s rationale.';
	                if ($item['action']<>'None') {
	                    $html .= '<br/><br/>'.$uname.' recommends the following action:<b>'.$item['action'].'</b>';
	                }
	            } elseif ($item['accept_rationale']=='unsure') {
	                $html .= '<br>'.$uname.' is unsure about the dscribe\'s rationale.';
	                if ($item['status']=='ip review') {
	                    $html .= '<br><br>'.$uname.' has sent it to Legal & Policy team for review';
	                }
	            }
	            if ($item['comments']<>'') {
	                $html .= '<br/><br/>'.$uname.' provided the following comments:<br/><br/>';
	                $html .= '<p style="background-color:#ddd;padding:5px;">'.$item['comments'].'</p><br/><br/>';
	            }
	            if ($item['approved']=='yes') {
	                $html .= '<br><br>Legal & Policy Review team have approved this claim.';
	            } elseif ($item['approved']=='no') {
	                $html .= '<br><br>Legal & Policy Review team have not approved this claim.';
	            } elseif($item['status']=='ip review' && $item['approved']=='pending') {
	                $html .= '<br><br>Legal & Policy Review team is reviewing this claim.';
	            }

				 } elseif ($type=='permission') {
	            if ($item['info_sufficient']=='yes') {
	                $html .= $uname.' decided that a permission form can be sent for this content object.';
	
	                if ($item['letter_sent']=='yes') {
	                    $html .= '<br/><br/>'.$uname.' indicated that the permission form has been sent.';
	                    if ($item['response_received']=='yes') {
	                        $html .= '<br/><br/>'.$uname.' indicated that a response has been received.';
	                        if ($item['approved']=='yes') {
	                            $html .= '<br/><br/>'.$uname.' indicated that request for permission was approved';
	                        } elseif ($item['approved']=='no') {
	                            $html .= '<br/><br/>'.$uname.' indicated that request for permission was not approved';
	                            if ($item['action']<>'None') {
	                                $html .= '<br/><br/>'.$uname.' recommends the following action:<b>'.$item['action'].'</b>';
	                            }
	                            if ($item['comments']<>'') {
	                                $html .= '<br/><br/>'.$uname.' provided the following comments:<br/><br/>';
	                                $html .= '<p style="background-color:#ddd; padding:5px;">'.$item['comments'].'</p><br/><br/>';
	                            }
	                        } else {
	                                $html .= $uname.' did not specify whether this request was approved or not';
	                        }
	                    } else {
	                        $html .= '<br/><br/>'.$uname.' indicated that a response has not been received.';
	                    }
	                } else {
	                    $html .= '<br/><br/>'.$uname.' indicated that the permission form has not been sent.';
	                }
	
	            } elseif ($item['info_sufficient']=='no') {
	                  $html .= $uname.' decided that a permission form should not be sent for this content object';
	                  if ($item['action']<>'None') {
	                      $html .= '<br/><br/>'.$uname.' recommends the following action:<b>'.$item['action'].'</b>';
	                  }
	                  if ($item['comments']<>'') {
	                      $html .= '<br/><br/>'.$uname.' provided the following comments:<br/><br/>';
	                      $html .= '<p style="background-color:#ddd; padding:5px;">'.$item['comments'].'</p><br/><br/>';
	                  }
	            }
	            if ($item['approved']=='yes') {
	                $html .= '<br><br>Legal & Policy Review team have approved this claim.';
	            } elseif ($item['approved']=='no') {
	                $html .= '<br><br>Legal & Policy Review team have not approved this claim.';
	            } elseif($item['status']=='ip review' && $item['approved']=='pending') {
	                $html .= '<br><br>Legal & Policy Review team is reviewing this claim.';
	            }

				 } elseif ($type=='fairuse') {

	          if ($item['warrant_review']=='yes') {
	              $html .= $uname.' indicated that this object <i>warrants</i> a legal review for fair use.';
	              if ($item['status']=='ip review') {
	                	$html .= '<br/><br/>'.$uname.' has sent this to the Legal & Policy review team';
	              }
	              if ($item['additional_rationale']<>'') {
	                  $html .= '<br/><br/>'.$uname.' provided the following additional rationale:<br/><br/>';
	                  $html .= '<p style="background-color:#ddd; padding:5px;">'.$item['additional_rationale'].
														 '</p><br/><br/>';
	              }
	
	          } elseif ($item['warrant_review']=='no') {
	              			$html .= $uname.' indicated that this object <i>does not warrant</i> a legal review for fair use.';
	              if ($item['action']<>'None') {
	                	$html .= '<br/><br/>'.$uname.' recommends the following action:<b>'.$item['action'].'</b>';
	              }
	              if ($item['comments']<>'') {
	                	$html .= '<br/><br/>'.$uname.' provided the following comments:<br/><br/>';
	                	$html .= '<p style="background-color:#ddd; padding:5px;">'.$item['comments'].
														 '</p><br/><br/>';
	              }
	          } elseif ($item['warrant_review']=='pending') {
	              			$html .= $uname.' did not specify whether this object warrants a fair use review or not.';
	          }
	            if ($item['approved']=='yes') {
	                $html .= '<br><br>Legal & Policy Review team have approved this claim.';
	            } elseif ($item['approved']=='no') {
	                $html .= '<br><br>Legal & Policy Review team not have approved this claim.';
	            } elseif($item['status']=='ip review' && $item['approved']=='pending') {
	                $html .= '<br><br>Legal & Policy Review team is reviewing this claim.';
	            }
				 }
		 } else {
				$html = 'No "'.ucfirst($type).'" claim found for this object.';
		 }

		 return $html;
	}

	/**
		 * Report on instructor responses to ask form questions 
     *
     * @access  public
     * @param   int			course id		
     * @param   int			material id		
     * @param   object	object 
     * @param   string	type  (original | replacement) 
     * @return  string
     */
  public function ask_instructor_report($cid, $mid, $obj, $type)
  {
			$html = '';

			if ($obj['ask_status'] <> 'done') {
					return 'This request is still under review by the instructor';
			}

			$uname = ($obj['modified_by']<>'') ? $this->ocw_user->username($obj['modified_by']) : 'Instructor';
			$uname = '<b>'.$uname.'</b>';

			if ($type=='original') {
		    	if ($obj['description']) {
		        	$html .= $uname.' provided the following description:<br/><br/>';
		        	$html .= '<p style="background-color:#ddd; padding:5px;">'.$obj['description'].'</p><br/><br/>';
		    	}
		    	if ($obj['instructor_owns']=='yes') {
		      		$html .= $uname.' indicated that they <i>hold</i> the copyright to this object.';
		    	} else {
		      		$html .= $uname.' indicated that they <em>do not hold</em> the copyright to this object.<br/><br/>';
		
		      		if ($obj['other_copyholder']=='') {
		        			$html .= $uname.' indicated that they <em>do not know</em> who holds the copyright.<br/><br/>';
		        			if ($obj['is_unique']=='yes') {
		          				$html .= $uname.' indicated that the representation of this information <em>is unique</em>';
		        			} else {
		          				$html .= $uname.' indicated that the representation of this information <em>is not unique</em>';
		        			}
		      		} else {
		        		$html .= $uname.' indicated that <em>'.$obj['other_copyholder'].'</em> holds the copyright.';
		      	 }
		     }

		} elseif ($type=='replacement') {

				if ($obj['suitable']=='yes') { 
    				$html .= '<h3>'.$uname.' approved this replacement:</h3>'.
						$this->ocw_utils->create_corep_img($cid,$mid,$obj['id'],$obj['location'],false);
  			} else {
    				$html .= '<h3>'.$uname.' rejected replacement:</h3>'.
    				$this->ocw_utils->create_corep_img($cid,$mid,$obj['id'],$obj['location'],false);
    				$html .= '<br/><br/><h3>Reason:</h3>'.
    				($obj['unsuitable_reason']=='') ? 'No reason provided' : $obj['unsuitable_reason']; 
  			} 
		}

		return $html;
 }



}
?>
