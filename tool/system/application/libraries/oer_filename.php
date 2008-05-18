<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * OER Filename Library Class
 *
 * @package		OER Tool
 * @subpackage	Libraries
 * @category	Template
 * @author		David Hutchful
 * @date      March 22 2008
 * @copyright	Copyright (c) 2006, University of Michigan
 */
class OER_Filename
{
  /**
   * Constructor
   *
   * @access  public
   */
  public function __construct()
  {
		$this->object =& get_instance();
    log_message('debug', "OER_Filename Class Initialized");
  }
  
  /**
   * Generate a sha1 digest from a random string
   *
   * @access  public
   * @param   string the name to use in creating the hash
   * @return  void
   */
  public function random_name($name='')
  {
  	$str = time().rand(1,10000000000).$name;
		return sha1($str);
  }

  /**
   * Generate a sha1 digest from a given file
   *
   * @access  public
   * @param   string the path to the file
   * @return  void
   */
  public function file_digest($filename)
  {
		return sha1_file($filename);
  }

  /**
   * Create a directory
   *
   * @access  public
   * @param   string the path to the directory
   * @return  boolean true=created | false=not created
   */
  public function mkdir($dirpath)
  {
		if (!is_dir($dirpath)) {
				mkdir($dirpath);
				chmod($dirpath,0777);
		}
		
		return is_dir($dirpath);
  }

}

?>
