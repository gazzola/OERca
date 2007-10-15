<?php
/**
 * Provides access to material information 
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Material extends Model 
{
	public function __construct()
	{
		parent::Model();
	}

	/**
     * Get materials for a given course 
     *
     * @access  public
     * @param   int	cid course id		
     * @param   int mid material id	
     * @param   boolean	in_ocw if true only get materials in ocw 
     * @param   boolean	as_listing 
     * @return  array
     */
	public function materials($cid, $mid, $in_ocw=false, $as_listing=false)
	{
		$materials = array();
		$where = ($mid=='') ? '' : "AND ocwdemo_materials.id='$mid'";

		$sql = "SELECT ocwdemo_materials.*, ocwdemo_filetypes.mimetype 
				  FROM ocwdemo_materials
				  LEFT JOIN ocwdemo_filetypes 
				    ON ocwdemo_filetypes.id = ocwdemo_materials.filetype_id
				 WHERE ocwdemo_materials.course_id = $cid $where
				 ORDER BY ocwdemo_materials.order";
		$q = $this->db->query($sql);

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) { 
					$row['comments'] = $this->comments($row['id'],'user_id,comments,modified_on');
					if ($in_ocw) {
						if ($row['in_ocw']) { $materials[]= $row; }
					} else {
						$materials[]= $row; 
					}
			}
		}
		return (sizeof($materials)) ? (($as_listing) ? $this->as_listing($materials):$materials) : null; 
	}

	/**
     * Get comments  for a material 
     *
     * @access  public
     * @param   int	mid material id 
     * @param   string details fields to return	
     * @return  array
     */
	public function comments($mid, $details='*')
	{
		$comments = array();
		$this->db->select($details)->from('material_comments')->where('material_id',$mid)->orderby('modified_on DESC');
		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				array_push($comments, $row);
			}
		} 

		return (sizeof($comments) > 0) ? $comments : null;
	}

	/**
     * Add a comment
     *
     * @access  public
     * @param   int material id
     * @param   int user id
     * @param   array data 
     * @return  void
     */
	public function add_comment($mid, $uid, $data)
	{
		$data['material_id'] = $mid;
		$data['user_id'] = $uid;
		$data['created_on'] = date('Y-m-d h:i:s');
		$data['modified_on'] = date('Y-m-d h:i:s');
		$this->db->insert('material_comments',$data);
	}


	/**
     * Update materials for a given course 
     *
     * @access  public
     * @param   int	mid material id		
     * @param   array	data
     * @return  void
     */
	public function update($mid, $data)
	{
		$this->db->update('materials',$data,"id=$mid");
	}
	
	/**
     * Get categories 
     *
     * @access  public
     * @return  array
     */
	public function categories()
	{
		$c = array();

		$this->db->select('*')->from('material_categories')->orderby('name');

		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
					$c[$row['id']] = $row['name'];
			}
		} 

		return (sizeof($c) > 0) ? $c : null;
	}

   	private function as_listing($materials)
    {
        $done = array();
        $course_materials = array();

        foreach ($materials as $cm) {
                $id = $cm['id'];
                $nodetype = $cm['parent'];
                $category = $cm['category'];

                // skip children for now
                if ($nodetype != 0) continue;

                if (!in_array($id, $done)) {
                    array_push($done, $id);

                    // find children
                    list($children, $done) = 
							$this->find_children($id, $materials,$done);

					//indicate if material has been fully cleared
                    $cm['validated'] = $this->is_cleared($id, $cm['embedded_ip']); 

                    if (sizeof($children) > 0) {
                        $cm['show'] = ($this->child_not_in_ocw($children))?1:0;
                        $cm['childitems'] = $children;
                    }
                }
                $course_materials[$category][$cm['order']] = $cm;
        }

        ksort($course_materials);
        return $course_materials;
    }

   // find contents of folders
    private function find_children($id, $materials, $done)
    {
        $tmp = array();

        foreach ($materials as $ccm) {
                $cid = $ccm['id'];
                $order = $ccm['order'];
                $parent = $ccm['parent'];

                if (!in_array($cid, $done) && $parent!=0) {
                    if ($parent == $id) {
                        array_push($done, $cid);
						
						$tmp[$order] = $ccm;
                        $tmp[$order]['validated'] = $this->is_cleared($cid, $ccm['embedded_ip']); 

                        // find more children if necessary
                        list($children, $done) = 
								$this->find_children($cid,$materials,$done);

                        if (sizeof($children) > 0) {
                            $tmp[$order]['show'] = 
									($this->child_not_in_ocw($children)) ? 1:0;
                            $tmp[$order]['childitems'] = $children;
                        }
                    }
                }
        }
        return array($tmp, $done);
    }

	// check to see if material is free of ip voilations
	private function is_cleared($mid, $has_ip)
	{
		if ($has_ip==0) return 1;
		$where = array('material_id'=>$mid, 'done'=>'0');
		$this->db->select('COUNT(*) AS not_done')->from('ipobjects')->where($where);
		$q = $this->db->get();
		$r = $q->row_array();
		return ($r['not_done'] > 0) ? 0 : 1;	
	}

    // check to see if there is a child object that is not in ocw
    private function child_not_in_ocw($children)
    {
        foreach($children as $child) {
                if ($child['in_ocw']==0) return true;
        }
        return false;
    }
}
?>
