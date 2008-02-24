<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * OER_progbar Class
 *
 * @package		OER Tool
 * @subpackage	Libraries
 * @category	 Generation
 * @author	Ali Asad Lotia <lotia@umich.edu>
 */
 
/**
 * TODO: Possibly change this class to output svg instead of a png.
 */ 
class OER_progbar {
  
  // set default length and height (pixels)
  private $width = 600;
  private $height = 60;
  
  // the image variable
  private $im;
  
  // TODO: provide a way for the user to reset the colors
  // the color values of the bars
  private $white = array(255, 255, 255);
  private $green = array(68, 146, 34);
  private $yellow = array(241, 191, 36);
  private $red = array(224, 41, 29);
  private $border = array(85, 85, 85);


  /**
   * Constructor
   *
   * @access  public
   */
  public function __construct()
  {
    log_message('debug', "OER_progbar Class Initialized");
    return($this);
  }

  // TODO: See if we can combine all the getters and setters into one function

  /**
   * Set the width of the progress bar
   *
   * @access  public
   * @param   int the total width of the progress bar (pixels)
   * @return  void
   */
  public function set_width($width)
  {
    $this->width = $width;
  }


  /**
   * Get the width of the progress bar
   *
   * @access  public
   * @return  int the width of the progress bar (pixels)
   */
  public function get_width()
  {
    return($this->width);
  }


  /**
   * Set the length of the progress bar
   *
   * @access  public
   * @param   int the length of the progress bar (pixels)
   * @return  void
   */
  public function set_length($length)
  {
    $this->length = $length;
  }


  /**
   * Get the length of the progress bar
   *
   * @access  public
   * @return  int the length of the progress bar (pixels)
   */
  public function get_length()
  {
    return($this->length);
  }


  /**
   * Draw the rectangles that are combined to make up the progress bar
   * @access  public
   * @param   int total number of objects, int number of objects cleared,
   *          int number of objects with 'yes' ask status, int number of 
   *          objects that have neither a 'yes' ask status and aren't cleared
   * @return  void
   */
  public function build_prog_bar($total_objects, $done_objects, $ask_objects,
    $rem_objects)
  { 
    
    // TODO: build in dynamic borders, currently they are fixed.
    
    // create the canvas and allocate the colors
    $this->im = imagecreatetruecolor(($this->width + 3), ($this->height + 3));
    $border_color = imagecolorallocate($this->im, $this->border[0], 
      $this->border[1], $this->border[2]);
    $bg_color = imagecolorallocate($this->im, $this->white[0], 
      $this->white[1], $this->white[2]);
    $done_color = imagecolorallocate($this->im, $this->green[0], 
      $this->green[1], $this->green[2]);
    $ask_color = imagecolorallocate($this->im, $this->yellow[0], 
      $this->yellow[1], $this->yellow[2]);
    $rem_color = imagecolorallocate($this->im, $this->red[0],
      $this->red[1], $this->red[2]);
      
    // calculate the coordinates of the status displays
    $done_x_2 = ($this->_set_prog_width($total_objects, $done_objects));
    $done_y_2 = ($this->height / 3);
    $ask_y_1 = (($this->height / 3) + 1);
    $ask_x_2 = ($this->_set_prog_width($total_objects, $ask_objects));
    $ask_y_2 = (2 * ($this->height / 3));
    $rem_y_1 = ((2 * ($this->height / 3)) + 1);
    $rem_x_2 = ($this->_set_prog_width($total_objects, $rem_objects));
      
    // fill the canvas with colors
    imagefill ($this->im, 0, 0, $border_color);
    
    // build the bars
    imagefilledrectangle($this->im, 2, 2, $this->width, $this->height, 
      $bg_color);
    imagefilledrectangle($this->im, 2, 2, $done_x_2, $done_y_2, $done_color);
    imagefilledrectangle($this->im, 2, $ask_y_1, $ask_x_2, $ask_y_2, 
      $ask_color);
    imagefilledrectangle($this->im, 2, $rem_y_1, $rem_x_2, $this->height,
      $rem_color);
      
      // TODO: allow user definable fonts. Use imageloadfont()
      $font = 6;
      
      // TODO: change the text placement stuff so it is less hackishly done
      // get the coordinates for the text placement on each status display
      $done_text_loc = $this->_place_text($done_objects, $font, 
        ($done_x_2 - 2), ($done_y_2 - 2));
      $ask_text_loc = $this->_place_text($ask_objects, $font, ($ask_x_2 - 2),
        ($ask_y_2 - $ask_y_1));
      $rem_text_loc = $this->_place_text($rem_objects, $font, ($rem_x_2 - 2), 
        ($this->height - $rem_y_1));
      
    // print the numbers of each object status on the respective display
      imagestring($this->im, $font, $done_text_loc["x"], $done_text_loc["y"],
        $done_objects, $border_color);
      imagestring($this->im, $font, $ask_text_loc["x"], 
        ($ask_text_loc["y"] + ($this->height / 3)), $ask_objects, 
          $border_color);
      imagestring($this->im, $font, $rem_text_loc["x"], 
        ($rem_text_loc["y"] + (2 * ($this->height / 3))), $rem_objects, 
        $border_color);
  }


  /**
   * Outputs the created image
   *
   * @access  public
   * @return  void
   */
  public function get_prog_bar()
  {
    header("Content-type: image/png\n\n");
    imagepng($this->im);
    imagedestroy($this->im);
  }


  /**
   * Sets the width of a progress line in the bar
   *
   * @access  private
   * @param   int total number of objects, int number of objects 
   * @return  int width of the progress bar
   */
  private function _set_prog_width($total_objects, $num_objects)
  {
    return(round($this->width * ($num_objects / $total_objects)));
  }


  /**
   * Sets the starting ordinates of the text
   *
   * @access  private
   * @param   int length of the text string, int width of the bar
   *          int height of the bar
   * @return  array the x and y ordinates where the string should start
   */
   private function _place_text($text, $font, $width, $height)
   {
     $text_start_point = array();
     
     $text_start_point["x"] = (($width - (strlen($text) * 
       imagefontwidth($font))) / 2);

     $text_start_point["y"] = (($height - (imagefontheight($font) * 0.75)) / 
       2);
     
     return($text_start_point);
   }
}
?>
