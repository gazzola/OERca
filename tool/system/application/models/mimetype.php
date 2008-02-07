<?php
/**
 * Manages filetypes used in the system 
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Mimetype extends Model 
{
	public function __construct()
	{
		parent::Model();
	}

	/**
     * Get filetypes 
     *
     * @access  public
     * @return  array
     */
	public function mimetypes($include_mimetype=false)
	{
		$filetypes = array();

		$this->db->select('*')->from('mimetypes')->orderby('name');

		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				if ($include_mimetype) {
					array_push($filetypes, $row);
				} else {
					$filetypes[$row['id']] = $row['name'];
				}
			}
		} 

		return (sizeof($filetypes) > 0) ? $filetypes : null;
	}
}
?>
