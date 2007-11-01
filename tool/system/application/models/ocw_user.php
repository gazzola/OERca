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
     * Get a user's courses 
     *
     * @access  public
     * @param   int user id
     * @return  array
     */
	public function get_courses($uid)
	{
		$courses = array();

		$this->db->select('ocwdemo_courses.*, ocwdemo_acl.role');
		$this->db->from('acl')->where("user_id=$uid");
		$this->db->join('courses','acl.course_id=courses.id');
		$this->db->orderby('start_date DESC');

		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) { $courses[] = $row; } 
		}

		return (sizeof($courses) > 0) ? $courses : null;
	}

	/**
     * Add a new user 
     *
     * @access  public
     * @param   string name
     * @param   string user name
     * @param   string email address
     * @return  string
     */
	public function add_user($name, $uname, $email)
	{
		$data = array('name'=>$name,
					  'user_name'=>$uname,
					  'email'=>$email,
	        		  'password'=> $this->freakauth_light->_encode($email));
		$this->db->insert('fa_user',$data);
		return $this->exists($email);
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
		$query = $this->db->getwhere('fa_user', $where); 
		return ($query->num_rows() > 0) ? $query->row_array() : false;
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
		$query = $this->db->getwhere('fa_user', $where); 
		return ($query->num_rows() > 0) ? $query->row_array() : false;
		
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
		$where = array('id'=>$uid);
		$this->db->select('user_name')->from('fa_user')->where($where); 
		$query = $this->db->get();
		$u = $query->row_array();
		return ($query->num_rows() > 0) ? $u['user_name'] : false;
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
}
?>
