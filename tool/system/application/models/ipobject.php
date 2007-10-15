<?php
/**
 * Provides access to ipobjects information 
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Ipobject extends Model 
{
	public function __construct()
	{
		parent::Model();
	}

	/**
     * Get ip objects for a given material 
     *
     * @access  public
     * @param   int	mid material id		
     * @param   string	details 
     * @param   int	ip object id 
     * @return  array
     */
	public function ipobjects($mid, $id='', $details='*')
	{
		$ipobjects = array();

		$this->db->select($details)->from('ipobjects')->where('material_id',$mid);
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				if ($id <> '') {
					if ($id == $row['id']) {
						$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
						array_push($ipobjects, $row);
					}
				} else {
					$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
					array_push($ipobjects, $row);
				}
			}
		} 

		return (sizeof($ipobjects) > 0) ? $ipobjects : null;
	}

	/**
     * Get comments  for an ip objects 
     *
     * @access  public
     * @param   int	oid ip object id		
     * @param   string details fields to return	
     * @return  array
     */
	public function comments($oid, $details='*')
	{
		$comments = array();
		$this->db->select($details)->from('ipobject_comments')->where('ipobject_id',$oid)->orderby('modified_on DESC');
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				array_push($comments, $row);
			}
		} 

		return (sizeof($comments) > 0) ? $comments : null;
	}

	/**
     * Add an ipobject comment
     *
     * @access  public
     * @param   int ipobject id
     * @param   int user id
     * @param   array data 
     * @return  void
     */
	public function add_comment($oid, $uid, $data)
	{
		$data['ipobject_id'] = $oid;
		$data['user_id'] = $uid;
		$data['created_on'] = date('Y-m-d h:i:s');
		$data['modified_on'] = date('Y-m-d h:i:s');
		$this->db->insert('ipobject_comments',$data);
	}

	/**
     * Add an ipobject
     *
     * @access  public
     * @param   int material id
     * @param   int user id
     * @param   array data 
     * @return  void
     */
	public function add($mid, $uid, $data)
	{
		$data['material_id'] = $mid;
		$data['modified_by'] = $uid;
		$data['done'] = '0';
		$this->db->insert('ipobjects',$data);
	}

	/**
     * Update ip objects for a given material 
     *
     * @access  public
     * @param   int	oid object ip id		
     * @param   array	data
     * @return  void
     */
	public function update($oid, $data)
	{
		$this->db->update('ipobjects',$data,"id=$oid");
	}

	/**
     * Remove an ip object
     *
     * @access  public
     * @param   int ip object id
     * @return  void
     */
	public function remove($ipid)
	{
		$data = array('id'=>$ipid);
		$this->db->delete('ipobjects',$data);
	}

	/**
     * Get IP Uses 
     *
     * @access  public
     * @return  array
     */
	public function ip_uses($include_info=false)
	{
		$uses = array();

		$this->db->select('*')->from('ipobject_uses')->orderby('`use`');

		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				if ($include_info) {
					array_push($uses, $row);
				} else {
					$uses[$row['id']] = $row['use'];
				}
			}
		} 

		return (sizeof($uses) > 0) ? $uses : null;
	}

	/**
     * Get IP types 
     *
     * @access  public
     * @return  array
     */
	public function ip_types($include_info=false)
	{
		$types = array();

		$this->db->select('*')->from('ipobject_types')->orderby('type');

		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				if ($include_info) {
					array_push($types, $row);
				} else {
					$protected = ($row['protected']) ? '(protected)' : '';
					$types[$row['id']] = $row['type'].' '.$protected;
				}
			}
		} 

		return (sizeof($types) > 0) ? $types : null;
	}
}
?>
