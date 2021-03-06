<?php
/**
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class School extends Model 
{
	public function __construct()
	{
		parent::Model();
	}

	/**
     * Add a school 
     *
     * @access  public
     * @param   string name 
     * @param   string description 
     * @return  boolean
     */
	public function add($name, $description)
	{
		if ($this->exists($name)) {
			return "School with name '" . $name . "' already exists!";
		} else {
			$data = array('name'=>$name,'description'=>$description);
			$this->db->insert('schools',$data);
		}
		return true;
	}

	/**
     * Update school
     *
     * @access  public
     * @param   int	sid school id		
     * @param   array data 
     * @return  boolean
     */
	public function update($sid, $data)
	{
		return $this->db->update('schools',$data,"id=$sid");
	}


	/**
     * Remove a school 
     *
     * @access  public
     * @param   int school id
     * @return  boolean
     */
	public function remove($sid)
	{
		if (!$this->exists($this->name($sid)))
			return "The school does not exist!";
		
		// Delete associated curriculum
		$this->db->delete('curriculums', array('school_id' => $sid));
		// Delete associated subjects
		$this->db->delete('subjects', array('school_id' => $sid));
		
		// Now delete the school
		$this->db->delete('schools', array('id' => $sid));
		
		return true;
	}

	/**
     * Get school
     *
     * @access  public
     * @param   int	sid school id		
     * @param   string	details 
     * @return  string | boolean
     */
	public function get_school($sid, $details='*')
	{
		$this->db->select($details)->from('schools')->where('id',$sid);
		$q = $this->db->get();
		$school = $q->row_array();
		return ($q->num_rows() > 0) ? $school : null;
	}

	/**
     * Get school name
     *
     * @access  public
     * @param   int	sid school id		
     * @return  string
     */
	public function name($sid)
	{
		$school = $this->get_school($sid,'name');
		return ($school==null) ? null : trim($school['name']); 
	}

	/**
     * Check to see if a school already exists 
     *
     * @access  public
     * @param   string name	
     * @return  boolean
     */
	public function exists($name)
	{
		$this->db->where('TRIM(LOWER(name))="'.strtolower($name).'"');
		$query = $this->db->get('schools'); 
		return ($query->num_rows() > 0) ? true : false;
	}
	
	
	/**
	 * Get a list of all schools
	 *
	 * @access public
	 * @return array composed of schools id's and names
	 */
	public function get_school_list()
	{
	  $school_list = NULL;
	  $this->db->select('id, name')->order_by('name');
	  $query = $this->db->get('schools');
	  
	  foreach ($query->result() as $row) {
	    $school_list[$row->id] = $row->name;
	  }
	  
	  return $school_list;
	}
	
	
	/**
	 * Get all the school info (including the description)
	 *
	 * @access public
	 * @return array composed of all school information
	 */
	public function get_schools()
	{
	  $school_array = NULL;
	  $this->db->select('id, name, description')->order_by('name');
	  $query = $this->db->get('schools');
	  
	  foreach ($query->result() as $row) {
	    $school_array[] = $row;
	  }
	  
	  return $school_array;
	}
}
?>
