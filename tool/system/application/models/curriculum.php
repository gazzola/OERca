<?php
/**
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Curriculum extends Model 
{
	public function __construct()
	{
		parent::Model();
	}

	/**
     * Add a curriculum 
     *
     * @access  public
     * @param   int school id 
     * @param   string name 
     * @param   string description 
     * @return  string | boolean
     */
	public function add($sid, $name, $description)
	{
		if ($this->exists($sid, $name))
			return "A curriculum with name $name already exists!";
			
		$data = array('school_id'=>$sid,'name'=>$name,'description'=>$description);
		return $this->db->insert('curriculums',$data);

	}

	/**
     * Update curriculum
     *
     * @access  public
     * @param   int	cid curriculum id		
     * @param   array data 
     * @return  boolean
     */
	public function update($cid, $data)
	{
		return $this->db->update('curriculums',$data,"id=$cid");
	}


	/**
     * Remove a curriculum 
     *
     * @access  public
     * @param   int curriculum id
     * @return  boolean
     */
	public function remove($cid)
	{
		$data = array('id'=>$cid);
		return $this->db->delete('curriculums',$data);
	}

	/**
     * Get curriculum
     *
     * @access  public
     * @param   int	cid curriculum id		
     * @param   string	details 
     * @return  string
     */
	public function get_curriculum($cid, $details='*')
	{
		$this->db->select($details)->from('curriculums')->where('id', $cid);
		$q = $this->db->get();
		$curriculum = $q->row_array();
		return ($q->num_rows() > 0) ? $curriculum : null;
	}

	/**
     * Get curriculum name
     *
     * @access  public
     * @param   int	cid curriculum id		
     * @return  string
     */
	public function name($cid)
	{
		$curriculum = $this->get_curriculum($cid,'name');
		return ($curriculum==null) ? null : trim($curriculum['name']); 
	}

	/**
     * Check to see if a curriculum already exists 
     *
     * @access  public
     * @param   int school id	
     * @param   string name	
     * @return  boolean
     */
	public function exists($sid, $name)
	{
		$this->db->where('school_id="'.$sid.'" AND LOWER(name)="'.strtolower($name).'"');
		$query = $this->db->get('curriculums'); 
		return ($query->num_rows() > 0) ? true : false;
	}

	/**
     * Get curriculum list (all curriculum, or all curriculum for optionally specified school_id)
     *
     * @access  public
     * @param   int	cid curriculum id		
     * @param   string	details 
     * @return  string
     */
	public function get_curriculum_list($school_id=NULL)
	{
		$curr_list = NULL;
		if ($school_id) {
			$this->db->where('school_id', $school_id);
		}
		$this->db->order_by('name');
		
		$q = $this->db->get('curriculums');
		
		foreach ($q->result() as $row) {
			$curr_list[] = $row;
		}
		
		return $curr_list;
	}

	/**
     * Get curriculum id->name list (all curriculum, or all curriculum for optionally specified school_id)
     *
     * @access  public
     * @param   int	cid curriculum id		
     * @param   string	details 
     * @return  string
     */
	public function get_curriculum_id_list($school_id=NULL)
	{
		$curr_list = NULL;
		if ($school_id) {
			$this->db->where('school_id', $school_id);
		}
		$this->db->order_by('name');
		
		$q = $this->db->get('curriculums');
		
		foreach ($q->result() as $row) {
			$curr_list[$row->id] = $row->name;
		}
		
		return $curr_list;
	}

}
?>
