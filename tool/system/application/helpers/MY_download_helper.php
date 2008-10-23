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
 */
  function force_file_download($download_name = '', 
    $resource_name = '', $delete_file = FALSE, $read_chunk = NULL)
  {
    if ($download_name == '' || $resource_name == '') {
      return FALSE;
    }
    // define the read_chunk if not specified
    if ($read_chunk == NULL) {
      $read_chunk = 1024 * 8;
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
      //ob_flush();
    }
    fclose($fp);
    ob_flush();
    // delete the file if requested
    if ($delete_file === TRUE) {
      unlink($resource_name);
    }
    exit();
  }
?>