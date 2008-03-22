<?php
/**
 * @package OER Tool
 * @author  Ali Asad Lotia <lotia@umich.edu>
 * @date    21 March 2008
 * @copyright Copyright (c) 2006, University of Michigan
 */
class Instructors extends Model
{
  /**
   * constructor
   *
   * @access  public
   * @return  void
   */
  public function __construct()
  {
    parent::Model();
  }
  
  
  /**
   * get the current instructor/creator values for a specified instcructor id
   *
   * @access  public
   * @param   int instructor id
   * @return  array that that contains the current instructor details
   */
  public function get_inst($inst_id, $details='*')
  {
    $this->db->select($details)->from('instructors')->
    where('id',$inst_id);
    $query = $this->db->get();
    $inst_info = $query->row_array();
    return ($query->num_rows() > 0) ? $inst_info : NULL;
  }
  
  
  /**
   * get the instructor name for a specified instructor id
   *
   * @access  public
   * @param   int instructor id
   * @return  void
   */
  public function get_inst_name($inst_id)
  {
    $inst_info = $this->get_inst($inst_id,'name');
    return ($inst_info==NULL) ? NULL : $inst_info['name'];
  }
  
  
  /**
	 * Update instructor info
	 *
	 * @access  public
	 * @param   int instructor id
	 * @param   array containing the values to be inserted into the table
	 * @return  void
	 */
	public function update_inst($inst_id, $data)
	{
	  $this->db->update('instructors',$data,"id=$inst_id");
	}
	
	
	/**
	 * Get instructor ID for supplied name
	 * TODO: The current data model is VERY broken. It will not find multiple
	 *      instructors with the same name. Fix it ASAP!
	 *
	 * @access  public
	 * @param   string name of instructor
	 * @param   int number of results
	 * @return  int id of instructor or NULL
	 */
	public function get_inst_id($inst_name, $num_results = 1)
	{
	  $trimmed_name = trim($inst_name);
	  $this->db->select('id')->where('name', $trimmed_name)->
	  limit($num_results);
	  $query = $this->db->get('instructors');
	  if ($query->num_rows() > 0) {
	    foreach ($query->result() as $row) {
	      return $row->id;
	    }
	  } else return NULL;
	}
	
	
	/**
	 * Add instructor to course
	 *
	 * @access  public
	 * @param   string instructor name
	 * @param   int course id
	 * @return  void
	 */
	 // TODO: See if locking the tables makes sense to prevent inaccuracies
	public function add_inst_to_course($inst_name, $cid)
	{
	  $data = NULL;
	  $trimmed_name = trim($inst_name);
	  $exist_user_id = $this->get_inst_id($trimmed_name);
	  if ($exist_user_id) {
	    $data['instructor_id'] = $exist_user_id;
	  } else {
	    $this->add_inst($trimmed_name);
	    $data['instructor_id'] = $this->get_inst_id('$trimmed_name');
	  }
	  $this->db->update('courses', $data, "id=$cid");
	}
	
	
	/**
	 * Add new instructor to the ocw_instructors table
	 *
	 * @access  public
	 * @param   string name of the instructor
	 * @return  void
	 */
	public function add_inst($inst_name)
	{
	  $data['name'] = trim($inst_name);
    $this->db->insert('instructors', $data);
	}
}

?>