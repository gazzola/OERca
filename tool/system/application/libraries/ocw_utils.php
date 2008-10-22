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
									  $objs[$i]['id'], $objs[$i]['location'],$filter,'repl',true,false,false,false,$class);
		  			$list .= "\n$y\n";
				}

				$list .= $break;

				if ($count==$size) {  $list .= "\n</div>"; }
			  $count++;
		} 
	
		return $list;
	}
	
	function create_co_img($cid, $mid, $oid, $loc, $filter,$type='orig', $shrink=true, $show_ctx=true, $show_edit=false, $showinfo=true,$optclass='') 
	{
		 	$name = $this->object->coobject->object_filename($oid);
		 	$path = $this->object->coobject->object_path($cid, $mid,$oid);
			$defimg = ($type=='orig') ? 'noorig.png' : 'norep.png';
			$dflag = ($type=='orig') ? 'grab' : 'rep';
      $image_details = $this->_get_imgurl($path, $name, $dflag);
      $imgurl = $image_details['imgurl'];
      $thumb_found = $image_details['thumb_found'];
      
	   	$imgurl = ($thumb_found) ? $imgurl : property('app_img').'/'.$defimg;
	   	$editurl = site_url("materials/object_info/$cid/$mid/$oid/$filter").
								 '?TB_iframe=true&height=630&width=800';

		  $locbar = '<p id="ciloc">'.$loc.'</p>';
			$size = ($shrink) ? 'width:150px; height:150px;':'width:300px; height:300px;';
			$title = 'Content Object :: Location: Page '.$loc;
			$slide=($show_ctx) ? '<p id="cislide">'.($this->create_slide($cid, $mid, $loc)).'</p>' : '';
			$magnify = '<p id="cimagnify"><a href="'.$imgurl.'" class="smoothbox" rel="gallery-cos">'.
	   				 		 '<img title="'.$title.'" src="'.property('app_img').'/search_16.gif" /></a></p>';

		  $editlnk=($show_edit) 
							? '<p id="ciedit"><a href="'.$editurl.'">'.
	   				 		 '<img title="'.$title.'" src="'.property('app_img').'/edit_16.gif" /></a></p>' 
						  :'';
						  /*
						  		  $editlnk=($show_edit) 
							? '<p id="ciedit">'.
								(anchor($editurl, 'Edit', array('class'=>'smoothbox','id'=>'edit-'.$oid))).'</p>' 
						  :'';
						  */
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

	   	return '<div class="'.$dcell.' '.$optclass.'"><span class="status_flag '.$statusclass.'"></span><div class="co_tile '.$statusclass.'">'.$imglnk.
						 '<div class="coimginfo">'.(($showinfo) ? $locbar.$slide.$editlnk.$magnify:
																									  '<p>&nbsp;</p>').'</div></div></div>';
	}

	function create_slide($cid,$mid,$loc,$text='View context',$useimage=false)
	{
			$name = $this->object->coobject->material_filename($mid);
			$path = $this->object->coobject->material_path($cid, $mid);
      
      $imgurl = '';
      $thumb_found = false;
	   	      
      $image_details = $this->_get_imgurl($path, $name, 'slide', $loc);
      // TODO: see if we can simply check for the 'thumb_found' array value
      if(array_key_exists('imgurl', $image_details)) {
        $imgurl = $image_details['imgurl'];
        $thumb_found = $image_details['imgurl'];
      }
	   	$img = '<small style="clear:both">'.$text.'</small><br>'.'<img src="'.$imgurl.'" width="150" height="150" />';
	   	$aurl = ($useimage) 
						?'<a href="'.$imgurl.'" class="smoothbox" title="" rel="gallery-slide">'.$img.'</a>'
						:'<a href="'.$imgurl.'" class="smoothbox" title="" rel="gallery-slide">'.$text.'</a>';

	   	return ($thumb_found) ? $aurl : '<span class="spanbutton">&nbsp;No context&nbsp;&nbsp;</span>';
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
    *             'thumb_found' boolean, set true if we match
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
    
    $p_imgurl = $base_url . ".png";
   	$p_imgurl_upper = $base_url . ".PNG";
   	$p_imgpath = $base_path . ".png";
   	$p_imgpath_upper = $base_path . ".PNG";
   	$j_imgurl = $base_url . ".jpg";
   	$j_imgurl_upper = $base_url . ".JPG";
   	$j_imgpath = $base_path . ".jpg";
   	$j_imgpath_upper = $base_path . ".JPG";
   	$g_imgurl = $base_url . ".gif";
   	$g_imgurl_upper = $base_url . ".GIF";
   	$g_imgpath = $base_path . ".gif";
   	$g_imgpath_upper = $base_path . ".GIF";
   	$t_imgurl = $base_url . ".tiff";
   	$t_imgurl_upper = $base_url . ".TIFF";
   	$t_imgpath = $base_path . ".tiff";
   	$t_imgpath_upper = $base_path . ".TIFF";
   	$s_imgurl = $base_url . ".svg";
   	$s_imgurl_upper = $base_url . ".SVG";
   	$s_imgpath = $base_path . ".svg";
   	$s_imgpath_upper = $base_path . ".SVG";
   	
   	if (is_readable($p_imgpath)) {
   	  $file_details['imgurl'] = $p_imgurl;
   	  $file_details['thumb_found'] = true;
   	} elseif (is_readable($j_imgpath)) {
   	  $file_details['imgurl'] = $j_imgurl;
   	  $file_details['thumb_found'] = true;
   	} elseif (is_readable($g_imgpath)) {
   	  $file_details['imgurl'] = $g_imgurl;
   	  $file_details['thumb_found'] = true;
   	} elseif (is_readable($t_imgpath)) {
   	  $file_details['imgurl'] = $t_imgurl;
   	  $file_details['thumb_found'] = true;
   	} elseif (is_readable($s_imgpath)) {
   	  $file_details['imgurl'] = $s_imgurl;
   	  $file_details['thumb_found'] = true;
   	}  elseif (is_readable($p_imgpath_upper)) {
   	  $file_details['imgurl'] = $p_imgurl_upper;
   	  $file_details['thumb_found'] = true;
   	} elseif (is_readable($j_imgpath_upper)) {
   	  $file_details['imgurl'] = $j_imgurl_upper;
   	  $file_details['thumb_found'] = true;
   	} elseif (is_readable($g_imgpath_upper)) {
			$file_details['imgurl'] = $g_imgurl_upper;
			$file_details['thumb_found'] = true;
   	} elseif (is_readable($t_imgpath_upper)) {
			$file_details['imgurl'] = $t_imgurl_upper;
			$file_details['thumb_found'] = true;
   	} elseif (is_readable($s_imgpath_upper)) {
			$file_details['imgurl'] = $s_imgurl_upper;
			$file_details['thumb_found'] = true;
   	} else {
			$file_details['imgurl'] = '';
			$file_details['thumb_found'] = false;
   	}
   	return $file_details;
  }

	/**
		* Log a message to the apache error log
		*
		* @access public
		* @param string level - message level indicator (i.e. 'error', 'warn', 'info', 'debug')
		* @param string message - message to be logged
		*/
	public function log_to_apache($level, $message)
	{
		$now = date("D M j G:i:s Y");
		$message = "[" . $now . "] [" . $level . "] " . $message . "\n";
		$stderr = @fopen('php://stderr', 'w');
		fwrite($stderr, $message);
		fclose($stderr);
	}

}

?>