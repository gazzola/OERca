<?php
include_once 'JPEG.php';  
include_once 'XMP.php';  

class ImageMetadata
{
  var $filename;
 
  function __construct($filename='') { $this->filename = $filename; }
  function set_filename($filename) { $this->filename = $filename; }
  function get_filename() { return $this->filename; }

  function get_ocw_array($filename='')
  {
    $results = array();

    $filename = ($filename=='') ? (($this->filename=='') ? '' : $this->filename) : $filename;

    if ($filename <> '') {
        $jpeg_header_data = get_jpeg_header_data( $filename );
        $results = Get_OCW_from_XMP(read_XMP_array_from_text( get_XMP_text( $jpeg_header_data ) )); 

    } else {
        $results =  array('error'=>'No filename provided');
    }
  
    return $results;
  }

  function get_ocw_html($filename='')
  {
    $results = ''; 

    $filename = ($filename=='') ? (($this->filename=='') ? '' : $this->filename) : $filename;

    if ($filename <> '') {
        $jpeg_header_data = get_jpeg_header_data( $filename );
        $results = Interpret_XMP_to_HTML(read_XMP_array_from_text(get_XMP_text( $jpeg_header_data ))); 

    } else {
        $results =  'Error: No filename provided';
    }
  
    return $results;
  }
}
?>
