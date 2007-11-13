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
        $this->object->load->model('ocw_user','u');
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
	function dump($var)
	{
		print '<pre>'; print_r($var); print '</pre>';
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
	
	/**
	 * Escape url 
	 *
	 * @access	public
	 * @param	string	the url to be escaped
	 * @return	string
	 */
	function escapeUrl($url)
	{
	    return rawurlencode($url);
	}
}
?>
