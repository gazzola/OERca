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
   * Check to see if a string is base64 endcoded 
   */
	public function is_base64_encoded($data)
  {
       return (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) ? true : false;
  }

	/** 
   * Add images captured with the snapper tool 
   */
  public function add_snapper_image($cid,$mid,$uid,$data)
  {
  	
  	try
  	{
      /* validate values */
      if (!isset($data['image']) || !$this->is_base64_encoded($data['image'])) {
          return 'Error adding image: image file is corrupt';
      } elseif (!isset($data['type']) || $data['type']=='') {
          return 'Error adding image: please specify whether it is a slide or object.';
      } elseif ($data['type'] == 'object' && (!isset($data['subtype_id']) || $data['subtype_id']=='22')) {
          return 'Error adding image: please specify image content type.';
      } elseif (!isset($data['location']) || $data['location']=='') {
          return 'Error adding image: please specify the slide or page number of the image.';
      }

      $loc = $data['location'];

      if ($data['type'] == 'slide') { // add new slide
          $path = $this->material_path($cid, $mid);
          if (!is_null($path)) {
              $newpath = $this->prep_path($path, true);
              $newpath = $newpath."/{$this->material_filename($mid)}_slide_$loc.jpg";
              if (!$fh = @fopen($newpath,'wb')) { return "Error adding file: cannot create slide filename."; }
              if (@fwrite($fh, base64_decode($data['image']))===FALSE) {
                  return 'Error adding file: cannot write image to file';
              }
              @fclose($fh);
              @chmod($newpath,0777);
          } else  {
              return 'Error adding image: could not find material path to add slide.';
          }
      } elseif ($data['type'] == 'object') { // add new object
          do { // generate a random name for the file
              $name = $this->oer_filename->random_name($data['image']);
          } while ($this->object_name_exists($name));

          $indata = array();
          $indata['done'] = '0';
          $indata['ask'] = 'no';
          $indata['name'] = $name;
          $indata['location'] = $loc;
          $indata['material_id'] = $mid;
          $indata['modified_by'] = $uid;
          $indata['status'] = 'in progress';
          $indata['subtype_id'] = $data['subtype_id'];
          $indata['description'] = $indata['citation'] = $indata['tags'] = '';

          # add object to db
          $this->db->insert('objects',$indata);
          $oid = $this->db->insert_id();

          # add object filename to db and file system
          $path = $this->prep_path($this->material_path($cid, $mid).'/odir_'.$name).'/'.$name.'_grab.jpg';
          if (!$fh = @fopen($path,'wb')) { return "Error adding file: cannot create object filename."; }
          if (@fwrite($fh, base64_decode($data['image']))===FALSE) {
                  return 'Error adding file: cannot write image to file';
          }
          @fclose($fh); @chmod($path,0777);

          $this->db->insert('object_files', array('object_id'=>$oid,
                                                  'filename'=>$name,
                                                  'modified_on'=>date('Y-m-d h:i:s'),
                                                  'created_on'=>date('Y-m-d h:i:s')));

					// indicate in materials table that content objects exist
					$this->db->update('materials',array('embedded_co'=>'1'),"id=$mid");
      } else {
          return 'Error adding image: please specify whether it is a slide or object.';
      }

      return true;
  	}
  	catch(Exception $e)
  	{
  		return $e->getMessage();
  	}
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
		}
		elseif ($action_type=='RCO') {
				$replacements = $this->replacements($mid,'','', $action_type='AskNo');
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
				$act_whr = '';
				switch ($action_type) {
					case 'Ask': $act_whr = "ask = 'yes'"; break;
					case 'Done': $act_whr = "done = '1'"; break;
					case 'Retain': $this->db->like('action_type', 'Retain:','after'); break;
					case 'Remove': $this->db->like('action_type', 'Remove','after'); break;
					default: $act_whr = "action_type= '$action_type'";
				}
				$where .= ($act_whr=='') ? '' : " AND $act_whr";
			}
		}

		$this->db->select($details)->from('objects')->where($where);
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
					if ($oid <> '') {
							if ($oid == $row['id']) {
									$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
									$row['questions'] = $this->questions($row['id'],'id,user_id,question,answer,role,status,created_on,modified_by,modified_on');
									$row['copyright'] = $this->copyright($row['id']);
									$row['log'] = $this->logs($row['id'],'user_id,log,modified_on');
									array_push($objects, $row);
							}
					} else {
							$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
							$row['questions'] = $this->questions($row['id'],'id,user_id,question,answer,role,status,created_on,modified_by,modified_on');
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
				case 'Ask': $idx = 'ask'; $ans = 'yes'; $where[$idx] = $ans; break;
				case 'AskNo': break;
				default: $idx = 'action_type'; $ans = $action_type;$where[$idx] = $ans;
			} 
		}
		$this->db->select($details)->from('object_replacements')->where($where);
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on','replacement');
				$row['questions'] = $this->questions($row['id'],'id,user_id,question,answer,role,status,modified_by,created_on,modified_on','replacement');
				$row['copyright'] = $this->copyright($row['id'],'*','replacement');
				$row['log'] = $this->logs($row['id'],'user_id,log,modified_on','replacement');
				array_push($objects, $row);
			}
		} 

		return (sizeof($objects) > 0) ? $objects : null;
	}


	public function num_objects($mid,	$action_type='')
	{
		$where['material_id'] = $mid;
		$action_type = ($action_type == 'Any') ? '' : $action_type;
		if ($action_type == 'AskRCO') {
				$table = 'object_replacements';
				$where['ask'] = 'yes';
		}elseif ( $action_type == 'RCO')
		{
				$table = 'object_replacements';
		} else {
				if ($action_type <> '') { 
						switch ($action_type) {
							case 'Ask': $where['ask'] = 'yes'; break;
							case 'Done': $where['done']= '1'; break;
							case 'Retain': $this->db->like('action_type', 'Retain:','after'); break;
							case 'Remove': $this->db->like('action_type', 'Remove','after'); break;
							default: $where['action_type']= $action_type;
						}
				}
				$table = 'objects';
		}						
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
			 if ($obj['done'] != 1) {

						/* get general question info for dscribe2 */
						if (!is_null($obj['questions'])) { 
								$questions = (isset($obj['questions']['dscribe2']) && sizeof($obj['questions']['dscribe2'])>0)	
													 ? $obj['questions']['dscribe2'] : null;

								if (!is_null($questions)) {
										$notalldone = false;
										$obj['otype'] = 'original';
										foreach ($questions as $k => $q) { 
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
											 		 			$obj['questions']['dscribe2'][$k] = $q;
										}
										if ($notalldone) { array_push($general, $obj); $num_general++; } 
										else { array_push($done['general'],$obj); $num_done++; }
								}
						} 

						/* get general question info for replacements as well */
            if ($this->replacement_exists($cid,$mid,$obj['id'])) {
                $robj = $this->replacements($mid, $obj['id']);
								$robj = $robj[0];

								if (!is_null($robj['questions'])) { 
										$questions = (isset($robj['questions']['dscribe2']) && sizeof($robj['questions']['dscribe2'])>0)	
															 ? $robj['questions']['dscribe2'] : null;
				
										if (!is_null($questions)) {
												$robj['otype'] = 'replacement';
												$notalldone = false;
												foreach ($questions as $k => $q) { 
														 		if($q['status']<>'done') { $notalldone = true; } 
												 		 		$q['ta_data'] = array('name'=>$robj['otype'].'_'.$robj['id'].'_'.$q['id'],
																													   	'value'=>$q['answer'],
																													   	'class'=>'do_d2_question_update',
																													   	'rows'=>'10', 'cols'=>'60');
												 		 		$q['save_data'] = array('name'=>$robj['otype'].'_status_'.$robj['id'],
																											   	  	  'id'=>'close_'.$robj['id'],
																												        'value'=>'Save for later',
																												        'class'=>'do_d2_question_update');
												 		 		$q['send_data'] = array('name'=>$robj['otype'].'_status_'.$robj['id'],
																															  'value'=>'Send to dScribe', 'class'=>'do_d2_question_update');
												 		 		$obj['questions']['dscribe2'][$k] = $q;
												}
												if ($notalldone) { array_push($general, $robj); $num_general++; } 
												else { array_push($done['general'],$obj); $num_done++; }
										}
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
		}}} 
	
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

		$this->db->select('*')->from($table)->where('object_id',$oid)->orderby('id DESC');
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) { array_push($claims, $row); }
		} 

		return (sizeof($claims) > 0) ? $claims : false;
	}
     
	/**
	 * Add the claim for the object
	 */
	public function add_object_claim($oid, $uid, $type, $data)
	{
		$cl = $this->claim_exists($oid,$type);

		if ($cl!==false && $cl[0]['status']<>'done') { 
				// there is already a record for this object
		    $claim_id = $cl[0]['id'];
				$ndata = $data;
				if ($type <> 'permission') { $ndata['rationale'] = $data['rationale']; }
				$ndata['modified_by'] = getUserProperty('id');
				$ndata['modified_on'] = date('Y-m-d h:i:s');
				$this->update_object_claim($oid, $claim_id, $type, $ndata);

		} else {
				// no record yet, insert one
				$table = 'claims_'.$type; 
				$data['object_id'] =$oid;
				$data['user_id'] = $uid;
				$data['created_on'] = date('Y-m-d h:i:s');
				$data['modified_by'] = getUserProperty('id');
				$data['modified_on'] = date('Y-m-d h:i:s');
				$this->db->insert($table, $data);
		}
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
				$this->db->select('action')->from($table)->where('id',$claim_id);
				$q = $this->db->get();
				if ($q->num_rows() > 0) {
						foreach($q->result_array() as $row) {  $d['action_type']=$row['action']; }
				} 
	  		$this->update($oid, $d);
				$this->add_log($oid, getUserProperty('id'), array('log'=>'dScribe2 has responded to this claim and sent it to the dscribe'));
		}
	}

	/** 
	 * return the current status of an object (which "bins" is it in)
   *
	 * status is determined in this order:
   *     0) It's in the cleared bin if it's 'done' field is set to true
   *     1) it's in replacement bin if there exists a replacement with pending instructor questions 
   *     2) Otherwise, in the ask bin if there it is an ask item that has not been answered and has no action associated with it.   
   *     3) Otherwise, it's in the bin specified by it's action type 
   *     4) Otherwise, it's in the new bin if no action type has been set. 
   *     5) All items not in the cleared bin are in the uncleared bin by default
   *    
   * @param int cid course id
   * @param int mid material id
	 * @param int oid object id
   * @return string status [new|ask:orig|ask:rco|fairuse|search|retain:perm|
	 *											  retain:nc|retain:pd|uncleared|cleared|permission|
	 *										    commission|replace|remove|recreate]
	 */
	public function object_status($cid, $mid, $oid) 
	{
			 $status = 'unknown';

       $obj =  $this->coobject->coobjects($mid, $oid);

       if ($obj != null) {
           $obj = $obj[0];

           if ($obj['done'] != 1) {
               if ($this->replacement_exists($cid, $mid, $oid)) { 
							 		 $robj = $this->replacements($mid, $oid);
                   $status = ($robj[0]['ask']=='yes' && $robj[0]['ask_status']<>'done') 
													 ? 'ask:rco' : $status;
							 }

							 if ($status=='unknown') {
               		if ($obj['ask']=='yes' && ($obj['ask_status']<>'done' || $obj['action_type']=='')) {
                   		$status='ask:orig';

               		} else {
                    	switch($obj['action_type']) {
                              case 'Search':     $status = 'search';      break;
                              case 'Fair Use':   $status = 'fairuse';     break;
                              case 'Permission': $status = 'permission';  break;
                              case 'Commission': $status = 'commission';  break;
                              case 'Re-Create':  $status = 'recreate';    break;
                              case 'Retain: Permission':    $status = 'retain:perm'; break;
                              case 'Retain: Public Domain': $status = 'retain:pd'; break;
                              case 'Retain: No Copyright':  $status = 'retain:nc'; break;
                              case 'Remove and Annotate':   $status = 'remove'; break;
                              default: $status = 'new';
                     	}
                	}
							 }
            } else { 
							$status = 'cleared'; 
						}
				}

				return $status;
	}


	/** 
	 * return an array which contains information on how many objects are 
   * in each "bin" and the content objects sorted into their bins. Note 
   * that content objects that fall into multiple bins will appear in 
   * each bin they qualify for.
   *
   * @param int cid course id
   * @param int mid material id
   * @return array stats  array('data'=>array, 'objects'=>array);
	 */
	public function object_stats($cid,$mid)
	{
			    $orig_objects =  $this->coobjects($mid);
					$askinfo = $this->ask_form_info($cid, $mid);

			    // get counts for different status 
			    $data['num_all'] = $data['num_new'] = $data['num_search'] =
			    $data['num_ask_orig'] = $data['num_ask_rco'] = $data['num_ask_generalinst'] = 
				  $data['num_ask_general'] = $data['num_ask_done'] = $data['num_ask_aitems'] = $data['num_fairuse'] =
			    $data['num_permission'] = $data['num_commission'] =
			    $data['num_retain_perm'] = $data['num_retain_nc'] = $data['num_retain_pd'] =
			    $data['num_replace'] = $data['num_recreate'] = $data['num_uncleared'] =
			    $data['num_remove'] = $data['num_cleared'] = 0;
			
					$objects = array();
			    $objects['all'] = $objects['new'] = $objects['search'] =
			    $objects['ask:orig'] = $objects['ask:rco'] = $objects['done'] = $objects['aitems'] = 
			    $objects['generalinst'] = $objects['general'] = $objects['fairuse'] =
			    $objects['permission'] = $objects['commission'] =
			    $objects['retain:perm'] = $objects['retain:nc'] = $objects['retain:pd'] =
			    $objects['replace'] = $objects['recreate'] = $objects['uncleared'] =
			    $objects['remove'] = $objects['cleared'] = array();
	
					// use information from ask form status	
					$objects['general'] = $askinfo['general'];	$data['num_ask_general'] = $askinfo['num_avail']['general'];	
					$objects['aitems'] = $askinfo['aitems'];	$data['num_ask_aitems'] = $askinfo['num_avail']['aitems'];	
					$objects['fairuse'] = $askinfo['fairuse'];	$data['num_fairuse'] = $askinfo['num_avail']['fairuse'];	
					$objects['permission'] = $askinfo['permission'];	$data['num_permission'] = $askinfo['num_avail']['permission'];	
					$objects['commission'] = $askinfo['commission'];	$data['num_commission'] = $askinfo['num_avail']['commission'];	
					$objects['retain:nc'] = $askinfo['retain'];	$data['num_retain_nc'] = $askinfo['num_avail']['retain'];	

			    if ($orig_objects != null) {
			        foreach ($orig_objects as $obj) {
											 $obj['otype']='original';

			                if ($obj['done'] != 1) {

                    			if (!is_null($obj['questions']) && $obj['ask_status']<>'done') {
															// get general question info for instructors 
                        			$questions = (isset($obj['questions']['instructor']) && sizeof($obj['questions']['instructor'])>0)
                                   			 ? $obj['questions']['instructor'] : null;
 
                        			if (!is_null($questions)) {
                            			$notalldone = false;
                            			foreach ($questions as $k => $q) { if($q['status']<>'done') { $notalldone = true; } }
                            			if ($notalldone) { array_push($objects['generalinst'], $obj); $data['num_ask_general']++;}
                        			}
                    			}

													// uncleared	
			                    $data['num_uncleared']++;
			                    array_push($objects['uncleared'], $obj); 
			
													// replacements	
               						if ($this->replacement_exists($cid,$mid,$obj['id'])) { 
															array_push($objects['replace'], $obj);
			                				$data['num_replace']++;
	
															// replacement with ask questions
							 		 						$robj = $this->replacements($mid, $obj['id']);
			                    		if ($robj[0]['ask']=='yes' && $robj[0]['ask_status']<>'done') {
																	$robj[0]['otype']='replacement';

			                        		array_push($objects['ask:rco'], $obj);
			                        		$data['num_ask_rco']++;

                    							if (!is_null($robj[0]['questions']) && $robj[0]['ask_status']<>'done') {

																			// get general question info for instructors 
                              				$questions = (isset($robj[0]['questions']['instructor'])&&sizeof($robj[0]['questions']['instructor'])>0)
                                         		 ? $robj[0]['questions']['instructor'] : null;
                          
                              				if (!is_null($questions)) {
                                  				$notalldone = false;
                                  				foreach ($questions as $k => $q) { if($q['status']<>'done') { $notalldone = true; } }
                                  				if ($notalldone) { array_push($objects['generalinst'], $robj[0]); $data['num_ask_general']++;}
																			}
                              		}
															} else { 
			                     	  		$data['num_ask_done']++;
			                     	  		array_push($objects['done'], $robj[0]); 
															}
													}
		
													// instructor ask items	
			                    if ($obj['ask']=='yes' && ($obj['action_type']=='' || $obj['ask_status']<>'done')) {
			                     	  $data['num_ask_orig']++;
			                     	  array_push($objects['ask:orig'], $obj); 
			                    }
	
													if ($obj['ask']=='yes' && $obj['ask_status']=='done') {
			                     	  $data['num_ask_done']++;
			                     	  array_push($objects['done'], $obj); 
													}	

													// claim items	
			                    switch($obj['action_type']) {
			                          case 'Search':
			                                array_push($objects['search'],$obj);
			                                $data['num_search']++; break;
			                          case 'Fair Use': // used data from $askinfo above 
																			break;
			                          case 'Permission': // used data from $askinfo above 
																			break;
			                          case 'Commission': // used data from $askinfo above 
																			break;
			                          case 'Retain: No Copyright': // used data from $askinfo above 
																			break;
			                          case 'Retain: Permission':
			                                array_push($objects['retain:perm'],$obj);
			                                $data['num_retain_perm']++; break;
			                          case 'Retain: Public Domain':
			                                array_push($objects['retain:pd'],$obj);
			                                $data['num_retain_pd']++; break;
			                          case 'Re-Create':
			                                array_push($objects['recreate'],$obj); 
			                                $data['num_recreate']++; break;
			                          case 'Remove and Annotate':
			                                array_push($objects['remove'],$obj);
			                                $data['num_remove']++; break;
			                          default:
			                                if ($obj['ask']=='no') {
			                                    array_push($objects['new'], $obj); 
			                                    $data['num_new']++;
			                                }
			                    	}
			                } else {
			                     array_push($objects['cleared'], $obj);
			                     $data['num_cleared']++;
			                }
			                array_push($objects['all'], $obj);
			                $data['num_all']++;
			        }
			    }
			
					return array('data'=>$data, 'objects'=>$objects, 'askinfo'=>$askinfo);
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
		$roles = $this->enum2array($table, 'role');

		foreach($roles as $r) { if ($role=='' || $role==$r) { $questions[$r] = array(); }}
		$where = ($role<>'') ? 'object_id="'.$oid.'" and role="'.$role.'"':	'object_id="'.$oid.'"';	

		$this->db->select($details)->from($table)->where($where)->orderby('created_on DESC');
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				if ($row['role'] =='') { // assign unassigned questions to dscribe2 by default
						$row['role'] = 'dscribe2';
						$d = array('role'=>'dscribe2');
	  				$this->db->update($table,$d,"id={$row['id']}");
				}
				array_push($questions[$row['role']], $row);
			}
		} 
	
		foreach($roles as $r) { if (isset($questions[$r]) && sizeof($questions[$r])==0) { unset($questions[$r]); }}

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

		if (isset($data['role'])) {
				if ($data['role']=='dscribe2' && $type=='original') { $this->update($oid, array('ask_dscribe2'=>'yes')); }
				if ($data['role']=='instructor') { $r = ($type=='original') ? $this->update($oid, array('ask'=>'yes')) 
																																		: $this->update_replacement($oid, array('ask'=>'yes')); }
		}
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

		// indicate in materials table that content objects exist
		$this->db->update('materials',array('embedded_co'=>'1'),"id=$mid");

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
                default: $ext='.jpg';
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

		if (isset($data['action_type'])) {
        if (($res = $this->valid_recommendation($oid, $data['action_type'])) !== true) { return $res; }
		}

		$this->db->update('objects',$data,"id=$oid");
		return true;
	}

	public function valid_recommendation($oid, $recommendation)
	{
			$fu = $this->claim_exists($oid,'fairuse');
			$pm = $this->claim_exists($oid,'permission');
			$cm = $this->claim_exists($oid,'commission');
			$rt = $this->claim_exists($oid,'retain');
			
			//	* ensure that the object is not currently being processed in another bin  
			$anotherbin = false;	
			if ($fu!==false && $fu[0]['status']<>'done') { $anotherbin = 'Fair Use'; }
			if ($pm!==false && $pm[0]['status']<>'done') { $anotherbin = 'Permission'; }
			if ($cm!==false && $cm[0]['status']<>'done') { $anotherbin = 'Commission'; }
			if ($rt!==false && $rt[0]['status']<>'done') { $anotherbin = 'Retainment'; }

			// check to see if the recommended action is the same as we already have 
			if ($anotherbin==false && $recommendation=='Fair Use') { if ($fu!==false && $fu[0]['status']=='done') { return true; } } 
			if ($anotherbin==false && $recommendation=='Permission') { if ($pm!==false && $pm[0]['status']=='done') { return true; } } 
			if ($anotherbin==false && $recommendation=='Commission') { if ($cm!==false && $cm[0]['status']=='done') { return true; } } 
			if ($anotherbin==false && substr($recommendation,0,6)=='Retain') { if ($rt!==false && $rt[0]['status']=='done') { return true;}} 

			return ($anotherbin==false or (substr($recommendation,0,6)==substr($anotherbin,0,6))) 
						? true : "Cannot accept recommended action: this object is currently under ".
										 "consideration for $anotherbin."; 
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
	public function update_rep_image($cid, $mid, $oid, $data, $files)
	{
		// check for slides and get any data embedded in the file
		if (is_array($files['userfile_0'])) {
				$filename = $files['userfile_0']['name'];
				$tmpname = $files['userfile_0']['tmp_name'];
				$data['name'] = $filename;
				$data = $this->prep_data($cid, $mid, $data, $filename, $tmpname);
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
    * @param    string search filter 
    * @param    string  which [prev|next|both] 
    * @param    string  type  [text|image] 
    *
    * @return   string the html source for the navigation links
    */
  public function prev_next($cid, $mid, $oid, $filter, $which='both', $type='text')
	{
		$s =  $this->object_stats($cid, $mid);

		// filter results
		$q_results = array();

		if (preg_match('/(fairuse|general|permission|commission|retain):all/',$filter,$m)) {
				$claims = split('\|','fairuse|general|permission|commission|retain');
				foreach($claims as $cl) {	$q_results = array_merge($q_results, $s['askinfo']['aitems'][$cl]); }

		} elseif (preg_match("/(fairuse|general|permission|commission|retain):\w+/",$filter,$m)) {
				$q_results = $s['objects'][$m[1]];
		} else {
				if ($filter=='retain') { $filter = 'retain:nc'; }
				$q_results = $s['objects'][$filter]; 
		}

		$total_num = sizeof($q_results);
		$prev_obj = $curr_num = $next_obj = null;

		/* content object ID's for previous and next items if any and get
		 * the number of the current content object $oid for the current
		 * material $mid nothing happens if no content objects are found */
		if ($total_num > 0) {
		   for ($i = 0; $i < $total_num; $i++) {
		     if ($q_results[$i]['id'] == $oid) {
		       	$curr_num = ($i + 1);
		       	if ($i > 0) { $prev_obj = $q_results[$i - 1]['id']; }
		       	if ($i < ($total_num - 1)) { $next_obj = $q_results[$i + 1]['id']; }
		     }
		   }
		 } 
	
		/* make buttons active if the respective values are defined,
		 * plain text otherwise */
		$curr_nav = ($curr_num) ? "$curr_num of $total_num" : "";
		if ($type=='image') {
				$prev_txt = 'View '.($curr_num-1).' of '.$total_num;
				$prev_img = ($prev_obj) 
							? '<img title="'.$prev_txt.'" id="pyes" class="parrow" src="'.property('app_img').'/coedit-1.png" />'
							: '<img id="pno" class="parrow" src="'.property('app_img').'/coedit-1.png" />';	

				$next_txt = 'View '.($curr_num+1).' of '.$total_num;
				$next_img = ($next_obj) 
							? '<img title="'.$next_txt.'" id="pyes" class="parrow" src="'.property('app_img').'/coedit-2.png" />'
							: '<img id="pno" class="parrow" src="'.property('app_img').'/coedit-2.png" />';	

				$prev_nav = ($prev_obj) 
							? '<a href="'.site_url("materials/object_info/$cid/$mid/$prev_obj/$filter").'">'.$prev_img.'</a>' : $prev_img; 

				$next_nav = ($next_obj) ? '<a href="'.site_url("materials/object_info/$cid/$mid/$next_obj/$filter").'">'.$next_img.'</a>' : $next_img;

		} else {
				$prev_nav = ($prev_obj) ? '<a href="'.site_url("materials/object_info/$cid/$mid/$prev_obj/$filter").'">&laquo;&nbsp;Previous</a>' : '&laquo;&nbsp;Previous';
				$next_nav = ($next_obj) ? '<a href="'.site_url("materials/object_info/$cid/$mid/$next_obj/$filter").'">Next&nbsp;&raquo;</a>' : 'Next&nbsp;&raquo;';
		}
		
		$prev_next = '';
		switch($which) {
				case 'prev': $prev_next = $prev_nav ; break;
				case 'next': $prev_next = $next_nav ; break;
				default: $prev_next = $prev_nav.'&nbsp;&nbsp;-&nbsp;'.$curr_nav.
															'&nbsp;-&nbsp;&nbsp;'.$next_nav;	
		}
		
		return $prev_next; 
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
			$ext = strtolower($matches[1]);

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
					$search_path = $newpath."/{$this->material_filename($mid)}_slide_$loc.*";
					$newpath = $newpath."/{$this->material_filename($mid)}_slide_$loc.$ext";
					// remove all old copies of this slide
					foreach (glob($search_path) as $filename) { @unlink($filename); }	
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
  public function claim_report($cid, $mid, $obj, $item_id, $type, $filter)
	{
			$html = '';

			if (($items = $this->claim_exists($obj['id'], $type)) !== FALSE) {
					 $item = array();
					 foreach($items as $i) { if ($item_id==$i['id']) {$item=$i;} }

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
															 $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],$filter,'rep',true);
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
     * @param   string	filter 
     * @return  string
     */
  public function ask_instructor_report($cid, $mid, $obj, $type, $filter)
  {
			$html = '';

			if ($obj['ask_status'] <> 'done') {
					return 'This request is still under review by the instructor';
			}

  		$instructors = $this->ocw_user->get_users_by_relationship($obj['modified_by'],'instructor', $cid);
			if (is_array($instructors)) {
					 $uid = '';
					 foreach($instructors as $i) { if ($obj['modified_by']==$i) {$uid=$i;} }
					 if ($uid=='') { $uid = $instructors[0]; }
					 $uname = ($uid<>'') ? $this->ocw_user->username($uid) : 'Instructor';
					$uname = '<b>'.$uname.'</b>';
			} else {
					$uname = '<b>Instructor</b>';
			}
		

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
						$this->ocw_utils->create_co_img($cid,$mid,$obj['object_id'],$obj['location'],$filter,'rep');
  			} else {
    				$html .= '<h3>'.$uname.' rejected replacement:</h3>'.
    				$this->ocw_utils->create_co_img($cid,$mid,$obj['object_id'],$obj['location'],$filter,'rep');
    				$html .= '<br style="clear:both"/><br/><h3>Reason:</h3>'.
    				(($obj['unsuitable_reason']=='') ? 'No reason provided' : $obj['unsuitable_reason']); 
  			} 
		}

		return $html;
 }



}
?>
