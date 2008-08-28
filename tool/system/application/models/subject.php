<?php
/**
 * @package OER Tool
 * @author  Ali Asad Lotia <lotia@umich.edu>
 * @date    18 March 2008
 * @copyright Copyright (c) 2006, University of Michigan
 */
class Subject extends Model
{
  /**
   * class constructor
   *
   * @access  public
   * @return  void
   */
  public function __construct()
  {
    parent::Model();
  }
  
	/**
	 * Check to see if a subject already exists 
	 *
	 * @access  public
	 * @param   int school id	
	 * @param   string code
	 * @param		string desc
	 * @return  boolean
	 */
	public function exists($sid, $code, $desc)
	{
		$this->db->where('school_id="'.$sid.'"');
		$this->db->where('LOWER(subj_code)="'.strtolower($code).'"');
		$this->db->where('LOWER(subj_desc)="'.strtolower($desc).'"');
		$query = $this->db->get('subjects'); 
		return ($query->num_rows() > 0) ? true : false;
	}

	/**
	 * Add a subject 
	 *
	 * @access  public
	 * @param   int school id 
	 * @param   string code 
	 * @param   string desc 
	 * @return  string | boolean
	 */
	public function add($sid, $code, $desc)
	{
		if ($this->exists($sid, $code, $desc))
			return "A subject, ". $code .":" . $desc .", already exists!";

		$data = array('school_id'=>$sid,'subj_code'=>$code,'subj_desc'=>$desc);
		return $this->db->insert('subjects', $data);
	}

	/**
	 * Update a subject
	 *
	 * @access  public
	 * @param   int	sid subject id		
	 * @param   array data 
	 * @return  boolean
	 */
	public function update($sid, $data)
	{
		return $this->db->update('subjects',$data,"id=$sid");
	}

	/**
	 * Remove a subject 
	 *
	 * @access  public
	 * @param   int sid subject id
	 * @return  boolean
	 */
	public function remove($sid)
	{
		$data = array('id'=>$sid);
		return $this->db->delete('subjects', $data);
	}

	/**
	 * Get subject
	 *
	 * @access  public
	 * @param   int	subj_id subject id		
	 * @param   string	details 
	 * @return  string
	 */
	public function get_subject($subj_id, $details='*')
	{
		$this->db->select($details)->from('subjects')->where('id', $subj_id);
		$q = $this->db->get();
		$subj = $q->row_array();
		return ($q->num_rows() > 0) ? $subj : null;
	}
  
  /**
   * get the list of subjects
   *
   * @access  public
   * @param   int school id (optional), will constrain by school if specified
   * @return  array with the subject id as key and subj_code and name as val
   *
   */
  public function get_subj_list($school_id=NULL)
  {
    $subj_list = NULL;
    if ($school_id) {
      $this->db->where('school_id', $school_id);
    }
    $this->db->order_by('school_id')->order_by('subj_desc')->
    order_by('subj_code');
    $query = $this->db->get('subjects');
    
    foreach ($query->result() as $row) {
      $subj_list[$row->id] = $row->subj_code . ": " . $row->subj_desc;
    }
    
    return $subj_list;
  }

  /**
   * get all subjects (for an optionally given school)
   *
   * @access  public
   * @param   int school id (optional), will constrain by school if specified
   * @return  array with the subject information
   *
   */
  public function get_subjects($school_id=NULL)
  {
    $subj_list = NULL;
    if ($school_id) {
      $this->db->where('school_id', $school_id);
    }
    $this->db->order_by('school_id')->order_by('subj_desc')->
    order_by('subj_code');
    $query = $this->db->get('subjects');
    
    foreach ($query->result() as $row) {
      $subj_list[] = $row;
    }
    
    return $subj_list;
  }

  /**
   * get all subjects by school_id
   *
   * @access  public
   * @param   int school id (optional), will constrain by school if specified
   * @return  array with the subject information
   *
   */
  public function get_subjects_by_schools()
  {
    $subj_list = NULL;
		
    $this->db->order_by('school_id')->order_by('subj_desc')->order_by('subj_code');
    $query = $this->db->get('subjects');
    
		if ($query->num_rows() > 0) {
			$subj_list = array();
		}
    foreach ($query->result() as $row) {
      if (!isset($subj_list[$row->school_id])) {
				$subj_list[$row->school_id] = array();
			}
			$subj_list[$row->school_id][] = $row;
		}
    return $subj_list;
  }

}
