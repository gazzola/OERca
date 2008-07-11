<?php
/**
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Materials extends Controller {

  // format for constructing filename timestamps as YYYY-MM-DD-HHMMSS
  private $date_format = "Y-m-d-His";
  
  public function __construct()
  {
    parent::Controller();	
    $this->load->model('tag');
    $this->load->model('mimetype');
    $this->load->model('postoffice');
    $this->load->model('course');
    $this->load->model('material');
    $this->load->model('coobject');
    $this->load->model('ocw_user');
    $this->load->model('school');
    $this->load->model('subject');
    $this->load->model('dbmetadata');
    $this->load->model('instructors');
    $this->load->library('zip');

    // $this->ocw_utils->dump($_SERVER);	// kwc debugging
    // do this check in the constructor to make sure we always check!
    $this->freakauth_light->check();
  }

  public function index($cid, $caller="") { $this->home($cid, $caller); }

	/**
	 * Process information coming from the snapper tool
	 */
  public function snapper($cid, $mid, $action='')
  {
      if ($action == 'submit') {
          $res = $this->coobject->add_snapper_image($cid, $mid, getUserProperty('id'), $_REQUEST);
          $success = ($res===true) ? 'true' : 'false';
          $msg = ($success=='true') ? 'Image uploaded' : $res;
          $value = array('success'=>$success, 'msg'=>$msg, 'url'=>site_url("materials/edit/$cid/$mid"));
          $this->ocw_utils->send_response($value, 'json');
      } else {
          $data = array('cid'=>$cid, 'mid'=>$mid);
          $data['select_subtypes'] =  $this->coobject->object_subtypes();
          $data['objects'] =  $this->coobject->coobjects($mid);
          $data['numobjects'] = count($data['objects']);
          $this->load->view('default/content/materials/snapper/snapper', $data);
      }
  }


  // TODO: highlight the currently selected field
  // TODO: move stuff into smaller functions, the home function is quite long
  public function home($cid, $caller='', $openpane=NULL)
  {
    $tags =  $this->tag->tags();
    $materials =  $this->material->materials($cid,'',true,true);
    $mimetypes =  $this->mimetype->mimetypes();
    $school_id = $this->school->get_school_list();
    $subj_id = $this->subject->get_subj_list();
    $coursedetails = $this->course->get_course($cid);
    
    // only get instructor details if an instructor is defined for the course
    $instdetails = array(
      "name" => NULL,
      "title" => NULL,
      "info" => NULL,
      "uri" => NULL,
      "imagefile" => NULL
      );
    if (isset($coursedetails['instructor_id'])) {
       $instdetails = $this->instructors->
       get_inst($coursedetails['instructor_id']);
    }
    
    $missing_menu_val = "-- select --";
    $school_id[0] = $missing_menu_val;
    $subj_id[0] = $missing_menu_val;
    // TODO: consider combining enum fetches into a single DB call since
    //      DB queries are expensive operations
    
    // get the enum values for the pulldowns
    $courselevel = NULL;
    $clevelsindb = $this->dbmetadata->
      get_enum_vals('ocw_courses', 'level');
    foreach ($clevelsindb as $levelval) {
      $courselevel[$levelval] = $levelval;
    }

    $courselength = NULL;
    $clengthindb = $this->dbmetadata->
      get_enum_vals('ocw_courses', 'length');
    foreach ($clengthindb as $lengthval) {
      $courselength[$lengthval] = $lengthval;
    }

    $term = NULL;
    $termnamesindb = $this->dbmetadata->
      get_enum_vals('ocw_courses', 'term');
    foreach ($termnamesindb as $termname) {
      $term[$termname] = $termname;
    }

    $curryear = mdate('%Y');

    $year = array(
      ($curryear + 2) => ($curryear + 2),
      ($curryear + 1) => ($curryear + 1),
      ($curryear) => ($curryear),
      ($curryear - 1) => ($curryear - 1),
      ($curryear - 2) => ($curryear - 2),
      ($curryear - 3) => ($curryear - 3),
      ($curryear - 4) => ($curryear - 4),
      ($curryear - 5) => ($curryear - 5)
      );

    // form field attributes
    $coursedescbox = array(
      'name' => 'description',
      'id' => 'description',
      'wrap' => 'virtual',
      'rows' => '20',
      'cols' => '40',
      'value' => $coursedetails['description']
      );

    $coursehighlightbox = array(
      'name' => 'highlights',
      'id' => 'highlights',
      'wrap' => 'virtual',
      'rows' => '5',
      'cols' => '40',
      'value' => $coursedetails['highlights']
      );

    $keywordbox = array(
      'name' => 'keywords',
      'id' => 'keywords',
      'wrap' => 'virtual',
      'rows' => '3',
      'cols' => '40',
      'value' => $coursedetails['keywords']
      );
      
      
    $titlebox = array(
      'name' => 'title',
      'id' => 'title',
      'wrap' => 'virtual',
      'rows' => '10',
      'cols' => '40',
      'value' => $instdetails['title']
      );
      
    $inst_infobox = array(
      'name' => 'info',
      'id' => 'info',
      'wrap' => 'virtual',
      'rows' => '20',
      'cols' => '40',
      'value' => $instdetails['info']
      );
      
    if ($coursedetails['year'] != 0000) {
      $curryear = $coursedetails['year'];
    }
    
    
    $data = array('title'=>'Materials',
      'materials'=>$materials, 
      'mimetypes'=>$mimetypes,
      'cname' => $this->course->course_title($cid), 
      'cid'=>$cid,
      'caller'=>$caller,
      'tags'=>$tags,
      'openpane'=>$openpane,
      'courselevel' => $courselevel,
      'courselength' => $courselength,
      'coursedescbox' => $coursedescbox,
      'coursehighlightbox' => $coursehighlightbox,
      'keywordbox' => $keywordbox,
      'term' => $term,
      'curryear' => $curryear,
      'year' => $year,
      'school_id' => $school_id,
      'subj_id' => $subj_id,
      'coursedetails' => $coursedetails,
      'titlebox' => $titlebox,
      'inst_infobox' => $inst_infobox,
      'instdetails' => $instdetails
      );
      
    $this->layout->buildPage('materials/index', $data);
  }

	public function update($cid,$mid,$field='',$val='',$resp=true)
	{
		$field = (isset($_REQUEST['field']) && $_REQUEST['field']<>'') ? $_REQUEST['field'] : $field;
		$val = (isset($_REQUEST['val'])) ? $_REQUEST['val'] : $val;

    $data = array($field=>$val);
    $this->material->update($mid, $data);            
		if ($resp) {
			$this->ocw_utils->send_response('success');            
			exit;
		}
	}

	public function add_comment($cid,$mid)
	{
		 if (isset($_REQUEST['comments']) && $_REQUEST['comments']<>'') {
	   		 $data['comments'] = $_REQUEST['comments'];
	   		 $this->material->add_comment($mid, getUserProperty('id'), $data);
     		 $this->ocw_utils->send_response('success');
		 } else {
     		 $this->ocw_utils->send_response('Please enter a comment');
		 }
     exit;
	}

	public function	add_material($cid,$type)
	{
		$valid = true;
		$errmsg = '';
		#$this->ocw_utils->dump($_POST);
		#$this->ocw_utils->dump($_FILES,true);

		$idx = ($type=='bulk') ? 'zip_userfile' : 'single_userfile';
		
		if (!isset($_FILES[$idx]['name']) || $_FILES[$idx]['name']=='') {
				$errmsg = 'Please specify a file to upload';
				$valid = false;
				
		} elseif (isset($_FILES[$idx]['name']) && $type=='bulk' && !preg_match('/\.zip$/',$_FILES[$idx]['name'])) {
				$errmsg .= (($errmsg=='') ? '':'<br/>')."Can only upload ZIP files for bulk uploads";
				$valid = false;
		}

		if ($_POST['author']=='') {
				$errmsg .= (($errmsg=='') ? '':'<br/>')."Author field is required.";
				$valid = false;
		}
		
		$role = getUserProperty('role');
		if ($valid == FALSE) {
				flashMsg($errmsg);
				redirect("materials/home/$cid/$role/uploadmat", 'location');
		}	else {
				$r = $this->material->manually_add_materials($cid, $type, $_POST,$_FILES);
				if ($r !== true) {
						flashMsg($r);
						redirect("materials/home/$cid/$role/uploadmat", 'location');
				} else {
						$msg = ($type=='bulk') ? 'Materials have been added.' : 'Added material to course.';
						flashMsg($msg);
						redirect("materials/home/$cid", 'location');
				}
		}	
	}

	public function	remove_material($cid, $mid)
	{
		$this->material->remove_material($cid, $mid);
		flashMsg('Material removed!');
		redirect("materials/home/$cid", 'location');
	}
	
	public function edit($cid, $mid, $caller='', $filter='Any', $openco=FALSE)
	{
		$tags =  $this->tag->tags();
		$mimetypes =  $this->mimetype->mimetypes();
		$objstats =  $this->coobject->object_stats($mid);
		$subtypes =  $this->coobject->object_subtypes();
	        $course = $this->course->get_course($cid); 
		$material =  $this->material->materials($cid,$mid,true);
		$numobjects =  $this->coobject->num_objects($mid,$filter);
		$objects =  $this->coobject->coobjects($mid,'',$filter);

		$data = array('title'=>'Edit Material &raquo; '.$material[0]['name'],
					  'material'=>$material[0], 
					  'coobjects'=>$objects, 
					  'numobjects'=>$numobjects, 
					  'cid'=>$cid,
					  'mid'=>$mid,
	   				'course'=> $course,
	   				'cname' => $course['number'].' '.$course['title'],
				  	'tags'=>$tags,
				  	'mimetypes'=>$mimetypes,
				  	'subtypes'=>$subtypes,
				  	'objstats'=>$objstats,
				  	'caller'=>$caller,
		        'list' => $this->ocw_utils->create_co_list($cid,$mid,$objects),
		        'filter' => $filter, 
						'openpane'=>$openco,
		);

    $this->layout->buildPage('materials/edit_material', $data);
	}


	// displays ask forms for dscribes, dscribes2, instructors && ip review team
	public function askforms($cid, $mid='', $view='', $questions_to='', $responsetype='all')
	{
		$role = getUserProperty('role');

	  /* common data for ask forms */
		$data['cid'] = $cid; 
		$data['mid'] = $mid; 
		$data['role'] = $role; 
		$data['response_type'] = $responsetype; 
		$data['title'] = 'Manage Content Objects &raquo; Ask Form'; 

		$data['course'] =  $this->course->get_course($cid); 
		$material =  $this->material->materials($cid,$mid,true);
		$data['material'] = $material[0]; 

		/* data for html elements */
		$data['select_subtypes'] =  $this->coobject->object_subtypes();
		$data['select_questions_to'] = array('dscribe2'=>'dScribe2', 'instructor'=>'Instructor');
		$data['select_copystatus'] = $this->coobject->enum2array('object_copyright','status'); 
		if ($role == 'dscribe2') { $data['select_questions_to']['ipreview'] = 'IP Review Team'; }
		if ($view == 'aitems') { $data['select_response_types'] = array('all'=>'All',
																																		'general'=>'General Questions',
																																	  'fairuse'=>'Fair Use Questions',
																																	  'permission'=>'Permission Questions',
																																	  'commission'=>'Commission Questions',
																																	  'retain'=>'No Copyright Questions',
																																	 ); }
		if ($view == 'fairuse') { $data['select_actions'] = $this->coobject->enum2array('claims_fairuse','action'); }
		if ($view == 'permission') { $data['select_actions'] = $this->coobject->enum2array('claims_permission','action'); }
		if ($view == 'commission') { $data['select_actions'] = $this->coobject->enum2array('claims_commission','action'); }
		if ($view == 'retain') { $data['select_actions'] = $this->coobject->enum2array('claims_retain','action'); }

		/* info for queries sent to instructor */
		if ($questions_to=='instructor' || ($role == 'instructor' && $questions_to=='') || $role=='') {
				$view = (!in_array($view, array('provenance','replacement','done'))) ? 'provenance' : $view;

				$prov_objects =  $this->coobject->coobjects($mid,'','Ask'); // objects with provenace questions
				$repl_objects =  $this->coobject->replacements($mid,'','','Ask'); // objects with replacement questions
				$num_obj = $num_repl = $num_prov = $num_done = 0;

				if ($prov_objects != null) {	
						foreach($prov_objects as $obj) {
										if ($obj['ask_status'] == 'done') { $num_done++; }
										if ($obj['ask_status'] <> 'done') { $num_prov++; }
										$num_obj++;
						}
				}
				if ($repl_objects != null) {	
						foreach($repl_objects as $obj) {
										if ($obj['ask_status'] == 'done') { $num_done++; }
										if ($obj['ask_status'] <> 'done') { $num_repl++; }
										$num_obj++;
						}
				}
				
				$data['view'] = $view; 
				$data['num_done'] = $num_done; 
				$data['num_prov'] = $num_prov; 
				$data['num_repl'] = $num_repl; 
				$data['numobjects'] = $num_obj;
				$data['need_input'] = $num_prov + $num_repl;
				$data['prov_objects'] = $prov_objects; 
				$data['repl_objects'] = $repl_objects; 
				$data['num_avail'] = array('provenance'=>$num_prov, 'replacement'=>$num_repl, 'done'=>$num_done);
				$data['list'] = $this->ocw_utils->create_co_list($cid,$mid,$prov_objects);

		} elseif ($questions_to=='dscribe2' || (($role=='dscribe1' || $role=='dscribe2') && $questions_to=='')) { // dscribes page info
				$view = ($view=='') ? 'general' : $view;

				$info =  $this->coobject->ask_form_info($cid, $mid); 

				$data['view'] = $view; 
				$data['cos'] = $info[$view]; 
				$data['num_avail'] = $info['num_avail'];
				$data['need_input'] = $info['need_input'];
				$data['num_general'] = $info['num_avail']['general']; 
				$data['num_fairuse'] = $info['num_avail']['fairuse']; 
				$data['num_permission'] = $info['num_avail']['permission']; 
				$data['num_commission'] = $info['num_avail']['commission']; 
				$data['num_retain'] = $info['num_avail']['retain']; 
				$data['num_done'] = $info['num_avail']['aitems']; 

		} elseif (($questions_to=='ipreview' && in_array($role,array('dscribe2','ipreviewer'))) || 
              ($role=='ipreviewer' && $questions_to=='')) { // ip review page info
				// TODO
		}

		/* go to the right view */
		if ($role == 'dscribe1') {
				$q2 = ($questions_to == '') ? 'dscribe2' : $questions_to;
				$data['questions_to'] = $q2; 

    		$this->layout->buildPage('materials/askforms/dscribe1/index', $data);

		} elseif ($role == 'dscribe2') {
			$q2 = ($questions_to == '') ? 'dscribe2' : $questions_to;
			$data['questions_to'] = $q2; 
			
			// check matchup of dscribe2 and dscribe
			$passUid = getUserProperty('id');
			$user_rels = $this->ocw_user->get_users_by_relationship($passUid,'dscribe1');
	        if ($user_rels == false) {
			// use Name ($passName) istead of the User ID  ($passUid)
			$passName = getUserProperty('name');
	        	$data['alert_missing_dscribe']="Alert: Could not find corresponding dscribes for the dscribe2 - ".$passName.".";   
	        }
	        
    		$this->layout->buildPage('materials/askforms/dscribe2/index', $data);

		} elseif ($role == 'ipreviewer') {
				$q2 = ($questions_to == '') ? 'ipreview' : $questions_to;
				$data['questions_to'] = $q2; 

    		$this->layout->buildPage('materials/askforms/ipreviewer/index', $data);

		} else {	// default: instructor view (no one really needs to login for this view)
    			$user_rels = $this->ocw_user->dscribes($cid);
    			$course =  $this->course->get_course($cid); 
                if ($user_rels[0] == NULL) {
                	$data['alert_missing_dscribe']="Alert: Could not find any dscribe for course - ".$course['title'].".";    
                }
    		$this->layout->buildPage('materials/askforms/instructor/index', $data);
		}
	}

	public function content_objects($cid, $mid,$filter='Any')
	{
		$objects =  $this->coobject->coobjects($mid,'',$filter);
		$data['numobjects'] = count($objects);
		$data['list'] = $this->ocw_utils->create_co_list($cid,$mid,$objects);
		$data['css'] = property('app_css');
		$data['script'] = property('app_js');
		$data['img'] = property('app_img');
		$data['cid'] = $cid; 
		$data['mid'] = $mid; 
		$data['filter'] = $filter; 
		$this->load->view('default/content/materials/co', $data);
	}

	public function	remove_object($cid, $mid, $oid, $type='original', $rid='')
	{
		if ($type=='original') {
				flashMsg('Content object removed!');
				$this->coobject->remove_object($cid, $mid, $oid);
		} else {
				flashMsg('Replacement object removed!');
				$this->coobject->remove_replacement($cid, $mid, $oid, $rid);
		}
		redirect("materials/edit/$cid/$mid", 'location');
	}
	

	public function add_object($cid, $mid) 
 	{
		$valid = true;
		$errmsg = '';

		if (!isset($_FILES['userfile_0']['name']) || $_FILES['userfile_0']['name']=='') {
				$errmsg = 'Please specify a file to upload';
				$valid = false;			
		} 

		if ($_POST['location']=='') {
				$errmsg .= (($errmsg=='') ? '':'<br/>')."Location field is required.";
				$valid = false;
		}
		if ($_POST['ask']=='') {
				$errmsg .= (($errmsg=='') ? '':'<br/>')."Ask Instructor field is required.";
				$valid = false;
		}
		
		if ($valid == FALSE) {
				flashMsg($errmsg);
				$role = getUserProperty('role');
				redirect("materials/edit/$cid/$mid/$role/Any/true", 'location');
		}	else {
			$this->coobject->add($cid, $mid,getUserProperty('id'),$_POST,$_FILES);
			$this->update($cid,$mid,'embedded_co','1',false);
			flashMsg('Content object added');
			redirect("materials/edit/$cid/$mid/", 'location');
		}
		
	}

	public function add_object_zip($cid, $mid) 
 	{
		$valid = true;
		$errmsg = '';

		if (!isset($_FILES['userfile']['name']) || $_FILES['userfile']['name']=='') {
				$errmsg = 'Please specify a ZIP file to upload';
				$valid = false;				
		} elseif (isset($_FILES['userfile']['name'])  && !preg_match('/\.zip$/',$_FILES['userfile']['name'])) {
				$errmsg .= (($errmsg=='') ? '':'<br/>')."Can only upload ZIP files for bulk uploads";
				$valid = false;
		}
	
		if ($valid == FALSE) {
				flashMsg($errmsg);
				$role = getUserProperty('role');
				redirect("materials/edit/$cid/$mid/$role/Any/true", 'location');
		}	else {
				$res = $this->coobject->add_zip($cid, $mid,getUserProperty('id'),$_FILES);
				$this->update($cid,$mid,'embedded_co','1',false);
				flashMsg($res);
				redirect("materials/edit/$cid/$mid/", 'location');
		}
	}

	public function update_object($cid, $mid, $oid, $field='', $val='') 
 	{
		$field = (isset($_REQUEST['field']) && $_REQUEST['field']<>'') ? $_REQUEST['field'] : $field;
		$val = (isset($_REQUEST['val'])) ? $_REQUEST['val'] : $val;

		$wrong_type='';
		if ($field=='rep' or $field=='irep') {
			if (is_array($_FILES['userfile_0'])) {
				// check to see whether this is a image file
				$type = $_FILES['userfile_0']['type'];
				$result = strchr($type, "image/");
				if ($result)
				{
					$data = array('location'=>$_REQUEST['location']);
					if ($this->coobject->replacement_exists($cid, $mid, $oid)) {
							$this->coobject->update_rep_image($cid, $mid, $oid, $data, $_FILES);
					} else {
							$this->coobject->add_replacement($cid, $mid, $oid, $data, $_FILES);
					}
				}
				else
				{
					// generate alert
					$wrong_type="wrong_type";
				}
			}
			
			if ($field == 'rep') {
				redirect("materials/object_info/$cid/$mid/$oid/upload/$wrong_type", 'location');
			} elseif($field=='irep') {
					$rnd = time().rand(10,10000); // used to overcome caching problem
					if (isset($_POST['view'])) { $rnd = $_POST['view']; }
					redirect("materials/askforms/$cid/$mid/$rnd", 'location');
			}

		} else {
				if ($field=='action_type') {
						$data = array($field=>$val);
						$res = $this->coobject->update($oid, $data);
	
						if ($res===true) {
								// update claim action as well.
								if ($val<>'Search' && $val<>'Re-Create' && $val<>'Remove and Annotate') {
										$claimtype = array('Permission'=>'permission','Commission'=>'commission', 'Fair Use'=>'fairuse',
																			 'Retain: Public Domain'=>'retain',
																			 'Retain: No Copyright'=>'retain',
																			 'Retain: Permission'=>'retain');
										$cl = $this->coobject->claim_exists($oid,$claimtype[$val]);
										if ($cl!== false) {
											  $ndata = array('action'=>$val);
												$this->coobject->update_object_claim($oid, $cl[0]['id'], $claimtype[$val], $ndata);
										}

               			/* EMAIL DSCRIBE2 */
                		$this->postoffice->dscribe1_dscribe2_email($cid, $mid, $oid,'original');
								}

								$lgcm = 'Changed action type to '.$val;
								$this->coobject->add_log($oid, getUserProperty('id'), array('log'=>$lgcm));

    						$this->ocw_utils->send_response('success');

						} else {
    						$this->ocw_utils->send_response($res);
						}

				} elseif ($field=='ask_status') {
						$data = array($field=>$val);
						$this->coobject->update($oid, $data);

						/* send email to dscribe from instructor */
						if ($val == 'done') { $this->postoffice->instructor_dscribe1_email($cid, $mid, $oid,'original'); }

				} elseif ($field == 'ask_inst') {
						$field = 'ask';
						$data = array($field=>$val);
						if ($val == 'yes') { $data['ask_status']='new'; }
						$this->coobject->update($oid, $data);

				} elseif ($field=='ask_dscribe2') {
						$data = array($field=>$val);
						if ($val=='yes') { $data['ask_dscribe2_status']='new'; }
						$this->coobject->update($oid, $data);

				} elseif ($field=='done') {
						$lgcm = 'Changed cleared status to '.(($val==1)?'"yes"':'"no"');
						$this->coobject->add_log($oid, getUserProperty('id'), array('log'=>$lgcm));
						$data = array($field=>$val);
						$this->coobject->update($oid, $data);

				} elseif ($field=='fairuse_rationale' || $field=='commission_rationale' || $field=='retain_rationale') {
					 	$claimtype = preg_replace('/_rationale/','',$field);
					 	$this->coobject->add_object_claim($oid, getUserProperty('id'), $claimtype, array('rationale'=>$val));
		
				} elseif ($field=='inst_question') {
						$this->coobject->add_additional_question($oid, getUserProperty('id'), array('question'=>$val,'role'=>'instructor'));

				} elseif ($field=='dscribe2_question') {
						$this->coobject->add_additional_question($oid, getUserProperty('id'), array('question'=>$val,'role'=>'dscribe2'));

            /* EMAIL DSCRIBE2 */
            $this->postoffice->dscribe1_dscribe2_email($cid, $mid, $oid,'original');

				} else {
					
					$data = array($field=>$val);
					$this->coobject->update($oid, $data);
				}

		}

    $this->ocw_utils->send_response('success');
	}

	public function update_contact($cid, $mid, $oid) 
 	{
 		if (isset($_REQUEST['field']) && $_REQUEST['field']<>'' && isset($_REQUEST['val']) && $_REQUEST['val']<>'') {
 				$field = $_REQUEST['field'];
 				$val = $_REQUEST['val'];
 		}

		$data = array($field=>$val);

    $this->coobject->add_object_claim($oid, getUserProperty('id'), 'permission', $data); 

		/* EMAIL DSCRIBE2 */
		$this->postoffice->dscribe1_dscribe2_email($cid, $mid, $oid,'original');

    $this->ocw_utils->send_response('success');
    exit;
	}

	public function update_replacement($cid, $mid, $oid, $rid) 
 	{
		$field = (isset($_REQUEST['field']) && $_REQUEST['field']<>'') ? $_REQUEST['field'] : '';
		$val = (isset($_REQUEST['val'])) ? $_REQUEST['val'] : '';

 		if ($field=='replacement_question')
		{
			$this->coobject->add_replacement_question($rid, $oid, getUserProperty('id'), array('question'=>$val,'role'=>'instructor'));
		}
		else
		{
	   		$data = array($field=>$val);
	   		$this->coobject->update_replacement($rid, $data);
		}
		 /* send email to dscribe from instructor */	
		 if ($field=='ask_status' and $val=='done') {
		     $this->postoffice->instructor_dscribe1_email($cid, $mid, $oid,'replacement');
		 }

     $this->ocw_utils->send_response('success');
     exit;
	}

	public function add_object_comment($oid,$type='original')
	{
		 if (isset($_REQUEST['comments']) && $_REQUEST['comments']<>'') {
	   		 $data['comments'] = $_REQUEST['comments'];
	  		 $this->coobject->add_comment($oid, getUserProperty('id'), $data,$type);
         $this->ocw_utils->send_response('success');
		 } else {
         $this->ocw_utils->send_response('Please enter a comment');
		 }
     exit;
	}

	public function add_object_question($cid,$mid,$oid,$role,$type='original')
	{
	   $data['role'] = $role;
		 if (isset($_REQUEST['question']) && $_REQUEST['question']<>'') {
	   		 $data['question'] = $_REQUEST['question'];
	   		 $this->coobject->add_question($oid, getUserProperty('id'), $data, $type);

         /* EMAIL DSCRIBE2 */
         $this->postoffice->dscribe1_dscribe2_email($cid, $mid, $oid,$type);

     		 $this->ocw_utils->send_response('success');
		 } else {
     		 $this->ocw_utils->send_response('Please enter a question');
		 }
     exit;
	}
	
	public function update_object_question($oid,$qid,$type='original',$status='')
	{
	   $data['answer'] = (isset($_REQUEST['answer'])) ? $_REQUEST['answer']:'';
	   if ($status<>'') { $data['status'] = $status; }
	   $this->coobject->update_question($oid, $qid, $data,$type);
           $this->ocw_utils->send_response('success');
	}

	public function update_questions_status($cid, $mid, $oid, $status, $role, $type='original')
	{
	   $data['status'] = $status;
	   $this->coobject->update_questions_status($oid, $data, $role, $type);

	   /* send email to dscribe1 */
	   if ($status=='done') {
	       $this->postoffice->dscribe2_dscribe1_email($cid, $mid, $oid,$type);
	   }

     $this->ocw_utils->send_response('success');
	}

	public function update_object_claim($cid, $mid, $oid, $claimtype, $claimid)
	{
		 $field = (isset($_REQUEST['field']) && $_REQUEST['field']<>'') ? $_REQUEST['field'] : '';
		 $value = (isset($_REQUEST['val'])) ? $_REQUEST['val'] : '';

		 $data[$field] = $value;
	   $this->coobject->update_object_claim($oid, $claimid, $claimtype, $data);

		 /* send email to dscribe1 */
		 if ($field=='status' && $value=='done') {
		        $this->postoffice->dscribe2_dscribe1_email($cid, $mid, $oid,'original');

		 /* send email to ip review team */
		 } elseif ($field=='status' && $value=='ip review') {

		 /* send email to commission review team */
		 } elseif ($field=='status' && $value=='commission review') {

		 }

     $this->ocw_utils->send_response('success');
	}

	public function update_object_copyright($oid,$type='original')
	{
		 $data = array($_REQUEST['field']=>$_REQUEST['val']);
		 if ($this->coobject->copyright_exists($oid, $type)) {
	   		 $this->coobject->update_copyright($oid, $data,$type);
		 } else {
	   		 $this->coobject->add_copyright($oid, $data,$type);
		 }
     $this->ocw_utils->send_response('success');
	}

	public function object_info($cid,$mid,$oid,$tab='', $alert_wrong_mimetype='')
	{
		$subtypes =  $this->coobject->object_subtypes();
		$obj = $this->coobject->coobjects($mid,$oid);
		$repl_objects =  $this->coobject->replacements($mid,$oid); 
		$objstats =  $this->coobject->object_stats($mid);
		
		// get the fairuse retional
		$fairuse_rationale = $this->coobject->getRationale($oid, "claims_fairuse");
		
		// get the commission retional
		$commission_rationale = $this->coobject->getRationale($oid, "claims_commission");
		
		// get the retain retional
		$retain_rationale = $this->coobject->getRationale($oid, "claims_retain");
		
		// get the permission contact
		$permission = $this->coobject->getClaimsPermission($oid);
		
    if (isset($_REQUEST['tab'])) { $tab = $_REQUEST['tab'][0]; }
    if ($tab=='upload') { $_REQUEST['viewing'] = 'replacement'; }

		//$this->ocw_utils->dump($obj[0],true);

		$data = array(
								'obj'=>$obj[0],
								'cid'=>$cid,
								'mid'=>$mid,
								'user'=>getUserProperty('user_name'),
								'subtypes'=>$subtypes,
							 	'objstats' => $objstats,
								'repl_obj'=>$repl_objects[0],
								'fairuse_rationale' => $fairuse_rationale,
								'commission_rationale' => $commission_rationale,
								'retain_rationale' => $retain_rationale,
				        'tab'=> (($tab<>'') ? array(ucfirst($tab)) : array('Status')),
				        'viewing' => ((isset($_REQUEST['viewing'])) ? $_REQUEST['viewing']: ''),
								'action_types' => $this->coobject->enum2array('objects','action_type'), 
								'alert_wrong_mimetype' => $alert_wrong_mimetype,
								
			      );
		$data = array_merge($data, $permission);
   	$this->load->view('default/content/materials/edit_co', $data);
	}
	
	
	
	/**
	  * Manipulate the materials for a course. This method gets form input and
	  * calls other functions to do the real work
	  * 
	  * @access   public
	  * @return   void
	  */
	  public function manipulate($cid)
	  {
	    $err_msg = "";
	    $conf_msg = "";
	    // TODO: Really think about form validation from the materials table
	    if (!array_key_exists('select_material', $_POST)) {
	      $err_msg = "No items were selected. Please select at least one item.";
	      flashMsg($err_msg);
	      redirect("materials/home/$cid", "location");
	    }
	    if (array_key_exists('delete', $_POST)) {
	      foreach ($_POST['select_material'] as $material_id) {
	        $this->material->remove_material($cid, $material_id);
        }
        $conf_msg = "Removed selected materials.";
        flashmsg($conf_msg);
        redirect("materials/home/$cid", 'location');
	    } elseif (array_key_exists('download', $_POST)) {
	      $material_list = $this->material->
	        get_material_paths($cid, $_POST['select_material']);
	      $file_list = $this->_get_material_files($material_list);
	      // $this->ocw_utils->dump($file_list);
	      $this->_download_material($file_list);
	    } else {
	      echo "We really shouldn't see this at all!";
	    }
	  }
	  
	  
	  /**
	    * Locate the files for the specified materials
	    * 
	    * @access   private
	    * @param    array the list of materials
	    * @return   mixed array listing the files for the material along with
	    *           the material_id, course number, course title, 
	    *           material name
	    */
	  private function _get_material_files($material_list)
	  {
	    $material_files = array();
	    foreach ($material_list as $material_info)
	    {
	      $file_names = array();
	      /* TODO: change the way materials are named so full names are
	       * retained in the DB
	       * construct the path to the materials dir */
	      $mat_path = $this->config->item('upload_path');
	      $mat_path .= "cdir_" . $material_info['course_dir'];
	      $mat_path .= "/mdir_" . $material_info['material_dir'];
	      /* find all items in the $mat_path directory and add to the
	       * $material_files array if they are files instead of directories */
	      $all_dir_items = (scandir($mat_path));
	      // $this->ocw_utils->dump($material_info);
	      foreach ($all_dir_items as $file_name) {
	        $rel_path = "$mat_path/$file_name";
	        if (is_file($rel_path))
	        {
	          $file_names[] = $rel_path;
	        }
	      }
	      
	      /* TODO: Figure out what fields we need from the DB to allow
         * sensible organization of the zip archives created for multiple
         * material downloads */
	      $material_files[] = array(
          'material_id' => $material_info['material_id'],
          'school_name' => $material_info['school_name'],
          'course_number' => $material_info['course_number'],
          'course_title' => $material_info['course_title'],
          'material_name' => $material_info['material_name'],
          'material_date' => $material_info['material_date'],
          'file_names' => $file_names,
          );
	    }
	    return($material_files);
	  }
	  
	  
	  /**
	    * Download the files for a selected set of materials. A zip
	    * file is created if there is more than one file. The organization
	    * of the zip file is based on total number of files in the
	    * archive. There is a configurable threshold, after which
	    * the files are separated out into subfolders.
	    *
	    * @access   private
	    * @param    array a list of files
	    * @param    int (optional) the max number of files that will be
	    *           put in a zip composed of a single folder
	    * @return   void
	    */
	    private function _download_material($file_list, $max_in_single_folder = 10)
	    {
	      $this->load->helper('download');
	      $this->load->library('zip');
	      
	      // get a timestamp formatted as YYYY-MM-DD-HHMMSS
	      $timestamp = date($this->date_format);
	      
	      /* add a top level folder in zip files so files aren't sprayed all 
	       * over the filesystem. Use "oer_materials-$timestamp" defined above
	       * as a folder name */
	      $parent_folder = "oer_materials-$timestamp";
	      
	      $num_files = 0;
	      // calculate the total number of files in the requested materials
	      foreach ($file_list as $mat_files) {
	        $num_files += count($mat_files['file_names']);
	      }
	      // directly download the file for a single file
	      if ($num_files == 1) {
	        $file_name = $file_list[0]['file_names'][0];
	        $data = file_get_contents($file_name);
	        $name = $this->_get_export_file_name($file_name, 0, $file_list[0]);
	        force_download($name, $data);
	      } else {
	        foreach ($file_list as $mat_files) {
	          foreach ($mat_files['file_names'] as $file_num => $file_name) {
	            $data = file_get_contents($file_name);
	            $name = $this->
	              _get_export_file_name($file_name, $file_num, $mat_files);
	            /* for between 2 and $max_in_single_folder files 
    	         * put everything in 1 folder */  
	            if (($num_files > 1) && ($num_files <= $max_in_single_folder)) {
	              $name = "$parent_folder/$name";
	            } 
	            /* for more than $max_in_single_folder files
    	         * create subfolders for materials */
	            elseif ($num_files > $max_in_single_folder) {
	              $name = "$parent_folder/{$mat_files['material_name']}_{$mat_files['material_date']}/$name";
	            }
	            $this->zip->add_data($name, $data);
	          }
	        }
	        $this->zip->download("$parent_folder.zip");
   	      $this->zip->clear_data(); // clear cached data
	      }
	    }
	    
	    
	    /**
	      * Determine the name for the material files from the metadata
	      * this is function attempts to do the best it can.
	      * 
	      * @access   private
	      * @param    string the original name of the file
	      * @param    int the array index value corresponding to the current filename
	      * @param    array mixed array containing the list of files for a material
	      *           and related metadata
	      * @return   string the more sensible, human readable file name
	      */
	    private function _get_export_file_name($orig_file_name, $file_num, $mat_file_list)
	    {
	      $name = "";
	      if ($mat_file_list['school_name']) {
          $name .= $mat_file_list['school_name'] . "_";
        }
        if ($mat_file_list['course_number']) {
          $name .= $mat_file_list['course_number'] . "_";
        }
        if ($mat_file_list['course_title']) {
          $name .= $mat_file_list['course_title'] . "_";
        }
        if ($mat_file_list['material_name']) {
          $name .= $mat_file_list['material_name'] . "_";
        }
        $name .= $mat_file_list['material_date'] . "_";
        $name .= ($file_num + 1);
        $name .= "." . pathinfo($orig_file_name, PATHINFO_EXTENSION);
        
        return $name;
	    }
	    
	    /**
	     * Download all replacement for content objects for this material
	     */
	    public function	download_all_rcos($cid, $mid)
		{
			$name = $this->material->getMaterialName($mid);
			
			$rcos = $this->coobject->replacements($mid);
			if ($rcos != null) {
			    foreach($rcos as $rco) {
					$object_id=$rco['object_id'];
					// object file path and name
					$object_filepath = $this->coobject->object_path($cid, $mid, $object_id);
					$object_filename = $this->coobject->object_filename($object_id);
					// the replacement file extension
					$rep_name = $rco['name'];
					$rep_name_parts=explode(".", $rep_name);
					$rep_extension = ".".$rep_name_parts[1];
					// the file path to the replacement data
					$rep_filepath=$object_filepath."/".$object_filename."_rep".$rep_extension;
					$this->zip->read_file(getcwd().'/uploads/'.$rep_filepath);
				}
			}
		
			// Download the file to your desktop. Name it "SITENAME_IMSCP.zip"
			$this->zip->download($name.'_RCOs.zip');
			
   	      	$this->zip->clear_data(); // clear cached data
		}
		
		
		/**
	     * Download the replacement content object
	     */
	    public function	download_rco($cid, $mid, $oid, $rid)
		{
			$name = $this->material->getMaterialName($mid);
			
			$rcos = $this->coobject->replacements($mid, $oid, $rid);
			if ($rcos != null) {
				// should be just one object
			    $rco=$rcos[0];
				$object_id=$rco['object_id'];
				// object file path and name
				$object_filepath = $this->coobject->object_path($cid, $mid, $object_id);
				$object_filename = $this->coobject->object_filename($object_id);
				// the replacement file extension, try all three: png, gif, jpg
				// the file path to the replacement data
				$ext_array = array(".png", ".gif", ".jpg");
				$rep_filepath=$object_filepath."/".$object_filename."_rep";
				$foundExt = "";
				foreach ($ext_array as $ext)
				{
					if (strlen($foundExt) == 0)
					{
						$rep_filepath_final = $rep_filepath.$ext;
						if (is_readable(property('app_uploads_path').$rep_filepath_final))
						{
							// find the replacement file
							$foundExt = $ext;
						}
					}
				}
				
				// get the replacement file name and data and download]
				if (strlen($foundExt) != 0)
				{
					$name = $rco['name'];
					// check to see whether file name is end with the extension. If not, append the extension to it
					$extLen = strlen($foundExt);
					// Look at the end of file for the substring the size of EndStr
					$nameStrEnd = substr($name, strlen($name) - $extLen);
					if ($nameStrEnd != $foundExt)
					{
						$name = $name.$foundExt;
					}
					
					$data = file_get_contents(getcwd().'/uploads/'.$rep_filepath_final); // Read the file's contents
					force_download($name, $data);
				}
			}
		}

		public function valid_recommendation($oid, $recommendation) 
		{
				$res = $this->coobject->valid_recommendation($oid, $recommendation);	
				return ($res===true) ?
    						$this->ocw_utils->send_response('success'):
    						$this->ocw_utils->send_response($res);
				exit;
		}		
}
?>
