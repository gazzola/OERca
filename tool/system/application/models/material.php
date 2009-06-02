<?php
/**
  * Provides access to material information 
  *
  * @package	OCW Tool		
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
    * @param   int 		 mid 	material id	
    * @param   int 		 uid  user id
    * @param   string  type  type of status [askform]	
    * @return  boolean true if there has been a change in status
    * 	       since the user's last login
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
    * @param   int	cid course id		
    * @param   int mid material id	
    * @param   boolean	in_ocw if true only get materials in ocw 
    * @param   boolean	as_listing 
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
    * @param   int	cid course id		
    * @param   int mid material id	
    * @param   boolean	in_ocw if true only get materials in ocw 
    * @param   boolean	as_listing 
    * @return  array
    */
  public function faceted_search_materials($cid, $mid='', $in_ocw=false, $as_listing=false, $author=0, $material_type=0, $file_type=0)
  {
    $materials = array();
    $where1 = (is_numeric($cid)) ? "ocw_materials.course_id = $cid" : "ocw_materials.course_id = 0";
    $where2 = ($mid=='') ? '' : "AND ocw_materials.id='$mid'";
    if ($author > 0) {
   		$authorslist = $this->material->authors_list();
    	$where3 = " AND (";
    	$authors = explode("z", $author);
    	foreach ($authors as $a) $authorwheres[] = "ocw_materials.author = '".$authorslist[$a]."' ";
    	$where3 .= implode(" OR ", $authorwheres);
    	$where3 .= ")";
    } else $where3 = "";
   	if ($material_type > 0) {
   		$material_typeslist = $this->material->material_types_list();
    	$where4 = " AND (";
    	$material_types = explode("z", $material_type);
    	foreach ($material_types as $m) $material_typewheres[] = "ocw_tags.name = '".$material_typeslist[$m]."' ";
    	$where4 .= implode(" OR ", $material_typewheres);
    	$where4 .= ")";
    } else $where4 = "";
    $where5 = ""; //placeholder for cc license param
   	if ($file_type > 0) {
   		$file_typeslist = $this->material->mimetypes_list();
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
    * @param   int	cid course id 
    * @param   int	mid material id 
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
    * @param   int	mid material id 
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
    * @param   int	course id		
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
    * @param   int	mid material id		
    * @param   array	data
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
    $q = $this->db->query("SELECT done, action_type, 
                action_taken FROM ocw_objects WHERE material_id=$mid");
    // $this->ocw_utils->dump($where);
    foreach($q->result_array() as $row) { 
      if ($row['done']=='1') { $status['done']++; }
      else { 
          // bdr OERDEV-146: let's count number of "recommended actions that are not NULL
          if ($row['action_type'] != NULL) { 
          	$status['recaction']++; 
          }
          elseif ($row['action_taken'] != NULL) { 
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
							$this->ocw_utils->log_to_apache('debug', __FUNCTION__.": Using random name for '{$filename}'");	// XXX XXX XXX
							$digest = $this->oer_filename->random_name($filename);
					} else {
							$digest = $this->oer_filename->file_digest($filename);
					}
					$generate_own = true;
			} while ($this->material_name_exists($digest));

			return $digest;
	}
	
	
	/**
	  * Get the file path to a provided list of materials
	  *
	  * @param    int/string course id
	  * @param    array of material ids
	  * @return   array of paths to materials
	  */
	// TODO: Make this function shorter if possible
	public function get_material_info($cid, $material_ids)
	{
	  // format for constructing filename timestamps as YYYY-MM-DD-HHMMSS
    $download_date_format = "Y-m-d-His";
    // get the content object info for all material ids
    $mat_cos_info = $this->get_co_info($cid, $material_ids);
	  $materials = array();
	  $last_mat_id = $material_ids[(count($material_ids) - 1)];
	  // TODO: Change this SQL to active record queries
	  $sql = "SELECT 
	    ocw_course_files.course_id,
	    ocw_schools.name AS school_name,
	    ocw_courses.number AS course_number,
	    ocw_courses.title AS course_title, 
	    ocw_course_files.filename AS course_dir,
	    ocw_material_files.material_id,
	    ocw_materials.name AS material_name,
	    ocw_material_files.filename AS material_dir,
	    ocw_material_files.created_on AS material_creation_date,
	    ocw_material_files.modified_on AS material_mod_date
	    FROM
	    ocw_course_files,
	    ocw_material_files,
	    ocw_courses,
	    ocw_materials,
	    ocw_schools
	    WHERE
	    ocw_courses.id = ocw_course_files.course_id
	    AND
	    ocw_materials.id = ocw_material_files.material_id
	    AND
	    ocw_materials.course_id = ocw_courses.id
	    AND
	    ocw_schools.id = ocw_courses.school_id
	    AND
	    ocw_courses.id = $cid AND ( ";
	    
	  /* construct the last 'WHERE' clause in the query
     * from the list of passed material_ids
     * the loop add all but the last material id and
     * the line after the loop does the rest
     */
    for ($i=0; $i < (count($material_ids) - 1); $i++) { 
      $sql .= "ocw_materials.id = $material_ids[$i] OR ";
    }
	    
	  $sql .= "ocw_materials.id = $last_mat_id )";
	    
	  $q = $this->db->query($sql);
	    
	  if ($q->num_rows() > 0) {
	    foreach ($q->result() as $row) {
	      $materials[] = array(
	        'course_id' => $row->course_id,
	        'school_name' => $row->school_name,
	        'course_number' => $row->course_number,
	        'course_title' => $row->course_title,
	        'course_dir' => $row->course_dir,
	        'material_id' => $row->material_id,
	        'material_name' => $row->material_name,
	        'material_dir' => $row->material_dir,
	        'material_date' => $this->ocw_utils->calc_later_date(
	          $row->material_creation_date, 
	          $row->material_mod_date, 
	          $download_date_format),
	        'material_cos_info' => $mat_cos_info[$row->material_id],
	        );
	    }
	  }
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
	public function get_co_info($cid, $material_ids) {
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
	public function authors_list($materials)
	{
		//get test curriculum
		$sql = "SELECT id FROM ocw_curriculums WHERE name = 'TEST'";
	    $q = $this->db->query($sql);
		$res = $q->result();
		$test_curriculum_id = $res[0]->id;
		$idlist = array();
		foreach ($materials['Materials'] as $m) $idlist[] = $m['id'];
		$materials_csv = implode(",", $idlist);
	    $sql = "SELECT m.id, m.author, c.curriculum_id FROM ocw_materials m INNER JOIN ocw_courses c ON m.course_id = c.id WHERE m.id IN ($materials_csv) GROUP BY m.author ORDER BY m.author ASC";
	    $q = $this->db->query($sql);
	  	if ($q->num_rows() > 0) {
	  		foreach ($q->result() as $row) {
	  			if ($row->curriculum_id != $test_curriculum_id) $author_array[$row->id] = $row->author; //only allow author name if not part of test curriculum
	  		}
	  	}
	  	return array_unique($author_array);
	}
	
		/**
   * Return distinct material license list 
   *
   * @access  public
   * @return array licenses
   * mbleed - faceted search 5/2009
   */
	public function licenses_list()
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
	public function mimetypes_list($materials)
	{
		$idlist = array();
		foreach ($materials['Materials'] as $m) $idlist[] = $m['id'];
		$materials_csv = implode(",", $idlist);

		$sql = "SELECT ocw_mimetypes.name, ocw_mimetypes.id AS mtid
	      FROM ocw_materials
	      LEFT JOIN ocw_mimetypes 
	      ON ocw_mimetypes.id = ocw_materials.mimetype_id
	      WHERE ocw_materials.id IN ($materials_csv)
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
	public function material_types_list($materials)
	{
		$idlist = array();
		foreach ($materials['Materials'] as $m) $idlist[] = $m['id'];
		$materials_csv = implode(",", $idlist);
		
		$sql = "SELECT t.name, t.id FROM ocw_tags t INNER JOIN ocw_materials m ON m.tag_id = t.id WHERE m.id IN ($materials_csv) ORDER BY t.name ASC";
	    $q = $this->db->query($sql);
	  	foreach ($q->result() as $row) {
	    	$mt_array[$row->id] = $row->name;
	  	}
		
	  	return array_unique($mt_array);
	}
}
?>