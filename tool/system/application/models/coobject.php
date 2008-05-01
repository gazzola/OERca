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

		} else {
			if ($action_type <> '') { 
				switch ($action_type) {
					case 'Ask': $idx = 'ask'; $ans = 'yes'; break;
					case 'Done': $idx = 'done'; $ans = '1'; break;
					default: $idx = 'action_type'; $ans = $action_type;
				}
				$where .= " AND $idx='$ans'";
			}
		}

		$this->db->select($details)->from('objects')->where($where);
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
					if ($oid <> '') {
							if ($oid == $row['id']) {
									$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
									$row['questions'] = $this->questions($row['id'],'id,user_id,question,answer,modified_on');
									$row['copyright'] = $this->copyright($row['id']);
									$row['log'] = $this->logs($row['id'],'user_id,log,modified_on');
									array_push($objects, $row);
							}
					} else {
							$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
							$row['questions'] = $this->questions($row['id'],'id,user_id,question,answer,modified_on');
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
				case 'Ask': $idx = 'ask'; $ans = 'yes'; break;
				default: $idx = 'action_type'; $ans = $action_type;
			}
			$where[$idx] = $ans; 
		}
		$this->db->select($details)->from('object_replacements')->where($where);
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on','replacement');
				$row['questions'] = $this->questions($row['id'],'id,user_id,question,answer,modified_on','replacement');
				$row['copyright'] = $this->copyright($row['id'],'*','replacement');
				$row['log'] = $this->logs($row['id'],'user_id,log,modified_on','replacement');
				array_push($objects, $row);
			}
		} 

		return (sizeof($objects) > 0) ? $objects : null;
	}


	public function num_objects($mid,	$action_type='')
	{
		$action_type = ($action_type == 'Any') ? '' : $action_type;
		
		if ($action_type == 'AskRCO') {
				$table = 'object_replacements';
				$where['Ask'] = 'yes'; 
		} else {
				if ($action_type <> '') { 
						switch ($action_type) {
								case 'Ask': $idx = 'ask'; $ans = 'yes'; break;
								case 'Done': $idx = 'done'; $ans = '1'; break;
								default: $idx = 'action_type'; $ans = $action_type;
						}
						$where[$idx] = $ans; 
				}
				$table = 'objects';
		}		

		$where['material_id'] = $mid;				
		$this->db->select("COUNT(*) AS c")->from($table)->where($where);		
		$q = $this->db->get();
		$row = $q->result_array();
		return $row[0]['c'];
	}

	public function object_stats($mid)
	{
		$stats = array();
		$stats['total'] = $this->num_objects($mid);
		$stats['ask'] = 0;
		$stats['cleared'] = 0;
		
		$q = $this->db->query("SELECT COUNT(*) AS c FROM ocw_objects WHERE material_id=$mid AND ask='yes'");
		$row = $q->result_array();
		$stats['ask'] = $row[0]['c'];
		
		$q = $this->db->query("SELECT COUNT(*) AS c FROM ocw_object_replacements WHERE material_id=$mid AND ask='yes'");
		$row = $q->result_array();
		$stats['ask'] += $row[0]['c'];

		$q = $this->db->query("SELECT COUNT(*) AS c FROM ocw_objects WHERE material_id=$mid AND done='1'");
		$row = $q->result_array();
		$stats['cleared'] = $row[0]['c'];
		
		$q = $this->db->query("SELECT COUNT(*) AS c FROM ocw_object_replacements WHERE material_id=$mid AND ask_status='done'");
		$row = $q->result_array();
		$stats['cleared'] += $row[0]['c'];

		$q = $this->db->query("SELECT action_type, COUNT(*) AS c FROM ocw_objects WHERE material_id=$mid GROUP BY action_type");
		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) { $stats[$row['action_type']] = $row['c']; }
		} 
	
		return $stats;
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
	public function questions($oid, $details='*', $type='original')
	{
		$questions = array();
		$table = ($type == 'original') ? 'object_questions' : 'object_replacement_questions';
		$this->db->select($details)->from($table)->where('object_id',$oid)->orderby('modified_on DESC');
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				array_push($questions, $row);
			}
		} 

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
	  $this->db->update($table,$data,"id=$qid AND object_id=$oid");
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
                default: $ext='.png';
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
    if (is_array($files['userfile']) and $files['userfile']['error']==0) {
        $zipfile = $files['userfile']['tmp_name'];
        $files = $this->ocw_utils->unzip($zipfile, property('app_co_upload_path')); 
				$replace_info = $orig_info = array();

        if ($files !== false) {
            foreach($files as $newfile) {
		
									if (preg_match('/Slide\d+|\-pres\.\d+/',$newfile)) { // find slides

											$this->add_slide($cid,$mid,$newfile);

									} else {
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

											if (!preg_match('/^\./',basename($newfile))) {
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
									}
						}		
				} else {
				     // zip file did not contain any jpg files
				}
       
    } else {
			exit('Cannot upload file: an error occurred while uploading file. Please contact administrator.');
    }
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
		$this->db->update('objects',$data,"id=$oid");
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
	public function update_rep_image($cid, $mid, $oid, $files)
	{
		// check for slides and get any data embedded in the file
		$data = array();
		if (is_array($files['userfile_0'])) {
				$filename = $files['userfile_0']['name'];
				$tmpname = $files['userfile_0']['tmp_name'];
				$data['name'] = $filename;
				$data = $this->prep_data($cid, $mid, array(), $filename, $tmpname);
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

	public function prev_next($cid, $mid, $oid)
	{
		$next = '';
		$prev = '';

		$this->db->select('id, name')->from('objects')->where('material_id',$mid)->orderby('id');
		$q = $this->db->get();
		$num = $q->num_rows();
		$thisnum = $count = 0;
	
		if ($num > 0) {
			foreach($q->result_array() as $row) {
				$count++;
				if ($row['id'] == ($oid - 1)) {
					$prev = '<a href="'.site_url("materials/object_info/$cid/$mid/{$row['id']}").'">&laquo;&nbsp;Previous</a>';
				}
				if ($row['id'] == ($oid + 1)) {
					$next = '<a href="'.site_url("materials/object_info/$cid/$mid/{$row['id']}").'">Next&nbsp;&raquo;</a>';
				}
				if ($row['id'] == $oid) { $thisnum = $count; }
			}
		}
		
		$prev = ($prev=='') ? '&laquo;&nbsp;Previous' : $prev;
		$next = ($next=='') ? 'Next&nbsp;&raquo;' : $next;
		$mid = ($num > 1) ? "$thisnum of $num" : '';
		return $prev.'&nbsp;&nbsp;-&nbsp;'.$mid.'&nbsp;-&nbsp;&nbsp;'.$next; 
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
					$this->add_slide($cid,$mid,$pathtofile);
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
	public function add_slide($cid, $mid, $slidefile)
	{
			preg_match('/\.(\w+)$/', $slidefile, $matches);
			$ext = $matches[1];

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
					$newpath = $newpath."/{$this->material_filename($mid)}_slide_$loc.$ext";
					@copy($slidefile, $newpath); 
					@chmod($newpath,'0777');
					@unlink($slidefile);
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
}
?>
