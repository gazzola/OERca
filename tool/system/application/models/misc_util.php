<?php
/**
 * Various database functions to support libraries that
 * need to access the DB
 *
 * @package	OER Tool		
 * @author  Ali Asad Lotia <lotia@umich.edu>
 * @date    27 May 2008
 * @copyright Copyright (c) 2006, University of Michigan
 */
class Misc_Util extends Model
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
  
  /**
    * Get the id value of the specified column in the specified table 
    * for the specified value from the DB. This is useful when 
    * attempting to define default values for tables that are under 
    * foreign key constraints and keeps default values out of the db
    * schema
    * 
    * @param    string table name
    * @param    string column name
    * @param    string value text
    * @param    string perl regexp pattern that represents the delimiters
    *           used to distinguish between db, table and column names
    * @return   int/string numerical (id) value of the passed text value
    */
    // TODO: think about whether we really care about the db name
  public function get_id_for_value($table_name, $column_name, 
    $value_text, $delim_preg='/\./')
  {
    $short_col_name;
    $full_col_name;
    $short_table_name;
    $full_table_name;
    $table_prefix = $this->db->dbprefix;
    $prefix_len = strlen($table_prefix);
    $full_t_name_len = NULL;
    $value_id = NULL;
    
    // figure out and adjust if the passed table name includes the dbprefix
    if (strncmp($table_prefix, $table_name, $prefix_len) == 0) {
      $full_table_name = $table_name;
      $short_table_name = substr($table_name, $prefix_len);
    } else {
      $short_table_name = $table_name;
      $full_table_name = $table_prefix . $table_name;
    }
    
    $full_t_name_len = strlen($full_table_name);
    
    if (strncmp($full_table_name, $column_name, $full_t_name_len) == 0) {
      $full_col_name = $column_name;
      $short_col_name = substr($column_name, $full_t_name_len);
    } else {
      $full_col_name = "$full_table_name.$column_name";
      $short_col_name = $column_name;
    }
    
    $this->db->select("id");
    $this->db->where($short_col_name, $value_text);
    $q = $this->db->get($short_table_name);
    if ($q->num_rows() > 0) {
      $row = $q->row();
      $value_id = $row->id;
    }
    return $value_id;
  }
}

?>
