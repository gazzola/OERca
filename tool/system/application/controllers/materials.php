<?php
/**
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Materials extends Controller {

  public function __construct()
  {
    parent::Controller();	
    $this->load->model('tag');
    $this->load->model('mimetype');
    $this->load->model('course');
    $this->load->model('material');
    $this->load->model('coobject');
    $this->load->model('ocw_user');
    $this->load->model('school');
    $this->load->model('subject');
    $this->load->model('dbmetadata');
    $this->load->model('instructors');
  }

  public function index($cid, $caller="") { $this->home($cid, $caller); }

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
      get_enum_vals('ocw', 'ocw_courses', 'level');
    foreach ($clevelsindb as $levelval) {
      $courselevel[$levelval] = $levelval;
    }

    $courselength = NULL;
    $clengthindb = $this->dbmetadata->
      get_enum_vals('ocw', 'ocw_courses', 'length');
    foreach ($clengthindb as $lengthval) {
      $courselength[$lengthval] = $lengthval;
    }

    $term = NULL;
    $termnamesindb = $this->dbmetadata->
      get_enum_vals('ocw', 'ocw_courses', 'term');
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

	public function update($cid,$mid,$field,$val,$resp=true)
	{
    $data = array($field=>$val);
    $this->material->update($mid, $data);            
		if ($resp) {
			$this->ocw_utils->send_response('success');            
			exit;
		}
	}

	public function add_comment($cid,$mid,$comments)
	{
	   $data['comments'] = $comments;
	   $this->material->add_comment($mid, getUserProperty('id'), $data);
     $this->ocw_utils->send_response('success');
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
		$data['select_questions_to'] = array('dscribe2'=>'dScribe2', 'instructor'=>'Instructor');
		if ($role == 'dscribe2') { $data['select_questions_to']['ipreview'] = 'IP Review Team'; }
		if ($view == 'aitems') { $data['select_response_types'] = array('all'=>'All',
																																		'general'=>'General Questions',
																																	  'fairuse'=>'Fair Use Questions',
																																	  'permission'=>'Permission Questions',
																																	  'commission'=>'Commission Questions',
																																	  'retain'=>'No Copyright Questions',
																																	 ); }

		/* info for queries sent to instructor */
		if ($questions_to=='instructor' || ($role=='dscribe1' && $questions_to=='') || $role=='') {
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

		} elseif ($questions_to=='dscribe2' || ($role=='dscribe2' && $questions_to=='')) { // dscribes page info
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
				$q2 = ($questions_to == '') ? 'instructor' : $questions_to;
				$data['questions_to'] = $q2; 

    		$this->layout->buildPage('materials/askforms/dscribe1/index', $data);

		} elseif ($role == 'dscribe2') {
				$q2 = ($questions_to == '') ? 'dscribe2' : $questions_to;
				$data['questions_to'] = $q2; 

    		$this->layout->buildPage('materials/askforms/dscribe2/index', $data);

		} elseif ($role == 'ipreviewer') {
				$q2 = ($questions_to == '') ? 'ipreview' : $questions_to;
				$data['questions_to'] = $q2; 

    		$this->layout->buildPage('materials/askforms/ipreviewer/index', $data);

		} else {	// default: instructor view (no one really needs to login for this view)
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
				$this->coobject->add_zip($cid, $mid,getUserProperty('id'),$_FILES);
				$this->update($cid,$mid,'embedded_co','1',false);
				flashMsg('Content objects added');
				redirect("materials/edit/$cid/$mid/", 'location');
		}
	}

	public function update_object($cid, $mid, $oid, $field, $val='') 
 	{
		if ($field=='rep' or $field=='irep') {
				if ($this->coobject->replacement_exists($cid, $mid, $oid)) {
						$this->coobject->update_rep_image($cid, $mid, $oid, $_FILES);
				} else {
						$this->coobject->add_replacement($cid, $mid, $oid, array(), $_FILES);
				}
				
				$rnd = time().rand(10,10000); // used to overcome caching problem
				
				if ($field == 'rep') {
						redirect("materials/object_info/$cid/$mid/$oid/$rnd", 'location');
				} elseif($field=='irep') {
						redirect("materials/askforms/$cid/$mid/$rnd", 'location');
				}
				exit;

		} else {
				if ($field=='action_type') {
						$lgcm = 'Changed action type to '.$val;
						$this->coobject->add_log($oid, getUserProperty('id'), array('log'=>$lgcm));
						$data = array($field=>$val);
						$this->coobject->update($oid, $data);
				} elseif ($field=='done') {
						$lgcm = 'Changed cleared status to '.(($val==1)?'"yes"':'"no"');
						$this->coobject->add_log($oid, getUserProperty('id'), array('log'=>$lgcm));
						$data = array($field=>$val);
						$this->coobject->update($oid, $data);
				} elseif ($field=='fairuse_rationale')
				{
					$this->coobject->add_fairuse_rationale($oid, getUserProperty('id'), array('rationale'=>$val));
				} elseif ($field=='commission_rationale')
				{
					$this->coobject->add_commission_rationale($oid, getUserProperty('id'), array('rationale'=>$val));
				} elseif ($field=='retain_rationale')
				{
					$this->coobject->add_retain_rationale($oid, getUserProperty('id'), array('rationale'=>$val));
				} elseif ($field=='question')
				{
					$this->coobject->add_additional_question($oid, getUserProperty('id'), array('question'=>$val,'role'=>'instructor'));
				} elseif ($field=='dscribe2_question')
				{
					$this->coobject->add_additional_question($oid, getUserProperty('id'), array('question'=>$val,'role'=>'dscribe2'));
				} else {
					$data = array($field=>$val);
					$this->coobject->update($oid, $data);
				}
		}

    $this->ocw_utils->send_response('success');
   	exit;
	}

	public function update_contact($cid, $mid, $oid, $field, $val='') 
 	{
		$data = array($field=>$val);
		$this->coobject->update_contact($oid, getUserProperty('id'), $data);
	
	    $this->ocw_utils->send_response('success');
	   	exit;
	}

	public function update_replacement($cid, $mid, $oid, $field, $val='') 
 	{
	   $data = array($field=>$val);
	   $this->coobject->update_replacement($oid, $data);
     $this->ocw_utils->send_response('success');
     exit;
	}

	public function add_object_comment($oid,$comments,$type='original')
	{
	   $data['comments'] = $comments;
	   $this->coobject->add_comment($oid, getUserProperty('id'), $data,$type);
     $this->ocw_utils->send_response('success');
     exit;
	}

	public function add_object_question($oid,$question,$type='original')
	{
	   $data['question'] = $question;
	   $this->coobject->add_question($oid, getUserProperty('id'), $data, $type);
     $this->ocw_utils->send_response('success');
     exit;
	}

	public function update_object_question($oid,$qid,$answer,$type='original',$status='')
	{
	   $data['answer'] = $answer;
		 if ($status<>'') { $data['status'] = $status; }
	   $this->coobject->update_question($oid, $qid, $data,$type);
     $this->ocw_utils->send_response('success');
	}

	public function update_questions_status($oid, $status, $role, $type='original')
	{
	   $data['status'] = $status;
	   $this->coobject->update_questions_status($oid, $data, $role, $type);
     $this->ocw_utils->send_response('success');
	}

	public function update_object_copyright($oid,$field,$val,$type='original')
	{
		 $data = array($field=>$val);
		 if ($this->coobject->copyright_exists($oid, $type)) {
	   		 $this->coobject->update_copyright($oid, $data,$type);
		 } else {
	   		 $this->coobject->add_copyright($oid, $data,$type);
		 }
     $this->ocw_utils->send_response('success');
	}

	public function object_info($cid,$mid,$oid)
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
		
		// get the instructor question
		$question = $this->coobject->getQuestion($oid, "instructor");
		
		// get the dscribe2 question
		$dscribe2_question = $this->coobject->getQuestion($oid, "dscribe2");
		
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
				  	'question'=> $question,
				  	'dscribe2_question'=>$dscribe2_question,
				);
		$data = array_merge($data, $permission);

    	$this->load->view('default/content/materials/edit_co', $data);
	}
}
?>
