<?php
/**
 * Provides access to course information 
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Course extends Model 
{
	public function __construct()
	{
		parent::Model();
                $this->load->model('material');
	}
	
	/**
   * add course 
	 *
	 * @access  public
	 * @return  array
	 */
   public function new_course($details)
   {
			$this->db->insert('courses', $details);
			$this->db->select('*')->from('courses')->where($details);
			$q = $this->db->get();
			$course = $q->row_array();
			$curr_mysql_time = $this->ocw_utils->get_curr_mysql_time();
			if ($q->num_rows() == 1) {
					$filename = $this->generate_course_name($course['title'].$course['start_date'].
																								 $course['end_date']);
					$dirname = property('app_uploads_path') . 'cdir_' . $filename; 
					$this->oer_filename->mkdir($dirname);
					$this->db->insert('course_files',
													array('filename'=>$filename,
													      'modified_on'=>$curr_mysql_time,
														    'created_on'=>$curr_mysql_time,
														    'course_id'=>$course['id']));
			}

			return ($q->num_rows() == 1) ? $course : null;
	}

	/**
   * remove a course and all the associated data
	 *
	 * @access	public
	 * @param		int	course id
	 * @param 	boolean
	 */
  public function remove_course($cid)
  {
		// Prepare to delete everything associated with this course
		$d = array('course_id' => $cid);

		// Remove all the acl connections with this course (dscribe1, dscribe2, or instructor)
		$this->db->delete('acl', $d);
		
		// Remove all materials associated with this course
		$this->db->select('id')->from('materials')->where("course_id=$cid");
		$q = $this->db->get();
		foreach($q->result_array() as $row) {
			$this->material->remove_material($cid, $row['id']);
		}

		// Remove all the course files associated with this course
		$cdirname = $this->course_path($cid);
		$this->ocw_utils->remove_dir($cdirname);
		
		// remove course_files entry from db
		$this->db->delete('course_files', array('course_id'=>$cid));
		
		// remove course from db
		$this->db->delete('courses', array('id'=>$cid));
	}
	
	/**
		* Get users (acl entries) associated with a course
		*
		* @access  public
		*	@param   int course_id
		*	@param   string user role (optional)
		*	@return  array of ocw_acl entries
		*/
	public function get_course_users($cid, $role = NULL) {
		$where = ($role == NULL) ? array('course_id' => $cid)
														 : array('course_id' => $cid, 'role' => $role);
		$this->db->select('*')->from('acl')->where($where);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				$cacls[] = $row;
    	}
		}
		return ($q->num_rows() > 0) ? $cacls : null;
	}

	/**
    * Complete (dscribe2_dscribe1) relationships when adding an acl entry
		*
		* @access  private
		* @param   array details (containing course_id, user_id, and role)
		* @return  boolean
		*/
	private function complete_relationships($details)
	{
		if ($details['role'] != 'dscribe1' && $details['role'] != 'dscribe2')
			return true;
		if ($details['role'] == 'dscribe1') {
			// create a relationship with existing dscribe2s
			$d2s = $this->get_course_users($details['course_id'], 'dscribe2');
			if (count($d2s) != 0) {
				foreach($d2s as $d2) {
					$this->ocw_user->set_relationship($details['user_id'], $d2['user_id']);
				}
			}
		} else if ($details['role'] == 'dscribe2') {
			// create a relationship with existing dscribe1s
			$d1s = $this->get_course_users($details['course_id'], 'dscribe1');
			if (count($d1s) != 0) {
				foreach($d1s as $d1) {
					$this->ocw_user->set_relationship($d1['user_id'], $details['user_id']);
				}
			}
		}
		return true;
	}

	/**
		* This function checks all the existing acl rows and verifies that
		* the role claimed in that row matches the roll of the user pointed
		* to by the user_id field
		* Inconsistencies are logged to the apache error log
		*
	  * @access private
	  * @return true
	  */
	private function check_for_inconsistent_acls()
	{
		$this->db->select("*")->from('acl');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $acl) {
				$this->db->select('id, role')->from('users')->where(array('id' => $acl['user_id']))->limit(1);
				$qu = $this->db->get();
				if ($qu->num_rows() != 1) {
					$errmsg = "check_for_inconsistent_acls: Invalid acl entry "
								. "(user_id " . $acl['user_id']
								. ", course_id " . $acl['course_id']
								. ", role " . $acl['role']
								. "), no matching user entry found";
					$this->ocw_utils->log_to_apache('warn', $errmsg);
				}
				foreach($qu->result_array() as $u) {
					if ($u['role'] != $acl['role']) {
						$errmsg = "check_for_inconsistent_acls: Invalid acl entry "
									. "(user_id " . $acl['user_id']
									. ", course_id " . $acl['course_id']
									. ", role " . $acl['role']
									. "), role doesn't match user entry role (" . $u['role'] . ")";
						$this->ocw_utils->log_to_apache('warn', $errmsg);
						// This goes to the CodeIgniter logs: log_message('error', $errmsg);
						// This goes to the php_error.log: error_log($errmsg, 0);
					}
				}
			}
		}
		return true;
	}

	/**
     * add user with the role to the course
	 *
	 * @access  public
	 * @param   array details (containing course_id, user_id, and role)
	 * @return  boolean
	 */
  public function add_user($details)
  {
		$this->complete_relationships($details);
		$this->db->insert('acl', $details);
		$this->check_for_inconsistent_acls();
		return true;
	}

  /**
    * remove a user from a course 
    *
    * @access  public
    * @param   int	course id 
    * @param   int	user id 
    * @param   string user role 
    * @return  string | boolean
    */
  public function remove_user($cid, $uid, $role)
  {
		// Don't allow deletion of the last user in any particular
		// role (dscribe1, dscribe2, or instructor) from a course
		$d1s = $this->get_course_users($cid, 'dscribe1');
		$d2s = $this->get_course_users($cid, 'dscribe2');
		$instrs = $this->get_course_users($cid, 'instructor');

		if ($role == 'instructor' && count($instrs) <= 1)
			return "Cannot remove the only instructor from a course";
		if ($role == 'dscribe2' && count($d2s) <= 1)
			return "Cannot remove the only dscribe2 from a course";
		if ($role == 'dscribe1' && count($d1s) <= 1)
			return "Cannot remove the only dscribe1 from a course";
		if ((count($d2s) + count($d1s) + count($instrs)) <= 1)
			return "Cannot remove the only user for a course";
		
		$d = array('user_id'=>$uid, 'course_id'=>$cid, 'role'=>$role);
		$this->db->delete('acl',$d);
		return true;
	}

	/**
     * Get course
     *
     * @access  public
     * @param   int	cid course id		
     * @param   string	details 
     * @return  string
     */
	public function get_course($cid, $details='*')
	{
		$this->db->select($details)->from('courses')->where('id',$cid);
		$q = $this->db->get();
		$course = $q->row_array();
		return ($q->num_rows() > 0) ? $course : null;
	}
	
	/**
     * Get course by title
     *
     * @access  public
     * @param   string title
     * @return  string
     */
	public function get_course_by_title($title, $details='*')
	{
		$this->db->select($details)->from('courses')->where('title',$title);
		$q = $this->db->get();
		$course = $q->row_array();
		return ($q->num_rows() > 0) ? $course : null;
	}

	/**
     * Get course by number
     *
     * @access  public
     * @param   string number
     * @return  string
     */
	public function get_course_by_number($number, $details='*')
	{
		$this->db->select($details)->from('courses')->where('number',$number);
		$q = $this->db->get();
		$course = $q->row_array();
		return ($q->num_rows() > 0) ? $course : null;
	}
	
		/**
     * Get instructor of a course by course id
     *  mbleed 08/29/08
     * @access  public
     * @param   string number
     * @return  string
     */
	public function get_course_users_by_cid($cid, $type)
	{
		if (!$cid || $type == '') {
			$this->ocw_utils->log_to_apache('error', __FUNCTION__.": called with bad parameters!");
			return '';
		}

		$sql = "SELECT ocw_users.name AS iname
				FROM ocw_courses, ocw_acl, ocw_users
				WHERE ocw_courses.id = ocw_acl.course_id
				AND ocw_acl.user_id = ocw_users.id
				AND ocw_acl.role = '$type'
				AND ocw_courses.id = $cid
				ORDER BY ocw_users.name ASC";
		$q = $this->db->query($sql);
        if ($q->num_rows() > 0) {
        	$course_users = array();
            foreach($q->result_array() as $row) { 
        		$course_users[] = $row['iname'];    	
            }
        }
		return ($q->num_rows() > 0) ? implode("<br>", $course_users) : '';
	}
	
    /**
     * Get all courses 
     *
     * @access  public
     * @return  array
     */
    // public function get_courses()
    // OERDEV-173  bdr - should only have a single get_courses routine
    public function get_courses($uid, $urole = NULL)
    {
        $courses = array();
	if ($urole == 'admin') {
		$uid = NULL;
		$role = 'admin';
	}

	if ($uid == NULL) {
		$sql = 'SELECT ocw_courses. *, ocw_curriculums.name AS cname, 
                        ocw_schools.name AS sname,
			ocw_courses.id AS cid
			FROM ocw_courses, ocw_curriculums, ocw_schools
			WHERE ocw_curriculums.id = ocw_courses.curriculum_id
			AND ocw_schools.id = ocw_curriculums.school_id
			ORDER BY ocw_courses.start_date DESC';
        } else {
    		$sql = "SELECT ocw_courses.*, 
      		ocw_curriculums.name AS cname, 
      		ocw_schools.name AS sname,
      		ocw_courses.id AS cid
      		FROM ocw_courses, ocw_curriculums, ocw_schools, ocw_acl
      		WHERE ocw_curriculums.id = ocw_courses.curriculum_id
      		AND ocw_schools.id = ocw_curriculums.school_id
      		AND ocw_acl.course_id = ocw_courses.id
      		AND ocw_acl.user_id = '$uid'
      		ORDER BY start_date DESC";
	}
	
    	$q = $this->db->query($sql);

        if ($q->num_rows() > 0) {
            foreach($q->result_array() as $row) { 
                 $row['instructors'] = $this->get_course_users_by_cid($row['cid'], 'instructor');
                 $row['dscribe1s'] = $this->get_course_users_by_cid($row['cid'], 'dscribe1');
                 $row['dscribe2s'] = $this->get_course_users_by_cid($row['cid'], 'dscribe2');
                 if (($urole != 'dscribe1')) { 
		    // bdr OERDEV-173 - count everything like materials list counts
                     $materials =  $this->material->materials($row['cid'],'',true,true);
                     $row['total'] = 0;
                     $row['done']  = 0;
                     $row['ask']   = 0;
                     $row['rem']   = 0;
         	     if ($materials != NULL) {
                       foreach($materials as $category => $cmaterial) {
                          foreach($cmaterial as $material) {
                             $row['rem'] += $material['mrem'];
                             $row['ask'] += $material['mask'];
                             $row['done'] += $material['mdone'];
                             //if ($material['mtotal'] != 1000000)				//OERDEV-181 mbleed: removed hardcoded total=1000000 logic
                             $row['total'] += $material['mtotal'];	
                          }
                       }
		     }
		     $row['statcount'] = $row['total'].'/'.$row['done'].'/'.$row['ask'].'/'.$row['rem'];
		     $row['notdone'] = $row['rem'];
		     // $this->ocw_utils->dump($row);
		 }
	         $courses[$row['sname']][$row['cname']][] = $row; 
            }
        }
      
      // get the courses that have NULL curriculum ids
        if ($uid == NULL) {
      		$sql_no_curr_id = "SELECT ocw_courses.*,
          			ocw_courses.curriculum_id AS cname,
          			ocw_courses.curriculum_id AS sname,
          			ocw_courses.id AS cid
        			FROM ocw_courses
        			WHERE ocw_courses.curriculum_id IS NULL
        			ORDER BY ocw_courses.start_date DESC";
        } else {
                $sql_no_curr_id = "SELECT ocw_courses.*, 
                	ocw_courses.curriculum_id AS cname, 
                	ocw_courses.curriculum_id AS sname,
                	ocw_courses.id AS cid
                	FROM ocw_courses, ocw_acl
                	WHERE ocw_courses.curriculum_id = NULL 
                	AND ocw_acl.course_id = ocw_courses.id
                	AND ocw_acl.user_id = '$uid'
                	ORDER BY start_date DESC";
        }

      $q_no_curr_id = $this->db->query($sql_no_curr_id);

      if ($q_no_curr_id->num_rows() > 0) {
        foreach ($q_no_curr_id->result_array() as $row) {
                 $row['instructors'] = $this->get_course_users_by_cid($row['cid'], 'instructor');
                 $row['dscribe1s'] = $this->get_course_users_by_cid($row['cid'], 'dscribe1');
                 $row['dscribe2s'] = $this->get_course_users_by_cid($row['cid'], 'dscribe2');
                 // bdr OERDEV-140 (which looks similiar to OERDEV-118
                 $uprop = getUserProperty('role');
                 // if (($uprop != 'dscribe1')) { // && ($row['cid'] == 35)) 
		 if (($role != 'dscribe1')) {
                    // bdr OERDEV-173 - count everything like materials list counts
                     $materials =  $this->material->materials($row['cid'],'',true,true);
                     $row['total'] = 0;
                     $row['done']  = 0;
                     $row['ask']   = 0;
                     $row['rem']   = 0;
                     foreach($materials as $category => $cmaterial) {
                          foreach($cmaterial as $material) {
                             $row['rem'] += $material['mrem'];
                             $row['ask'] += $material['mask'];
                             $row['done'] += $material['mdone'];
                             if ($material['mtotal'] != 1000000)
                                   $row['total'] += $material['mtotal'];
                          }                
                      }                 
                     $row['statcount'] = $row['total'].'/'.$row['done'].'/'.$row['ask'].'/'.$row['rem'];
		     $row['notdone'] = $row['rem'];
                     // $this->ocw_utils->dump($row);
                 }
          $courses['No School Specified']['No Curriculum Specified'][] = $row;
        }
      }
      
      return (sizeof($courses) > 0) ? $courses : null;
    }

	/**
     * Get course title
     *
     * @access  public
     * @param   int	cid course id		
     * @return  string
     */
	public function course_title($cid)
	{
		$course = $this->get_course($cid,'number,title');
		$title = $course['number'].' '.$course['title'];
		return ($course==null) ? null : trim($title); 
	}

	/**
     * Update course
     *
     * @access  public
     * @param   int	cid course id		
     * @param   array data 
     * @return  void
     */
	public function update_course($cid, $data)
	{
		$this->db->update('courses',$data,"id=$cid");
	}

	/**
     * check to see if a user has access to a course
     *
     * @access  public
     * @param   int	uid user id		
     * @param   int	cid course id		
     * @return  boolean 
     */
	public function has_access($uid, $cid)
	{
		$where = array('user_id'=>$uid, 'course_id'=>$cid);
		$this->db->select('*')->from('acl')->where($where);
		$q = $this->db->get();
		$course = $q->row_array();
		return ($q->num_rows() > 0 || sizeof($course) > 0) ? $course : null;
	}


  private function course_name_exists($name)
  {
     $this->db->select('filename')->from('course_files')->where("filename='$name'");
     $q = $this->db->get();   
     return ($q->num_rows() > 0) ? true : false;
  }

  public function generate_course_name($uniqstr)
  {
      $digest = '';
      do {
         $digest = $this->oer_filename->random_name($uniqstr);
				 $uniqstr = $digest;
      } while ($this->course_name_exists($digest));

      return $digest;
  }

	/**
   * Return path for course information
   *
   * @access  public
   * @param   int	cid course id
   * @return  string path name
   */
	public function course_path($cid)
	{
		# get course directory name
		$path = property('app_uploads_path');
		$this->db->select('filename')->from('course_files')->where("course_id=$cid")->order_by('created_on desc')->limit(1);
		$q = $this->db->get();
		$r = $q->row();
		$path .= 'cdir_'.$r->filename;
		return $path;
	}

}
?>
