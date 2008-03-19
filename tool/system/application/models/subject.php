<?php
/**
 * @package OCW Tool
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
   * get the list of all subjects
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
}
