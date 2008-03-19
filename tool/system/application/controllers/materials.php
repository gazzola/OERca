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
  }

  public function index($cid, $caller="") { $this->home($cid, $caller); }

  // TODO: highlight the currently selected field
  public function home($cid, $caller='', $openpane=NULL)
  {
    $tags =  $this->tag->tags();
    $materials =  $this->material->materials($cid,'',true,true);
    $mimetypes =  $this->mimetype->mimetypes();
    $school_id = $this->school->get_school_list();
    $subj_id = $this->subject->get_subj_list();

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
      'cols' => '40'
      );

    $coursehighlightbox = array(
      'name' => 'highlights',
      'id' => 'highlights',
      'wrap' => 'virtual',
      'rows' => '5',
      'cols' => '40'
      );

    $keywordbox = array(
      'name' => 'keywords',
      'id' => 'keywords',
      'wrap' => 'virtual',
      'rows' => '3',
      'cols' => '40'
      );

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
      'subj_id' => $subj_id
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
		//$this->ocw_utils->dump($_POST);
		//$this->ocw_utils->dump($_FILES);

		$idx = ($type=='bulk') ? 'zip_userfile' : 'single_userfile';
		
		if (!isset($_FILES[$idx]['name'])) {
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
		
		if ($valid == FALSE) {
				$role = getUserProperty('role');
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

	public function viewform($form, $cid, $mid='', $view='provenance')
	{
		if ($form == 'ask') {
			$prov_objects =  $this->coobject->coobjects($mid,'','Ask'); // objects with provenace questions
			$repl_objects =  $this->coobject->replacements($mid,'','','Ask'); // objects with replacement questions
			$material =  $this->material->materials($cid,$mid,true);
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

			$data['num_done'] = $num_done; 
			$data['num_prov'] = $num_prov; 
			$data['num_repl'] = $num_repl; 
			$data['numobjects'] = $num_obj;
			$data['prov_objects'] = $prov_objects; 
			$data['repl_objects'] = $repl_objects; 
			$data['material'] = $material[0]; 
			$data['course'] =  $this->course->get_course($cid); 
			$data['list'] = $this->ocw_utils->create_co_list($cid,$mid,$prov_objects);
			$data['cid'] = $cid; 
			$data['mid'] = $mid; 
			$data['view'] = $view; 
			$data['title'] = 'Manage Content Objects'; 
    		$this->layout->buildPage('materials/askform', $data);
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

	public function	remove_object($cid, $mid, $oid, $type="original", $name="")
	{
		if ($type=='original') {
				flashMsg('Content object removed!');
				$this->coobject->remove_object($cid, $mid, $oid);
		} else {
				flashMsg('Replacement object removed!');
				$this->coobject->remove_replacement($oid, $name);
				//redirect("materials/object_info/$cid/$mid/$name", 'location');
		}
		redirect("materials/edit/$cid/$mid", 'location');
	}
	

	public function add_object($cid, $mid) 
 	{
		$valid = true;
		$errmsg = '';

		if (!isset($_FILES['userfile_0']['name'])) {
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

		if (!isset($_FILES['userfile']['name'])) {
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
				$name = "c$cid.m$mid.o$oid";
				if ($this->ocw_utils->replacement_exists($name)) {
						$this->coobject->update_rep_image($cid, $mid, $oid, $_FILES);
				} else {
						$this->coobject->add_replacement($cid, $mid, $oid, array(), $_FILES);
				}
				
				$rnd = time().rand(10,10000); // used to overcome caching problem
				
				if ($field == 'rep') {
						redirect("materials/object_info/$cid/$mid/$name/$rnd", 'location');
				} elseif($field=='irep') {
						redirect("materials/viewform/ask/$cid/$mid/$rnd", 'location');
				}
				exit;

		} else {
				if ($field=='action_type') {
						$lgcm = 'Changed action type to '.$val;
						$this->coobject->add_log($oid, getUserProperty('id'), array('log'=>$lgcm));
				} elseif ($field=='done') {
						$lgcm = 'Changed cleared status to '.(($val==1)?'"yes"':'"no"');
						$this->coobject->add_log($oid, getUserProperty('id'), array('log'=>$lgcm));
				} else {}
				$data = array($field=>$val);
				$this->coobject->update($oid, $data);
		}

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

	public function update_object_question($oid,$qid,$answer,$type='original')
	{
	   $data['answer'] = $answer;
	   $this->coobject->update_question($oid, $qid, $data,$type);
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

	public function object_info($cid,$mid,$oname)
	{
		$subtypes =  $this->coobject->object_subtypes();
		$obj = $this->coobject->coobjects($mid,$oname);
    list($undef,$undef,$oid) = split("\.", $oname);
    $oid = preg_replace('/o/','',$oid);
		$repl_objects =  $this->coobject->replacements($mid,$oid); 
		$objstats =  $this->coobject->object_stats($mid);
		
		$data = array(
					  'obj'=>$obj[0],
					  'cid'=>$cid,
					  'mid'=>$mid,
					  'user'=>getUserProperty('user_name'),
				  	'subtypes'=>$subtypes,
						'objstats' => $objstats,
				  	'repl_obj'=>$repl_objects[0],
				);

    	$this->load->view('default/content/materials/edit_co', $data);
	}
		
	public function make_image($text)
	{
		$this->ocw_utils->new_image($text);
		exit;
	}
}
?>
