<?php
/**
 * Manages filetypes used in the system 
 *
 * @package	OCW Tool		
 * @author David Hutchful <dkhutch@umich.edu>
 * @date 1 September 2007
 * @copyright Copyright (c) 2006, University of Michigan
 */

class Mimetype extends Model 
{
	public function __construct()
	{
		parent::Model();
	}

	/**
     * Get filetypes 
     *
     * @access  public
     * @return  array
     */
	public function mimetypes($include_mimetype=false)
	{
		$filetypes = array();

		$this->db->select('*')->from('mimetypes')->orderby('name');

		$q = $this->db->get();

		if ($q->num_rows() > 0) {
			foreach($q->result_array() as $row) {
				if ($include_mimetype) {
					array_push($filetypes, $row);
				} else {
					$filetypes[$row['id']] = $row['name'];
				}
			}
		} 

		return (sizeof($filetypes) > 0) ? $filetypes : null;
	}
	
	/**
     * Get mimetype id
     *
     * @access  public
	 * @param	type
     * @return  id
     */
	public function getMimetypeId($type)
	{
		$ids = array();

		$this->db->select('id')->from('mimetypes')->where('mimetype', $type);
		$q = $this->db->get();	
		$rv = null;
       	if ($q->num_rows() > 0)
       	{
           foreach($q->result_array() as $row) 
		   { 
               $rv = $row['id'];
			}
        }
       return $rv;
	}

  public function get_mimetype_id_from_filename($filename)
  {
      $mime = $this->get_mime($filename);
      $mimeid = $this->getMimetypeId($mime);
			return ($mimeid==null) ? 6 : $mimeid; #TODO: get notype id dynamically 
  }

/**
 * Tries to get mime data of the file.
 * @return {String} mime-type of the given file
 * @param $filename String
 */
	function get_mime($filename)
	{
    preg_match("/\.(.*?)$/", $filename, $m);    # Get File extension for a better match
    switch(strtolower($m[1])){
        case "js": return "application/javascript";
        case "json": return "application/json";
        case "jpg": case "jpeg": case "jpe": return "image/jpeg";
        case "png": case "gif": case "bmp": return "image/".strtolower($m[1]);
        case "css": return "text/css";
        case "ppt": return "application/pdf";
        case "xml": return "application/xml";
        case "html": case "htm": case "php": return "text/html";
        default:
            if(function_exists("mime_content_type")){ # if mime_content_type exists use it.
               $m = mime_content_type($filename);
            }else if(function_exists("")){    # if Pecl installed use it
               $finfo = finfo_open(FILEINFO_MIME);
               $m = finfo_file($finfo, $filename);
               finfo_close($finfo);
            }else{    # if nothing left try shell
               if(strstr($_SERVER['HTTP_USER_AGENT'], "Windows")){ # Nothing to do on windows
                   return ""; # Blank mime display most files correctly especially images.
               }
               if(strstr($_SERVER['HTTP_USER_AGENT'], "Macintosh")){ # Correct output on macs
                   $m = trim(exec('file -b --mime '.escapeshellarg($filename)));
               }else{    # Regular unix systems
                   $m = trim(exec('file -bi '.escapeshellarg($filename)));
               }
            }
            $m = split(";", $m);
            return trim($m[0]);
    	}
	}
}
?>
