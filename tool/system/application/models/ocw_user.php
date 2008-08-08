<?php
/**
  * Provides access to instructor and dScribe information 
  *
  * @package	OCW Tool		
  * @author David Hutchful <dkhutch@umich.edu>
  * @date 1 September 2007
  * @copyright Copyright (c) 2006, University of Michigan
  */

class OCW_user extends Model 
{
  public function __construct()
  {
    parent::Model();
  }

	/**
    * Add a new user 
    *
    * @access  public
    * @param   array details
    * @param   array file  profile image
    * @return  string
    */
	public function add_user($details, $file='')
	{
    if ($details['name']=='') { return "Please specify name."; }
    if ($details['email']=='') { return "Please specify an email address."; }
    if ($details['role']=='') { return "Please specify a user role."; }
    if ($details['user_name']=='') { return "Please specify a username."; }

		// make sure user does not exist
		$exists = $this->exists($details['email']);
    if ($exists || $this->existsByUserName($details['user_name'])) {
				return ($exists) ?	"A user with this email already exists. Please pick a new one."
             						 :	"A user with this username already exists. Please pick a new one.";
	
		// add user
    } else {
				$profile = (isset($details['profile'])) ? $details['profile'] : false;
        unset($details['submit']); unset($details['profile']); 
				$details['password'] = $this->freakauth_light->_encode($details['email']);
				$this->db->insert('users', $details);
				$uid = $this->db->insert_id();	
				if ($profile !== false) { 
						$res = $this->add_profile($uid, $profile, $file); 
						if ($res!==true) { return "Added user, but could not add profile:<br/><br/>$res"; }
				}
    }
    return true;
	}
	
	/**
    * Add a new user profile 
    *
    * @access  public
    * @param   int   uid user id
    * @param   array details
    * @param   array file -- profile image
    * @return  string
    */
	public function add_profile($uid, $details, $files='')
	{
    if ($details['title']=='') { return "Please specify the user's title."; }
    if ($details['info']=='') { return "Please specify the user's information."; }


		// make sure user does not exist
		if ($this->profile_exists($uid)) {
				$this->update_profile($uid, $details, $files);
	
		// add profile
    } else {
				if ($files <> '' && is_array($files) && $files['profile']['error']==0) { 
						$tmpname  = $files['profile']['tmp_name'];
            $fp = fopen($tmpname, 'r');
            $content = fread($fp, filesize($tmpname));
            $content = addslashes($content);
            fclose($fp);
						$details['imagefile'] = $content;
						$details['imagetype'] = $files['profile']['type'];
				} 
				$details['user_id'] = $uid;
				$this->db->insert('user_profiles', $details);
    }

    return true;
	}

	/**
     * Update user 
     *
     * @access  public
		 * @param   int id user id
     * @param   array details
     * @param   array file profile picture
     * @return  string
     */
	public function update_user($uid, $details, $file='')
	{
		if (isset($details['profile'])) {
				$profile = $details['profile'];
				unset($details['profile']);
				$this->update_profile($uid, $profile,$file);
		}
		$this->db->update('users',$details,"id=$uid");
		return true;
	}
	
	/**
     * Update user profile
     *
     * @access  public
		 * @param   int id user id
     * @param   array details
     * @param   array files profile image
     * @return  string
     */
	public function update_profile($uid, $details, $files='')
	{
		if ($files <> '' && is_array($files) && $files['profile']['error']==0) { 
						$tmpname  = $files['profile']['tmp_name'];
            $fp = fopen($tmpname, 'r');
            $content = fread($fp, filesize($tmpname));
            $content = addslashes($content);
            fclose($fp);
						$details['imagefile'] = $content;
						$details['imagetype'] = $files['profile']['type'];
		} 
		$details['user_id'] = $uid;
		$this->db->update('user_profiles',$details, "user_id=$uid");
	
		return true;
	}
	
	/**
    * Remove a user 
    *
    * @access  public
    * @param   int uid user id
    * @return  boolean
    */
	public function remove_user($uid)
	{
		// make sure user does not exist
		$u = $this->get_user_by_id($uid);

		if ($u['id'] == getUserProperty('id')) {
				return "You cannot delete yourself while you're logged in.";
		}

		if ($u !== false) {
				// remove profile first
				$this->remove_profile($uid);
	
				// remove association with any courses / dscribes / instructors	
				switch($u['role']) {
						case 'dscribe1':
									$this->db->delete('acl',array('user_id'=>$uid,'role'=>'dscribe1'));
									$this->db->delete('dscribe2_dscribe1',array('dscribe1_id'=>$uid));
									break;		
						case 'dscribe2':
									$this->db->delete('acl',array('user_id'=>$uid,'role'=>'dscribe2'));
									$this->db->delete('dscribe2_dscribe1',array('dscribe2_id'=>$uid));
									break;		
						case 'instructor':
									$this->db->delete('acl',array('user_id'=>$uid,'role'=>'instructor'));
									break;		
						default:
				}

				// remove user
				$this->db->delete('users',array('id'=>$uid));

		} else {
			return "This user does not exist";
		} 

		return true;
	}


	/**
    * Remove a user profile 
    *
    * @access  public
    * @param   int uid user id
    * @return  boolean
    */
	public function remove_profile($uid)
	{
		if ($this->profile_exists($uid) !== false) {
				$this->db->delete('user_profiles',array('user_id'=>$uid));
		}
		return true;
	}

  /**
   * Retrieves all records and all fields (or those passed in the $fields string)
   * from the table user. It is possible (optional) to pass the wonted fields, 
   * the query limit, and the query WHERE clause.
   *
   * @param string of fields wanted $fields
   * @param array $limit
   * @param string $where
   * @return query string
   */
	function getUsers($fields=null, $limit=null, $where=null)
	{	
    $users = array();

		($fields != null) ? $this->db->select($fields) :'';
		
    $this->db->from('users');
		
		($where != null) ? $this->db->where($where) :'';
		
		($limit != null ? $this->db->limit($limit['start'], $limit['end']) : '');
        
		$q = $this->db->get();

    if ($q->num_rows() > 0) {
      foreach($q->result_array() as $row) { $users[] = $row; }
    }

    return (sizeof($users) > 0) ? $users : null;
	}

	/**
    * Get user info based on username 
    *
    * @access  public
    * @param   string	username 
    * @return  string | boolean
    */
  public function get_user($uname)
  {
    $where = array('user_name'=>$uname);
    $query = $this->db->getwhere('users', $where); 
    return ($query->num_rows() > 0) ? $query->row_array() : false;
  }

  /**
    * Get user info based on id 
    *
    * @access  public
    * @param   int	id 
    * @return  string | boolean
    */
  public function get_user_by_id($uid)
  {
    $where = array('id'=>$uid);
    $query = $this->db->getwhere('users', $where); 
    return ($query->num_rows() > 0) ? $query->row_array() : false;
  }
  

 	/**
	  * Get individuals with the specified relationship to the given uid 
	  *
	  * @access   public
	  * @param    int 		uid		id of known user 
	  * @param    string 	type	type of relationship (instructor, dscribe1, dscribe2)	
	  * @param    int 		cid 	optional course id	
	  *
	  * @return   array containing the information of the related users 
	  */
	public function get_users_by_relationship($uid, $type, $cid='')
	{
	  $users = array();
		$urole = getUserPropertyFromId($uid, 'role');

		if ($urole=='') {
				return 'Error: Cannot determine user\'s role';

		} elseif($urole==$type) {
				$table = 'users'; 
				$field = 'id AS uid'; 
				$where = array('role'=>$type);

		} elseif ($urole=='dscribe1') {
				$table = ($type=='instructor') ? 'acl' : 'dscribe2_dscribe1';
				$field = ($type=='instructor') ? 'user_id AS uid' : 'dscribe2_id AS uid';
				$where = ($type=='instructor') ? array('course_id'=>$cid, 'role'=>'instructor')
																			 : array('dscribe1_id'=>$uid);

		} elseif ($urole=='dscribe2') {
				// presently, dscribe2 have no formal relationships with instructors
				if ($type=='instructor' && $cid=='') { return 'Error: cannot define a relationship between instructor and dscribe2'; }

				$field = ($type=='instructor' && $cid<>'') ? 'user_id as uid' : 'dscribe1_id AS uid';
				$table = ($type=='instructor' && $cid<>'') ? 'acl' : 'dscribe2_dscribe1';
				$where = ($type=='instructor' && $cid<>'') ? array('course_id'=>$cid,'role'=>'instructor')
																									 : array('dscribe2_id'=>$uid);

		} elseif ($urole=='instructor') {
				// presently, dscribe2 have no formal relationships with instructors
				if ($type=='dscribe2') { return 'Error: cannot define a relationship between instructor and dscribe2'; }

				$table = 'acl';
				$field = 'user_id AS uid';
				$where =  array('course_id'=>$cid, 'role'=>'dscribe1');

		} else {
				return 'Error: the current user\'s role is not recognized.';
		}

		$this->db->select($field)->from($table)->where($where);
		$q = $this->db->get();
 
		if ($q->num_rows() > 0) { foreach ($q->result() as $row) { array_push($users, $row->uid); } }

	  // only return the array if we get results, else return false
	  return (sizeof($users) > 0) ? $users : false;
	}

  /**
    * get user profile 
    *
    * @access  public
    * @param   int	usid 
    * @return  array profile of user
    */
  public function profile($uid)
  {
		if ($uid==null || $uid=='') {
				return false;
		} else {
    	$where = array('user_id'=>$uid);
    	$this->db->select('*')->from('user_profiles')->where($where); 
    	$query = $this->db->get();
    	$u = $query->row_array();
    	return ($query->num_rows() > 0) ? $u : null;
		}
  }

  /**
    * get user name 
    *
    * @access  public
    * @param   int	usid 
    * @return  string username 
    */
  public function username($uid)
  {
		if ($uid==null || $uid=='') {
				return false;
		} else {
    	$where = array('id'=>$uid);
    	$this->db->select('user_name')->from('users')->where($where); 
    	$query = $this->db->get();
    	$u = $query->row_array();
    	return ($query->num_rows() > 0) ? $u['user_name'] : false;
		}
  }

  /** bdr - silly fix for displaying long user_name on questions form 
    * system/application/views/default/content/materials/co/_edit_orig_status.php
    */
  public function goofyname($uid)
  {
                if ($uid==null || $uid=='') {
                                return false;
                } else {
        $where = array('id'=>$uid);
        $this->db->select('name')->from('users')->where($where);
        $query = $this->db->get();
        $u = $query->row_array();
        return ($query->num_rows() > 0) ? $u['name'] : false;
                }
  }

	/**
     * Check to see if a user already exists 
     *
     * @access  public
     * @param   string	email 
     * @return  string | boolean
     */
	public function exists($email)
	{
		$where = array('email'=>$email);
		$query = $this->db->getwhere('users', $where); 
		return ($query->num_rows() > 0) ? $query->row_array() : false;
	}
	
	/**
     * Check to see if a user profile already exists 
     *
     * @access  public
     * @param   int	user id 
     * @return  string | boolean
     */
	public function profile_exists($uid)
	{
		$where = array('user_id'=>$uid);
		$query = $this->db->getwhere('user_profiles', $where); 
		return ($query->num_rows() > 0) ? $query->row_array() : false;
	}
	
	/**
     * Check to see if a user already exists by uniqname
     *
     * @access  public
     * @param   string user_name 
     * @return  string | boolean
     */
	public function existsByUserName($user_name)
	{
		$where = array('user_name'=>$user_name);
		$query = $this->db->getwhere('users', $where);
		return ($query->num_rows() > 0) ? $query->row_array() : false;
	}

  /**
    * Check to see if a user has a particular role 
    *
    * @access  public
    * @param   int	uid user id		
    * @param   int	cid course id		
    * @param   string	role (instructor|dscribe1|dscribe2)		
    * @return  boolean
    */
  public function has_role($uid, $cid, $role)
  {
    $where = array('user_id'=>$uid,'course_id'=>$cid,'role'=>$role);
    $query = $this->db->getwhere('acl', $where); 
    return ($query->num_rows() > 0) ? true : false;
  }
	

	// ----- Courses related functions -----
	
  /**
    * Get a user's courses 
    *
    * @access  public
    * @param   int user id
    * @param (optional) string role
    * @return  array
    */
  public function get_courses($uid, $role = NULL)
  {
    $courses = array();
    
    $sql = "SELECT ocw_courses.*, 
      ocw_curriculums.name AS cname, 
      ocw_schools.name AS sname
      FROM ocw_courses, ocw_curriculums, ocw_schools, ocw_acl
      WHERE ocw_curriculums.id = ocw_courses.curriculum_id
      AND ocw_schools.id = ocw_curriculums.school_id
      AND ocw_acl.course_id = ocw_courses.id
      AND ocw_acl.user_id = '$uid'
      ORDER BY start_date DESC";
      
    $q = $this->db->query($sql);

    if ($q->num_rows() > 0) {
      foreach($q->result_array() as $row) { 
        $courses[$row['sname']][$row['cname']][] = $row; 
      }
    }
    
    // get the courses that have NULL curriculum ids
    $sql_no_curr_id = "SELECT ocw_courses.*,
      ocw_courses.curriculum_id AS cname,
      ocw_courses.curriculum_id AS sname
      FROM ocw_courses, ocw_acl
      WHERE ocw_courses.curriculum_id IS NULL
      AND ocw_acl.course_id = ocw_courses.id
      AND ocw_acl.user_id = '$uid'
      ORDER BY ocw_courses.start_date DESC";
    
    $q_no_curr_id = $this->db->query($sql_no_curr_id);
    
    if ($q_no_curr_id->num_rows() > 0) {
      foreach ($q_no_curr_id->result_array() as $row) {
        $courses['No School Specified']['No Curriculum Specified'][]
         = $row;
      }
    }
    
    return (sizeof($courses) > 0) ? $courses : null;
  }

  /** 
    * Get a the courses for a user identified by their user_id (uid).
    * A simplified version of get_courses() above, which returns fewer
    * values.
    *
    * @param    int uid the numerical user id for a user
    * @return   mixed array with each element containing
    *           an array with keys:
    *           'id' = course id
    *           'number' = course number
    *           'title' = title of the course
    * 
    * again possibly duplicated functionality to get a very simple list of
    * a user's courses
    * TODO: get rid of this function if it is more efficient to use the
    * get_courses function 
    * TODO: get school name and subject code, figure out sql to provide
    * results for school name and subject code as NULL if the values are
    * not defined in the DB tables
    */
  public function get_courses_simple($uid)
  {
    $courses = array();
    
    $this->db->from('courses');
    $this->db->
      join('acl', 'acl.course_id = courses.id', 'inner')->
      join('users', 'users.id = acl.user_id', 'inner');
    $this->db->where('ocw_users.id', $uid);
    $this->db->select('ocw_courses.id, ocw_courses.number, 
      ocw_courses.title');
    $q = $this->db->get();
    if ($q->num_rows() > 0) {
      foreach ($q->result() as $row) {
        $courses[] = array(
          'id' => $row->id,
          'number' => $row->number, 
          'title' => $row->title
          );
      }
    }
    return((sizeof($courses) > 0) ? $courses : NULL);
  }
  
 
	// ----- dscribe related functions -----

  /**
    * (un)Assign a dscribe 1 to a dscribe 2 
    *
    * @access  public
    * @param   int	d1_uid dscribe1 user id		
    * @param   int	d2_uid dscribe2 user id		
    * @param   string	action what to do 
    * @return  boolean
    */
  public function set_relationship($d1_uid, $d2_uid, $action='assign')
  {
			$d = array('dscribe2_id'=>$d2_uid, 'dscribe1_id'=>$d1_uid);
		if ($action=='assign') {
				$this->db->insert('dscribe2_dscribe1',$d);
		} else {
				$this->db->delete('dscribe2_dscribe1',$d);
		}
		return true;
  }

  /**
    * Check to see if a user is a dscribe 
    *
    * @access  public
    * @param   int	uid user id		
    * @param   int	cid course id		
    * @return  boolean
    */
  public function is_dscribe($uid, $cid)
  {
    $this->db->where('user_id="'.$uid.'" AND course_id="'.$cid.
      '" AND role LIKE "dscribe%"');
    $query = $this->db->get('acl'); 
    return ($query->num_rows() > 0) ? true : false;
  }

	
  /**
    * Get dscribes for a course 
    *
    * @access  public
    * @param   int course id
    * @return  array
    */
  public function dscribes($cid)
  {
    $dscribes = array();

    $this->db->select('ocw_users.*');
    $this->db->from('acl')->where("course_id='$cid' AND ocw_acl.role = 'dscribe1'");
    $this->db->join('users','acl.user_id=users.id');

    $q = $this->db->get();

    if ($q->num_rows() > 0) {
      foreach($q->result_array() as $row) { $dscribes[] = $row; }
    }

    return (sizeof($dscribes) > 0) ? $dscribes : null;
  }

  /**
    * Add a dScribe to a course 
    *
    * @access  public
    * @param   mixed	data 
    * @return  string | boolean
    */
  public function add_dscribe($cid, $data)
  {
		if ($data['name']=='') { return "Please specify name."; }
		if ($data['email']=='') { return "Please specify an email address."; }
		if ($data['user_name']=='') { return "Please specify a username."; }

		if (($u = $this->get_user($data['user_name'])) !== false) { 
				// user exists just add them to the course
				$d = array('user_id'=>$u['id'], 'course_id'=>$cid, 'role'=>$data['role']);
				$this->db->insert('acl',$d);
		} else {
				# try to add user first
				if ($this->exists($data['email'])) { 
						return "A user with this email already exists. Please pick a new one."; }

				# prep new user data
				unset($data['task']); unset($data['add_dscribe']);

				if ($this->add_user($data)) {
						$u = $this->get_user($data['user_name']);	
						if ($u) {	
							$d = array('user_id'=>$u['id'], 'course_id'=>$cid, 'role'=>$data['role']);
							$this->db->insert('acl',$d);
						}
				} else {
						return "Could not add new user. Please contact administrator";
				}
		}
		return true;
  }

  /**
    * remove a dScribe from a course 
    *
    * @access  public
    * @param   int	course id 
    * @param   int	dscribe id 
    * @return  string | boolean
    */
  public function remove_dscribe($cid, $did)
  {
		$d = array('user_id'=>$did, 'course_id'=>$cid, 'role'=>'dscribe1');
		$this->db->delete('acl',$d);
		return true;
	}

	// ----- instructor related functions -----
 
	/**
   * get the current instructor/creator values for a specified instructor id
   *
   * @access  public
   * @param   int instructor id
   * @return  array that that contains the current instructor details
   */
  public function instructor($inst_id, $details='*')
  {
		$u = $this->get_user_by_id($inst_id);
		if ($u !== false) {
				$u['profile'] = $this->profile($inst_id);
		}
    return ($u!==false) ? $u : null;
  }
	
	/**
   * Update instructor info
   *
   * @access  public
   * @param   int instructor id
   * @param   array containing the values to be inserted into the table
   * @return  void
   */
  public function update_instructor($inst_id, $data)
  {
    $this->update_profile($inst_id, $data);
  }
}
?>
