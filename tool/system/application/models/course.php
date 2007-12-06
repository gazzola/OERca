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
	}

	/**
     * Add a user to a course 
     *
     * @access  public
     * @param   int user id
     * @param   int course id
     * @param   string role 
     * @return  void
     */
	public function add_user($uid, $cid, $role)
	{
		$data = array('course_id'=>$cid,'user_id'=>$uid,'role'=>$role);
		$this->db->insert('acl',$data);
	}

	/**
     * Remove a user from a course 
     *
     * @access  public
     * @param   int user id
     * @param   int course id
     * @return  void
     */
	public function remove_user($uid, $cid)
	{
		$data = array('course_id'=>$cid,'user_id'=>$uid);
		$this->db->delete('acl',$data);
	}

	/**
     * Add a new course 
     *
     * @access  public
     * @param   array details
     * @return  void
     */
	public function new_course($details)
	{
		// handle curriculums
		if ($details['curriculum']=='new') {
			$data = array('title'=>$details['newc'],'description'=>'');
			$curr_id = $this->new_curriculum($data);
		} else {
			$curr_id = $details['curriculum'];
		}
		$curr_id = ($curr_id=='none') ? 0 : $curr_id;

		// handle sequences
		if ($details['sequence']=='new') {
			$data = array('name'=>$details['news']);
			$seq_id = $this->new_sequence($data);
		} else {
			$seq_id = $details['sequence'];
		}
		$seq_id = ($seq_id=='none') ? 0 : $seq_id;

		// link curriculum and sequence	
		if ($seq_id!=0 and $curr_id!=0) {
			$this->db->insert('curriculums_sequences', array('curriculum_id'=>$curr_id,
													         'sequence_id'=>$seq_id));
		}
	
		// add course	
		$data = array('number'=>$details['cnumber'],
					  'title'=>$details['ctitle'], 'start_date'=>$details['sdate'],
					  'end_date'=>$details['edate'], 'curriculum_id'=>$curr_id, 
					  'sequence_id'=>$seq_id, 
					  'class'=>$details['class'], 'director'=>$details['director'],
					  'collaborators'=>$details['collabs']);
		$this->db->insert('courses', $data);
		$course_id = $this->db->insert_id();


		if ($details['dscribe']==1) {
			$this->add_user(getUserProperty('id'), $course_id, getUserProperty('role'));
		}

		return $course_id;	
	}
	
		
	/**
     * Check to see if a course already exists by course number
     *
     * @access  public
     * @param   string courseNumber
     * @return  string | boolean
     */
	public function existsByNumber($courseNumber)
	{
		$where = array('number'=>$courseNumber);
		$query = $this->db->getwhere('courses', $where); 
		return ($query->num_rows() > 0) ? $query->row_array() : false;
		
	}
	
			
	/**
     * Check to see course id by course number
     *
     * @access  public
     * @param   string courseNumber
     * @return  course id
     */
	public function getCourseIdByNumber($courseNumber)
	{
		$courseId='';
		$this->db->select('id')->from('courses')->where('number',$courseNumber);
		$q = $this->db->get();
		if ($q->num_rows() > 0) 
		{
			foreach($q->result_array() as $row) 
			{ 
				$courseId = $row['id'];
			}
		} 
		return $courseId; 
		
	}

	/**
     * Get course
     *
     * @access  public
     * @param   int	cid course id		
     * @param   string	details 
     * @return  string
     */
	public function details($cid, $details='*')
	{
		$this->db->select($details)->from('courses')->where('id',$cid);
		$q = $this->db->get();
		$course = $q->row_array();
		return ($q->num_rows() > 0) ? $course : null;
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
		$course = $this->details($cid,'number,title');
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
     * Get dScribes for a given course 
     *
     * @access  public
     * @param   int	cid course id		
     * @return  array
     */
	public function dscribes($cid)
	{
		$dscribes = array();

		$this->db->select('id,name,user_name,email,ocwdemo_acl.role');
		$this->db->from('acl');
		$this->db->join('fa_user','acl.user_id=fa_user.id');
		$this->db->where(array('ocwdemo_acl.role !='=>'instructor',
							   'course_id'=>$cid));

		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				array_push($dscribes, $row);
			}
		} 

		return (sizeof($dscribes) > 0) ? $dscribes : null;
	}

	/**
     * Get curriculums 
     *
     * @access  public
     * @return  array
     */
	public function curriculums($include_desc=false)
	{
		$c = array();

		$this->db->select('*')->from('curriculums')->orderby('title');

		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				if ($include_desc) {
					array_push($c, $row);
				} else {
					$c[$row['id']] = $row['title'];
				}
			}
		} 

		return (sizeof($c) > 0) ? $c : null;
	}

	/**
     * Add a new curriculum 
     *
     * @access  public
     * @param   array details
     * @return  void
     */
	public function new_curriculum($details)
	{
		$this->db->insert('curriculums',$details);
		return $this->db->insert_id();
	}

	/**
     * Get sequences 
     *
     * @access  public
     * @return  array
     */
	public function sequences()
	{
		$c = array();

		$this->db->select('*')->from('sequences')->orderby('name');

		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
					$c[$row['id']] = $row['name'];
			}
		} 

		return (sizeof($c) > 0) ? $c : null;
	}

	/**
     * Add a new sequence 
     *
     * @access  public
     * @param   array details
     * @return  void
     */
	public function new_sequence($details)
	{
		$this->db->insert('sequences',$details);
		return $this->db->insert_id();
	}

	/**
     * Get course sequence name
     *
     * @access  public
     * @param   int	sid sequence id		
     * @return  string
     */
	public function sequence_name($sid)
	{
		$this->db->select('name')->from('sequences')->where('id',$sid);
		$q = $this->db->get();
		$r = $q->row_array();
		return ($q->num_rows() > 0) ? $r['name'] : '';
	}

	/**
     * Check to see if a user has access to this course 
     *
     * @access  public
     * @param   int	uid user id		
     * @param   int	cid course id		
     * @return  boolean
     */
	public function has_access($uid, $cid)
	{
		$this->db->where('user_id="'.$uid.'" AND course_id="'.$cid.'"');
		$query = $this->db->get('acl'); 
		return ($query->num_rows() > 0) ? true : false;
	}
}
?>
