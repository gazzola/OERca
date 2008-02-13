<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * OER_progbar Class
 *
 * @package		OER Tool
 * @subpackage	Libraries
 * @category	 Generation
 * @author	Ali Asad Lotia <lotia@umich.edu>
 */
class OER_progbar {
  
  // set default length and height (pixels)
  private $width = 300;
  private $height = 120;
  
  // the image variable
  private $im;
  
  // the colors values of the bars
  private $white = array(255, 255, 255);
  private $green = array(68, 146, 34);
  private $yellow = array(241, 191, 36);
  private $red = array(224, 41, 29);
  private $black = array(0, 0, 0);
  
  public function __construct()
  {
    $this->object =& get_instance();
    log_message('debug', "OER_progbar Class Initialized");
    return($this->object);
  }

  public function set_width($width)
  {
    $this->width = $width;
  }

  public function get_width()
  {
    return($this->width);
  }

  public function set_length($length)
  {
    $this->length = $length;
  }

  public function get_length()
  {
    return($this->length);
  }
  
  public function build_prog_bar($totalObjects, $doneObjects, $askObjects,
    $remObjects)
  {
    // create the canvas and allocate the colors
    $this->im = imagecreatetruecolor(($this->width + 3), ($this->height + 3));
    $bgColor = imagecolorallocate($this->im, $this->white[0], 
      $this->white[1], $this->white[2]);
    $doneColor = imagecolorallocate($this->im, $this->green[0], 
      $this->green[1], $this->green[2]);
    $askColor = imagecolorallocate($this->im, $this->yellow[0], 
      $this->yellow[1], $this->yellow[2]);
    $remColor = imagecolorallocate($this->im, $this->red[0],
      $this->red[1], $this->red[2]);
    $textColor = imagecolorallocate($this->im, $this->black[0],
      $this->black[1], $this->black[2]);
    
    // build the bars
    imagefilledrectangle($this->im, 
      2, 
      2, 
      $this->width, 
      $this->height, 
      $bgColor);
    imagefilledrectangle($this->im, 
      2, 
      2, 
      ($this->_set_bar_width($totalObjects, $doneObjects)), 
      ($this->height / 3), 
      $doneColor);
    imagefilledrectangle($this->im, 
      2,
      (($this->height / 3) + 1), 
      ($this->_set_bar_width($totalObjects, $askObjects)), 
      (2 * ($this->height / 3)), 
      $askColor);
    imagefilledrectangle($this->im,
      2,
      ((2 * ($this->height / 3)) + 1),
      ($this->_set_bar_width($totalObjects, $remObjects)),
      $this->height,
      $remColor);
    return ($this->im);
  }
  
  public function get_prog_bar()
  {
    imagepng($this->im);
    imagedestroy($this->im);
  }
  
  private function _set_bar_width($totalObjects, $numObjects)
  {
    return(round($this->width * ($numObjects / $totalObjects)));
  }
  
}
?>