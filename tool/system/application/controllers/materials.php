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
	}
	
	public function index($cid, $caller="") { $this->home($cid, $caller); }

	public function home($cid, $caller='')
	{
			$tags =  $this->tag->tags();
			$materials =  $this->material->materials($cid,'',true,true);
			$data = array('title'=>'Materials',
						  'materials'=>$materials, 
	   					'cname' => $this->course->course_title($cid), 
						  'cid'=>$cid,
						  'caller'=>$caller,
					  	'tags'=>$tags
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
	   $this->material->add_comment($mid, 2, $data);
       $this->ocw_utils->send_response('success');
       exit;
	}


	public function edit($cid, $mid, $caller='', $filter='Any')
	{
		$tags =  $this->tag->tags();
		$mimetypes =  $this->mimetype->mimetypes();
		$numobjects =  $this->coobject->num_objects($mid);
		$objstats =  $this->coobject->object_stats($mid);
		$subtypes =  $this->coobject->object_subtypes();
	  $course = $this->course->get_course($cid); 
		$material =  $this->material->materials($cid,$mid,true);
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

	public function add_object($cid, $mid) 
 	{
		//$this->ocw_utils->dump($_FILES);
		$this->coobject->add($cid, $mid,2,$_POST,$_FILES);
		$this->update($cid,$mid,'embedded_co','1',false);
		redirect("materials/edit/$cid/$mid/", 'location');
	}

	public function add_object_zip($cid, $mid) 
 	{
		//$this->ocw_utils->dump($_FILES); exit;
		$this->coobject->add_zip($cid, $mid,2,$_FILES);
		$this->update($cid,$mid,'embedded_co','1',false);
		redirect("materials/edit/$cid/$mid/", 'location');
	}

	public function update_object($cid, $mid, $oid, $field, $val='') 
 	{
	   /** HACK: dreamhost messing around and converting spaces to
           underscores - remove when hosted on Bezak **/
	   $val = ($val=='in_progress') ? 'in progress' : $val;

		if ($field=='rep' or $field=='irep') {
				$name = "c$cid.m$mid.o$oid";
				if ($this->ocw_utils->replacement_exists($name)) {
						$this->coobject->update_rep_image($cid, $mid, $oid, $_FILES);
				} else {
						$this->coobject->add_replacement($cid, $mid, $oid, array(), $_FILES);
				}

				if ($field == 'rep') {
						redirect("materials/object_info/$cid/$mid/$name", 'location');
				} elseif($field=='irep') {
						redirect("materials/viewform/ask/$cid/$mid/", 'location');
				}
				exit;

		} else {
				if ($field=='action_type') {
						$lgcm = 'Changed action type to '.$val;
						$this->coobject->add_log($oid, 2, array('log'=>$lgcm));
				} elseif ($field=='done') {
						$lgcm = 'Changed cleared status to '.($val=='1')?'"yes"':'"no"';
						$this->coobject->add_log($oid, 2, array('log'=>$lgcm));
				} else {}
				$data = array($field=>$val);
				$this->coobject->update($oid, $data);
		}

    $this->ocw_utils->send_response('success');
   	exit;
	}


	public function update_replacement($cid, $mid, $oid, $field, $val='') 
 	{
	   /** HACK: dreamhost messing around and converting spaces to
           underscores - remove when hosted on Bezak **/
	   $val = ($val=='in_progress') ? 'in progress' : $val;
	   $data = array($field=>$val);
	   $this->coobject->update_replacement($oid, $data);
     $this->ocw_utils->send_response('success');
     exit;
	}

	public function add_object_comment($oid,$comments,$type='original')
	{
	   $data['comments'] = $comments;
	   $this->coobject->add_comment($oid, 2, $data,$type);
     $this->ocw_utils->send_response('success');
     exit;
	}

	public function add_object_question($oid,$question,$type='original')
	{
	   $data['question'] = $question;
	   $this->coobject->add_question($oid, 2, $data, $type);
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

		//$this->ocw_utils->dump($repl_objects); exit;
		$data = array(
					  'obj'=>$obj[0],
					  'cid'=>$cid,
					  'mid'=>$mid,
					  'user'=>'jsmith',
				  	'subtypes'=>$subtypes,
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
