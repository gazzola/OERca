<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * OER_decompose_hachoir class
 * Invokes hachoir python code to extract Content Objects
 * from a material file
 * 
 * @package			OERca
 * @subpackage	Libraries
 * @category		Utility, Archiving
 * @author			Kevin Coffman <kwc@umich.edu>
 */

class OER_decompose_hachoir
{
  
	private $CI = NULL;
	private $staging_dir = NULL;
	private $python_path = NULL;
	private $hachoir_inst_base = NULL;
	private $hachoir_script = NULL;
	private $hachoir_python_path = NULL;
	private $hachoir_options = "";
  
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
    
		//$this->CI->ocw_utils->log_to_apache('debug', "+++++ OER_decompose_hachoir Class Constructor +++++");

		$this->python_path = property('app_python_path');
		//$this->CI->ocw_utils->log_to_apache('debug', "python is located at '{$this->python_path}'");
		$this->hachoir_inst_base = property('app_hachoir_base');
		//$this->CI->ocw_utils->log_to_apache('debug', "hachoir base is located at '{$this->hachoir_inst_base}'");
		
		$this->hachoir_script = $this->hachoir_inst_base . "hachoir-subfile/hachoir-subfile";
		//$this->CI->ocw_utils->log_to_apache('debug', "hachoir-subfile is located at '{$this->hachoir_script}'");

		$this->hachoir_python_path = $this->hachoir_inst_base . "hachoir-core" . ":" .
																 $this->hachoir_inst_base . "hachoir-regex";

		// Limit the hachoir search to file categories we care about
		// (*current*  list of possible categories is: archive,audio,common,container,file_system,game,image,misc,network,program,video)
		$this->hachoir_options = "--quiet --category audio,image,misc,video";


		//$this->CI->ocw_utils->log_to_apache('debug', "BEFORE: PYTHONPATH is '" . getenv("PYTHONPATH") . "'");
		if (! $path_set) {
			//$this->CI->ocw_utils->log_to_apache('debug', "PYTHONPATH WAS NOT PREVIOUSLY SET BY US");
			if (getenv("PYTHONPATH") == "") {
				//$this->CI->ocw_utils->log_to_apache('debug', "PYTHONPATH was previously empty");
				putenv("PYTHONPATH=" . "$this->hachoir_python_path");
			} else {
				//$this->CI->ocw_utils->log_to_apache('debug', "PYTHONPATH was previously NON-empty");
				putenv("PYTHONPATH=" . getenv("PYTHONPATH") . ":" . "$this->hachoir_python_path");
			}
			$path_set = 1;
		}
		//$this->CI->ocw_utils->log_to_apache('debug', "AFTER:  PYTHONPATH is '" . getenv("PYTHONPATH") . "'");

		return $this;
	}
  
  public function __destruct()
	{
		//$this->CI->ocw_utils->log_to_apache('debug', "----- OER_decompose_hachoir Class Destructor -----");
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
		//$this->CI->ocw_utils->log_to_apache('debug', "hachoir::set_staging_dir: entered with: '{$passed_dir}'");
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
		//$this->CI->ocw_utils->log_to_apache('debug', "hachoir::get_staging_dir: returning with: '{$this->staging_dir}'");
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
		//$this->CI->ocw_utils->log_to_apache('debug', "hachoir::rm_staging_dir: removing directory '{$this->staging_dir}'");
		if (is_dir($this->staging_dir)) {
			$this->CI->ocw_utils->remove_dir($this->staging_dir);
			$this->staging_dir = NULL;
			return TRUE;
		} else {
			//$this->CI->ocw_utils->log_to_apache('debug', "hachoir::rm_staging_dir: '{$this->staging_dir}' is not a directory!");
			return FALSE;
		}
  }
  
  
  /**
   * Creates an archive that contains the specified materials
   * including any associated image files. Return the name of
   * the created file. To allow the creation of multiple archive
   * types, this calls a private function to build the actual
   * archive.
   *
   * @access   public
   * @param    full path to the materials file to be decomposed
	 * @return   the return code from hachoir invocation
   */
  public function do_decomp($materials_file)
  {
		$this->CI->ocw_utils->log_to_apache('debug', "hachoir::do_decomp: '{$materials_file}'");
		
		
		if ($this->staging_dir == NULL) {
			$subdir = "decomp_" . sha1(time() . rand(1,10000000000));
			$this->staging_dir = property('app_mat_decompose_dir') . $subdir;
			//$this->CI->ocw_utils->log_to_apache('debug', "hachoir::do_decomp: defaulted staging_dir to '{$this->hachoir_python_path}'");
		}

		//$this->CI->ocw_utils->log_to_apache('debug', "hachoir::do_decomp: pythonpath is '{$this->hachoir_python_path}'");
		//$this->CI->ocw_utils->log_to_apache('debug', "hachoir::do_decomp: hachoir script is '{$this->hachoir_script}'");
		//$this->CI->ocw_utils->log_to_apache('debug', "hachoir::do_decomp: staging dir is '{$this->staging_dir}'");

		$hcode = -1;
		$hout = array();	// dummy array to hold output
		// Redirect stderr to /dev/null to eliminate messages in the apache log file
		exec("$this->python_path $this->hachoir_script $this->hachoir_options $materials_file $this->staging_dir >& /dev/null", &$hout, &$hcode);
		unset($hout);
		$this->CI->ocw_utils->log_to_apache('debug', "hachoir::do_decomp: returning '{$hcode}'");

		return $hcode;
	}

}

?>
