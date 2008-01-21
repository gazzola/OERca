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
						  'breadcrumb'=>$this->breadcrumb($cid),
	   					  'cname' => $this->course->course_title($cid), 
						  'cid'=>$cid,
						  'caller'=>$caller,
					  	  'tags'=>$tags
					 	);
       		$this->layout->buildPage('materials/index', $data);
	}

	public function update($cid,$mid,$field,$val)
	{
	    $data = array($field=>$val);
	    $this->material->update($mid, $data);            
		$this->ocw_utils->send_response('success');            
		exit;
	}

	public function add_comment($cid,$mid,$comments)
	{
	   $data['comments'] = $comments;
	   $this->material->add_comment($mid, 2, $data);
       $this->ocw_utils->send_response('success');
       exit;
	}

	public function edit($cid, $mid, $caller='')
	{
		$tags =  $this->tag->tags();
		$mimetypes =  $this->mimetype->mimetypes();
		$material =  $this->material->materials($cid,$mid,true);
		$numobjects =  $this->coobject->num_objects($mid);
		$subtypes =  $this->coobject->object_subtypes();
		$objstats =  $this->coobject->object_stats($mid);
	    $course = $this->course->get_course($cid); 

		//$this->ocw_utils->dump($subtypes);

		$data = array('title'=>'Edit Material &raquo; '.$material[0]['name'],
					  'material'=>$material[0], 
					  'numobjects'=>$numobjects, 
					  'breadcrumb'=>$this->breadcrumb($cid,$material[0],'edit',$caller),
					  'cid'=>$cid,
	   				  'course'=> $course,
	   				  'cname' => $course['number'].' '.$course['title'],
				  	  'tags'=>$tags,
				  	  'mimetypes'=>$mimetypes,
				  	  'subtypes'=>$subtypes,
				  	  'objstats'=>$objstats,
				  	  'caller'=>$caller
				);
    	$this->layout->buildPage('materials/edit_material', $data);
	}


	public function breadcrumb($cid, $material='', $section='default', $caller='')
	{
		$breadcrumb = array();
	   	$name = $this->course->course_title($cid); 

		$breadcrumb[] = array('url'=>site_url(), 'name'=>'Home');
		$breadcrumb[] = array('url'=>site_url('home'), 'name'=>'Manage Courses');

		if ($section == 'default') {
			$breadcrumb[] = array('url'=>'', 'name'=>$name);

		} elseif ($section == 'edit') {
			$breadcrumb[] = array('url'=>site_url('materials/home/'.$cid.'/'.$caller), 'name'=>$name);
			$breadcrumb[] = array('url'=>'', 'name'=>$material['name']);

		} elseif ($section == 'askform') {
			$breadcrumb[] = array('url'=>site_url('materials/home/'.$cid), 'name'=>$name);
			$breadcrumb[] = array('url'=>site_url('materials/edit/'.$cid.'/'.$material['id']), 
								   'name'=>$material['name']);
			$breadcrumb[] = array('url'=>'', 'name'=>'Verify Form');
		}
		return $breadcrumb;
	}

	public function viewform($form, $cid, $mid='')
	{
		if ($form == 'ask') {
			$objects =  $this->coobject->coobjects($mid,'','Ask');
			$material =  $this->material->materials($cid,$mid,true);
			$data['numobjects'] = count($objects);
			$data['course'] =  $this->course->get_course($cid); 
			$data['list'] = $this->ocw_utils->create_co_list($cid,$mid,$objects);
			$data['cid'] = $cid; 
			$data['mid'] = $mid; 
			$data['title'] = 'Verify Media in Material'; 
			$data['objects'] = $objects; 
			$data['material'] = $material[0]; 
			//$data['breadcrumb']=$this->breadcrumb($cid,$material[0],'askform');
    		$this->layout->buildPage('materials/askform', $data);
		}
	}

	public function processform($form, $cid, $mid)
	{
		if ($form == 'ask') {
			$data1 = array();
			foreach ($_POST['ask'] as $oid => $d) {
					if ($d['own']=='yes') { 
						$cm = 'Instructor says: I own this media';
					    $lgcm = 'Instructor has claimed ownership of CO'. 
								' - setting CO to cleared';
						$data1['done'] = '1'; 
						$this->coobject->add_log($oid, 2, array('log'=>$lgcm));
						$this->coobject->add_comment($oid, 2, 
													array('comments'=>$cm));
					} else {
					    $cm = 'Instructor says: I do not own this media'; 
						$this->coobject->add_comment($oid, 2, array('comments'=>$cm));
					}	
					if ($d['comments'] <> '') {
						$cm = 'Instructor says: '.$d['comments'];
						$this->coobject->add_comment($oid, 2, 
													array('comments'=>$cm));
					}
					$data1['ask'] = 'no';
					$data1['significance'] = $d['significance'];
					$this->coobject->update($oid, $data1);
					$data1 = array();
			}
			redirect("materials/viewform/ask/$cid/$mid/", 'location');
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
		redirect("materials/edit/$cid/$mid/", 'location');
	}

	public function update_object($cid, $mid, $oid, $field, $val='') 
 	{
		if ($field=='rep') {
			$this->coobject->update_rep_image($cid, $mid, $oid, $_FILES);
			$name = "c$cid.m$mid.o$oid";
			redirect("materials/object_info/$cid/$mid/$name", 'location');
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

	public function add_object_comment($oid,$comments)
	{
	   $data['comments'] = $comments;
	   $this->coobject->add_comment($oid, 2, $data);
       $this->ocw_utils->send_response('success');
       exit;
	}

	public function object_info($cid,$mid,$oname)
	{
		$subtypes =  $this->coobject->object_subtypes();
		$obj = $this->coobject->coobjects($mid,$oname);

		//$this->ocw_utils->dump($obj); exit;


		$data = array(
					  'obj'=>$obj[0],
					  'cid'=>$cid,
					  'mid'=>$mid,
					  'user'=>'jsmith',
				  	  'subtypes'=>$subtypes,
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
