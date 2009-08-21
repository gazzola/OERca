<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * OER_decompose_pdfparse class
 * Invokes pdf file utililty programs to extract Content Objects
 * (Images) from a material file
 * 
 * @package			OERca
 * @subpackage	Libraries
 * @category		Utility, Archiving
 * @author			Kevin Coffman <kwc@umich.edu>
 */

class OER_decompose_pdfparse
{
  
	private $CI = NULL;
	private $staging_dir = NULL;
	private $pdfparse_pgm = "";
  
	/**
	 * Constructor
	 *
	 * @access  public
	 * @return  an instance of the class
	 */
	public function __construct()
	{
		static $path_set = 0;
		
		$this->CI =& get_instance();
		$this->CI->load->library('ocw_utils');
    
		//$this->CI->ocw_utils->log_to_apache('debug', "+++++ OER_decompose_apache_poi Class Constructor +++++");

		$this->pdfparse_pgm = property('app_pdfparse_path');

		return $this;
	}
  
  public function __destruct()
	{
		//$this->CI->ocw_utils->log_to_apache('debug', "----- OER_decompose_apache_poi Class Destructor -----");
	}


  /**
   * Set the directory in which content objects (COs) are held
	 * while metadata is collected and COs are added 
   *
   * @access  public
   * @param   string Directory in which COs are spewed
   * @return  void
   */
  public function set_staging_dir($passed_dir)
  {
		//$this->CI->ocw_utils->log_to_apache('debug', "pdfparse::set_staging_dir: entered with: '{$passed_dir}'");
		$this->staging_dir = $passed_dir;
  }
  
  
  /**
   * Get the directory in which COs are spewed
   *
   * @access  public
   * @return  name of the directory in which COs are spewed
   */
  public function get_staging_dir()
  {
		//$this->CI->ocw_utils->log_to_apache('debug', "pdfparse::get_staging_dir: returning with: '{$this->staging_dir}'");
		return $this->staging_dir;
  }
  
  
  /**
   * Clean up the temporary directory in which COs are spewed
   *
   * @access  public
   * @return  boolean
   */
  public function rm_staging_dir()
  {
		//$this->CI->ocw_utils->log_to_apache('debug', "pdfparse::rm_staging_dir: removing directory '{$this->staging_dir}'");
		if (is_dir($this->staging_dir)) {
			$this->CI->ocw_utils->remove_dir($this->staging_dir);
			$this->staging_dir = NULL;
			return TRUE;
		} else {
			//$this->CI->ocw_utils->log_to_apache('debug', "pdfparse::rm_staging_dir: '{$this->staging_dir}' is not a directory!");
			return FALSE;
		}
  }
  
  
  /**
   * Call the pdfparse program (script) to extract images from
	 * the materials file.
	 *
	 * Setting the DYLD_LIBRARY_PATH="" should only be necessary when running
	 * the web server on a Mac under MAMP.  Hopefully, it does not affect things when
	 * running on a Linux web server.
	 *
   * @access   public
   * @param    full path to the materials file to be decomposed
	 * @return   the return code from pdfparse invocation
   */
  public function do_decomp($materials_file)
  {
		$this->CI->ocw_utils->log_to_apache('debug', "pdfparse::do_decomp: '{$materials_file}'");
		
		if ($this->staging_dir == NULL) {
			$subdir = "decomp_" . sha1(time() . rand(1,10000000000));
			$this->staging_dir = property('app_mat_decompose_dir') . $subdir;
			//$this->CI->ocw_utils->log_to_apache('debug', "pdfparse::do_decomp: defaulted staging_dir to '{$this->staging_dir}'");
		}

		//$this->CI->ocw_utils->log_to_apache('debug', "pdfparse::do_decomp: staging dir is '{$this->staging_dir}'");

		$pdfcode = -1;
		$pdfout = array();
		// Redirect stderr to /dev/null to eliminate "random" messages from the apache log file
		// See note above about DYLD_LIBRARY_PATH
		exec("export DYLD_LIBRARY_PATH=\"\"; $this->pdfparse_pgm \"$materials_file\" $this->staging_dir &>/dev/null", $pdfout, $pdfcode);
		unset($pdfout);
		$this->CI->ocw_utils->log_to_apache('debug', "pdfparse::do_decomp: returning '{$pdfcode}'");

		return $pdfcode;
	}

}

?>
