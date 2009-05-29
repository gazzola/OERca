<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * OCW_utils Class
 *
 * @package		OCW Tool
 * @subpackage	Libraries
 * @category	Utilities
 * @author		David Hutchful <dkhutch@umich.edu>
 */
class OCW_utils {

	/**
	 * Constructor
	 *
	 * @access	public
	 */	
	function OCW_utils()
	{
		$this->object =& get_instance();
		$this->object->load->model('coobject');
		log_message('debug', "OCW_utils Class Initialized");
	}



	// calculate the difference between two dates.
	function time_diff_in_words($from_time, $incl_secs=false)
	{
        $from_time = strtotime($from_time); 
        $to_time = mktime(); 
        $diff_in_min = round(abs($to_time - $from_time) / 60);
        $diff_in_sec = round(abs($to_time - $from_time));
		$secs = 0;
	
		if ($diff_in_sec >= 0 && $diff_in_sec <= 5) {
            $secs = 'less than 5 seconds'; } 
		elseif ($diff_in_sec >= 6 && $diff_in_sec <= 10) {
            $secs = 'less than 10 seconds'; }
		 elseif ($diff_in_sec >= 11 && $diff_in_sec <= 20) {
            $secs = 'less than 20 seconds'; }
		 elseif ($diff_in_sec >= 21 && $diff_in_sec <= 40) {
            $secs = 'half a minute'; }
		 elseif ($diff_in_sec >= 41 && $diff_in_sec <= 59) {
            $secs = 'less than a minute'; 
		} else  { $secs = '1 minute'; }

		if ($diff_in_min >= 0 && $diff_in_min <= 1) {
			return 'Today'; }
            #return ($diff_in_min==0) ? 'less than a minute' 
			#						 : (($incl_secs) ? $secs : '1 minute'); }
		elseif ($diff_in_min >= 2 && $diff_in_min <= 45) {
          	return 'Today'; }
          	#return 'about 1 hour'; }
		elseif ($diff_in_min >= 46 && $diff_in_min <= 90) {
          	return 'Today'; }
          	#return  $diff_in_min.' minutes'; } 
		elseif ($diff_in_min >= 90 && $diff_in_min < 1440) {
          	return 'Today'; }
            #return 'about '.round(floatval($diff_in_min) / 60.0).' hours'; } 
		elseif ($diff_in_min >= 1441 && $diff_in_min < 2880) {
          	return '1 day ago'; }
	    else {  return round($diff_in_min / 1440).' days ago'; }
	}

	/**
     * send_response  - return a value in html/xml/JSON format to a 
     * XMLHTTP object request (AJAX call)
     *
     * @param mixed $value response to send 
     */
    function send_response($value, $type='plain')
    {
		include_once 'JSON.php';

		$json = new Services_JSON();

        if ($type == 'html') {
            header('Content-Type: text/html');
            echo $value;
        } elseif ($type == 'xml') {
            header('Content-Type: text/xml');
            echo $value;
        } elseif ($type == 'plain') {
            header('Content-Type: text/plain');
            echo $value;
        } elseif ($type == 'php') {
            header('Content-Type: text/plain');
            echo serialize($value);
        } elseif ($type == 'phpdump') {
            header('Content-Type: text/html');
            $this->dump($value);
        } else {
            $json = $json->encode($value);
            header('Content-Type: text/plain');
            echo $json;
        }
        exit;
    }

	/**
	 * Display the innards of a variable 
	 *
	 * @access	public
	 * @param	mixed 
	 * @return	void
	 */
	function dump($var, $exit=false)
	{
		print '<pre>'; print_r($var); print '</pre>';
		if($exit) {exit();}
	}

	
	/**
	 * Fetch a single line of text from the language array
	 *
	 * @access	public
	 * @param	string	the language line
	 * @return	string
	 */
	function icon($mimetype='', $width='', $height='')
	{
		$file = '';
		$width = ($width=='') ? '' : $width;
		$height = ($height=='') ? '15' : $height;

		 switch($mimetype) {
    		case 'application/mspowerpoint': $file = 'ppt.gif'; break;
    		case 'application/msword': $file = 'msword.gif'; break;
    		case 'application/octet-stream': $file =''; break;
    		case 'application/pdf': $file = 'file_acrobat.gif'; break;
    		case 'application/postscript': $file = ''; break;
    		case 'application/smil': $file = ''; break;
    		case 'application/vnd.ms-excel': $file = ''; break;
    		case 'application/x-cdlink': $file = ''; break;
    		case 'application/x-gzip': $file = ''; break;
  		 	case 'application/x-shockwave-flash': $file = ''; break;
    		case 'application/x-tar': $file = ''; break;
    		case 'application/zip': $file = ''; break;
   			case 'audio/midi': $file = ''; break;
    		case 'audio/mpeg': $file = ''; break;
    		case 'audio/TSP-audio': $file = ''; break;
    		case 'audio/x-pn-realaudio': $file = ''; break;
    		case 'audio/x-realaudio': $file = ''; break;
    		case 'audio/x-wav': $file = ''; break;
    		case 'image/gif': $file = ''; break;
    		case 'image/jpeg': $file = ''; break;
    		case 'image/png': $file = ''; break;
    		case 'image/tiff': $file = ''; break;
    		case 'image/svg+xml': $file = ''; break;
    		case 'image/x-xbitmap': $file = ''; break;
    		case 'model/vrml': $file = ''; break;
    		case 'text/css': $file = 'page.png'; break;
    		case 'text/html': $file = ''; break;
    		case 'text/plain': $file = 'page.png'; break;
    		case 'text/rtf': $file = 'page.png'; break;
    		case 'text/xml': $file = ''; break;
    		case 'video/mpeg': $file = ''; break;
    		case 'video/quicktime': $file = ''; break;
    		case 'video/vnd.vivo': $file = ''; break;
    		case 'video/x-msvideo': $file = ''; break;
    		case 'folder': $file = 'folder.gif'; break;
        	default: $file='page.png';
    	}

		$img = property('app_img').'/mimetypes/'; 
		return ($file=='') 
		   ? ''
		   : '<img src="'.$img.$file.'" width="'.$width.'" height="'.
			  $height.'" />';
	}

	function create_co_list($cid,$mid,$objs,$filter,$inclrep=false,$cols=5)
	{
		$id = ($inclrep) ? 'id="edrepl"' : '';
    $list = "<div $id class='column first last'>\n";
		$size = count($objs);
		$cols = (($cols-1) <= 0) ? 1 : $cols;
		$count = 1;	

		for($i = 0; $i < $size; $i++) {
			  $class = (($count % $cols)==0 && ($count >= $cols)) 
							 ? 'column span-4 last' 
							 : ( ((($count % $cols)==1 && $count >= $cols) || $count==1) 
									? 'column span-4 first': 'column span-4'); 
	
			  $break = (($count % $cols)==0 && ($count >= $cols) ) 
							 ? "\n</div>\n<div $id class='column first last'>\n" : '';

				$y = $this->create_co_img($cid, $mid, $objs[$i]['id'], $objs[$i]['location'],
																	$filter,'orig',true,true,true,true,
																	$class.(($inclrep)?' edrepl1' : ''));
		  	$list .= "\n$y\n";

				if ($inclrep) {
						$class .= ' edrepl2';
						$y = $this->create_co_img($cid, $mid, 
									  $objs[$i]['id'], $objs[$i]['location'],$filter,'repl',true,false,false,true,$class);
		  			$list .= "\n$y\n";
				}

				$list .= $break;

				if ($count==$size) {  $list .= "\n</div>"; }
			  $count++;
		} 
	
		return $list;
	}
	
	function scalecoimage($location, $maxw=NULL, $maxh=NULL){
		    $img = @getimagesize($location);
		    if($img){
		        $w = $img[0];
		        $h = $img[1];
		
		        $dim = array('w','h');
		        foreach($dim AS $val){
		            $max = "max{$val}";
		            if(${$val} > ${$max} && ${$max}){
		                $alt = ($val == 'w') ? 'h' : 'w';
		                $ratio = ${$alt} / ${$val};
		                ${$val} = ${$max};
		                ${$alt} = ${$val} * $ratio;
		            }
		        }
		        $hoffset = ($maxh - $h)/2;
		        $woffset = ($maxw - $w)/2;
				$style_line = "width: ".$w."px; height: ".$h."px; margin-top: ".$hoffset."px; margin-bottom: ".$hoffset."px; margin-left: ".$woffset."px; margin-right: ".$woffset."px;";
		    } else {
		    	$style_line = "width: 150px; height: 150px; margin-top: 0px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;";
		    }
		    return($style_line);
	}
	
	function create_co_img($cid, $mid, $oid, $loc, $filter,$type='orig', $shrink=true, $show_ctx=true, $show_edit=false, $showinfo=true, $optclass='',$offset='212') 
	{
		$name = $this->object->coobject->object_filename($oid);
		$path = $this->object->coobject->object_path($cid, $mid,$oid);
		$defimg = ($type=='orig') ? 'noorig.png' : 'norep.png';
		$dflag = ($type=='orig') ? 'grab' : 'rep';
		$image_details = $this->_get_imgurl($path, $name, $dflag);
		if ($image_details['thumb_found'] === true) {
			$imgurl = $image_details['thumburl'];
			$imgpath = $image_details['thumbpath'];
		} else  if ($image_details['img_found'] === true) {
			$imgurl = $image_details['imgurl'];
			$imgpath = $image_details['imgpath'];
		} else {
			$imgurl = property('app_img').'/'.$defimg;
			$imgpath = property('app_img').'/'.$defimg;
		}
		// Show the original image for "magnify", unless there is no original image
		$magurl = $image_details['img_found'] ? $image_details['imgurl'] : $imgurl;

		$editurl = site_url("materials/object_info/$cid/$mid/$oid/$filter").
								 '?TB_iframe=true&height=630&width=800';

		$size = ($shrink) ? $this->scalecoimage($imgpath, 150, 150) : $this->scalecoimage($imgpath, 300, 300);

		$title = 'Content Object :: Location: Page '.$loc;
		$slide=($show_ctx) ? '<li id="cislide">'.($this->create_slide($cid, $mid, $loc)).'</li>' : '';
		$magnify = '<li id="cimagnify"><a href="'.$magurl.'" class="smoothbox" rel="gallery-cos">'.
	   				 		 '<img title="'.$title.'" src="'.property('app_img').'/search_16.gif" /></a></li>';

		$editlnk=($show_edit) 
							? '<li id="ciedit"><a class="smoothbox" href="'.$editurl.'">'.
	   				 		 '<img title="'.$title.'" src="'.property('app_img').'/edit_16.gif" /></a></li>' 
						  :'';
		$imglnk= ($show_edit) 
							?	 '<a href="'.$editurl.'" class="smoothbox">'.
						 		 '<img title="'.$title.'" src="'.$imgurl.'" style="'. $size .'" /></a>'
							:	 '<img title="'.$title.'" src="'.$imgurl.'" style="'. $size .'" />';

		$dcell = ($shrink) ? 'dcell':'dcellbig';
			
		switch ($this->object->coobject->object_progress($oid)) {
			case 'notcleared':
				$statusclass = 'status_notcleared';
				break;
			case 'inprogress':
				$statusclass = 'status_inprogress';
				break;
			case 'cleared':
				$statusclass = 'status_cleared';
				break;
			default:
				$statusclass = 'status_unknown';
				break;				
		} //end switch
		
		$locbar = '<li id="ciloc">'.$loc.'</li>';
		$flagclass = 'status_flag';
		if (!$shrink) $offset = "341";
		$offsetclass = "status_flag_offset_".$offset;
		
		$coimginfo_html = ($showinfo) ? "$locbar $slide $editlnk $magnify" : '<div>&nbsp;</div>';

		if ($type=='orig') {
			$flag_html = "	<span class=\"$flagclass $statusclass $offsetclass \">&nbsp; </span>";
		} else {
			$flag_html = "";
			$statusclass = 'status_unknown'; //remove hover color
		}
		
		$tile_html = <<<htmleoq
		
		<div class="$dcell $optclass" style="background-color: #FFF;">
				<div class="co_tile $statusclass">
					$imglnk
					<div class="coimginfo">
						<ul>
							$coimginfo_html
						</ul>
					</div>
				</div>
						$flag_html
		</div>
		
htmleoq;

	   	return $tile_html;
	}

	function create_slide($cid,$mid,$loc,$text='View context',$useimage=false)
	{
			$name = $this->object->coobject->material_filename($mid);
			$path = $this->object->coobject->material_path($cid, $mid);
      
      $imgurl = '';
      $img_found = false;
	   	      
      $image_details = $this->_get_imgurl($path, $name, 'slide', $loc);
      // TODO: see if we can simply check for the 'img_found' array value
      if(array_key_exists('imgurl', $image_details)) {
        $imgurl = $image_details['imgurl'];
        $img_found = $image_details['imgurl'];
      }
	   	$img = '<small style="clear:both">'.$text.'</small><br>'.'<img src="'.$imgurl.'" width="150" height="150" />';
	   	$aurl = ($useimage) 
						?'<a href="'.$imgurl.'" class="smoothbox" title="" rel="gallery-slide">'.$img.'</a>'
						:'<a href="'.$imgurl.'" class="smoothbox" title="" rel="gallery-slide">'.$text.'</a>';

	   	return ($img_found) ? $aurl : '<span class="spanbutton">&nbsp;No context&nbsp;&nbsp;</span>';
	}

	function remove_dir($dir)
  {
        if(is_dir($dir))
        {
            $dir = (substr($dir, -1) != "/")? $dir."/":$dir;
            $openDir = opendir($dir);
            while($file = readdir($openDir))
            {
                if(!in_array($file, array(".", "..")))
                {
                    if(!is_dir($dir.$file))
                        @unlink($dir.$file);
                    else
                        $this->remove_dir($dir.$file);
                }
            }
            closedir($openDir);
            @rmdir($dir);
        }
   }

    /**
     * Escape url 
     *
     * @access  public
     * @param   string  the url to be escaped
     * @return  string
     */
    function escapeUrl($url)
    {
        return rawurlencode($url);
    }


    function xmp_data($filename)
    {
      if (preg_match('/\.jpe?g$/i',$filename)) {
          include_once 'metadata.class.php';
          $mdata = new ImageMetadata($filename);
          return $mdata->get_ocw_array();
      }

      return false;
    }

  /**
    * Unzip the source_file in the destination dir
    *
    * @param   string      The path to the ZIP-file.
    * @param   string      The path where the zipfile should be unpacked, if false the directory of the zip-file is used
    * @param   boolean     Indicates if the files will be unpacked in a directory with the name of the zip-file (true) or not (false) (only if the destination directory is set to false!)
    * @param   boolean     Overwrite existing files (true) or not (false)
    *
    * @return  boolean     Succesful or not
    */
    function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true)
  {
    $zip_files = array();
    if(function_exists("zip_open")) {  
        if(!is_resource(zip_open($src_file))) {
            $src_file=dirname($_SERVER['SCRIPT_FILENAME'])."/".$src_file;
        }
     
        if (is_resource($zip = zip_open($src_file))) {         
            $splitter = ($create_zip_name_dir === true) ? "." : "/";
            if ($dest_dir === false) $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
        
            // Create the directories to the destination dir if they don't already exist
            $this->create_dirs($dest_dir);

            // For every file in the zip-packet
            while ($zip_entry = zip_read($zip)) {
              // Now we're going to create the directories in the destination directories
          
              // If the file is not in the root dir
              $pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
              if ($pos_last_slash !== false) {
                // Create the directory where the zip-entry should be saved (with a "/" at the end)
                $this->create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
              }

              // Open the entry
              if (zip_entry_open($zip,$zip_entry,"r")) {
                // The name of the file to save on the disk
                $file_name = $dest_dir.zip_entry_name($zip_entry);
            
                // Check if the files should be overwritten or not
                if ($overwrite === true || $overwrite === false && !is_file($file_name)) {
                  // Get the content of the zip entry
                  $fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));          
               
                  if(!is_dir($file_name)) {           
                    file_put_contents($file_name, $fstream );
                  }

                  // Set the rights
                  if(file_exists($file_name)) {
										 if (!preg_match('/^\./',basename($file_name),$match)) {
                      	chmod($file_name, 0777);
                      	array_push($zip_files, $file_name);
										 } 
                      #echo "<span style=\"color:#1da319;\">file saved: </span>".$file_name."<br />";
                  } else {
                      #echo "<span style=\"color:red;\">file not found: </span>".$file_name."<br />";
                  }
                }
            
                // Close the entry
                zip_entry_close($zip_entry);
              }     
            }
            // Close the zip-file
            zip_close($zip);
        } else {
          #echo "No Zip Archive Found.";
          return false;
        }
    
        return (sizeof($zip_files) > 0) ? $zip_files : false;

    } else {
        if(version_compare(phpversion(), "5.2.0", "<"))
        $infoVersion="(use PHP 5.2.0 or later)";
        #echo "You need to install/enable the php_zip.dll extension $infoVersion";
    }
  }

  function create_dirs($path)
  {
    if (!is_dir($path)) {
      $directory_path = "";
      $directories = explode("/",$path);
      array_pop($directories);
  
      foreach($directories as $directory) {
        $directory_path .= $directory."/";
        if (!is_dir($directory_path)) {
          mkdir($directory_path);
          chmod($directory_path, 0777);
        }
      }
    }
  }
  
  
  // TODO: consider making this a database agnostic time comparison
  /**
    * Calculate and return the later mysql timestamp as a human readable 
    * time value with the option to specify the format of the returned 
    * string
    *
    * @param    string mysql_time_1 the first mysql timestamp
    * @param    string mysql_time_2 the second mysql timestamp
    * @param    string date_format (optional) the format for the time using
    *           the php "date" function formatting parameters
    * @return   string human readable date string
    */
  public function calc_later_date($mysql_time_1, $mysql_time_2, 
    $date_format='m-d-Y H:i:s')
  {
    $unix_fmt_time_1 = mysql_to_unix($mysql_time_1);
    $unix_fmt_time_2 = mysql_to_unix($mysql_time_2);
    if ($unix_fmt_time_2 > $unix_fmt_time_1) {
      $mat_date = date($date_format, $unix_fmt_time_2);
    } else {
      $mat_date = date($date_format, $unix_fmt_time_1);
    }
    return $mat_date;
  }
  
  
  /**
    * Get the current timestamp in a format suitable for the MySQL
    * TIMESTAMP or DATETIME field
    * lifted from http://snippets.dzone.com/posts/show/1455
    *
    * @return   string a timestamp that can be stored in MySQL
    */
  public function get_curr_mysql_time() {
    $curr_unix_time = time();
    return gmdate("Y-m-d H:i:s", $curr_unix_time);
  }
  
  
  /**
    * Get the image url and indicate if thumbnail is found
    *
    * @param    string path of the file
    * @param    string name to the file
    * @param    string (optional) file pre-suffix, e.g. "grab" if it is
    *           a screen grab
    * @param    int (optional) location (page or slide number) of the image
    * @return   array:
    *             'img_found' boolean, set true if we match
    *             'imgurl' string url to image
    */
  private function _get_imgurl($path, $name, $pre_ext = '', $location = NULL) {
      $base_url = property('app_uploads_url') . $path . "/";
    $base_path = property('app_uploads_path') . $path . "/";
    
    $file_details;      
    // TODO: should we check to make sure we've been passed an int?
    if ($location) {
      $base_path .= "{$name}_" . $pre_ext . "_" . $location;
      $base_url .= "{$name}_" . $pre_ext . "_" . $location;
    } else {
      $base_path .= $name . "_" . $pre_ext;
      $base_url .= $name . "_" . $pre_ext;
    }
    
		// Prepare for failure
		$file_details['imgurl'] = '';
		$file_details['imgpath'] = '';
		$file_details['img_found'] = false;
		$file_details['thumburl'] = '';
		$file_details['thumbpath'] = '';
		$file_details['thumb_found'] = false;

		// Search for lower-case first, then upper-case.
		// These are also listed in order of likely-hood
		$supported_exts = array (".png", ".jpg", ".gif", ".tiff", ".svg",
		                         ".PNG", ".JPG", ".GIF", ".TIFF", ".SVG");

		foreach ($supported_exts as $ext) {
			$path = $base_path . $ext;
			if (is_readable($path)) {
				$file_details['imgpath'] = $path;
				$file_details['imgurl'] = $base_url . $ext;
				$file_details['img_found'] = true;

				// See if there is a corresponding thumbnail file
				// If none is found, try to create one and look again
				// XXX Make this a function and simplify this???
				$tried_create = 0;
				while ($pre_ext != "slide" && $file_details['thumb_found'] == false && $tried_create <= 1 ) {
					$thumb_extensions = array(".png", $ext);
					foreach ($thumb_extensions as $te) {
						$thumbpath = $base_path . "_thumb" . $te;
						if (is_readable($thumbpath)) {
							$file_details['thumbpath'] = $thumbpath;
							$file_details['thumburl'] = $base_url . "_thumb" . $te;
							$file_details['thumb_found'] = true;
							break;
						}
					}
					// If no thumbnail found, try to create one
					if ($file_details['thumb_found'] == false && $tried_create == 0) {
						$this->create_thumbnail($path);
					}
					$tried_create++;
				}
				break;
			}
		}

		if ($pre_ext != "slide" && $file_details['thumb_found'] == false)
			$this->log_to_apache('debug', __FUNCTION__.": Failed to create a thumbnail for {$path}!");
   	return $file_details;
  }

	/**
		* Convert string level value to numeric value
		* @access private
		* @param string level
		*/
	private function _level_to_numeral($level)
	{
		switch ($level) {
			case 'debug':	    // Most likely case
				return 1;
			case 'info':
				return 2;
			case 'warn':
				return 3;
			case 'error':
				return 4;
			default:
				return FALSE;
		}
	}

	/**
		* Log a message to the apache error log
		* based on the logging level selected
		*
		* If no config option is set, then only 'error'
		* messages are logged.  Otherwise, if the message
		* is at or 'above' the level of the config option,
		* then it is logged.
		*
		* @access public
		* @param string level - message level indicator (i.e. 'error', 'warn', 'info', 'debug')
		* @param string message - message to be logged
		*/
	public function log_to_apache($level, $message)
	{
		static $config_level = -1;

		// Attempt to only set this once
		if ($config_level == -1) {
		    $config_level = $this->_level_to_numeral($this->object->config->item('log_to_apache'));
		    if ($config_level === FALSE)
			$config_level = $this->_level_to_numeral('error');
		}
		$user_level = $this->_level_to_numeral($level);
		
		if ($user_level < $config_level)
				return;
		$now = date("D M j G:i:s Y");
		$message = "[" . $now . "] [" . $level . "] " . $message . "\n";
		$stderr = @fopen('php://stderr', 'w');
		fwrite($stderr, $message);
		fclose($stderr);
	}

	/**
	 * Get File Extension by Mimetype
	 *
	 * Translates a mime type into a file extension based on config/mimes.php. 
	 * Returns FALSE if it can't determine the extension or open the mime config file
	 *
	 * Note: This is a complementary function to get_mime_by_extension() provided by CodeIgniter
	 *
	 * @access	public
	 * @param	string	mimetype
	 * @return	mixed
	 */	
	public function get_extension_by_mime($mimetype)
	{
		global $mimes;

		if ( ! $mimetype || $mimetype == '') {
			return FALSE;
		}

		// Try a short-cut of comparing some common values first
		$ext = FALSE;
		switch (strtolower($mimetype)) {
			case 'image/pjpeg':
			case 'image/jpeg':
				$ext= 'jpg';
				break;
			case 'image/png':
				$ext= 'png';
				break;
			case 'image/gif':
				$ext= 'gif';
				break;
			case 'image/tiff':
				$ext= 'tiff';
				break;
			case 'image/svg+xml':
				$ext='svg';
				break;
    }
	  if ($ext) {
			//$this->log_to_apache('debug', "get_extension_by_mime: returning early with " . $ext);
			return $ext;
		}

		// Otherwise, do it the long way, looking "backward" through the mimes array
		if ( ! is_array($mimes))
		{
			if ( ! require_once(APPPATH . 'config/mimes.php'))
			{
				return FALSE;
			}
		}

		//$this->log_to_apache('debug', "\n\nget_extension_by_mime: the list:\n");
		foreach ($mimes as $x => $mt) {
			//$this->log_to_apache('debug', "\t\t $x \t\t $mt");
			if (is_array($mt)) {
				foreach ($mt as $xx => $mmtt) {
					//$this->log_to_apache('debug', "Comparing given mimetype'" . $mimetype . "' with '" . $mmtt ."'");
					if ($mimetype == $mmtt) {
						//$this->log_to_apache('debug', "get_extension_by_mime: extension is " . $x);
						return $x;
					}
				}
			}
			//$this->log_to_apache('debug', "Comparing given mimetype'" . $mimetype . "' with '" . $mt ."'");
			if ($mimetype == $mt) {
				//$this->log_to_apache('debug', "get_extension_by_mime: extension is " . $x);
				return $x;
			}
		}
		//$this->log_to_apache('debug', "get_extension_by_mime: NO MATCH");
		return FALSE;
	}

	/**
	 * Convert numeric string value from php.ini,
	 * possibly containing some scale factors as K, M, and G.
	 * Example taken from the PHP manual.
	 *
	 * @access  private
	 * @param string  php.ini size value
	 * @return  int
	 */
	private function _string_to_int($s)
	{
		$v = (int) $s;
		$last = strtolower($s[strlen($s)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g': $v *= 1024; /*. missing_break; .*/
			case 'm': $v *= 1024; /*. missing_break; .*/
			case 'k': $v *= 1024; /*. missing_break; .*/
			/*. missing_default: .*/
		}
		return $v;
	}

	/**
	 * Return human readable sizes
	 *
	 * @author      Aidan Lister <aidan@php.net>
	 * @version     1.2.0
	 * @link        http://aidanlister.com/repos/v/function.size_readable.php
	 * @param       int     $size        size in bytes
	 * @param       string  $max         maximum unit
	 * @param       bool    $si          use SI (1000) prefixes
	 * @param       string  $retstring   return string format
	 */
	public function size_readable($size, $max = null, $si = true, $retstring = '%01.2f %s')
	{
			// Pick units
			if ($si === true) {
					$sizes = array('B', 'K', 'MB', 'GB', 'TB', 'PB');
					$mod   = 1000;
			} else {
					$sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
					$mod   = 1024;
			}
	 
			// Max unit to display
			if ($max && false !== $d = array_search($max, $sizes)) {
					$depth = $d;
			} else {
					$depth = count($sizes) - 1;
			}
	 
			// Loop
			$i = 0;
			while ($size >= 1024 && $i < $depth) {
					$size /= $mod;
					$i++;
			}
	 
			return sprintf($retstring, $size, $sizes[$i]);
	}

	/**
	 * Examine maximum values from php.ini and determine
	 * the maximum upload size allowed.  Returns value
	 * in string, or numeric mode.
	 *
	 * @access  public
	 * @param  string 'num' to get numeric value, else returns string representation
	 * @return  int or string
	 */
	public function max_upload_size($type='string')
	{
		$upload_max = $this->_string_to_int(trim(ini_get("upload_max_filesize")));
		$post_max = $this->_string_to_int(trim(ini_get("post_max_size")));
		$max_allowed = min($upload_max, $post_max);
		if ($type == 'num') {
			return $max_allowed;
		} else {
			return $this->size_readable($max_allowed, null, false);
		}
	}

	/**
	 * Examine maximum values from php.ini and determine
	 * whether the maximum allowed has been exceeded.
	 *
	 * @access  public
	 * @return  boolean
	 */
	public function is_invalid_upload_size()
	{
		$max_allowed = $this->max_upload_size('num');

		if ($_SERVER['CONTENT_LENGTH'] > $max_allowed) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Given the full path names for an original file
	 * and a symlink, create the symlink "locally".
	 * i.e instead of creating a symlink like:
	 *   /fee/fie/foe/linktofum --> /fee/fie/foe/fum
	 * it is created locally so that:
	 *   /fee/fie/foe/linktofum --> fum
	 * Obviously, the original file and the link
	 * must be in the same directory.  Otherwise,
	 * the "non-local" symlink is created.
	 *
	 * @access private
	 * @param string original - full path of original file
	 * @param string linkfile - full path of symlink (to original file)
	 * @return boolean
	 */

	private function _local_symlink($original, $linkfile)
	{
		$o = pathinfo($original);
		$s = pathinfo($linkfile);

		if ($o['dirname'] == $s['dirname']) {
				$savedir = getcwd();
				chdir($o['dirname']);
				$retval = @symlink($o['basename'], $s['basename']);
				chdir($savedir);
				return $retval;
		} else {
				return @symlink($original, $linkfile);
		}
	}

	/**
	 * Calculate new image dimensions to new constraints
	 *
	 * @param Original X size in pixels
	 * @param Original Y size in pixels
	 * @return New X maximum size in pixels
	 * @return New Y maximum size in pixels
	 */
	function _scale_image($x, $y, $cx, $cy) {
		//Set the default NEW values to be the old, in case it doesn't even need scaling
		list($nx, $ny) = array($x, $y);
   
		//If image is generally smaller, don't even bother
		if ($x >= $cx || $y >= $cx) {
           
			//Work out ratios
			if ($x > 0) $rx = $cx / $x;
			if ($y > 0) $ry = $cy / $y;
       
			//Use the lowest ratio, to ensure we don't go over the wanted image size
			if ($rx > $ry) {
				$r = $ry;
			} else {
				$r = $rx;
			}
       
			//Calculate the new size based on the chosen ratio
			$nx = intval($x * $r);
			$ny = intval($y * $r);
		}   
   
		//Return the results
		return array($nx,$ny);
	}

	/**
	 * Create a thumbnail image for an existing full-size image.
	 * If the imagick php extension is available, use it to create
	 * the thumbnail.  Otherwise, we assume that ImageMagick is
	 * available and exec() the "convert" program.
	 *
	 * @access  public
	 * @param  string original - full path of original image name
	 * @param  integer maxWidth - optional width to make the thumbnail
	 * @param  integer maxHeight - optional height to make the thumbnail
	 * @return  boolean
	 */
	public function create_thumbnail($original, $maxWidth='300', $maxHeight='300')
	{
		$supported_exts = array ("png", "jpg", "gif", "tiff"); # ImageMagick has trouble with ".svn" files
		$default_thumb_extension = "png";

		if (!is_file($original))
			return FALSE;

		$pinfo = pathinfo($original);

		$linkfile = "{$pinfo['dirname']}/{$pinfo['filename']}_thumb.{$pinfo['extension']}";
		$thumbnail = "{$pinfo['dirname']}/{$pinfo['filename']}_thumb.{$default_thumb_extension}";

		// Just create symlinks for files that ImageMagick has trouble with
		if (in_array(strtolower($pinfo['extension']), $supported_exts) === FALSE) {
			return $this->_local_symlink($original, $linkfile);
		}

		if (extension_loaded('imagick')) {
			try {
				$im = new Imagick();
				if ($im == NULL) {
					$this->log_to_apache('error', __FUNCTION__.
							": error creating instance of Imagick.");
					return FALSE;
				}

				if ($im->readImage($original) !== TRUE) {
					$this->log_to_apache('error', __FUNCTION__.
						": error reading original image, '{$original}'");
					return FALSE;
				}

				// Assure that the current aspect ratio is maintained
				$origWidth = $im->getImageWidth();
				$origHeight = $im->getImageHeight();
				list($width, $height) = $this->_scale_image($origWidth, $origHeight,
																										$maxWidth, $maxHeight);

				if ($im->thumbnailImage($width, $height) !== TRUE) {
					$this->log_to_apache('error', __FUNCTION__.
							": error converting original image, '{$original}'");
					$im->destroy();
					return FALSE;
				}

				if ($im->writeImage($thumbnail) !== TRUE) {
					$this->log_to_apache('error', __FUNCTION__.
						": error writing thumbnail image, '{$thumbnail}'");
					$im->destroy();
					return FALSE;
				}

				$im->clear();
				$im->destroy();

			} catch (Exception $e) {
				$this->log_to_apache('error', __FUNCTION__.
						": Exception, '" . $e->getMessage() . "', creating a symlink.");
				if ($im != NULL)
					$im->destroy();
				return $this->_local_symlink($original, $linkfile);
			}
		} else {
			$convert_pgm = property('app_convert_pgm_path');
			$convert_out = array();
			$convert_cmd = "{$convert_pgm} {$original} -thumbnail {$maxWidth}x{$maxHeight} {$thumbnail}";
			exec($convert_cmd, &$convert_out, &$convert_code);

			if ($convert_code != 0 || !file_exists($thumbnail)) {
				return $this->_local_symlink($original, $linkfile);
			}
		}

		// A thumbnail was successfully created.  If it isn't a space savings
		// of at least half, throw it away and use a symlink instead
		$orig_size = filesize($original);
		$thumb_size = filesize($thumbnail);

		if ($thumb_size > ($orig_size / 2)) {
			@unlink($thumbnail);
			return $this->_local_symlink($original, $linkfile);
		}
		return TRUE;
	}

}

?>
