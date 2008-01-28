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
	public function coobjects($mid, $oname='', $action_type='', $details='*')
	{
		$objects = array();
		$where = array('material_id' => $mid);

		$action_type = ($action_type == 'Any') ? '' : $action_type;
		if ($action_type <> '') { 
			switch ($action_type) {
				case 'Ask': $idx = 'ask'; $ans = 'yes'; break;
				case 'Done': $idx = 'done'; $ans = '1'; break;
				default: $idx = 'action_type'; $ans = $action_type;
			}
			$where[$idx] = $ans; 
		}
		$this->db->select($details)->from('objects')->where($where);
		$q = $this->db->get();

		/* HACK: dreamhost rewriting screws up the parameter values */
		$oname = preg_replace("/_/", ".", $oname);

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				if ($oname <> '') {
					if ($oname == $row['name']) {
						$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
						$row['questions'] = $this->questions($row['id'],'id,user_id,question,answer,modified_on');
						$row['log'] = $this->logs($row['id'],'user_id,log,modified_on');
						array_push($objects, $row);
					}
				} else {
					$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
					$row['questions'] = $this->questions($row['id'],'id,user_id,question,answer,modified_on');
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
				$row['log'] = $this->logs($row['id'],'user_id,log,modified_on','replacement');
				array_push($objects, $row);
			}
		} 

		return (sizeof($objects) > 0) ? $objects : null;
	}


	public function num_objects($mid)
	{
		$this->db->select("COUNT(*) AS c")->from('objects');
		$this->db->where('material_id',$mid);
		$q = $this->db->get();
		$row = $q->result_array();
		return $row[0]['c'];
	}

	public function object_stats($mid)
	{
		$stats = array();
		$stats['total'] = $this->num_objects($mid);

		$q = $this->db->query("SELECT COUNT(*) AS c FROM ocw_objects WHERE material_id=$mid AND ask='yes'");
		$row = $q->result_array();
		$stats['ask'] = $row[0]['c'];

		$q = $this->db->query("SELECT COUNT(*) AS c FROM ocw_objects WHERE material_id=$mid AND done='1'");
		$row = $q->result_array();
		$stats['cleared'] = $row[0]['c'];

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
		$data['material_id'] = $mid;
		$data['modified_by'] = $uid;
		$data['done'] = '0';
		$data['status'] = 'in progress';
		$data['name'] = $this->get_next_name($cid, $mid);

		$name = $data['name'];
		$comment = $data['comment'];
		unset($data['comment']);
		unset($data['co_request']);

		// add new object
		$this->db->insert('objects',$data);
		$oid = $this->db->insert_id();

		// add comments
		if ($comment <> '') {
			$this->add_comment($oid, 2, array('comments'=>$comment));
		}

		// add files
		if (is_array($files['userfile_0'])) {
			$fname = $files['userfile_0']['name'];
			$type = $files['userfile_0']['type'];
			$tmpname = $files['userfile_0']['tmp_name'];
			$size = $files['userfile_0']['size'];

			$path = $this->prep_path($name);

			$ext = '';
  			switch (strtolower($type))
        	{
                case 'image/gif':  $ext= '.gif'; break;
                case 'jpg':
                case 'image/jpeg': $ext= '.jpg'; break;
                case 'image/png':  $ext= '.png'; break;
                default: $ext='.png';
        	}

			// move file to new location
			move_uploaded_file($tmpname, $path.'/'.$name.'_grab'.$ext);
		}
	}

	public function prep_path($name)
	{
		list($c,$m,$o) = split("\.", $name);
		$path = property('app_uploads_path').$c;
		if (!is_dir($path)) { mkdir($path); }
		$path .= '/'.$m;
		if (!is_dir($path)) { mkdir($path); }
		$path .= '/'.$o;
		if (!is_dir($path)) { mkdir($path); }
		return $path;
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
     * @param   int	oid object ip id		
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
		// add files
		$name = "c$cid.m$mid.o$oid";

		if (is_array($files['userfile_0'])) {
			$fname = $files['userfile_0']['name'];
			$type = $files['userfile_0']['type'];
			$tmpname = $files['userfile_0']['tmp_name'];
			$size = $files['userfile_0']['size'];

			$path = $this->prep_path($name);

			$ext = '';
  			switch (strtolower($type))
        	{
                case 'image/gif':  $ext= '.gif'; break;
                case 'jpg':
                case 'image/jpeg': $ext= '.jpg'; break;
                case 'image/png':  $ext= '.png'; break;
                default: $ext='.png';
        	}

			// move file to new location
			move_uploaded_file($tmpname, $path.'/'.$name.'_rep'.$ext);
		}
	}

	/**
     * Remove an ip object
     *
     * @access  public
     * @param   int ip object id
     * @return  void
     */
	public function remove($ipid)
	{
		$data = array('id'=>$ipid);
		$this->db->delete('objects',$data);
	}

	/**
     * Remove an ip object replacement
     *
     * @access  public
     * @param   int ip object id
     * @return  void
     */
	public function remove_replacement($ipid)
	{
		$data = array('id'=>$ipid);
		$this->db->delete('object_replacements',$data);
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
					$prev = '<a href="'.site_url("materials/object_info/$cid/$mid/{$row['name']}").'">&laquo;&nbsp;Previous</a>';
				}
				if ($row['id'] == ($oid + 1)) {
					$next = '<a href="'.site_url("materials/object_info/$cid/$mid/{$row['name']}").'">Next&nbsp;&raquo;</a>';
				}
				if ($row['id'] == $oid) { $thisnum = $count; }
			}
		}
		
		$prev = ($prev=='') ? '&laquo;&nbsp;Previous' : $prev;
		$next = ($next=='') ? 'Next&nbsp;&raquo;' : $next;
		$mid = ($num > 1) ? "$thisnum of $num" : '';
		return $prev.'&nbsp;&nbsp;-&nbsp;'.$mid.'&nbsp;-&nbsp;&nbsp;'.$next; 
	}

	private function get_next_name($cid, $mid)
	{
		$name = "c$cid.m$mid.";
		$q = $this->db->query('SELECT MAX(id) + 1 AS nextid FROM ocw_objects'); 
		$row = $q->result_array();
		return $name.$row['nextid'];
	}
}
?>
