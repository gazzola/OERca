<?php
/**
 * @package     OCW Tool
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Materials extends Controller {

  // format for constructing filename timestamps as YYYY-MM-DD-HHMMSS
  private $date_format = "Y-m-d-His";
  // absolute path to directory where materials are recomposed
  private $recomp_dir_path = NULL;

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
    $this->load->library('oer_decompose');

    // $this->ocw_utils->dump($_SERVER);        // kwc debugging
    // do this check in the constructor to make sure we always check!
    $this->freakauth_light->check();
  }

  public function index($cid) { $this->home($cid); }

  // TODO: highlight the currently selected field
  public function home($cid, $author=0,$material_type=0,$file_type=0)
  {
    $tags =  $this->tag->tags();
    $mimetypes =  $this->mimetype->mimetypes();
    $materials =  $this->material->faceted_search_materials($cid,'',true,true, $author, $material_type, $file_type); //faceted search mbleed

    $data = array('title'=>'Materials',
		  'cid'=>$cid,
		  'cname' => $this->course->course_title($cid),
		  'materials'=>$materials,
		  'mimetypes'=>$mimetypes,
		  'tags'=>$tags,
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

  // edit material information
  public function editinfo($cid, $mid)
  {
    $tags =  $this->tag->tags();
    $mimetypes =  $this->mimetype->mimetypes();
    $course = $this->course->get_course($cid);
    $material =  $this->material->materials($cid,$mid,true);
    $co_count = $this->coobject->num_objects($mid);

    $data = array(
		  'material'=>$material[0],
		  'cid'=>$cid,
		  'mid'=>$mid,
		  'course'=> $course,
		  'tags'=>$tags,
		  'mimetypes'=>$mimetypes,
		  'co_count'=>$co_count,
		  );

    $this->load->view(property('app_views_path').'/materials/_edit_material_info.php', $data);
  }

  // edit material comments
  public function editcomments($cid, $mid)
  {
    $material =  $this->material->materials($cid,$mid,true);
    $data = array('material'=>$material[0], 'cid'=>$cid, 'mid'=>$mid);
    $this->load->view(property('app_views_path').'/materials/_edit_material_comments.php', $data);
  }

  // add material comments
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

  /**
   * Add material functionality from add form
   * may include zip files. This will go away
   * when ctools import comes on line
   */
  private function _manually_add_materials($cid, $type, $details, $files)
  {
    //$this->ocw_utils->dump($details);             // KWC
    if ($details['collaborators']=='') { unset($details['collaborators']);}
    if ($details['ctools_url']=='') { unset($details['ctools_url']); }
    $details['course_id'] = $cid;
    $details['created_on'] = date('Y-m-d h:i:s');

    // add new material
    $idx = ($type=='bulk') ? 'zip_userfile' : 'single_userfile';

    if ($type=='single') {
      preg_match('/(\.\w+)$/',$files[$idx]['name'],$match);
      $details['name'] = (isset($match[1])) ? basename($files[$idx]['name'],$match[1]):basename($files[$idx]['name']);
      $details['`order`'] = $this->material->get_nextorder_pos($cid);
      $mid = $this->material->insert_material($details);
      //$this->ocw_utils->dump($files); exit();               // KWC
      $newmatfile = $this->material->upload_materials($cid, $mid, $files[$idx]);
      if ($details['embedded_co']) {
        $this->db_session->set_userdata('progress', "Decomposing file ...");
	$this->oer_decompose->decompose_material($cid, $mid, $newmatfile);
      }
    } else {
      // handle zip files
      if ($files[$idx]['error']==0) {
	$zipfile = $files[$idx]['tmp_name'];
	$files = $this->ocw_utils->unzip($zipfile, property('app_mat_upload_path'));
	if ($files !== false) {
	  foreach($files as $newfile) {
	    if (is_file($newfile) && !preg_match('/^\./',basename($newfile))) {
	      preg_match('/(\.\w+)$/',$newfile,$match);
	      $details['name'] = (isset($match[1])) ? basename($newfile,$match[1]):basename($newfile);
	      $details['`order`'] = $this->material->get_nextorder_pos($cid);
	      $details['mimetype_id'] = $this->mimetype->get_mimetype_id_from_filename($newfile);
	      $mid = $this->material->insert_material($details);
	      $filedata = array();
	      $filedata['name'] = $newfile;
	      $filedata['tmp_name'] = $newfile;
	      $newmatfile = $this->material->upload_materials($cid, $mid, $filedata);
	      if ($details['embedded_co']) {
	        $bname = basename($newfile);
	        $this->db_session->set_userdata('progress', "Decomposing {$bname} ...");
		$this->oer_decompose->decompose_material($cid, $mid, $newmatfile);
	      }
	    }
	  }
	}
      } else {
	return('Cannot upload file: an error occurred while uploading file. Please contact administrator.');
      }
    }
    $this->db_session->set_userdata('progress', "done");
    return true;
  }

  // add material
  public function add_material($cid,$type,$action='add')
  {
    if ($action == 'add') {
      $valid = true;
      $errmsg = '';

      // Catch issues of filesize early (since $FILES and $_POST are empty in that case)
      if ($this->ocw_utils->is_invalid_upload_size()) {
	flashMsg("The file you attempted to upload is larger than the maximum of ". $this->ocw_utils->max_upload_size() . "!");
	redirect("materials/add_material/$cid/$type/view", 'location');
      }

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

      if (!isset($_POST['embedded_co'])) { $_POST['embedded_co'] = 0; }

      $role = getUserProperty('role');
      if ($valid == FALSE) {
	flashMsg($errmsg);
	redirect("materials/add_material/$cid/$type/view", 'location');
      }       else {
	$r = $this->_manually_add_materials($cid, $type, $_POST,$_FILES);
	if ($r !== true) {
	  flashMsg($r);
	} else {
	  $msg = ($type=='bulk') ? 'Materials have been added.':'Added material to course.';
	  flashMsg($msg);
	}
	redirect("materials/add_material/$cid/$type/view", 'location');
      }
    } else {
      // show add form
      $tags =  $this->tag->tags();
      $mimetypes =  $this->mimetype->mimetypes();
      $data = array('tags'=>$tags, 'mimetypes'=>$mimetypes, 'cid'=>$cid, 'view'=>$type);
      $this->load->view(property('app_views_path').'/materials/_add_materials.php', $data);
    }
  }

  // remove material
  public function remove_material($cid, $mid)
  {
    $this->material->remove_material($cid, $mid);
    flashMsg('Material removed!');
    redirect("materials/home/$cid", 'location');
  }


  // edit content objects
  public function edit($cid, $mid, $fs_action=0,$fs_type=0,$fs_repl=0,$fs_status=0)
  {
    $object_bin = array();
    $course = $this->course->get_course($cid);
    $material =  $this->material->materials($cid,$mid,true);
    $stats = $this->coobject->object_stats($cid, $mid);
    //echo "<pre>"; print_r($stats); echo "</pre>";
    //$this->ocw_utils->dump($stats);

    //faceted search filter 1 - recommended action type
    if ($fs_action > 0) {
      $fs_actions = $this->material->rec_action_list($mid);
      $segment_array = explode("z", $fs_action);
      foreach ($segment_array as $sa) {
	$view = $this->material->map_recommended_action($fs_actions[$sa]);
	if (isset($stats['objects'][$view])) {
	  $object_bin = array_merge($object_bin, $stats['objects'][$view]);
	}
      }
    } else {
      $view = 'all';
      $object_bin = array_merge($object_bin, $stats['objects'][$view]);
    }
    //faceted search filter 2 - co type
    if ($fs_type > 0) {
      $fs_types = $this->material->co_type_list($mid);
      $segment_array = explode("z", $fs_type);
      foreach ($object_bin as $key=>$o) {
	if(!in_array($o['subtype_id'], $segment_array)) unset($object_bin[$key]);
      }
    }
    //faceted search filter 3 - replacement?
    if ($fs_repl > 0) {
      $fs_repls = $this->material->replacement_list($mid);
      $segment_array = explode("z", $fs_repl);
      if (count($segment_array) != 2) {
	$replacement_oids = array();
	foreach ($stats['objects']['replace'] as $key=>$o)  $replacement_oids[] = $o['id'];
	foreach ($segment_array as $sa) {
	  foreach ($object_bin as $key=>$o) {
	    if ($sa == 1 ) { //with replacement
	      //echo $o['id'];
	      if(!in_array($o['id'], $replacement_oids)) unset($object_bin[$key]);
	    } else { //without replacement
	      if(in_array($o['id'], $replacement_oids)) unset($object_bin[$key]);
	    }
	  }
	}
      }
    }

    //faceted search filter 4 - status
    $tmp_object_bin = array();
    if ($fs_status > 0) {
      $fs_statuss = $this->material->status_list($mid);
      $segment_array = explode("z", $fs_status);
      // Objects may be marked as cleared (done) w/o ever having a recommended action.
      // Make sure that we only count them once by always checking 'done'.
      foreach ($object_bin as $key=>$o) {
	if (in_array(1, $segment_array)) { //no action assigned
	  if ($o['done'] == 0 && (empty($o['action_type']) && empty($o['action_taken'])
				  && $o['ask_status'] == 'new' && $o['ask_dscribe2_status'] == 'new')) {
	    $tmp_object_bin[] = $o;
	  }
	}
	if (in_array(2, $segment_array)) { //in progress
	  if ($o['done'] == 0 && (!empty($o['action_type']) || !empty($o['action_taken']) ||
				  ($o['ask'] == 'yes' && $o['ask_status'] != 'new') ||
				  ($o['ask_dscribe2'] == 'yes' && $o['ask_dscribe2_status'] != 'new'))) {
	    $tmp_object_bin[] = $o;
	  }
	}
	if (in_array(3, $segment_array)) { //cleared
	  if ($o['done'] == 1 && !empty($o['action_taken'])) $tmp_object_bin[] = $o;
	}
      }
    }  else $tmp_object_bin = $object_bin;

    $object_bin = array_values($tmp_object_bin); //rekey array numerically 0-n
    //echo "<pre>"; print_r($object_bin); echo "</pre>";

    $idarray = array();
    foreach ($object_bin as $key => $o) {
      $idarray[] = $o['id'];
    }

    // get values for display
    $data = array(
		  'cid'=>$cid,
		  'mid'=>$mid,
		  'cname' => $course['number'].' '.$course['title'],
		  'director' => $course['director'],
		  'material' =>  $material[0],
		  'objects' => $object_bin,
		  'idarray' => json_encode($idarray),
		  'num_objects' => sizeof($object_bin),
		  'num_unfiltered_objects' => $stats['data']['num_all'],
		  'view' => $view,
		  'title'=>'Edit Material &raquo; '.$material[0]['name'],
		  );

    $data = array_merge($data, $stats['data']);

    $data['select_filter'] =
      array(
	    'all' => 'All ('.$data['num_all'].')',
	    'new' => 'New ('.$data['num_new'].')',
	    'replace' => 'Replacements ('.$data['num_replace'].')',
	    'cleared' => 'Cleared ('.$data['num_cleared'].')',
	    'uncleared' => 'Un-Cleared ('.$data['num_uncleared'].')',
	    'ask:orig' => 'Ask Instructor: Originals ('.$data['num_ask_orig'].')',
	    'ask:rco' => 'Ask Instructor: Replacements ('.$data['num_ask_rco'].')',
	    'permission' => 'Permission ('.$data['num_permission'].')',
	    'search' => 'Search ('.$data['num_search'].')',
	    'retain:perm' => 'Retain: Permission ('.$data['num_retain_perm'].')',
	    'retain:ca' => 'Retain: Copyright Analysis ('.$data['num_retain_ca'].')',
	    'retain:pd' => 'Retain: Public Domain ('.$data['num_retain_pd'].')',
	    'create' => 'Create ('.$data['num_create'].')',
	    'commission' => 'Commission ('.$data['num_commission'].')',
	    'fairuse' => 'Fair Use ('.$data['num_fairuse'].')',
	    'remove' => 'Remove ('.$data['num_remove'].')',
	    );

    $this->layout->buildPage('materials/_edit_material_cos', $data);
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
    if ($view == 'aitems') { $data['select_response_types'] =
	array('all'=>'All',
	      'general'=>'General Questions',
	      'replacement'=>'Replacement Questions',
	      'fairuse'=>'Fair Use Questions',
	      'permission'=>'Permission Questions',
	      'commission'=>'Commission Questions',
	      'retain'=>'Copyright Analysis Questions',
	      );
    }
    $tmp_actions = array();
    if ($view == 'fairuse') { $tmp_actions = $this->coobject->enum2array('claims_fairuse','action'); }
    if ($view == 'permission') { $tmp_actions = $this->coobject->enum2array('claims_permission','action'); }
    if ($view == 'commission') { $tmp_actions = $this->coobject->enum2array('claims_commission','action'); }
    if ($view == 'retain') { $tmp_actions = $this->coobject->enum2array('claims_retain','action'); }


    /*
     * Trim out the actions that we don't want to be used any longer.
     * These actions cannot be removed from the database because there
     * are existing objects that may already have these actions chosen.
     */
    function make_some_actions_unselectable($var) {
      switch ($var) {
      case "Permission":
      case "Fair Use":
      case "Commission":
	return FALSE;
      default:
	return TRUE;
      }
    }
    $data['select_actions'] = array_filter($tmp_actions, "make_some_actions_unselectable");

    /* info for queries sent to instructor */
    if ($questions_to=='instructor' || ($role == 'instructor' && $questions_to=='') || $role=='') {
      //$view = (!in_array($view, array('general', 'provenance','replacement','done'))) ? 'general' : $view; //commented out mbleed oerdev-168

      $prov_objects =  $this->coobject->coobjects($mid,'','Ask'); // objects with provenace questions
      $repl_objects =  $this->coobject->replacements($mid, '', '', 'Ask'); // objects with replacement questions
      $num_obj = $num_general = $num_repl = $num_prov = $num_done = 0;
      $general = $done = array();

      if ($prov_objects != null) {
	foreach($prov_objects as $obj) {
	  if ($obj['ask_status'] == 'done') { $num_done++; }
	  if ($obj['ask_status'] <> 'done') { $num_prov++; }
	  $num_obj++;
	}
      }

      $orig_objs = $this->coobject->coobjects($mid);
      if (!is_null($orig_objs)) {
	foreach ($orig_objs as $obj) {
	  // get general question info for instructors
	  if (!is_null($obj['questions'])) {
	    $questions = (isset($obj['questions']['instructor']) && sizeof($obj['questions']['instructor'])>0)
	      ? $obj['questions']['instructor'] : null;

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
		$obj['questions']['instructor'][$k] = $q;
	      }
	      if ($notalldone) { array_push($general, $obj); $num_general++;}
	      else { array_push($done['general'],$obj); $num_done++; }
	    }
	  }
	}
      }

      if ($repl_objects != null) {
	foreach($repl_objects as $obj) {
	  if ($obj['ask_status'] == 'done' && ($obj['ask'] == 'no' || ($obj['ask'] == 'yes' && $obj['suitable'] != 'pending'))) {
	    $num_done++;
	  } else {
	    $num_repl++;
	  }
	  $num_obj++;
	}
      }

      //added this to check for objs and set default based on rules defined in OERDEV-168 mbleed
      if ($num_prov > 0) $default_ins_view = 'provenance';
      elseif ($num_repl > 0) $default_ins_view = 'replacement';
      else $default_ins_view = 'done';
      $view = (!in_array($view, array('general', 'provenance','replacement','done'))) ? $default_ins_view : $view;

      $data['view'] = $view;

      //$data['cos'] = $info[$view];
      $data['num_done'] = $num_done;
      $data['num_general'] = $num_general;
      $data['num_prov'] = $num_prov;
      $data['num_repl'] = $num_repl;
      $data['numobjects'] = $num_obj;
      $data['need_input'] = $num_prov + $num_repl;
      $data['prov_objects'] = $prov_objects;
      $data['repl_objects'] = $repl_objects;
      $data['general'] = ($num_general != 0)?$general:null;
      $data['num_avail'] = array('general'=>$num_general, 'provenance'=>$num_prov, 'replacement'=>$num_repl, 'done'=>$num_done);

    } elseif ($questions_to=='dscribe2' || (($role=='dscribe1' || $role=='dscribe2') && $questions_to=='')) { // dscribes page info
      $view = ($view=='') ? 'general' : $view;

      $info =  $this->coobject->ask_form_info($cid, $mid);

      $data['view'] = $view;
      $data['cos'] = $info[$view];
      $idarray = array();
      // This is way uglier than I'd like, but we want
      // to count only those objects that will be displayed
      // Note that the questions for the instructor are processed
      // differently, and require changes to the view to count them
      if (count($data['cos']) > 0) {
	if ($view == 'aitems') {  // aka "Done"
	  if ($responsetype == 'all') {
	    // Get all the response types
	    foreach ($data['cos'] as $rt) {
	      if (count($rt) > 0) {
		foreach($rt as $o) {
		  $idarray[] = ($o['otype'] == 'original') ? $o['id'] : $o['object_id'];
		}
	      }
	    }
	  } else {
	    // Get only the specified response type
	    foreach ($data['cos'][$responsetype] as $o) {
	      $idarray[] = ($o['otype'] == 'original') ? $o['id'] : $o['object_id'];
	    }
	  }
	} else {
	  foreach ($data['cos'] as $o) {
	    $idarray[] = ($view == 'replacement') ? $o['object_id'] : $o['id'];
	  }
	}
      }
      $data['idarray'] = json_encode($idarray);
      $data['num_avail'] = $info['num_avail'];
      $data['need_input'] = $info['need_input'];
      $data['num_general'] = $info['num_avail']['general'];
      $data['num_repl'] = $info['num_avail']['replacement'];
      $data['num_fairuse'] = $info['num_avail']['fairuse'];
      $data['num_permission'] = $info['num_avail']['permission'];
      $data['num_commission'] = $info['num_avail']['commission'];
      $data['num_retain'] = $info['num_avail']['retain'];
      $data['num_done'] = $info['num_avail']['aitems'];
      $data['repl_objects'] = $info['replacement'];

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

    } else {        // default: instructor view (no one really needs to login for this view)
      $user_rels = $this->ocw_user->dscribes($cid);
      $course =  $this->course->get_course($cid);
      if ($user_rels[0] == NULL) {
	$data['alert_missing_dscribe']="Alert: Could not find any dscribe for course - ".$course['title'].".";
      }
      $this->layout->buildPage('materials/askforms/instructor/index', $data);
    }
  }

  public function remove_object($cid, $mid, $oid, $type='original', $rid='')
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

  /**
   * Add content object information coming from the snapper tool
   */
  public function snapper($cid, $mid, $action='')
  {
    if ($action == 'submit') {
      $res = $this->coobject->add_snapper_image($cid, $mid, getUserProperty('id'), $_REQUEST);
      $success = ($res===true) ? 'true' : 'false';
      $msg = ($success=='true') ? 'Image uploaded' : $res;
      $value = array('success'=>$success, 'msg'=>$msg,
		     'url'=>site_url("materials/add_object/$cid/$mid"));
      $this->ocw_utils->send_response($value, 'json');
    } else {
      $data = array('cid'=>$cid, 'mid'=>$mid);
      $data['select_subtypes'] =  $this->coobject->object_subtypes();
      $data['objects'] =  $this->coobject->coobjects($mid);
      $data['numobjects'] = count($data['objects']);
      $this->load->view('default/content/materials/snapper/snapper', $data);
    }
  }

  public function add_object($cid, $mid, $type='snapper', $action='view')
  {
    if ($action == 'add') {
      $valid = true;
      $errmsg = '';

      // Catch issues of filesize early (since $FILES and $_POST are empty in that case)
      if ($this->ocw_utils->is_invalid_upload_size()) {
	flashMsg("The file you attempted to upload is larger than the maximum of ". $this->ocw_utils->max_upload_size() . "!");
	redirect("materials/add_object/$cid/$mid/$type", 'location');
      }

      $idx = ($type=='bulk') ? 'userfile' : 'userfile_0';

      $valid = true;
      $errmsg = '';

      if (!isset($_FILES[$idx]['name']) || $_FILES[$idx]['name']=='') {
	$errmsg = 'Please specify a file to upload';
	$valid = false;
      }

      if ($type=='bulk') {
	if(isset($_FILES[$idx]['name'])  && !preg_match('/\.zip$/',$_FILES[$idx]['name'])) {
	  $errmsg .= (($errmsg=='') ? '':'<br/>')."Can only upload ZIP files for bulk uploads";
	  $valid = false;
	}
      }

      if ($type=='single') {
	if ($_POST['location']=='') {
	  $errmsg .= (($errmsg=='') ? '':'<br/>')."Location field is required.";
	  $valid = false;
	}
	if ($_POST['ask']=='') {
	  $errmsg .= (($errmsg=='') ? '':'<br/>')."Ask Instructor field is required.";
	  $valid = false;
	}
      }

      if ($valid == FALSE) {
	flashMsg($errmsg);
	redirect("materials/add_object/$cid/$mid/$type", 'location');
      }       else {
	if ($type=='bulk') {
	  $res = $this->coobject->add_zip($cid, $mid,getUserProperty('id'),$_FILES);
	  $this->update($cid,$mid,'embedded_co','1',false);
	  flashMsg($res);
	} else {
	  $this->coobject->add($cid, $mid,getUserProperty('id'),$_POST,$_FILES);
	  $this->update($cid,$mid,'embedded_co','1',false);
	  flashMsg('Content object added');
	}
	redirect("materials/add_object/$cid/$mid/$type", 'location');
      }
    } else {
      // show add form
      $tags =  $this->tag->tags();
      $subtypes =  $this->coobject->object_subtypes();
      $mimetypes =  $this->mimetype->mimetypes();
      $data = array('tags'=>$tags, 'mimetypes'=>$mimetypes, 'cid'=>$cid, 'mid'=>$mid,
		    'subtypes'=>$subtypes, 'view'=>$type);
      $this->load->view(property('app_views_path').'/materials/_add_content_objects.php', $data);
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
	redirect("materials/object_info/$cid/$mid/$oid/all/upload/$wrong_type", 'location');
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
	  if ($val<>'Search' && $val<>'Create' && $val<>'Remove and Annotate') {
	    $claimtype = array('Permission'=>'permission','Commission'=>'commission', 'Fair Use'=>'fairuse',
			       'Retain: Public Domain'=>'retain',
			       'Retain: Copyright Analysis'=>'retain',
			       'Retain: Permission'=>'retain');
	    $cl = $this->coobject->claim_exists($oid,$claimtype[$val]);
	    if ($cl!== false) {
	      $ndata = array('action'=>$val,'status'=>"request sent"); // also update claim status to indicate that email has been queued
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

  //mbleed oerdev-162
  public function update_claim_status($oid, $status, $claimtype='retain') {
    //$this->ocw_utils->log_to_apache('error', __FUNCTION__.": updating claim_{$claimtype} status for {$oid} to '{$status}'");
    $cl = $this->coobject->claim_exists($oid, $claimtype);
    if ($cl!==false) {
      //$this->ocw_utils->log_to_apache('error', __FUNCTION__.": changing existing claim_{$claimtype} status for ${oid} from '{$cl[0]['status']}' to '{$status}'");
      $claimid = $cl[0]['id'];
      $this->coobject->update_object_claim_status($oid, $claimid, $claimtype, $status);
    } else {
      //$this->ocw_utils->log_to_apache('error', __FUNCTION__.": did not find an existing claim_{$claimtype} for {$oid}!?!?");
    }
    //echo $this->db->last_query();
  }

  public function override_action_type($oid, $action_type) {
    //$this->ocw_utils->log_to_apache('error', __FUNCTION__.": overriding action_type for {$oid} to '{$action_type}'");
    $data = array('action_type' => $action_type);
    $this->db->update('objects', $data, "id=$oid");
    $lgcm = "Overrode action type to {$action_type}";
    $this->coobject->add_log($oid, getUserProperty('id'), array('log'=>$lgcm));
    //echo $this->db->last_query();
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
    $role = getUserProperty('role');
    $role = ($role == 'dscribe2') ? $role : 'instructor';

    if ($field=='replacement_question')
      {
	$this->coobject->add_replacement_question($rid, $oid, getUserProperty('id'), array('question'=>$val,'role'=>$role));
      }
    else
      {
	$data = array($field=>$val);
	$this->coobject->update_replacement($rid, $data);
      }
    /* send email to dscribe */
    if ($field=='ask_status' and $val=='done') {
      if ($role == 'instructor') {
	$this->postoffice->instructor_dscribe1_email($cid, $mid, $oid,'replacement');
      } else {
	$this->postoffice->dscribe2_dscribe1_email($cid, $mid, $oid,'replacement');
      }
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

  public function object_info($cid,$mid,$oid,$filter='all',$tab='status',$alert_wrong_mimetype='')
  {
    $obj = $this->coobject->coobjects($mid,$oid);
    $repl_object =  $this->coobject->replacements($mid,$oid);
    $subtypes =  $this->coobject->object_subtypes();

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

    $action_tips =
      "<b>[ Search ]</b><br/>Replace this through a search.<br/><br/>
						 <b>[ Retain: Permission ]</b><br/>  Keep this because it has a copyright license or other permission to publish.<br/><br/>
						 <b>[ Retain: Public Domain ]</b><br/>  Keep this because it clearly indicates it is in the public domain.<br/><br/>
						 <b>[ Retain: Copyright Analysis ]</b><br/>  Keep this but understand it needs further copyright review (including fair use).<br/><br/>
						 <b>[ Create ]</b><br/>  Create a replacement for this.<br/><br/>
						 <b>[ Remove and Annotate ]</b><br/>  Remove this and add an annotation in its place.";

      $data = array(
		    'cid'=>$cid,
		    'mid'=>$mid,
		    'obj'=>$obj[0],
		    'repl_obj'=>$repl_object[0],
		    'fairuse_rationale' => $fairuse_rationale,
		    'commission_rationale' => $commission_rationale,
		    'retain_rationale' => $retain_rationale,
		    'user'=>getUserProperty('user_name'),
		    'tab'=> (($tab<>'') ? array(ucfirst($tab)) : array('Status')),
		    'viewing' => ((isset($_REQUEST['viewing'])) ? $_REQUEST['viewing']: ''),
		    'filter'=>$filter,
		    'subtypes'=>$subtypes,
		    'action_types' => $this->coobject->enum2array('objects','action_type'),
		    'action_tips' => $action_tips,
		    'alert_wrong_mimetype' => $alert_wrong_mimetype
		    );
      $data = array_merge($data, $permission);
      $data_2 = $data;
      $data['data'] = $data_2;
      $this->load->view('default/content/materials/co/index', $data);
  }



  /**
   * Manipulate the materials for a course. This method gets form input and
   * calls other functions to do the real work. Specifying the $mid
   * (material id) results in the download of just the material file without
   * any content objects or context images.
   *
   * @access   public
   * @param    int course id
   * @param    int material id (optional)
   * @return   void
   */
  public function manipulate($cid, $mid=NULL)
  {
    $err_msg = "";
    $conf_msg = "";
    $selected_materials = NULL;
    $rec_file_dets = NULL;

    // Make sure course_id is an integer.
    if (!$this->_validate_integer_param($cid)) {
      $err_msg = "Invalid course. Please select a different course.";
      flashMsg($err_msg);
      redirect("", "location");
    }

    // TODO: Go over sanitization measures taken on $_POST input data.
    if (!array_key_exists('select_material', $_POST) && $mid == NULL) {
      $err_msg = "No items were selected. Please select at least one item.";
      flashMsg($err_msg);
      redirect("materials/home/$cid", "location");
    } elseif (array_key_exists('select_material', $_POST)) {
      $selected_materials =
	$this->_validate_integer_param($_POST['select_material']);

      if (!$selected_materials) {
	$err_msg = "Invalid material selection. Please try again.";
	flashMsg($err_msg);
	redirect("materials/home/$cid". "location");
      }
    }

    if (array_key_exists('delete', $_POST)) {
      foreach ($selected_materials as $material_id) {
	$this->material->remove_material($cid, $material_id);
      }
      $conf_msg = "Removed selected materials.";
      flashMsg($conf_msg);
      redirect("materials/home/$cid", 'location');
    } elseif (array_key_exists('download', $_POST) || $mid) {
      $this->db_session->set_userdata('progress', "Processing download...");
      $mid = $this->_validate_integer_param($mid);
      if ($mid && $this->_validate_integer_param($mid)) {
	$material_list = $this->material->
	  get_material_info($cid, array($mid));
      } else {
	$material_list = $this->material->
	  get_material_info($cid, $selected_materials);
      }

      $this->db_session->set_userdata('progress', "Gathering list of materials...");
      $file_list = $this->_get_material_files($material_list);
      $recomp_workfile =
	$this->_write_recomp_workfile($material_list);
      if ($recomp_workfile !== FALSE) {
        $this->db_session->set_userdata('progress', "Recomposing/Reconstructing materials...");
	$recomp_done =
	  $this->oer_decompose->recompose_material($recomp_workfile);
	if ($recomp_done === 0 || $recomp_done === "0") {
	  $rec_file_dets =
	    $this->_get_recomped_files_dets($recomp_workfile);
	} elseif ($recomp_done !==0 || $recomp_done !== "0") {
	  /* TODO: should we check the return value to see if
	     deletion worked? */
	  $this->oer_decompose->del_recomp_dir($this->recomp_dir_path);
	}
      }

      if ($mid) {
	$file_list[0]['file_names'] =
	  array_slice($file_list[0]['file_names'], 0, 1);
      }

      /* $this->ocw_utils->dump($material_list); */
      /* $this->ocw_utils->dump($file_list); */
      $this->_download_material($file_list, $rec_file_dets);
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
  /* TODO: change to allow a material only parameter so we don't have
   * to special case the material only download */
  private function _get_material_files($material_list)
  {
    $material_files = array();
    foreach ($material_list as $material_info)
      {
	$file_names = array();
	/* TODO: change the way materials are named so full names are
	 * retained in the DB */
	$mat_path = $material_info['material_path'];
	/* find all items in the $mat_path directory and add to the
	 * $material_files array if they are files instead of directories */
	if (is_dir($mat_path)) {
	  $all_dir_items = (scandir($mat_path));
	  foreach ($all_dir_items as $file_name) {
	    $rel_path = "$mat_path/$file_name";
	    if (is_file($rel_path)) {
	      $rpi = pathinfo($rel_path);
	      // PHP < 5.2 doesn't return 'filename'
	      if (!isset($rpi['filename'])) {
		$rpi['filename'] = substr($rpi['basename'], 0, strrpos($rpi['basename'], '.'));
	      }
	      if ($rpi['filename'] == $material_info['material_file']) {
		$file_names['material_file'] = $rel_path;
	      } else {
		$file_names['ctxt_images'][] = $rel_path;
	      }
	    }
	  }
	}
	if ($material_info['material_cos_info']) {
	  $co_info = $this->_select_cos($material_info['material_cos_info']);
	  if ($co_info) {
	    $file_names['co_info'] = $co_info;
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
				  'material_dir' => $material_info['material_file'],
				  'file_names' => $file_names,
				  );
      }

    return($material_files);
  }


  /**
   * Download the files for a selected set of materials. An archive
   * file is created if there is more than one file.
   *
   * @access    private
   * @param     array a list of files
   * @param     array list of recomped materials.
   * @return    void
   */
  // TODO: refactor this function, it is turning into a monster!
  private function _download_material($file_list, $rec_file_dets)
  {
    $this->load->helper('download');
    $this->load->library('oer_create_archive');

    $user_name = getUserProperty('user_name');
    $down_name = FALSE; // name of downloaded resource
    $obj_num = 0; // number attached to co suffix

    $this->db_session->set_userdata('progress', "Creating archive...");
    // get a timestamp formatted as YYYY-MM-DD-HHMMSS
    $timestamp = date($this->date_format);

    /* add a top level folder in zip files so files aren't sprayed all
     * over the filesystem. Use "$timestamp" defined above
     * as a part of the folder name */
    $parent_folder = "oer_materials-$timestamp";

    $archive_name = $parent_folder . "-" . "$user_name";
    $archive_cont_info = array();
      foreach ($file_list as $mat_files) {
	$export_name = NULL;
	// include any material files
	if (array_key_exists("material_file", $mat_files['file_names'])) {
	  // define the name of the material file
	  $export_name = $archive_name . '/' .
	    $mat_files['material_name'] . '.' .
	    pathinfo($mat_files['file_names']['material_file'],
		     PATHINFO_EXTENSION);
	  $archive_cont_info[] = array(
				       'orig_name' => $mat_files['file_names']['material_file'],
				       'export_name' => $export_name,
				       );
	}
	// include any context images
	if (array_key_exists("ctxt_images", $mat_files['file_names'])) {
	  foreach ($mat_files['file_names']['ctxt_images'] as $ctxt_image) {
	    /* define the export name for the context image which includes
	       the slide number of the image */
	    $ctxt_image_match =
	      "/(${mat_files['material_dir']})(_slide_)(.*)(\..*)/";
	    if (preg_match($ctxt_image_match, $ctxt_image, $matches) > 0) {
	      $export_name = $mat_files['material_name'] . $matches[2] .
		$matches[3] . $matches[4];
	      $export_name = $archive_name . '/' .
		$mat_files['material_name'] . '/' .
		'context_images/' . $export_name;
	      /* end definition of context image export name */
	      $archive_cont_info[] = array(
					   'orig_name' => $ctxt_image,
					   'export_name' => $export_name,
					   );
	    }
	  }
	}
	// include any content objects
	if (array_key_exists("co_info", $mat_files['file_names'])) {
	  foreach ($mat_files['file_names']['co_info'] as $co_info) {
	    /* define the export name for the content object. the
	     * existing values in the array are searched to see if the
	     * export_name is unique and a number is added if the name
	     * isn't unique
	     */

	    // define the directory location and initial co name
	    $export_name = $archive_name . '/' .
	      $mat_files['material_name'] . '/' .
	      'content_objects/' . $mat_files['material_name'] . '_slide_';
	    // break apart the object location information
	    // TODO: possibly predefine the split regexp
	    $locations = preg_split("/\s*,\s*/", $co_info['co_location']);
	    for($i = 0; $i < count($locations); $i++) {
	      $export_name .= trim($locations[$i]) . '_';
	    }
	    // add 'replacement_' to the name if the co is a replacement
	    $rep_ident = "[_rep\..*]";
	    if (preg_match($rep_ident, $co_info['co_file']) > 0) {
	      $export_name .= "replacement_";
	    }
	    $export_name .= "obj_";
	    /* determine which number to assign to the current obj */
	    if (count($archive_cont_info) > 0) {
	      $obj_num = 0; // to ensure we don't get counts for old searches
	      foreach($archive_cont_info as $archive_entry) {
		$cont_obj_string = ("[($export_name)]");
		$existing_file_info = pathinfo($archive_entry['export_name']);
		// PHP < 5.2 doesn't return 'filename'
		if (!isset($existing_file_info['filename'])) {
		  $existing_file_info['filename'] =
		    substr($existing_file_info['basename'], 0,
			   strrpos($existing_file_info['basename'], '.'));
		}
		$existing_name = $existing_file_info['dirname'] . '/' .
		  $existing_file_info['filename'];
		if (preg_match($cont_obj_string, $existing_name) > 0) {
		  $obj_num++;
		}
	      }
	    }
	    // append object number to $export_name
	    $export_name .= $obj_num + 1;
	    // append the extension to $export_name
	    $export_name .= '.' . pathinfo($co_info['co_file'],
					   PATHINFO_EXTENSION);
	    $archive_cont_info[] = array(
					 'orig_name' => $co_info['co_path'] .
					 '/' . $co_info['co_file'],
					 'export_name' => $export_name,
					 );
	  }
	}
      }
      $this->_add_recmpd_files_to_archive($rec_file_dets,
					  $archive_name,
					  $archive_cont_info);
      $path_to_archive = $this->oer_create_archive->
	make_archive($archive_name,$archive_cont_info);
      $down_name = pathinfo($path_to_archive, PATHINFO_BASENAME);
      // delete the recomp files and working directory
      $this->oer_decompose->del_recomp_dir($this->recomp_dir_path);
      $this->db_session->set_userdata('progress', "done");
      force_file_download($down_name, $path_to_archive, TRUE);
  }


  /**
   * Determine the name for the material files from the metadata
   * this function attempts to do the best it can.
   *
   * @access   private
   * @param    string the original name of the file
   * @param    int the array index value corresponding to the current
   *           filename
   * @param    array mixed array containing the list of files for a
   *           material and related metadata
   * @return   string the more sensible, human readable file name
   */
  /* TODO: make changes to support new material download naming
   * scheme */
  private function _get_export_file_name($orig_file_name,
					 $file_num, $mat_file_list)
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
  public function download_all_rcos($cid, $mid)
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
	$rep_extension = "." . pathinfo($rep_name, PATHINFO_EXTENSION);
	// the file path to the replacement data
	// path to the uploads dir
	$uploads_path = getcwd().'/uploads/';

	// the absolute path to the filename
	$rep_filepath=$uploads_path.$object_filepath."/".
	  $object_filename."_rep".$rep_extension;

	/* variants to make sure that we find the file since the file name in
	 * the DB and that on the filesystem may have differences in whether
	 * extension is upper or lower cased.
	 */
	$rep_filepath_lowerext = $uploads_path.$object_filepath."/".
	  $object_filename."_rep".strtolower($rep_extension);

	$rep_filepath_upperext = $uploads_path.$object_filepath."/".
	  $object_filename."_rep".strtoupper($rep_extension);

	// check as detected, uppercase and lowercase file name extensions
	if (file_exists($rep_filepath)) {
	  $this->zip->read_file($rep_filepath);
	} elseif (file_exists($rep_filepath_upperext)) {
	  $this->zip->read_file($rep_filepath_upperext);
	} elseif (file_exists($rep_filepath_lowerext)) {
	  $this->zip->read_file($rep_filepath_lowerext);
	}
      }
      // Download the file to your desktop. Name it "SITENAME_IMSCP.zip"
      $this->zip->download($name.'_RCOs.zip');

      $this->zip->clear_data(); // clear cached data
    } else {
      $msg = 'There are no Replacement Content Objects for this material';
      flashMsg($msg);
      redirect("materials/edit/$cid/$mid", 'location');
    }
  }


  /**
   * Download the replacement content object
   */
  public function download_rco($cid, $mid, $oid, $rid)
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


  /**
   * Determine which cos will be downloaded by looking at the
   * action taken value and choosing the orig or replacement
   *
   * @access   private
   * @param    array of content object info (handles multiple cos)
   * @param    (optional) array of action types that will be filtered out
   * @param    (optional) array of action types that indicate presence of
   *           replacement content objects
   * @return   mixed array of co info that includes details on files to
   *           download based on specified selection criteria
   */
  private function _select_cos($cos_info, $filter_actions = NULL, $repl_actions = NULL)
  {
    $selected_cos = array();
    if (!$filter_actions) {
      $filter_actions = array(
			      "Permission",
			      "Commission",
			      "Fair Use",
			      "Remove and Annotate",
			      );
    }

    if (!$repl_actions) {
      $repl_actions = array(
			    "Search",
			    "Create",
			    );
    }
    /* TODO: aal - this fails for some of the endocrine courses because the
     * action_taken field is blank in the DB. Should we look at the action_type
     * field if there is no action_taken action in the DB and done == 1?
     */
    foreach ($cos_info as $co_info) {
      // filter out unwanted 'co_fin_action' content objects
      if ($co_info['co_fin_action'] &&
	  array_search($co_info['co_fin_action'], $filter_actions) === FALSE) {
	$co_dir = $co_info['co_path'];
	if (is_dir($co_dir)) {
	  $co_files = scandir($co_dir);
	  // define whether we select the original co or the replacement
	  if (array_search($co_info['co_fin_action'], $repl_actions) !==
	      FALSE) {
	    $match_expr = "/(${co_info['co_name']}_rep)(\..*)/";
	  } else {
	    $match_expr = "/(${co_info['co_name']})(_grab)?(\..*)/";
	  }
	  foreach ($co_files as $co_file) {
	    if (preg_match($match_expr, $co_file, $matches) > 0) {
	      $co_info['co_file'] = $co_file;
	      /* TODO: aal - manipulate the original array after passing it
	       * in by reference instead of doing an array copy and wasting
	       * memory */
	      array_push($selected_cos, $co_info);
	    }
	  }
	}
      }
    }
    return ($selected_cos);
  }


  /**
   * Generate a navigation arrow while editing Content Objects
   *
   * @access   public
   * @param    string which arrow (previous or next)
   * @param    int course ID
   * @param    int material ID
   * @param    int object ID
   * @return   html to display left or right arrow
   */
  public function prev_next_arrow($which, $cid, $mid, $oid)
  {
    /**
     *
     * Convert an object to an array
     *
     * @param    object  $object The object to convert
     * @return   array
     *
     */
    function objectToArray($object)
    {
      if (!is_object($object) && !is_array($object)) {
	return $object;
      }
      if (is_object($object)) {
	$object = get_object_vars( $object );
      }
      return array_map( 'objectToArray', $object );
    }

    $stdClassArray = json_decode($_POST['idarray']);
    $idarray = objectToArray($stdClassArray);

    $res = $this->coobject->prev_next_lite($cid, $mid, $oid, $which, $idarray);
    return $this->ocw_utils->send_response($res);
    exit;
  }


  /**
   * Make sure the arguments in the post variable or a parameter are
   * integers.
   *
   * @access   private
   * @param    mixed single material id or an array of material ids
   * @return   mixed FALSE if validation fails. xss_cleaned parmeter
   *           if true
   */
  private function _validate_integer_param($specified_params) {
    $specified_params = $this->input->xss_clean($specified_params);
    if (is_array($specified_params)) {
      foreach ($specified_params as $param) {
	if (!$this->_is_string_int($param)) {
	  return FALSE;
	}
      }
    } elseif (!$this->_is_string_int($specified_params)) {
      return FALSE;
    }
    return $specified_params;
  }


  /**
   * Check to see if the input string is numeric and further check to
   * see if it is an integer. Return TRUE if is an integer and FALSE
   * otherwise.
   *
   * @access    private
   * @param     mixed string to be tested
   * @return    boolean
   */
  private function _is_string_int($inp_string) {
    if (is_numeric($inp_string) && is_int((int)$inp_string)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }


  /**
   * Write the json file that is passed to the openoffice recomp tool
   * to actually get the recomp work done.
   */
  // TODO: Should this function be moved to the material model?
  private function _write_recomp_workfile($material_list)
  {
    $recomp_version = 1;
    $recomp_id = 321;
    $recomp_ops = FALSE;
    $recomp_files = array();
    $timestamp = date($this->date_format);
    $user_name = getUserProperty('user_name');
    $json_file_name = NULL;
    $workfile_size = FALSE;

    foreach ($material_list as $material) {
      if ($material['material_manip_ops'] !== FALSE) {
	$recomp_files[] = clone $material['material_manip_ops'];
      }
    }
    if (count($recomp_files) > 0) {
      $recomp_ops->version = $recomp_version;
      $recomp_ops->id = $recomp_id;
      $recomp_ops->decompFiles = $recomp_files;
      if (!file_exists($material['rec_work_dir'])) {
	$this->recomp_dir_path = $material['rec_work_dir'];
	mkdir($material['rec_work_dir'], 0700);
      }
      $json_file_name = $material['rec_work_dir'] . "recomp-" .
	$user_name . "-" . $timestamp . ".json";
      $workfile_size = file_put_contents($json_file_name,
					 json_encode($recomp_ops));
      //$this->ocw_utils->log_to_apache('debug', __FUNCTION__.": writing json to file {$json_file_name}");
      //file_put_contents("/tmp/foo.json", json_encode($recomp_ops));  // Save another copy
    }
    return ($workfile_size !== FALSE) ?
      $json_file_name :
      $workfile_size;
  }


  /**
   * Get the location of the recomped files. We are going about this
   * somewhat inefficiently, but optimization comes later. The single
   * parameter provided prevents the JSON file from being included in
   * the archive.
   *
   * @access    public
   * @param     string absoulute path to the recomp JSON file
   */
  private function _get_recomped_files_dets($recomp_workfile)
  {
    $recomped_files = array();
    $recomp_dir_conts = scandir($this->recomp_dir_path);
    foreach ($recomp_dir_conts as $content) {
      $cont_abs_path = $this->recomp_dir_path . $content;
      if (is_file($cont_abs_path) &&
	  (strcmp($recomp_workfile, $cont_abs_path) !== 0)) {
	$proc_file['abspath'] = $cont_abs_path;
	$proc_file['basename'] = $content;
	$recomped_files[] = $proc_file;
      }
    }

    return $recomped_files;
  }


  /**
   * Add the recomped files to the array used to create the download
   * archive.
   *
   * @access    private
   * @param     array recomped file location info. Specifically the
   *            output of the _get_recomped_files_dets function.
   * @param     string the name of the archive
   * @param     the array that represents the archive contents.
   */
  private function _add_recmpd_files_to_archive($rec_file_dets,
						$archive_name,
						&$archive_cont_info)
  {
    if ($rec_file_dets !== NULL) {
      foreach ($rec_file_dets as $rec_file) {
	$archive_item['orig_name'] = $rec_file['abspath'];
	$archive_item['export_name'] = $archive_name . "/" .
	  $rec_file['basename'];
	$archive_cont_info[] = $archive_item;
      }
    }
  }

  /**
   * Get session 'progress'
   *
   * Returns operation status, or progress, information for upload (decomposition)
   * and download (recomposition) operations.
   *
   * @access    public
   * @param     none
   */
  public function get_session_status()
  {
    if (!isset($this->db_session->userdata['progress'])) {
      $this->ocw_utils->log_to_apache('debug', __FUNCTION__.": has been invoked, session has no progress!!!");
      $this->ocw_utils->send_response("Working ...");
      exit;
    }
    $current_status = $this->db_session->userdata['progress'];
    $this->ocw_utils->log_to_apache('debug', __FUNCTION__.": has been invoked, and is about to return '${current_status}'!!!");
    $this->ocw_utils->send_response($current_status);
    exit;
  } 
}
?>
