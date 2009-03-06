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

		$this->poi_libpath = APPPATH . "libraries/oer_decompose_apache_poi.php";
		$this->have_poi_lib = 0;
		$this->pdf_libpath = APPPATH . "libraries/oer_decompose_pdfparse.php";
		$this->have_pdf_lib = 0;
		$this->hachoir_libpath = APPPATH . "libraries/oer_decompose_hachoir.php";
		$this->have_hachoir_lib = 0;
		$this->oo_libpath = APPPATH . "libraries/oer_decompose_openoffice.php";
		$this->have_oo_lib = 0;
		
		// Check which deomp libraries are available
		if (file_exists($this->poi_libpath)) {
			$this->CI->load->library('OER_decompose_apache_poi');
			$this->have_poi_lib = 1;
		} else {
			//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: library, {$this->poi_libpath}, doesn't exist");
		}
		if (file_exists($this->pdf_libpath)) {
			$this->CI->load->library('OER_decompose_pdfparse');
			$this->have_pdf_lib = 1;
		} else {
			//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: library, {$this->pdf_libpath}, doesn't exist");
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
		}
		// Try to use pdfparse for pdf files
		else if ($this->have_pdf_lib && (stristr($material_file, ".pdf"))) {
			//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: instantiating pdfparse instance");
			$dcomp = new OER_decompose_pdfparse();
		}
		// If nothing else fits, try hachoir
		else if ($this->have_hachoir_lib) {
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

			// Attempt to get context objects
			$this->_add_context_images($cid, $mid, $material_file, $dcomp->get_staging_dir());

			if (0) {
				$this->CI->ocw_utils->log_to_apache('error', "decompose_material: skipping removal of directory: " . $decomp_dir);
			} else {
				// Clean up any temporary directory regardless of success or failure
				if (is_dir($dcomp->get_staging_dir())) {
					$dcomp->rm_staging_dir();
				}
			}
		}

		//$this->CI->ocw_utils->log_to_apache('debug', "decompose_material: returning");
		return ($decomp_code == 0) ? TRUE : FALSE;
	}

	/**
		* Parse the given CO filename and obtain a page number if possible
		* If the page number cannot be determined, return zero
		*
		* @access public
		* @param string cofile The filename of the Content Object
		* @return integer
		*/
	private function _parse_image_page_number($cofile)
	{
		//$this->CI->ocw_utils->log_to_apache('debug', "_parse_image_page_number for '{$cofile}'");
		$base = basename($cofile);

		// Currently only pdfparse leaves the images in a format with page numbers available
		// that pattern is "image-<ppppp>-<nnnn>.png" where "<ppppp>" is the 5-digit page
		// number (with leading zeros), and "<nnnn>" is the image number within the page.
		$matches = array();
		if (preg_match('/image-(\d{5,5})-(\d*)\.(.*)/', $base, $matches)) {
			$page = $matches[1] * 1;	// coerce into numeric value
			//$number = $matches[2];
			//$suffix = $matches[3];
			return $page;
		}

		return 0;
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
			$pagenumber = $this->_parse_image_page_number($cofile);
			$postdata['location'] = $pagenumber;
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

		// Get list of supported mime types
		global $mimes;
		if ( ! is_array($mimes))
		{
			if ( ! require_once(APPPATH.'config/mimes.php'))
			{
				return FALSE;
			}
		}
		$allowed_exts = array_keys($mimes);
		
		$full_list = scandir($dir);

		foreach ($full_list as $key => $filename) {
			$path = $dir . '/' . $filename;
			//$this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: processing {$filename}");

			// Skip directories and unreadable stuff
			if (!is_file($path) || !is_readable($path)) {
				continue;
			}

			/*
			 * This is currently done within specific decomposition libraries,
			 * so it is currently disabled here.
			 *
			 *	// Skip things that are too small (XXX Should the minsize vary by filetype?)
			 *	$size = filesize($path);
			 *	//$this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: {$filename}, size {$size}");
			 *	if ($size < 1024) {
			 *		//$this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: {$filename}, size {$size} is too small");
			 *		continue;
			 *	}
			 */

			/*
			 * Attempt to transform images of unsupported types to something supported (png)
			 *
			 * Note there is a known issue converting some PICT files:
			 * http://www.imagemagick.org/discourse-server/viewtopic.php?f=3&t=10718
			 */
			$name_parts = explode(".", $filename);
			$ext = $name_parts[count($name_parts) - 1];				
			if (! in_array($ext, $allowed_exts)) {

				// Transform the original <name>.<ext> into <name>.png
				$pattern = '/(.*)\.' . $ext . '$/';
				$newpath = preg_replace($pattern, '${1}.png', $path);
				// $this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: Attempting to convert '{$path}' to '{$newpath}'");

				// Local MAMP server needs some extra parameters
				$SET_DYLD_PATH = "";
				$SET_MAMP_PATH = "";
				if ($this->CI->config->item('is_local_mamp_server')) {
					//$this->CI->ocw_utils->log_to_apache('debug', __FUNCTION__.": *** Using LOCAL Settings ***");
					$SET_DYLD_PATH .= "export DYLD_LIBRARY_PATH=\"\";";
					$SET_MAMP_PATH .= "export PATH=\"/opt/local/bin:/opt/local/sbin:/usr/bin:/bin:/usr/sbin:/sbin:/usr/local/bin:/usr/X11/bin\";";
				}

				// Try to convert from the original type to png

				$convert_pgm = property('app_convert_pgm_path');
				$convert_out = array();
				//$this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: running '{$convert_pgm} {$path} {$newpath}'");
				exec("$SET_DYLD_PATH $SET_MAMP_PATH $convert_pgm $path $newpath", &$convert_out, &$convert_code);

				if ($convert_code == 0 && file_exists($newpath)) {
					//$this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: ### Adding file '{$newpath}'");
					$out_array[] = $newpath;
				} else {
					$this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: converting {$path} to {$newpath} returned '{$convert_code}'");
				}

				unlink($path);	// remove original version
				continue;
			}
			
			// OK, add it
			//$this->CI->ocw_utils->log_to_apache('debug', "_get_COs_from_directory: {$filename} **added**");
			$out_array[] = $path;
		}

		return $out_array;
	}

	/**
		* Attempt to obtain context images from an uploaded Material
		*
		* @access public
		* @param int cid The course ID
		* @param int mid The material ID
		* @param string material_file The name of the file containing the uploaded material
		* @param string work_dir The name of a working directory to use when creating the context images
		* @return boolean
		*/
	private function _add_context_images($cid, $mid, $material_file, $work_dir)
	{
		//$this->CI->ocw_utils->log_to_apache('debug', __FUNCTION__.": entered with material '{$material_file}' and directory '{$work_dir}'");

		// We only support PDF files at this time
		$name_parts = explode(".", $material_file);
		$ext = $name_parts[count($name_parts) - 1];				
		if ($ext != "pdf") {
			$this->CI->ocw_utils->log_to_apache('debug', __FUNCTION__.": skipping unsupported file type '{$material_file}'");
			return FALSE;
		}

		if (!is_dir($work_dir)) {
			mkdir($work_dir, 0700, TRUE);
		}

		// Local MAMP server needs some extra parameters
		$SET_DYLD_PATH = "";
		if ($this->CI->config->item('is_local_mamp_server')) {
			//$this->CI->ocw_utils->log_to_apache('debug', __FUNCTION__.": *** Using LOCAL Settings ***");
			$SET_DYLD_PATH .= "export DYLD_LIBRARY_PATH=\"\";";
		}

		// ImageMagick (convert) creates the page numbers with a base of zero.
		// We really want a base of one.  Insteady, use Ghostscript directly.
		$ghostscript_pgm = property('app_ghostscript_pgm_path');
		$gs_out = array();
		$out_path = $work_dir . "/Slide%03d.jpg";
		$ghostscript_cmd = "$ghostscript_pgm -dSAFER -dBATCH -dNOPAUSE -sDEVICE=jpeg -r150 -dTextAlphaBits=4 ";
		$ghostscript_cmd .= "-dGraphicsAlphaBits=4 -dMaxStripSize=8192 -sOutputFile={$out_path} {$material_file}";
		//$this->CI->ocw_utils->log_to_apache('debug', __FUNCTION__.": ghostscript command '{$ghostscript_cmd}'");

		$this->CI->ocw_utils->log_to_apache('debug', __FUNCTION__.": running ghostscript on '{$material_file}'");
		exec("$SET_DYLD_PATH $ghostscript_cmd", &$gs_out, &$gs_code);
		$this->CI->ocw_utils->log_to_apache('debug', __FUNCTION__.": ghostscript returned code '{$gs_code}'");

		if ($gs_code != 0) {
			return FALSE;
		}

		$full_list = scandir($work_dir);

		foreach ($full_list as $key => $filename) {
			$path = $work_dir . '/' . $filename;

			// Skip directories and unreadable stuff
			if (!is_file($path) || !is_readable($path)) {
				continue;
			}

			//$this->CI->ocw_utils->log_to_apache('debug', __FUNCTION__.": Adding slide {$path}");
			$this->CI->coobject->add_slide($cid, $mid, $filename, $path);
		}

		return TRUE;
	}
}

?>
