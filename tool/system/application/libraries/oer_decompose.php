<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * OCW_utils Class
 *
 * @package		OCW Tool
 * @subpackage	Libraries
 * @category	Utilities
 * @author		Kevin Coffman <kwc@umich.edu>
 */
class OER_decompose {

	/**
	 * Constructor
	 *
	 * @access	public
	 */	
	function OER_decompose()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('coobject');
		$this->CI->load->library('ocw_utils');

		$this->poi_libpath = APPPATH . "libraries/OER_decompose_apache_poi.php";
		$this->have_poi_lib = 0;
		$this->hachoir_libpath = APPPATH . "libraries/OER_decompose_hachoir.php";
		$this->have_hachoir_lib = 0;
		$this->oo_libpath = APPPATH . "libraries/OER_decompose_openoffice.php";
		$this->have_oo_lib = 0;
		
		// Check which deomp libraries are available
		if (file_exists($this->poi_libpath)) {
			$this->CI->load->library('OER_decompose_apache_poi');
			$this->have_poi_lib = 1;
		} else {
			//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: library, {$this->poi_libpath}, doesn't exist");
		}
		if (file_exists($this->hachoir_libpath)) {
			$this->CI->load->library('OER_decompose_hachoir');
			$this->have_hachoir_lib = 1;
		} else {
			//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: library, {$this->hachoir_libpath}, doesn't exist");
		}
		if (file_exists($this->oo_libpath)) {
			$this->CI->load->library('OER_decompose_openoffice');
			$this->have_oo_lib = 1;
		} else {
			//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: library, {$this->oo_libpath}, doesn't exist");
		}
		
		if (! $this->have_hachoir_lib && ! $this->have_oo_lib && ! $this->have_poi_lib) {
			$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: no decomposition library found!");
			return FALSE;
		}

		log_message('debug', "OER_decompose class Initialized");
	}

	/**
		* Attempt to decompose a material file, extracting Content Objects
		*
		* @access public
		* @param int cid The course ID
		* @param int mid The material ID
		* @param string material_file Material file name
		* @return boolean
		*/
	public function decompose_material($cid, $mid, $material_file)
	{
		
		$dcomp = null;
		$decomp_code = -1;

		// The Apache POI stuff only handles Microsoft documents (Word and Powerpoint)
		if ($this->have_poi_lib && (stristr($material_file, ".doc") || stristr($material_file, ".ppt"))) {
			//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: instantiating apache_poi instance");
			$dcomp = new OER_decompose_apache_poi();
		} else if ($this->have_hachoir_lib) {
			//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: instantiating hachoir instance");
			$dcomp = new OER_decompose_hachoir();
		}
		if ($dcomp) {
			//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: new decomp instance created");
	
			$decomp_dir = property('app_mat_decompose_dir') . "decomp_" . sha1(time() . rand(1,10000000000));

			// Create temporary directory to hold COs
			//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: calling set_staging_dir");
			$dcomp->set_staging_dir($decomp_dir);

			// Run decomp putting COs into temporary directory
			//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: calling do_decomp");
			$decomp_code = $dcomp->do_decomp($material_file);

			// If result is good, display COs and request metadata info, and add the COs
			if ($decomp_code == 0 && is_dir($dcomp->get_staging_dir())) {
				//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: processing COs in directory: " . $decomp_dir);
				$this->_process_decomposed_COs($cid, $mid, $dcomp->get_staging_dir());
			}

			// Clean up any temporary directory regardless of success or failure
			if (is_dir($dcomp->get_staging_dir())) {
				$dcomp->rm_staging_dir();
			}
		}

		//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: returning");
		return ($decomp_code == 0) ? TRUE : FALSE;
	}

	/**
		* Process the decomposed Content Objects from an uploaded Material
		*
		* @access public
		* @param int cid The course ID
		* @param int mid The material ID
		* @param string decomp_dir Directory containing the decomposed COs
		* @return boolean
		*/
	private function _process_decomposed_COs($cid, $mid, $decomp_dir)
	{
		//$this->CI->ocw_utils->log_to_apache('debug', "_process_decomposed_COs: entered with directory: " . $decomp_dir);
		$this->CI->load->helper('file');
		$uid = getUserProperty('id');
		
		// Get list of files to include
		$co_array = $this->_get_COs_from_directory($decomp_dir);
		if (count($co_array) == 0) {
			return TRUE;
		}

		// The post data is the same for each file
		$postdata['location'] = 0;			// Specify location of "0" for now. This can be edited later.
		$postdata['ask'] = "no";
		$postdata['citation'] = "none";
		$postdata['contributor'] = "";
		$postdata['question'] = "";
		$postdata['comment'] = "";
		$postdata['copyurl'] = "";
		$postdata['copynotice'] = "";
		$postdata['copyholder'] = "";
		$postdata['copystatus'] = "";
		$postdata['done'] = '0';
		$postdata['material_id'] = $mid;
		$postdata['modified_by'] = $uid;
		$postdata['status'] = 'in progress';
		
		// Add each file
		$filedata['userfile_0'] = array();
		foreach ($co_array as $key => $cofile) {			
			$filedata['userfile_0']['name'] = basename($cofile);
			$filedata['userfile_0']['tmp_name'] = $cofile;
			$filedata['userfile_0']['type'] = get_mime_by_extension($cofile);
			//$this->CI->ocw_utils->log_to_apache('debug', "_process_decomposed_COs: file: {$cofile}, mime {$filedata['userfile_0']['type']}");
			
			$this->CI->coobject->add($cid, $mid, $uid, $postdata, $filedata);
		}
		
		return TRUE;
	}

	/**
		* Choose the list of files in a given directory to be presented as potential Content Objects
		*
		* @access public
		* @param string dir Directory containing the decomposed COs
		* @return array of content object file names
		*/
	private function _get_COs_from_directory($dir)
	{
		//$this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: entered with directory: " . $dir);
		$out_array = array();
		
		/*
		// XXX Should this array be global and/or configurable??
		// XXX This should come from the global mimes array!!!
		$allowed_exts = array(
				// Image files
					"bmp", "gif", "jpg", "png", "tif",
				// Audio files
					"aac", "aif", "iff", "m3u", "mid", "mp3", "mpa", "ra", "ram", "wav", "wma",
				// Video files
					"3gp", "asf", "asx", "avi", "mov", "mp4", "mpg", "qt", "rm", "swf", "wmv",
				// Other files
					"wmf",
		);
		*/
		global $mimes;
		if ( ! is_array($mimes))
		{
			if ( ! require_once(APPPATH.'config/mimes.php'))
			{
				return FALSE;
			}
		}

		// XXX Should we take this list and remove items, or just specify exactly what we can handle?
		$allowed_exts = array_keys($mimes);
		
		$full_list = scandir($dir);

		foreach ($full_list as $key => $filename) {
			$path = $dir . '/' . $filename;
			//$this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: processing {$filename}");

			// Skip directories or unreadable stuff
			if (!is_file($path) || !is_readable($path)) {
				continue;
			}

			/* Don't bother with this for now...
			// Skip things that are too small (XXX Should the minsize vary by filetype?)
			$size = filesize($path);
			//$this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: {$filename}, size {$size}");
			if ($size < 1024) {
				//$this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: {$filename}, size {$size} is too small");
				continue;
			}
			*/

			// Skip files with extensions we don't support
			$name_parts = explode(".", $filename);
			$ext = $name_parts[count($name_parts) - 1];				
			if (! in_array($ext, $allowed_exts)) {
				$this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: ${filename} has unsupported extension");
				continue;
			}
			
			// OK, add it
			//$this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: {$filename} **added**");
			$out_array[] = $path;
		}

		return $out_array;
	}

}

?>
