<?php
// TODO: Move object specific function to coobject model.
/**
 * Provides access to material information
 *
 * @package    OCW Tool
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Material extends Model
{
  public function __construct()
  {
    parent::Model();
    # remove material objects and related info
    $this->load->model('coobject','co');
    $this->load->model('mimetype');
  }

  /**
   * Get status of a material for a particular user
   *
   * @access  public
   * @param   int               mid    material id
   * @param   int               uid  user id
   * @param   string  type  type of status [askform]
   * @return  boolean true if there has been a change in status
   *          since the user's last login
   */
  public function status($mid, $uid, $type)
  {
    if (!in_array($type,array('askform'))) { return false; }
    if ($type=='askform') {
      // check to see if there are any unsent emails for this user
      // in the askforms
      $sql = "SELECT COUNT(*) AS num
			 FROM ocw_postoffice
			 WHERE material_id=$mid AND sent='no' AND to_id=$uid";
      $q = $this->db->query($sql);
      $r = $q->row();
      return ($r->num > 0) ? true : false;
    }
  }

  /**
   * add material based on information given
   *
   */
  public function add_material ($details)
  {
    $query=$this->db->insert('materials',$details);
    $this->db->select('id');
    $courseId = $details['course_id'];
    $name = $details['name'];
    $this->db->from('materials')->where('course_id', $courseId)->where('name', $name)->where('in_ocw', '1');
    $q = $this->db->get();
    $rv = null;
    if ($q->num_rows() > 0)
      {
	foreach($q->result_array() as $row) {
	  $rv = $row['id'];
	}
      }
    return $rv;
  }

  /**
   * remove material based on information given
   *
   */
  public function remove_material($cid, $mid)
  {
    # remove content objects and their related files
    $this->co->remove_objects($cid, $mid);

    # remove material comments
    $this->db->delete('material_comments', array('material_id'=>$mid));

    # remove material from filesystem
    $paths = $this->material_path($cid, $mid, true);
    if (!is_null($paths)) {
      foreach($paths as $path) { $this->ocw_utils->remove_dir($path); }
    }

    # The following must be done _after_ removing material from
    # filesystem so we can find the name to be removed!

    # remove material_files entry
    $this->db->delete('material_files', array('material_id'=>$mid));
    # remove material from db
    $this->db->delete('materials',array('id'=>$mid, 'course_id'=>$cid));

    return true;
  }

  /**
   * Find where the material is marked for ocw already
   */
  public function getMaterialName($id)
  {
    $this->db->select('name');
    $where = "id='".$id."'";
    $this->db->from('materials')->where($where);
    $q = $this->db->get();
    $rv = null;
    if ($q->num_rows() > 0)
      {
	foreach($q->result_array() as $row) {
	  $rv = $row['name'];
	}
      }
    return $rv;
  }

  /**
   * Find where the material is marked for ocw already
   */
  public function findOCWMaterial($cid, $name)
  {
    $this->db->select('id');
    $where = "course_id='".$details['course_id']."' AND name='".$details['name']."' AND in_ocw='1'";
    $this->db->from('materials')->where($where);
    $q = $this->db->get();
    $rv = null;
    if ($q->num_rows() > 0)
      {
	foreach($q->result_array() as $row) {
	  //print '<pre>'; print_r($row); print '</pre>';
	  $rv = $row['id'];
	}
      }
    return $rv;
  }


  /**
   * Get materials for a given course
   *
   * @access  public
   * @param   int      cid course id
   * @param   int mid material id
   * @param   boolean  in_ocw if true only get materials in ocw
   * @param   boolean  as_listing
   * @return  array
   */
  public function materials($cid, $mid='', $in_ocw=false, $as_listing=false, $fs_status=0,$fs_action=0,$fs_type=0,$fs_repl=0)
  {
    $materials = array();
    $where1 = (is_numeric($cid)) ? "ocw_materials.course_id = $cid" : "ocw_materials.course_id = 0";
    $where2 = ($mid=='') ? '' : "AND ocw_materials.id='$mid'";

    $sql = "SELECT ocw_materials.*, ocw_mimetypes.mimetype, ocw_mimetypes.name AS mimename, ocw_tags.name AS tagname
      FROM ocw_materials
      LEFT JOIN ocw_mimetypes
      ON ocw_mimetypes.id = ocw_materials.mimetype_id
      LEFT JOIN ocw_tags
      ON ocw_tags.id = ocw_materials.tag_id
      WHERE $where1 $where2
      ORDER BY ocw_materials.order";
    $q = $this->db->query($sql);

    if ($q->num_rows() > 0) {
      foreach($q->result_array() as $row) {
	$row['display_date'] = $this->ocw_utils->calc_later_date(
								 $row['created_on'], $row['modified_on'],'d M, Y H:i:s'); // define the display date
	$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
	$row['files'] = $this->material_files($cid, $row['id']);
	if ($in_ocw) {
	  if ($row['in_ocw']) { $materials[]= $row; }
	} else {
	  $materials[]= $row;
	}
      }
    }

    return (sizeof($materials)) ? (($as_listing) ? $this->as_listing($materials):$materials) : null;
  }

  /**
   * Get materials for a given course
   *
   * @access  public
   * @param   int      cid course id
   * @param   int mid material id
   * @param   boolean  in_ocw if true only get materials in ocw
   * @param   boolean  as_listing
   * @return  array
   */
  public function faceted_search_materials($cid, $mid='', $in_ocw=false, $as_listing=false, $author=0, $material_type=0, $file_type=0)
  {
    $materials = array();
    $where1 = (is_numeric($cid)) ? "ocw_materials.course_id = $cid" : "ocw_materials.course_id = 0";
    $where2 = ($mid=='') ? '' : "AND ocw_materials.id='$mid'";
    if ($author > 0) {
      $authorslist = $this->material->authors_list($cid);
      $where3 = " AND (";
      $authors = explode("z", $author);
      foreach ($authors as $a) $authorwheres[] = "ocw_materials.author = ".$this->db->escape($authorslist[$a])." ";
      $where3 .= implode(" OR ", $authorwheres);
      $where3 .= ")";
    } else $where3 = "";
    if ($material_type > 0) {
      $material_typeslist = $this->material->material_types_list($cid);
      $where4 = " AND (";
      $material_types = explode("z", $material_type);
      foreach ($material_types as $m) $material_typewheres[] = "ocw_tags.name = '".$material_typeslist[$m]."' ";
      $where4 .= implode(" OR ", $material_typewheres);
      $where4 .= ")";
    } else $where4 = "";
    $where5 = ""; //placeholder for cc license param
    if ($file_type > 0) {
      $file_typeslist = $this->material->mimetypes_list($cid);
      $where6 = " AND (";
      $file_types = explode("z", $file_type);
      foreach ($file_types as $fkey=>$f) $file_typewheres[] = "ocw_materials.mimetype_id = ".$f." ";
      $where6 .= implode(" OR ", $file_typewheres);
      $where6 .= ")";
    } else $where6 = "";

    $sql = "SELECT ocw_materials.*, ocw_mimetypes.mimetype, ocw_mimetypes.name AS mimename, ocw_tags.name AS tagname
      FROM ocw_materials
      LEFT JOIN ocw_mimetypes
      ON ocw_mimetypes.id = ocw_materials.mimetype_id
      LEFT JOIN ocw_tags
      ON ocw_tags.id = ocw_materials.tag_id
      WHERE $where1 $where2 $where3 $where4 $where5 $where6
      ORDER BY ocw_materials.order";
    //echo $sql;
    $q = $this->db->query($sql);
    if ($q->num_rows() > 0) {
      foreach($q->result_array() as $row) {
	$row['display_date'] = $this->ocw_utils->calc_later_date(
								 $row['created_on'], $row['modified_on'],'d M, Y H:i:s'); // define the display date
	$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
	$row['files'] = $this->material_files($cid, $row['id']);
	if ($in_ocw) {
	  if ($row['in_ocw']) { $materials[]= $row; }
	} else {
	  $materials[]= $row;
	}
      }
    }
    return (sizeof($materials)) ? (($as_listing) ? $this->as_listing($materials):$materials) : null;
  }

  /**
   * Get materials for a given course in a given category
   *
   * @access  public
   * @param   int cid course id
   * @param   int mid material id
   * @param   boolean in_ocw if true only get materials in ocw
   * @param   boolean as_listing
   * @param   int category category
   * @return  array
   */
  public function categoryMaterials($cid, $mid, $in_ocw=false, $as_listing=false, $category)
  {
    $materials = array();
    $where = ($mid=='') ? '' : "AND ocw_materials.id='$mid'";
    $where = ($category=='') ? $where : $where."AND ocw_materials.category='$category'";

    $sql = "SELECT ocw_materials.*, ocw_mimetypes.mimetype
      FROM ocw_materials
      LEFT JOIN ocw_mimetypes
      ON ocw_mimetypes.id = ocw_materials.mimetype_id
      WHERE ocw_materials.course_id = '$cid' $where
      ORDER BY ocw_materials.order";
    $q = $this->db->query($sql);

    if ($q->num_rows() > 0) {
      foreach($q->result_array() as $row) {
	$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
	if ($in_ocw) {
	  if ($row['in_ocw']) { $materials[]= $row; }
	} else {
	  $materials[]= $row;
	}
      }
    }
    return (sizeof($materials)) ? (($as_listing) ? $this->as_listing($materials):$materials) : null;
  }


  /**
   * Get files for a material
   *
   * @access  public
   * @param   int      cid course id
   * @param   int      mid material id
   * @param   string details fields to return
   * @return  array
   */
  public function material_files($cid, $mid, $details='*')
  {
    $files = array();

    // get course filename
    $this->db->select('filename')->from('course_files')->where("course_id=$cid")->order_by('created_on desc')->limit(1);
    $q = $this->db->get();
    $r = $q->row();
    $cname = 'cdir_'.$r->filename;

    $this->db->select($details)->from('material_files')->where('material_id',$mid)->orderby('modified_on DESC');
    $q = $this->db->get();

    if ($q->num_rows() > 0) {
      foreach($q->result_array() as $row) {
	$row['fileurl'] =  property('app_uploads_url').$cname.'/mdir_'.$row['filename'].'/'.$row['filename'];
	$row['filepath'] = property('app_uploads_path').$cname.'/mdir_'.$row['filename'].'/'.$row['filename'];
	array_push($files, $row);
      }
    }

    return (sizeof($files) > 0) ? $files : null;
  }

  /**
   * Get comments  for a material
   *
   * @access  public
   * @param   int      mid material id
   * @param   string details fields to return
   * @return  array
   */
  public function comments($mid, $details='*')
  {
    $comments = array();
    $this->db->select($details)->from('material_comments')->where('material_id',$mid)->orderby('modified_on DESC');
    $q = $this->db->get();

    if ($q->num_rows() > 0) {
      foreach($q->result_array() as $row) {
	array_push($comments, $row);
      }
    }

    return (sizeof($comments) > 0) ? $comments : null;
  }

  /**
   * Add a comment
   *
   * @access  public
   * @param   int material id
   * @param   int user id
   * @param   array data
   * @return  void
   */
  public function add_comment($mid, $uid, $data)
  {
    $data['material_id'] = $mid;
    $data['user_id'] = $uid;
    $data['created_on'] = date('Y-m-d h:i:s');
    $data['modified_on'] = date('Y-m-d h:i:s');
    $this->db->insert('material_comments',$data);
  }


  /**
   * Return # of materials for a course
   *
   * @access  public
   * @param   int      course id
   * @return  int
   */
  public function number($cid)
  {
    $q = $this->db->query("SELECT COUNT(*) AS num FROM ocw_materials WHERE course_id=$cid");
    $r = $q->row();
    return $r->num;
  }

  /**
   * Update materials for a given course
   *
   * @access  public
   * @param   int      mid material id
   * @param   array    data
   * @return  void
   */
  public function update($mid, $data)
  {
    // We're modifying the material.  Update the 'modified_on' time.
    // It seems we could get this for free from the DB code by
    // making a schema change?
    if (!isset($data['modified_on']))
      $data['modified_on'] = date('Y-m-d h:i:s');
    $this->db->update('materials',$data,"id=$mid");
  }

  /**
   * Get categories
   *
   * @access  public
   * @return  array
   */
  public function categories()
  {
    $c = array();

    $this->db->select('*')->from('material_categories')->orderby('name');

    $q = $this->db->get();

    if ($q->num_rows() > 0) {
      foreach($q->result_array() as $row) {
	$c[$row['id']] = $row['name'];
      }
    }

    return (sizeof($c) > 0) ? $c : null;
  }

  private function as_listing($materials)
  {
    $done = array();
    $course_materials = array();

    foreach ($materials as $cm) {
      $id = $cm['id'];
      $nodetype = $cm['parent'];
      $category = $cm['category'];

      // skip children for now
      if ($nodetype != 0) continue;

      if (!in_array($id, $done)) {
	array_push($done, $id);

	// find children
	list($children, $done) =
	  $this->find_children($id, $materials,$done);

	//indicate if material has been fully cleared
	$status = $this->is_cleared($id, $cm['embedded_co']);
	$cm['validated'] = ($status['notdone'] > 0) ? 0 : 1;
	$cm['statcount'] = $status['done'] .'/'.($status['done']+$status['notdone']);

	$cm['mdone'] = $status['done'];
	$cm['mrem'] = $status['notdone'];
	$cm['mask'] = $status['recaction'] + $status['actaken'];
	$cm['mtotal'] = $status['done']  + $status['notdone'] + $status['recaction'] + $status['actaken'];
	$cm['mdash'] = 0;     //  if date modified is NULL, show dashes   OERDEV-147
	if ($cm['modified_on'] == '0000-00-00 00:00:00') $cm['mdash'] = 1; // material is brand new;
	if ($cm['modified_on'] <> '0000-00-00 00:00:00' && $cm['embedded_co'] == 0) $cm['mdash'] = 2; // edited and co=no
	if ($cm['modified_on'] <> '0000-00-00 00:00:00' && $cm['embedded_co'] == 1) $cm['mdash'] = 3; // edited and co=yes
	if ($cm['modified_on'] <> '0000-00-00 00:00:00' && $cm['embedded_co'] == 1 && $cm['mtotal']== 0) $cm['mdash'] = 4; // edited and co=yes and total = 0 OERDEV-181 mbleed

	//  OERDEV-140 - let's see if we can make a progress bar green with no CO's
	//if ($cm['embedded_co'] == 0) $cm['mtotal'] = 1000000; //OERDEV-181 mbleed: remove hardcoded 1000000 total case and replace by sending $dash=2 to progbar function

	// bdr OERDEV-146: let's try to figure out if all CO's have a Recommended Action
	$cm['recaction'] = 0;
	$cm['actaken'] = 0;

	if ($status['recaction'] > 0) {
	  if ($status['recaction'] == ($status['done']+$status['notdone']))
	    $cm['recaction'] = 1;
	}

	if ($status['notdone'] == 0)  // if all marked for final action & marked done
	  if ($status['actaken'] == $status['done']) $cm['actaken'] = 1;

	if ($status['done'] == 0 && $status['notdone'] == 0 && $cm['embedded_co'] != 0)
	  {       // if both done and notdone object are equal to 0,
	    // mark the validated attribute to be false, in order
	    // to force dscribes to do content object capture
	    $cm['validated'] = 0;
	    $cm['actaken'] = 0; // per Piet comment on OERDEV146
	  }

	if (sizeof($children) > 0) {
	  $cm['show'] = ($this->child_not_in_ocw($children))?1:0;
	  $cm['childitems'] = $children;
	}
      }
      // $this->ocw_utils->dump($cm);
      $course_materials[$category][$cm['order']] = $cm;
    }

    ksort($course_materials);
    return $course_materials;
  }

  // find contents of folders
  private function find_children($id, $materials, $done)
  {
    $tmp = array();

    foreach ($materials as $ccm) {
      $cid = $ccm['id'];
      $order = $ccm['order'];
      $parent = $ccm['parent'];

      if (!in_array($cid, $done) && $parent!=0) {
	if ($parent == $id) {
	  array_push($done, $cid);

	  $tmp[$order] = $ccm;
	  $status = $this->is_cleared($cid, $ccm['embedded_co']);

	  $tmp[$order]['validated'] = ($status['notdone'] > 0) ? 0 : 1;
	  $tmp[$order]['statcount'] = $status['done'] .'/'.($status['done']+$status['notdone']);

	  $tmp[$order]['mdone'] = $status['done'];
	  $tmp[$order]['mrem'] = $status['notdone'];
	  $tmp[$order]['mask'] = $status['recaction'] + $status['actaken'];
	  $tmp[$order]['mtotal'] = $status['done']  + $status['notdone'] + $status['recaction'] + $status['actaken'];
	  $tmp[$order]['mdash'] = 0;     //  if date modified is NULL, show dashes   OERDEV-147
	  if ($tmp[$order]['modified_on'] == '0000-00-00 00:00:00') $tmp[$order]['mdash'] = 1; // material is brand new;
	  if ($tmp[$order]['modified_on'] <> '0000-00-00 00:00:00' && $tmp[$order]['embedded_co'] == 0) $tmp[$order]['mdash'] = 2; // edited and co=no
	  if ($tmp[$order]['modified_on'] <> '0000-00-00 00:00:00' && $tmp[$order]['embedded_co'] == 1) $tmp[$order]['mdash'] = 3; // edited and co=yes
	  if ($tmp[$order]['modified_on'] <> '0000-00-00 00:00:00' && $tmp[$order]['embedded_co'] == 1 && $tmp[$order]['mtotal'] == 0) $tmp[$order]['mdash'] = 4; // edited and co=yes and total=0 OERDEV-181 mbleed


	  //  OERDEV-140 - let's see if we can make a progress bar green with no CO's
	  //if ($cm['embedded_co'] == 0) $tmp[$order]['mtotal'] = 1000000; //OERDEV-181 mbleed: remove hardcoded 1000000 total case and replace by sending $dash=2 to progbar functio

	  // bdr OERDEV-146: let's try to figure out if all CO's have a Recommended Action
	  $tmp[$order]['recaction'] = 0;
	  $tmp[$order]['actaken'] = 0;

	  if ($status['recaction'] > 0) {
	    if ($status['recaction'] == ($status['done']+$status['notdone']))
	      $tmp[$order]['recaction'] = 1;
	  }

	  if ($status['notdone'] == 0)  // if all marked for final action & marked done
	    if ($status['actaken'] == $status['done']) $tmp[$order]['actaken'] = 1;

	  if ($status['done'] == 0 && $status['notdone'] == 0 && $cm['embedded_co'] != 0)
	    {       // if both done and notdone object are equal to 0,
	      // mark the validated attribute to be false, in order
	      // to force dscribes to do content object capture
	      $tmp[$order]['validated'] = 0;
	      $tmp[$order]['actaken'] = 0; // per Piet comment on OERDEV146
	    }

	  // find more children if necessary
	  list($children, $done) =
	    $this->find_children($cid,$materials,$done);

	  if (sizeof($children) > 0) {
	    $tmp[$order]['show'] =
	      ($this->child_not_in_ocw($children)) ? 1:0;
	    $tmp[$order]['childitems'] = $children;
	  }
	}
      }
    }
    return array($tmp, $done);
  }

  // check to see if material is free of ip voilations
  private function is_cleared($mid, $has_ip)
  {
    // bdr OERDEV146:  add "recaction" to count CO's with a Recommended Action
    $status = array('done'=>0,'notdone'=>0,'recaction'=>0,'actaken'=>0);
    if ($has_ip==0) return $status;    // bdr - means no embedded CO in this material

    $where = array('material_id'=>$mid);
    // bdr OERDEV146:  let's also ask DB for action_type so we can figure out Recommend Action
    $q = $this->db->query("SELECT done, action_type, action_taken,
      ask, ask_status, ask_dscribe2, ask_dscribe2_status FROM ocw_objects WHERE material_id=$mid");
    // $this->ocw_utils->dump($where);
    foreach($q->result_array() as $row) {
      if ($row['done'] == '1' && !empty($row['action_taken']))  { $status['done']++; }
      else {
	// bdr OERDEV-146: let's count number of "recommended actions that are not NULL
	if (!empty($row['action_type'])) {
	  $status['recaction']++;
	}
	elseif (!empty($row['action_taken'])) {
	  $status['actaken']++;
	}
	elseif ($row['ask'] == 'yes' && $row['ask_status'] != 'new') {
	  $status['actaken']++;
	}
	elseif ($row['ask_dscribe2'] == 'yes' && $row['ask_dscribe2_status'] != 'new') {
	  $status['actaken']++;
	}
	else {
	  $status['notdone']++;
	}
      }
    }
    return $status;
  }

  // check to see if there is a child object that is not in ocw
  private function child_not_in_ocw($children)
  {
    foreach($children as $child) {
      if ($child['in_ocw']==0) return true;
    }
    return false;
  }


  /**
   * Get the number of content objects in a particular state e.g.
   * cleared, ask or new
   * @param    int cid the course id
   * @param    string isAsk should be "NULL", "yes" or "no"
   * @param    string isDone (optional) oddly, this is a string
   *           even though it is "0" or "1", perhaps it can be an int
   * @return   int count of the content objects
   *
   * these functions possibly duplicate functionality to get counts of
   * content objects
   * TODO: get rid of the functions if there is a better way
   * to do this
   * TODO: check to see if the parameter types can be changed?
   * TODO: alter this function to accept multiple courses so a single
   *       DB query can be performed
   */
  public function get_co_count($cid, $isAsk = NULL, $isDone = '0')
  {
    $this->db->from('objects');
    $this->db->
      join('materials', 'materials.id = objects.material_id', 'inner')->
      join('courses', 'courses.id = materials.course_id', 'inner');

    $passedParams = array('courses.id' => $cid);

    if ($isAsk == 'yes') {
      // this is the "in-progress" count
      $passedParams['objects.ask'] = $isAsk;
      $passedParams['objects.done'] = $isDone;
    } elseif ($isAsk == 'no') {
      // this should be the "not cleared" count
      $passedParams['objects.ask'] = $isAsk;
      $passedParams['objects.done'] = $isDone;
    } elseif ($isDone == '1') {
      // this is collecting the "cleared" count
      $passedParams['objects.done'] = $isDone;
    }

    $this->db->where($passedParams);
    $q = $this->db->get();
    //return the number of results
    // $this->ocw_utils->dump($q->num_rows());
    // $this->ocw_utils->dump($q);
    return($q->num_rows());
  }


  /**
   * The next three functions simply call "get_co_count()" above
   * for each content object state. They are merely for convenience
   * to avoid having to pass all the params to "get_co_count()" from
   * the calling controller.
   *
   * @param    int cid the course id
   * @return   int the number of content objects
   *
   * TODO: see if "get_co_count()" can categorize each content object
   *       state instead of making 3 DB calls
   */
  public function get_done_count($cid)
  {
    return($this->get_co_count($cid, NULL, '1'));
  }

  public function get_ask_count($cid)
  {
    return($this->get_co_count($cid, 'yes'));
  }

  public function get_rem_count($cid)
  {
    return($this->get_co_count($cid, 'no'));
  }

  /**
   * upload materials to correct path
   */
  public function upload_materials($cid, $mid, $file)
  {
    $this->load->model('course');
    $tmpname = $file['tmp_name'];
    $path = property('app_uploads_path');
    $r = NULL; //placeholder for DB $row results
    $curr_mysql_time = $this->ocw_utils->get_curr_mysql_time();

    # get course directory name
    $this->db->select('filename')->from('course_files')->where("course_id=$cid")->order_by('created_on desc')->limit(1);
    $q = $this->db->get();
    if ($q->num_rows() > 0) {
      $r = $q->row();
      $path .= 'cdir_'.$r->filename;
    } else {
      // TODO: account for the case where there is no course data
      $cdata = $this->course->get_course($cid);
      $filename = $this->course->generate_course_name($cdata['title'].
						      $cdata['start_date'].$cdata['end_date']);
      $dirname = property('app_uploads_path') . 'cdir_' . $filename;
      $this->oer_filename->mkdir($dirname);
      $this->db->insert('course_files',
			array('filename' => $filename,
			      'modified_on' => $curr_mysql_time,
			      'created_on' => $curr_mysql_time,
			      'course_id'=>$cdata['id']));
      $path = $dirname;
    }

    $this->oer_filename->mkdir($path);

    # get material directory name
    $name = $this->generate_material_name($tmpname);
    $path .= '/mdir_'.$name;
    $this->oer_filename->mkdir($path);

    # get file extension
    preg_match('/\.(\w+)$/',$file['name'],$match);
    $ext = (isset($match[1])) ? '.'.$match[1]:'';

    // move file to new location
    if (is_uploaded_file($tmpname)) {
      move_uploaded_file($tmpname, $path.'/'.$name.$ext);
    } else {
      copy($tmpname, $path.'/'.$name.$ext);
      unlink($tmpname);
    }

    # store new filename
    $this->db->insert('material_files', array('material_id'=>$mid,
					      'filename'=>$name,
					      'modified_on'=>date('Y-m-d h:i:s'),
					      'created_on'=>date('Y-m-d h:i:s')));

    return $path.'/'.$name.$ext;
  }

  /* return the path to a material on the file system
   *
   * returns path to latest version of material unless
   * all is true and then it returns paths to all versions
   */
  private function material_path($cid, $mid, $all=false)
  {
    $path = property('app_uploads_path');

    # get course directory name
    $this->db->select('filename')->from('course_files')->where("course_id=$cid")->order_by('created_on desc')->limit(1);
    $q = $this->db->get();
    $r = $q->row();
    $path .= 'cdir_'.$r->filename;

    $this->db->select('filename')->from('material_files')->where("material_id=$mid")->order_by('created_on desc');
    if (!$all) { $this->db->limit(1); }

    $q = $this->db->get();

    if ($q->num_rows() > 0) {
      if ($all) {
	$cpath = $path;
	$path = array();
	foreach($q->result_array() as $row) {
	  array_push($path, $cpath.'/mdir_'.$row['filename']);
	}
      } else {
	$r = $q->row();
	$path .= '/mdir_'.$r->filename;
      }
    } else {
      return null;
    }

    return $path;
  }

  /**
   * Insert material info into DB and return resulting material id
   *
   * @param    array detail information for new material
   * @return   integer material id
   */
  public function insert_material($details)
  {
    $this->db->insert('materials', $details);
    return $this->db->insert_id();
  }

  // TODO: change the SQL query to check for null and return 0? is that a good
  //      idea
  public function get_nextorder_pos($cid)
  {
    $q = $this->db->query("SELECT MAX(`order`) + 1 AS nextpos FROM ocw_materials WHERE course_id=$cid");
    $row = $q->result_array();
    if ($row[0]['nextpos']) {
      return $row[0]['nextpos'];
    } else return 0;
  }

  private function material_name_exists($name)
  {
    $this->db->select('filename')->from('material_files')->where("filename='$name'");
    $q = $this->db->get();
    return ($q->num_rows() > 0) ? true : false;
  }
  private function generate_material_name($filename)
  {
    $digest = '';
    $generate_own = false;
    do {
      if ($generate_own) {
	$this->ocw_utils->log_to_apache('debug', __FUNCTION__.": Using random name for '{$filename}'"); // XXX XXX XXX
	$digest = $this->oer_filename->random_name($filename);
      } else {
	$digest = $this->oer_filename->file_digest($filename);
      }
      $generate_own = true;
    } while ($this->material_name_exists($digest));

    return $digest;
  }


  /**
   * Get detailed info on a provided list of material ids and their
   * respective content objects.
   *
   * @param    int/string course id
   * @param    array of material ids
   * @return   array of paths to materials
   */
  // TODO: Make this function shorter if possible
  public function get_material_info($cid, $material_ids)
  {

    $uploads_dir = property('app_uploads_path');
    // format for constructing filename timestamps as YYYY-MM-DD-HHMMSS
    $download_date_format = "Y-m-d-His";
    $materials = array();
    $query_params = array($cid);

    // TODO: Change this SQL to active record queries
    $sql = "SELECT
	    ocw_course_files.course_id,
	    ocw_schools.name AS school_name,
	    ocw_courses.number AS course_number,
	    ocw_courses.title AS course_title,
	    ocw_course_files.filename AS course_file,
	    ocw_material_files.material_id,
	    ocw_materials.name AS material_name,
	    ocw_material_files.filename AS material_file,
	    ocw_material_files.created_on AS material_creation_date,
	    ocw_material_files.modified_on AS material_mod_date,
	    ocw_objects.id AS object_id,
	    ocw_objects.name AS object_name,
	    ocw_objects.location AS object_location,
	    ocw_objects.author AS object_author,
	    ocw_objects.contributor AS object_contributor,
	    ocw_objects.action_type AS object_rec_action,
	    ocw_objects.action_taken AS object_fin_action,
	    ocw_objects.citation AS object_citation,
            ocw_object_files.filename AS object_file_name,
	    ocw_object_replacements.id AS object_rep_id,
	    ocw_object_replacements.name AS object_rep_name,
	    ocw_object_replacements.location AS object_rep_location,
            ocw_object_replacements.author AS object_rep_author,
	    ocw_object_replacements.contributor AS object_rep_contributor,
	    ocw_object_replacements.citation AS object_rep_citation,
	    ocw_object_copyright.status AS object_copyright_status,
            ocw_object_copyright.holder AS object_copyright_holder,
	    ocw_object_copyright.url AS object_copyright_url,
	    ocw_object_replacement_copyright.status AS object_rep_copyright_status,
	    ocw_object_replacement_copyright.holder AS object_rep_copyright_holder,
            ocw_object_replacement_copyright.url AS object_rep_copyright_url
	    FROM
	    ocw_course_files
            INNER JOIN ocw_courses ON (ocw_courses.id = ocw_course_files.course_id)
	    INNER JOIN ocw_schools ON (ocw_schools.id = ocw_courses.school_id)
	    INNER JOIN ocw_materials ON (ocw_materials.course_id = ocw_courses.id)
	    INNER JOIN ocw_material_files ON (ocw_material_files.material_id = ocw_materials.id)
            LEFT OUTER JOIN ocw_objects ON (ocw_objects.material_id = ocw_materials.id)
            LEFT OUTER JOIN ocw_object_files ON (ocw_object_files.object_id = ocw_objects.id)
            LEFT OUTER JOIN ocw_object_replacements ON (ocw_object_replacements.object_id = ocw_objects.id)
	    LEFT OUTER JOIN ocw_object_copyright ON (ocw_object_copyright.object_id = ocw_objects.id)
            LEFT OUTER JOIN ocw_object_replacement_copyright ON (ocw_object_replacement_copyright.object_id =
	      ocw_object_replacements.id)
	    WHERE
	    ocw_courses.id = ? AND ( ";

    /* construct the last 'WHERE' clause in the query
     * from the list of passed material_ids
     * the loop adds all but the last material id placeholder
     * and the line after the loop does the rest
     */
    for ($i=0; $i < (count($material_ids) - 1); $i++) {
      $sql .= "ocw_materials.id = ? OR ";
    }

    $sql .= "ocw_materials.id = ? )";

    $query_params = array_merge ($query_params, $material_ids);

    $q = $this->db->query($sql, $query_params);

    if ($q->num_rows() > 0) {
      foreach ($q->result() as $row) {
	if (!array_key_exists($row->material_id, $materials)) {
	  $materials[$row->material_id] =
	    array(
		  'course_id' => $row->course_id,
		  'school_name' => $row->school_name,
		  'course_number' => $row->course_number,
		  'course_title' => trim($row->course_title),
		  'course_file' => $row->course_file,
		  'material_id' => $row->material_id,
		  'material_name' => trim($row->material_name),
		  'material_file' => $row->material_file,
		  'material_date' =>
		  $this->ocw_utils->calc_later_date(
						    $row->material_creation_date,
						    $row->material_mod_date,
						    $download_date_format),
		  'material_cos_info' => array(),
		  );
	  $materials[$row->material_id]['course_path'] = $uploads_dir .
	    "cdir_" . $row->course_file;
	  $materials[$row->material_id]['material_path'] =
	    $materials[$row->material_id]['course_path'] .
	    "/mdir_" . $row->material_file;
	}

	// the content object information
	$co_array =  array(
			   'co_id' => $row->object_id,
			   'co_name' => trim($row->object_name),
			   'co_rec_action' => $row->object_rec_action,
			   'co_fin_action' => $row->object_fin_action,
			   'co_location' => $row->object_location,
			   'co_filename' => $row->object_file_name,
			   'co_rep_id' => $row->object_rep_id
			   );
	$co_array['co_replace'] = $this->_replace_object($co_array);
	$co_array['co_path'] =
	  $materials[$row->material_id]['material_path'] . "/odir_" .
	  $row->object_file_name;
	if ($co_array['co_replace'] === TRUE) {
	  $co_array['co_citation'] = $this->
	    _format_co_citation(trim($row->object_rep_citation),
				trim($row->object_rep_author),
				trim($row->object_rep_copyright_holder),
				trim($row->object_rep_copyright_url));
	} else {
	  $co_array['co_citation'] = $this->
	    _format_co_citation(trim($row->object_citation),
				trim($row->object_author),
				trim($row->object_copyright_holder),
				trim($row->object_copyright_url));
	}

	$materials[$row->material_id]['material_cos_info'][$row->object_id] =
	  $co_array;
      }
    }

    $this->_set_mat_manip_ops($materials);
    /* $this->ocw_utils->dump($materials); */
    /* exit(); */
    return((count($materials) > 0) ? $materials : NULL);
  }


  /**
   * Get detailed information for the content objects for a specified
   * material including the path to the object directory
   *
   * @access   public
   * @param    integer course id
   * @param    array of material ids
   * @return   array of mids with info about the cos for each material
   */
  // TODO: See if this function is even needed
  public function get_co_info($cid, $material_ids)
  {
    $mat_cos_info = array();
    foreach($material_ids as $mid) {
      $co_info = $this->coobject->coobjects($mid, '', 'Done');
      if ($co_info) {
	foreach ($co_info as $co_index => $co_details) {
	  /* TODO: see if we can get path info for all content objects
	   * in one DB query instead of a query per content object */
	  $co_info[$co_index]['co_path'] = $this->coobject->
	    object_path($cid, $mid, $co_details['id']);
	}
      } else {
	$co_info = NULL;
      }
      $mat_cos_info[$mid] = $co_info;
    }
    return $mat_cos_info;
  }


  /**
   * Return distinct material authors list
   *
   * @access  public
   * @return array authors
   * mbleed - faceted search 5/2009
   */
  public function authors_list($cid)
  {
    //get test curriculum
    /*
      $sql = "SELECT id FROM ocw_curriculums WHERE name = 'TEST'";
      $q = $this->db->query($sql);
      $res = $q->result();
      $test_curriculum_id = $res[0]->id;

      if (sizeof($materials) > 0) {
      $idlist = array();
      foreach ($materials['Materials'] as $m) $idlist[] = $m['id'];
      //$materials_csv = implode(",", $idlist);
      //$sql = "SELECT m.id, m.author, c.curriculum_id FROM ocw_materials m INNER JOIN ocw_courses c ON m.course_id = c.id WHERE m.id IN ($materials_csv) GROUP BY m.author ORDER BY m.author ASC";
      $sql = "SELECT m.id, m.author, c.curriculum_id FROM ocw_materials m INNER JOIN ocw_courses c ON m.course_id = c.id GROUP BY m.author ORDER BY m.author ASC";
      $q = $this->db->query($sql);
      if ($q->num_rows() > 0) {
      foreach ($q->result() as $row) {
      if (in_array($row->id, $idlist)) $author_array[$row->id] = $row->author;
      }
      }
      } */
    $author_array = array();
    $sql = "SELECT m.id, m.author FROM ocw_materials m WHERE m.course_id = $cid GROUP BY m.author ORDER BY m.author ASC";
    $q = $this->db->query($sql);
    if ($q->num_rows() > 0) {
      foreach ($q->result() as $row) {
	$author_array[$row->id] = $row->author;
      }
    }
    return $author_array;
  }

  /**
   * Return distinct material license list
   *
   * @access  public
   * @return array licenses
   * mbleed - faceted search 5/2009
   */
  public function licenses_list($cid)
  {
    $license_array = array(1=>'Permission',2=>'Search',3=>'Create');

    return $license_array;
  }

  /**
   * Return distinct mimetypes list that have associated materials
   *
   * @access  public
   * @return array mimetypes
   * mbleed - faceted search 5/2009
   */
  public function mimetypes_list($cid)
  {
    $mimetype_array = array();
    $sql = "SELECT ocw_mimetypes.name, ocw_mimetypes.id AS mtid
	      FROM ocw_materials
	      LEFT JOIN ocw_mimetypes
	      ON ocw_mimetypes.id = ocw_materials.mimetype_id
	      WHERE ocw_materials.course_id = $cid
	      ORDER BY ocw_mimetypes.mimetype ASC";

    $q = $this->db->query($sql);
    foreach ($q->result() as $row) {
      $mimetype_array[$row->mtid] = $row->name;
    }

    return array_unique($mimetype_array);
  }

  /**
   * Return distinct material types list
   *
   * @access  public
   * @return array mimetypes
   * mbleed - faceted search 5/2009
   */
  public function material_types_list($cid)
  {
    $mt_array = array();
    $sql = "SELECT t.name, t.id FROM ocw_tags t INNER JOIN ocw_materials m ON m.tag_id = t.id WHERE m.course_id = $cid ORDER BY t.name ASC";

    $q = $this->db->query($sql);
    foreach ($q->result() as $row) {
      $mt_array[$row->id] = $row->name;
    }

    return array_unique($mt_array);
  }

  /**
   * Return recommended actions list
   *
   * @access  public
   * @return array rec actions
   * mbleed - faceted search 5/2009
   */
  public function rec_action_list($mid)
  {
    $list_array = array();
    $sql = "SELECT id, action_type, action_taken FROM ocw_objects WHERE material_id=$mid ORDER BY action_type ASC";

    $q = $this->db->query($sql);
    foreach ($q->result() as $row) {
      if (is_null($row->action_type)) $row->action_type = 'None';
      $list_array[$row->id] = $row->action_type;
    }
    return array_unique($list_array);
  }

  /**
   * Return co types list
   *
   * @access  public
   * @return array co types
   * mbleed - faceted search 5/2009
   */
  public function co_type_list($mid)
  {
    $sql = "SELECT o.id, o.subtype_id, s.name FROM ocw_objects o INNER JOIN ocw_object_subtypes s ON s.id = o.subtype_id WHERE material_id=$mid ORDER BY s.name ASC";
    $q = $this->db->query($sql);
    $list_array = array();
    if ($q->num_rows() > 0) {
      foreach ($q->result() as $row) {
	$list_array[$row->subtype_id] = $row->name;
      }
    }
    return array_unique($list_array);
  }

  /**
   * Return replacement exists list
   *
   * @access  public
   * @return array replacement
   * mbleed - faceted search 5/2009
   */
  public function replacement_list($mid)
  {
    $list_array = array(1=>'With Replacement', 2=>'Without Replacement');
    return $list_array;
  }

  /**
   * Return co status list
   *
   * @access  public
   * @return array co status
   * mbleed - faceted search 5/2009
   */
  public function status_list($mid)
  {
    $list_array = array(1=>'No Action Assigned', 2=>'In Progress', 3=>'Cleared');
    return $list_array;
  }


  /**
   * Map recommended actions to array key names. In case of the "Retain"
   * actions, the faceted search doesn't display objects because the
   * action and the array key that represents the action are different.
   * This function provides a mapping between the action types and the
   * array key names.
   *
   * @access  public
   * @param   string action name
   * @return  string array key
   */
  public function map_recommended_action($action_name)
  {
    $action_key = "";
    switch($action_name) {
    case "Retain: Permission":
      $action_key = "retain:perm";
      break;
    case "Retain: Public Domain":
      $action_key = "retain:pd";
      break;
    case "Retain: Copyright Analysis":
      $action_key = "retain:ca";
      break;
    case "Remove and Annotate":
      $action_key = "remove";
      break;
    case "Fair Use":
      $action_key = "fairuse";
      break;
    case "None":
      $action_key = "new";
      break;
    default:
      $action_key = strtolower($action_name);
    }
    return $action_key;
  }


  /**
   * Determine if the content object should be replaced. Unclear if this
   * function should even be in the material model, but since the query
   * is already fetching the content object info, it seems silly to load
   * a class for no reason or to locate this function in the coobject
   * model.
   *
   * @access	private
   * @param	array content object info fetched by the
   *		get_material_info function.
   * @return	boolean FALSE if the object is not to be replaced
   *		TRUE if it is to be replaced.
   */
  private function _replace_object($co_info)
  {
    $rep_obj = FALSE;

    if (!empty($co_info['co_rep_id'])) {
      if ($co_info['co_fin_action'] == "Search") {
	$rep_obj = TRUE;
      } else if ($co_info['co_rec_action'] == "Search" &&
		 empty($co_info['co_fin_action'])) {
	$rep_obj = TRUE;
      } else if ($co_info['co_rec_action'] == "Search" &&
		 $co_info['co_fin_action'] == "Search") {
	$rep_obj = TRUE;
      }
    }

    return $rep_obj;
  }


  /**
   * Format the content object citation. Check the citation to see if it
   * also includes the other parameters. If not, add them to the
   * citation text.
   *
   * @access	private
   * @param	string citation string
   * @param	string author info
   * @param	string copyright holder info
   * @param	string url related to the object
   * @return	string citation with as much correct information as
   *		is present in the DB.
   *
   */
  // TODO: Should we also work with the contributor information?
  private function _format_co_citation($cit_text,
				       $author,
				       $copyright_holder,
				       $url)
  {
    /* the db has many instances of the strings below in the citation
       fields of the ocw_objects and ocw_object_replacements tables.
       Prevent those from being used as legitimate citation text.
    */
    $empty_citation_synonyms = array ("None",
				      "unk",
				      "unknown",
				      "undetermined"
				      );

    if (!empty($cit_text)) {
      foreach ($empty_citation_synonyms as $no_cite) {
	if (strcasecmp(trim($cit_text), $no_cite) == 0) {
	  $cit_text = "";
	  break;
	}
      }
      //remove any newlines in citations
      $cit_text = str_replace("\n", ", ", $cit_text);
    }
    // add any attribution info and URLs
    $cit_text = $this->_proc_cit_attrib($cit_text,
					$author,
					$copyright_holder);
    $cit_text = $this->_proc_cit_url($cit_text, $url);

    /* replace cases of two instances of comma space ", , " with single
       comma space ", ". These are caused by misformatted citations. */
    $cit_text = str_replace(", , ", ", ", $cit_text);

    return $cit_text;
  }


  /**
   * Check to see if the attribution information is already in the
   * citation. If not, add the author and copyright holder information
   * to the citation text.
   *
   * @access	private
   * @param	string citation text
   * @param	string author
   * @param	string copyright holder
   * @return	string citation text with attribution info if the
   *		citation info is not empty and if it isn't already
   *		present in the passed citation text.
   */
  private function _proc_cit_attrib($cit_text,
				    $obj_author,
				    $obj_copyright_holder)
  {
    if (!empty($obj_copyright_holder) &&
	strcasecmp($obj_author, $obj_copyright_holder != 0) &&
	(stripos($cit_text, $obj_copyright_holder) === FALSE)) {
      $cit_text = $obj_copyright_holder . ", " . $cit_text;
    }
    if (!empty($obj_author) &&
	(stripos($cit_text, $obj_author) !== FALSE)) {
      $cit_text = $obj_author . ", " . $cit_text;
    }
    return $cit_text;
  }


  /**
   * Check to see if the specified URLs are already in the citation
   * text. If not, add them in the appropriate spot. Due to the
   * absence of any regular delimiters between citation fields, the
   * placement of the URL may be suboptimal.
   *
   * @access	private
   * @param	string citation text
   * @param	string URL
   * @return	string citation text with any correctly formatted
   * 		URLs. The format check is very naive at present
   *		since the entered data is not always correctly
   *		formatted.
   */
  private function _proc_cit_url($cit_text, $url)
  {
    $valid_url_beg_patt = "[https?://]";

    if ((preg_match($valid_url_beg_patt, $url) > 0) &&
	(strpos($cit_text, $url) === FALSE)) {
      $cit_text = $cit_text . ", " . $url;
    }
    return $cit_text;
  }


  /**
   * Parse the material list and add any recomp related operations.
   * This function alters the array in place since the material list
   * may get very large for some material selections.
   *
   * The recomp operations are added as an array element of the
   * material list. If no operations are to be done, the
   * ['material_manip_ops'] element for each material is set to
   * FALSE. If any operations are to be done, the array element is
   * set to an object with the properties needed by the openoffice
   * recomp tool.
   *
   * @access	private
   * @param	array list of materials. The output of the
   *		get_material_info function.
   * @return	nothing since this function manipulates the material
   *		list in place instead of working on a copy.
   */
  private function _set_mat_manip_ops(&$material_list)
  {
    foreach ($material_list as $material) {
      $mat_loc_det =
	$this->_locate_item($material['material_path'],
			    $material['material_file']);
      // check the extensions
      if ($this->_is_mat_recompable($mat_loc_det['extension']) ===
	  FALSE) {
	$material_list[$material['material_id']]['material_manip_ops'] =
	  FALSE;
      } else {
	$material_list[$material['material_id']]['material_manip_ops']->decompFileOps =
	  array();
	// TODO: check if we need to clone the objects at all
	foreach ($material['material_cos_info'] as $co_info) {
	  $co_rep_op = NULL;
	  $co_cit_op = NULL;

	  $co_placement =
	    $this->_get_rec_place_info($co_info['co_name']);

	  if ($co_info['co_replace'] === TRUE &&
	      $co_placement !== FALSE) {
	    $co_filename = $co_info['co_filename'] . "_rep";
	    $co_loc_det =
	      $this->_locate_item($co_info['co_path'], $co_filename);
	    $material_list[$material['material_id']]['material_manip_ops']->decompFileOps[] =
	      clone $this->_def_co_rep_op($co_loc_det, $co_placement);
	  }

	  if (!empty($co_info['co_citation']) &&
	      $co_placement !== FALSE) {
	    $material_list[$material['material_id']]['material_manip_ops']->decompFileOps[] =
	      clone $this->_def_co_cit_op($co_info['co_citation'], $co_placement);
	  }
	}

	if (count($material_list[$material['material_id']]['material_manip_ops']->decompFileOps)
	    > 0) {
	  $material_list[$material['material_id']]['material_manip_ops']->inputFile =
	    $mat_loc_det['dirname'] . "/" . $mat_loc_det['basename'];
	} else {
	  $material_list[$material['material_id']]['material_manip_ops'] = FALSE;
	}
      }
    }
  }


  /**
   * Locate the specified item on the filesystem. This function
   * returns detailed information about the item location as provided
   * by the pathinfo function.
   *
   * @access	private
   * @param	string the full path to the item directory
   * @param	string the base name of the item without file
   * 		extension
   * @return	array as returned by the PHP pathinfo function
   *		(PHP 5.2.0) onwards. See http://php.net/pathinfo
   *		for further details.
   */
  private function _locate_item($full_path, $item_name)
  {
    $item_loc_det = NULL;

    $all_dir_items = scandir($full_path);
    foreach ($all_dir_items as $dir_item) {
      $full_file_path = "$full_path/$dir_item";
      if (is_file($full_file_path)) {
	$file_info = pathinfo($full_file_path);
	// pathinfo doesn't return filename for php < 5.2.0
	if (!isset($file_info['filename'])) {
	  $file_info['filename'] =
	    substr($file_info['basename'],
		   0,
		   strrpos($file_info['basename'], '.')
		   );
	}
	if ($file_info['filename'] == $item_name) {
	  $item_loc_det = $file_info;
	}
      }
    }

    return $item_loc_det;
  }


  /**
   * Check to see if the material file is in a format that can be
   * recomped by the openoffice tool. If the file matches a type
   * defined in $recompable_extensions, return TRUE, else return
   * FALSE;
   *
   * @access	private
   * @param	string file extension
   * @return	boolean TRUE if the extension matches one of the
   * 		recompable extensions and FALSE otherwise.
   */
  private function _is_mat_recompable($mat_file_ext)
  {
    $recompable_extensions = array("ppt",
				   "pptx",
				   "odp");

    return (in_array(strtolower($mat_file_ext),
		     $recompable_extensions));
  }


  /**
   * Defines the input required by the openoffice recomp tool for
   * object replacement. This function outputs an object, to set
   * up the format correctly for the eventual JSON file used by
   * the openoffice recomp tool.
   *
   * @access	private
   * @param	array pathinfo output for a content object file
   * @param	array of integers that has page number and image
   *		numbers defined. See the output returned by the
   *		_get_rec_place_info function for more details.
   * @return	object that contains the properties required for
   *		object replacement in the JSON file used by the
   *		openoffice recomp tool.
   */
  private function _def_co_rep_op($co_file_det, $co_placement)
  {
    $co_op->operation = "REPLACE";
    $co_op->repImageFile = $co_file_det['dirname'] . "/" .
      $co_file_det['basename'];
    $co_op->pageNum = $co_placement['page_num'];
    $co_op->imageNum = $co_placement['img_num'];
    return $co_op;
  }


  /**
   * Defines the input required by the openoffice recomp tool for
   * a citation. This function outputs an object, to set
   * up the format correctly for the eventual JSON file used by
   * the openoffice recomp tool.
   *
   * @access	private
   * @param	string the object citation
   * @param	array of integers that has page number and image
   *		numbers defined. See the output returned by the
   *		_get_rec_place_info function for more details.
   * @return	object that contains the properties required for
   *		citation in the JSON file used by the openoffice
   *		recomp tool.
   */
  private function _def_co_cit_op($co_citation, $co_placement)
  {
    $co_op->operation = "CITE";
    $co_op->citationText = $co_citation;
    $co_op->pageNum = $co_placement['page_num'];
    $co_op->imageNum = $co_placement['img_num'];
    return $co_op;
  }


  /**
   * Determine the page and image numbers from the original content
   * object file name. This only works for a specific file name
   * format.
   *	image-<page-num>-<image-num>.<extension>
   * The <page-num> and <image-num> values are both integers which
   * may have leading zeros. The <extension> is the file extension.
   *
   * @access	private
   * @param	string the original file name of the content object
   *		before it is replaced with sha1 hash value.
   * @return	mixed FALSE if the file name isn't in the required
   *		format. Array containing page and image numbers if
   *		file name is in the required format.
   */
  private function _get_rec_place_info($co_orig_name)
  {
    $place_info = FALSE;
    $valid_name_patt = "/image\-\d+\-\d+/";
    $loc_delimiter = "/-/";

    $name_no_ext = substr($co_orig_name,
			  0,
			  strrpos($co_orig_name, '.')
			  );
    if (preg_match($valid_name_patt, $co_orig_name) > 0) {
      $name_parts = preg_split($loc_delimiter, $name_no_ext);
      // cast the location strings to get rid of leading zeros.
      $place_info['page_num'] = (int)$name_parts[1];
      $place_info['img_num'] = (int)$name_parts[2] + 1;
    }

    return $place_info;
  }

}
?>
