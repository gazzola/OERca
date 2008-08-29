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
			$this->db->select('*')->from('courses')->where('title',$details['title']);
			$q = $this->db->get();
			$course = $q->row_array();
			$curr_mysql_time = $this->ocw_utils->get_curr_mysql_time();
	
			if ($q->num_rows() > 0) {
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

			return ($q->num_rows() > 0) ? $course : null;
	}

	/**
     * add user with the role to the course
	 *
	 * @access  public
	 * @return  array
	 */
  public function add_user($details)
  {
		$this->db->insert('acl', $details);
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
  public function remove_user($cid, $did, $role)
  {
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
	public function get_course_instructor_by_id($id)
	{
		$sql = "SELECT ocw_users.name AS iname
				FROM ocw_courses, ocw_acl, ocw_users
				WHERE ocw_courses.id = ocw_acl.course_id
				AND ocw_acl.user_id = ocw_users.id
				AND ocw_acl.role = 'instructor'
				AND ocw_courses.id = $id
				ORDER BY ocw_instructors.name ASC";
		$q = $this->db->query($sql);
        if ($q->num_rows() > 0) {
        	$course_instructors = array();
            foreach($q->result_array() as $row) { 
        		$course_instructors[] = $row['iname'];    	
            }
        }
		return ($q->num_rows() > 0) ? implode("<br>", $course_instructors) : '';
	}
	
    /**
     * Get all courses 
     *
     * @access  public
     * @return  array
     */
    public function get_courses()
    {
        $courses = array();
		    $sql = 'SELECT ocw_courses. *, ocw_curriculums.name AS cname, 
                                   ocw_schools.name AS sname,
				   ocw_courses.id AS cid
				  FROM ocw_courses, ocw_curriculums, ocw_schools
				 WHERE ocw_curriculums.id = ocw_courses.curriculum_id
				   AND ocw_schools.id = ocw_curriculums.school_id
				 ORDER BY ocw_courses.start_date DESC';
		   $q = $this->db->query($sql);

        if ($q->num_rows() > 0) {
            foreach($q->result_array() as $row) { 
		 // bdr OERDEV-140 (which looks similiar to OERDEV-118
                 $uprop = getUserProperty('role');
                 if (($uprop != 'dscribe1')) { // && ($row['cid'] == 35)) 
                     $row['total'] = $this->material->get_co_count($row['cid']);
                     $row['done'] = $this->material->get_done_count($row['cid']);
                     $row['ask'] = $this->material->get_ask_count($row['cid']);
                     $row['rem'] = $this->material->get_rem_count($row['cid']);
                     $row['instructors'] = $this->get_course_instructor_by_id($row['cid']);
		     $row['statcount'] = $row['total'].'/'.$row['done'].'/'.$row['ask'].'/'.$row['rem'];
		     $row['notdone'] = $row['total'] - $row['done'];
		     //$this->ocw_utils->dump($row);
		 }
	         $courses[$row['sname']][$row['cname']][] = $row; 
            }
        }
      
      // get the courses that have NULL curriculum ids
      $sql_no_curr_id = "SELECT ocw_courses.*,
          ocw_courses.curriculum_id AS cname,
          ocw_courses.curriculum_id AS sname,
          ocw_courses.id AS cid
        FROM ocw_courses
        WHERE ocw_courses.curriculum_id IS NULL
        ORDER BY ocw_courses.start_date DESC";

      $q_no_curr_id = $this->db->query($sql_no_curr_id);

      if ($q_no_curr_id->num_rows() > 0) {
        foreach ($q_no_curr_id->result_array() as $row) {
                 // bdr OERDEV-140 (which looks similiar to OERDEV-118
                 $uprop = getUserProperty('role');
                 if (($uprop != 'dscribe1')) { // && ($row['cid'] == 35)) 
                     $row['total'] = $this->material->get_co_count($row['cid']);
                     $row['done'] = $this->material->get_done_count($row['cid']);
                     $row['ask'] = $this->material->get_ask_count($row['cid']);
                     $row['rem'] = $this->material->get_rem_count($row['cid']);
                     $row['instructors'] = $this->get_course_instructor_by_id($row['cid']);
                     $row['statcount'] = $row['total'].'/'.$row['done'].'/'.$row['ask'].'/'.$row['rem'];
		     $row['notdone'] = $row['total'] - $row['done'];
                     //   $this->ocw_utils->dump($row);
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
}
?>