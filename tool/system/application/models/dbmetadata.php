<?php
/**
 * Various functions to get db metadata info
 *
 * @package	OER Tool		
 * @author  Ali Asad Lotia <lotia@umich.edu>
 * @date    18 March 2008
 * @copyright Copyright (c) 2006, University of Michigan
 */
class DBMetadata extends Model
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
  
  
  // TODO: See if this can be made database agnostic?
  // TODO: Use a less braindead regexp to make this work.
  /**
   * Get enum values from an enum field in the mysql database
   *
   * @access  public
   * @param   string name of the table that contains the field
   * @param   string name of the field
   * @return  array of values in the enum field type
   */
  public function get_enum_vals($dbname, $table, $field)
  {
    $rawqueryresult = NULL;
    $matchedvals= NULL;
    $enumvals = NULL;
    
    $sql = "SELECT COLUMNS.DATA_TYPE, COLUMNS.COLUMN_TYPE
    FROM information_schema.COLUMNS
    WHERE COLUMNS.TABLE_SCHEMA = '$dbname'
    AND COLUMNS.TABLE_NAME = '$table'
    AND COLUMNS.DATA_TYPE = 'enum'
    AND COLUMNS.COLUMN_NAME = '$field'";
    
    $q = $this->db->query($sql);
    
    if ($q->num_rows() > 0) {
      foreach($q->result_array() as $row) {
        foreach($row as $values)
        $rawqueryresult = $values;
      }
    }
    $regex = "/'(.*?)'/";
    preg_match_all($regex, $rawqueryresult, $matchedvals);
    return($matchedvals[1]);
  }
}
?>