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

