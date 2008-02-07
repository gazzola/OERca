<?php
/**
 * Manages tags used in the system 
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Tag extends Model 
{
	public function __construct()
	{
		parent::Model();
	}

	/**
     * Get tags 
     *
     * @access  public
     * @return  array
     */
	public function tags($include_desc=false)
	{
		$tags = array();

		$this->db->select('*')->from('tags')->orderby('name');

		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				if ($include_desc) {
					array_push($tags, $row);
				} else {
					$tags[$row['id']] = $row['name'];
				}
			}
		} 

		return (sizeof($tags) > 0) ? $tags : null;
	}
}
?>
