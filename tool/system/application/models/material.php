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
  }


  /**
    * add material based on information given
    * 
    */
  public function add_material ($details)
  {
    $query=$this->db->insert('materials',$details);
    $this->db->select('id');
    $where = "course_id='".$details['course_id']."' AND name='".$details['name']."' AND in_ocw='1'";
    $this->db->from('materials')->where($where);
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
  public function materials($cid, $mid='', $in_ocw=false, $as_listing=false)
  {
    $materials = array();
    $where = ($mid=='') ? '' : "AND ocw_materials.id='$mid'";

    $sql = "SELECT ocw_materials.*, ocw_mimetypes.mimetype 
      FROM ocw_materials
      LEFT JOIN ocw_mimetypes 
      ON ocw_mimetypes.id = ocw_materials.mimetype_id
      WHERE ocw_materials.course_id = $cid $where
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

        if (sizeof($children) > 0) {
          $cm['show'] = ($this->child_not_in_ocw($children))?1:0;
          $cm['childitems'] = $children;
        }
      }
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
    $status = array('done'=>0,'notdone'=>0);

    if ($has_ip==0) return $status;

    $where = array('material_id'=>$mid);
    $this->db->select('done')->from('objects')->where($where);
    $q = $this->db->get();

    foreach($q->result_array() as $row) { 
      if ($row['done']=='1') { $status['done']++; }
      else { $status['notdone']++; }
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


  /** these functions possibly duplicate functionality to get counts of 
    * content objects 
    * TODO: possibly get rid of the functions if there is a better way
    * to do this
    */
  public function get_co_count($cid, $isAsk = NULL, $isDone = '0')
  {
    $this->db->from('objects');
    $this->db->
      join('materials', 'materials.id = objects.material_id', 'inner')->
      join('courses', 'courses.id = materials.course_id', 'inner');

    $passedParams = array('ocw_courses.id' => $cid);

    if ($isAsk == 'yes') {
      $passedParams['ocw_objects.ask'] = $isAsk;
      $passedParams['ocw_objects.done'] = $isDone;
    } elseif ($isAsk == 'no') {
      $passedParams['ocw_objects.ask'] = $isAsk;
      $passedParams['ocw_objects.done'] = $isDone; 
    } elseif ($isDone == '1') {
      $passedParams['ocw_objects.done'] = $isDone;
    }

    $this->db->where($passedParams);

    $q = $this->db->get();
    
    //return the number of results
    return($q->num_rows());

  }

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
	 * Add material functionality from add form
	 * may include zip files. This will go away
	 * when ctools import comes on line
	 */
	public function manually_add_materials($cid, $type, $details, $files)
	{
		if ($details['collaborators']=='') { unset($details['collaborators']);}
		if ($details['ctools_url']=='') { unset($details['ctools_url']); }
		$details['course_id'] = $cid;
		$details['created_on'] = date('Y-m-d h:i:s');
	
		// add new material
		$idx = ($type=='bulk') ? 'zip_userfile' : 'single_userfile';
		
		if ($type=='single') {
				$details['name'] = $files[$idx]['name'];
				$details['`order`'] = $this->get_nextorder_pos($cid);
				$this->db->insert('materials',$details);
				$mid = $this->db->insert_id();
				$this->upload_materials($cid, $mid, $files[$idx]);
		} else {
					// handle zip files
				if ($files[$idx]['error']==0) {
		        $zipfile = $files[$idx]['tmp_name'];
		        $files = $this->ocw_utils->unzip($zipfile, property('app_mat_upload_path')); 
		    		if ($files !== false) {
		            foreach($files as $newfile) {
									if (is_file($newfile) && !preg_match('/^\./',basename($newfile))) {
											preg_match('/(\.\w+)$/',$newfile,$match);
											$details['name'] = basename($newfile,$match[1]);
											$details['`order`'] = $this->get_nextorder_pos($cid);
											$this->db->insert('materials',$details);
											$mid = $this->db->insert_id();
                     	$filedata = array();
											$filedata['name'] = $newfile;
                      $filedata['tmp_name'] = $newfile;
											$this->upload_materials($cid, $mid, $filedata);
									}
								}
		        }
		    } else {
					return('Cannot upload file: an error occurred while uploading file. Please contact administrator.');
		    }
		}
		
		return true;
	}
	
	/** 
	 * upload materials to correct path
	 * TODO: version checking
	 */
	public function upload_materials($cid, $mid, $file)
	{
		$tmpname = $file['tmp_name'];
		$path = $this->prep_path($cid,$mid);
		
		preg_match('/\.(\w+)$/',$file['name'],$match);
		$ext = $match[1];
		
		// move file to new location
		$name = "c$cid.m$mid";
		if (is_uploaded_file($tmpname)) {
				move_uploaded_file($tmpname, $path.'/'.$name.'.version_1.'.$ext);
		} else {
				copy($tmpname, $path.'/'.$name.'.version_1.'.$ext);
				unlink($tmpname);
		}
	}
	
	public function prep_path($cid, $mid)
	{
		$path = property('app_uploads_path').'c'.$cid;
		if (!is_dir($path)) { mkdir($path); chmod($path, 0777); }
		$path .= '/m'.$mid;
		if (!is_dir($path)) { mkdir($path); chmod($path, 0777); }
		return $path;
	}
	
	private function get_nextorder_pos($cid)
	{
		$q = $this->db->query("SELECT MAX(`order`) + 1 AS nextpos FROM ocw_materials WHERE course_id=$cid"); 
		$row = $q->result_array();
		return $row[0]['nextpos'];
	}
}
?>
