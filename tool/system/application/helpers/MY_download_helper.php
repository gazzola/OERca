<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Force download of file
 * Generate headers that force the specified file to be downloaded.
 * This function reads an existing file in a (hopefully) more memory
 * efficient way than the standard force_download helper function.
 * Much of this code is copied from the force_download function.
 * 
 * @param   string download name
 * @param   string path to file to be downloaded
 * @param   optional boolean, delete the file if set to yes
 * @param   optional integer, the size of the read chunk
 */
  function force_file_download($download_name = '', 
    $resource_name = '', $delete_file = FALSE, $read_chunk = NULL)
  {
    $CI =& get_instance();
    
    if ($download_name == '' || $resource_name == '') {
      return FALSE;
    }
    // exit and display an error if the resource_name is undefined
    /* TODO: see if we should display errors in a standard way and
     * redirect somewhere instead of using the exit() function. */
    if (!file_exists($resource_name)) {
      $error_msg = "Error. The selected materials could not be found.";
      $CI->load->library('ocw_utils');
      $CI->ocw_utils->log_to_apache('error', $error_msg);
      exit($error_msg);
    }
    // define the read_chunk if not specified
    if ($read_chunk == NULL) {
      $read_chunk = 4096 * 8;
    }
    $extension = pathinfo($resource_name, PATHINFO_EXTENSION);
    
    // Load the mime types
    @INCLUDE(APPPATH.'config/mimes'.EXT);
	
		// Set a default mime if we can't find it
		if (!isset($mimes[$extension])) {
			$mime = 'application/octet-stream';
		} else {
			$mime = (is_array($mimes[$extension])) ? 
			  $mimes[$extension][0] : $mimes[$extension];
		}
		
		$file_size = filesize($resource_name);
		
		// turn off output buffering if enabled
		if (ob_get_level() > 0) {
		  ob_end_clean();
		}
		
		// Generate the server headers
		if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
		{
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$download_name.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header("Content-Transfer-Encoding: binary");
			header('Pragma: public');
			header("Content-Length: $file_size");
		}
		else
		{
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$download_name.'"');
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Pragma: no-cache');
			header("Content-Length: $file_size");
		}
		
		// open the file
		$fp = fopen($resource_name, 'rb');
		set_time_limit(0);
		//start buffered download
    while(!feof($fp))
    {
      //reset time limit for big files
      print(fread($fp, $read_chunk));
      flush();
    }
    fclose($fp);
    
    // delete the file if requested
    if ($delete_file === TRUE) {
      unlink($resource_name);
    }
    exit();
  }
?>
