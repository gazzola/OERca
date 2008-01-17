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
     * @return  void
     */
	public function add($sid, $name, $description)
	{
		if (!$this->exists($name)) {
			$data = array('school_id'=>$sid,'name'=>$name,'description'=>$description);
			$this->db->insert('curriculum',$data);
		}
	}

	/**
     * Update curriculum
     *
     * @access  public
     * @param   int	cid curriculum id		
     * @param   array data 
     * @return  void
     */
	public function update($cid, $data)
	{
		$this->db->update('curriculum',$data,"id=$cid");
	}


	/**
     * Remove a curriculum 
     *
     * @access  public
     * @param   int curriculum id
     * @return  void
     */
	public function remove($cid)
	{
		$data = array('id'=>$cid);
		$this->db->delete('curriculum',$data);
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
		$this->db->select($details)->from('curriculum')->where('id',$cid);
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
	public function exsits($sid, $name)
	{
		$this->db->where('school_id="'.$sid.'" AND LOWER(name)="'.strtolower($name).'"');
		$query = $this->db->get('curriculum'); 
		return ($query->num_rows() > 0) ? true : false;
	}
}
?>
