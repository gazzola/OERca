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

	function create_co_list($cid,$mid,$objs)
	{
    $list = '';
		$size = sizeof($objs);
	
		for($i = 0; $i < $size; $i++) {
			$y = $this->create_co_img($cid, $mid, 
									  $objs[$i]['id'],
									  $objs[$i]['location'],false);
		  $list .= '<span id="objspan_'.$objs[$i]['id'].'"><li class="car-li" id="carousel-item-'.$i.'">'.($i+1).'&nbsp;'.$y.'</li></span>';
		} 
	
		return $list;
	}
	
	function create_co_img($cid, $mid, $oid, $loc, $linkable=true, $shrink=true, $show_ctx=true) 
	{
		 	$name = $this->object->coobject->object_filename($oid);
		 	$path = $this->object->coobject->object_path($cid, $mid,$oid);

	   	$p_imgurl = property('app_uploads_url').$path.'/'.$name.'_grab.png';
	   	$p_imgpath = property('app_uploads_path').$path.'/'.$name.'_grab.png';
	   	$j_imgurl = property('app_uploads_url').$path.'/'.$name.'_grab.jpg';
	   	$j_imgpath = property('app_uploads_path').$path.'/'.$name.'_grab.jpg';
	   	$g_imgurl = property('app_uploads_url').$path.'/'.$name.'_grab.gif';
	   	$g_imgpath = property('app_uploads_path').$path.'/'.$name.'_grab.gif';
	   	$imgurl = '';

	   	if (is_readable($p_imgpath) || is_readable($j_imgpath) || is_readable($g_imgpath)) {
				 	$thumb_found = true;	
				 	$imgurl = (is_readable($p_imgpath)) ? $p_imgurl : ((is_readable($j_imgpath)) ? $j_imgurl : $g_imgurl);
	   	} else {
					$thumb_found = false;	
	   	}

	   	$imgUrl = ($thumb_found) ? $imgurl : property('app_img').'/noorig.png';

	   	$aurl = '<a href="'.site_url("materials/object_info/$cid/$mid/$oid").'?TB_iframe=true&height=500&width=520" class="smoothbox">';

			$size = ($shrink) ? 'width="85" height="85"':'width="300" height="300"';
			$title = 'title="Content Object :: Location: Page '.$loc.'<br>Click image to edit"';
			$slide = ($show_ctx) ? $this->create_slide($cid, $mid, $loc) : '';

	   	return ($linkable) 
					? $aurl.'<img id="object-'.$oid.'" class="carousel-image tooltip" '.$title.' src="'.$imgUrl.'" '. $size .'"/></a>'.$slide
					: '<img id="object-'.$oid.'" class="carousel-image tooltip" '.$title.' src="'.$imgUrl.'" '.$size.' />'.$slide;
	}

	function create_corep_img($cid, $mid, $oid, $loc, $linkable=true, $shrink=true) 
	{
		 	$name = $this->object->coobject->object_filename($oid);
		 	$path = $this->object->coobject->object_path($cid, $mid,$oid);

	   	$p_imgurl = property('app_uploads_url').$path.'/'.$name.'_rep.png';
	   	$p_imgpath = property('app_uploads_path').$path.'/'.$name.'_rep.png';
	   	$j_imgurl = property('app_uploads_url').$path.'/'.$name.'_rep.jpg';
	   	$j_imgpath = property('app_uploads_path').$path.'/'.$name.'_rep.jpg';
	   	$g_imgurl = property('app_uploads_url').$path.'/'.$name.'_rep.gif';
	   	$g_imgpath = property('app_uploads_path').$path.'/'.$name.'_rep.gif';
	   	$imgurl = '';

	   	if (is_readable($p_imgpath) || is_readable($j_imgpath) || is_readable($g_imgpath)) {
					$thumb_found = true;	
				 	$imgurl = (is_readable($p_imgpath)) ? $p_imgurl : ((is_readable($j_imgpath)) ? $j_imgurl : $g_imgurl);
	   	} else {
					$thumb_found = false;	
					$name = "none";
	   	}

	   	$imgUrl = ($thumb_found) ? $imgurl : property('app_img').'/norep.png';

	   	$aurl = '<a href="'.site_url("materials/object_info/$cid/$mid/$oid").'?TB_iframe=true&height=500&width=520" class="smoothbox">';

			$size = ($shrink) ? 'width="85" height="85"':'width="300" height="300"';
			$title = 'title="Content Object :: Location: Page '.$loc.'<br>Click image to edit"';

	   	return ($linkable) 
					? $aurl.'<img id="object-'.$oid.'" class="carousel-image tooltip" '.$title.' src="'.$imgUrl.'" '.$size.'/></a>'.
						$this->create_slide($cid, $mid, $loc)
					: '<img id="object-'.$oid.'" class="carousel-image" '.$title.' src="'.$imgUrl.'" '.$size.'/>';
	}

	function create_slide($cid,$mid,$loc,$text='view context',$useimage=false)
	{
			$name = $this->object->coobject->material_filename($mid);
			$path = $this->object->coobject->material_path($cid, $mid);

	   	$p_imgurl = property('app_uploads_url')."$path/{$name}_slide_$loc.png";
	   	$p_imgpath = property('app_uploads_path')."$path/{$name}_slide_$loc.png";
	   	$j_imgurl = property('app_uploads_url')."$path/{$name}_slide_$loc.jpg";
	   	$j_imgpath = property('app_uploads_path')."$path/{$name}_slide_$loc.jpg";
	   	$g_imgurl = property('app_uploads_url')."$path/{$name}_slide_$loc.gif";
	   	$g_imgpath = property('app_uploads_path')."$path/{$name}_slide_$loc.gif";
	   	$imgurl = '';
	
	   	if (is_readable($p_imgpath) || is_readable($j_imgpath) || is_readable($g_imgpath)) {
					$thumb_found = true;	
				 	$imgurl = (is_readable($p_imgpath)) ? $p_imgurl : ((is_readable($j_imgpath)) ? $j_imgurl : $g_imgurl);
	   	} else {
					$thumb_found = false;	
	   	}

	   	$img = '<small style="clear:both">'.$text.'</small><br>'.'<img src="'.$imgurl.'" width="150" height="150" />';
	   	$aurl = ($useimage) 
						?'<a href="'.$imgurl.'" class="smoothbox" title="" rel="gallery-slide">'.$img.'</a>'
						:'<a href="'.$imgurl.'" class="smoothbox" title="" rel="gallery-slide">'.$text.'</a>';

	   	return '<span style="clear:both"><br/>'.(($thumb_found) ? $aurl : '<small>no context view found</small>').'</span>';
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
    *  @return  boolean     Succesful or not
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
										 if (!preg_match('/^\./',$file_name,$match)) {
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
}
?>
