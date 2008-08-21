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
  
  // set default width and height (pixels)
  private $width = 600;
  private $height = 20;
  
  // set key image dimensions (pixels)
  private $key_width = 10;
  private $key_height = 10;
  
  // the image variable
  private $im;
  
  // the key image variable
  private $key_im;
  
  // TODO: allow user definable fonts. Use imageloadfont()
  private $font = 6;
  
  // TODO: provide a way for the user to reset the colors
  // the color values of the bars
  private $done_rgb = array(68, 246, 34);
  private $ask_rgb = array(50,200,255);  // OERDEV-146/140 change to blue per Pieter
  private $rem_rgb = array(224, 41, 29);
  private $border = array(85, 85, 85);
  private $tot_rgb = array(255,255,255);  // white for the total amount
  
  private $total_objects = NULL;

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
   * Set the height of the progress bar
   *
   * @access  public
   * @param   int the height of the progress bar (pixels)
   * @return  void
   */
  public function set_height($height)
  {
    $this->height = $height;
  }


  /**
   * Get the height of the progress bar
   *
   * @access  public
   * @return  int the height of the progress bar (pixels)
   */
  public function get_height()
  {
    return($this->height);
  }


  /**
    * Set the dimensions of the progress bar
    *
    * @access   public
    * @param    int with in pixels
    * @param    int height in pixels
    * @return   void
    */
  public function set_size($width, $height) 
  {
    $this->width = $width;
    $this->height = $height;
  }


  /**
    * Get the dimensions of the progress bar
    *
    * @access   public
    * @return   array with width and height as keys and their 
    *           associated values as values
    */
  public function get_size() 
  {
    return array(
      "width" => $this->width,
      "height" => $this->height
    );
  }
    

  /**
   * Set the width of the status key
   *
   * @access  public
   * @param   int width of the status key (pixels)
   * @return  void
   */
  public function set_key_width($width)
  {
    $this->key_width = $width;
  }


  /**
   * Get the width of the status key
   *
   * @access  public
   * @return  int the width of the progress bar (pixels)
   */
  public function get_key_width()
  {
    return($this->key_width);
  }


  /**
   * Set the height of the status key
   *
   * @access  public
   * @param   int height of the status key (pixels)
   * @return  void
   */
  public function set_key_height($height)
  {
    $this->key_height = $height;
  }


  /**
   * Get the height of the progress bar
   *
   * @access  public
   * @return  int the height of the status key (pixels)
   */
  public function get_key_height()
  {
    return($this->key_height);
  }
  

  /**
    * Set the dimensions of the status key
    *
    * @access   public
    * @param    int with in pixels
    * @param    int height in pixels
    * @return   void
    */
  public function set_key_size($width, $height) 
  {
    $this->key_width = $width;
    $this->key_height = $height;
  }


  /**
    * Get the dimensions of the progress bar
    *
    * @access   public
    * @return   array with width and height as keys and their 
    *           associated values as values
    */
  public function get_key_size() 
  {
    return array(
      "key_width" => $this->key_width,
      "key_height" => $this->key_height
    );
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
    $rem_objects, $my_width, $my_height, $font_size)
  { 
    // TODO: build in dynamic borders, currently they are fixed.
    $this->total_objects = $total_objects;
    $this->width = $my_width;
    $this->height = $my_height;
    $fudge = 0;
   
 
    /* create the canvas and allocate the colors. the canvas is padded
     * to allow for borders etc. which take up space */
    $canv_pad = 3; 
    
    $this->im = imagecreatetruecolor(($this->width + $canv_pad),
      ($this->height + $canv_pad));
   
    //  save room to show total objects - bdr
    $this->width = $my_width - 20;
 
    $text_color = imagecolorallocate($this->im, 0, 0, 0);
    $pointsize = $font_size;     // font size of text displayed in rectangular box
    $fontfile = "./assets/tool2/fonts/collegec.ttf";
    
    $done_color = imagecolorallocate($this->im, $this->done_rgb[0], 
      $this->done_rgb[1], $this->done_rgb[2]);
    $ask_color = imagecolorallocate($this->im, $this->ask_rgb[0], 
      $this->ask_rgb[1], $this->ask_rgb[2]);
    $rem_color = imagecolorallocate($this->im, $this->rem_rgb[0],
      $this->rem_rgb[1], $this->rem_rgb[2]);
    $tot_color = imagecolorallocate($this->im, $this->tot_rgb[0],
      $this->tot_rgb[1], $this->tot_rgb[2]);

      
    // fill the canvas with the a white color
    imagefill ($this->im, 0, 0, $tot_color);
    
    /* TODO: prevent rounding from making combined width > than total
     * width of image */
    // calculate the coordinates of the status displays
    $rem_x1  = 0;
    $ask_x1  = $rem_x1;
    $done_x1 = $rem_x1;
    $tot_x1  = $rem_x1;
    
    // set all ending points to the initial starting point
    $rem_x2  = $rem_x1;
    $ask_x2  = $rem_x1;
    $done_x2 = $rem_x1;
    $tot_x2  = $rem_x1;

    // setup stuff for fudging the progress graph based on objects
    $rem_fudge = 0;
    $ask_fudge = 0;
    $done_fudge = 0;

    // figure out if we have to fudge the width for small "counts"  - bdr
    if (( 100 * ($rem_objects / $this->total_objects)) < 15) {
	$rem_fudge = 1;
        $fudge = $fudge + $rem_fudge;
    }
    if (( 100 * ($ask_objects / $this->total_objects)) < 15) {
        $ask_fudge = 1;
        $fudge = $fudge + $ask_fudge;
    }
    if (( 100 * ($done_objects / $this->total_objects)) < 15) {
        $done_fudge = 1;
        $fudge = $fudge + $done_fudge;
    }

    // figure out whther we nedd to save 10% or 20 % of width for small count
    $sludge = round((($fudge * 10) * $total_objects) / 100) -2;

    $y1 = $rem_x1;
    $y2 = $this->height;
    
    /* if there are COs of a particular type:
     *  calculate horizontal end point
     *  draw the progress bar section
     *  get the coordinates for the text placement on each status display
     *  print the number of objects
     *  and change the starting point for the next C0 types */
    // TODO: change the text placement stuff so it is less hackishly done
    if ($rem_objects > 0) {
      if (($rem_fudge) && ($rem_objects != $this->total_objects)) {
		// $rem_x2 = $rem_x1 + 10;
                // $ask_x2 = $ask_x1 + 10;
                $cal1 = $sludge / $fudge;
                $cal2 = $cal1 / 100;
                $cal3 = $this->width * $cal2;
                $rem_x2 = $rem_x1 + $cal3;
      } else {
          $rem_x2 = ($this->_set_prog_width($rem_objects, $sludge));
      }
      imagefilledrectangle($this->im, $rem_x1, $y1, $rem_x2, $y2, $rem_color);
      $ask_x1 = $rem_x2;
      $done_x1 = $rem_x2;
      $tot_x1  = $rem_x2;
    } 
    
    if ($ask_objects > 0) {
      if (($ask_fudge) && ($ask_objects != $this->total_objects)) {
		// $ask_x2 = $ask_x1 + 10;
		$cal1 = $sludge / $fudge;
		$cal2 = $cal1 / 100;
		$cal3 = $this->width * $cal2;
		$ask_x2 = $ask_x1 + $cal3;
      } else {
          $ask_x2 = $ask_x1 + ($this->_set_prog_width($ask_objects, $sludge));
      }
      imagefilledrectangle($this->im, $ask_x1, $y1, $ask_x2, $y2, $ask_color);
      $done_x1 = $ask_x2;
      $tot_x1  = $ask_x2;
    } 

    if ($done_objects > 0) {
      if (($done_fudge) && ($done_objects != $this->total_objects)) {
                $cal1 = $sludge / $fudge;
                $cal2 = $cal1 / 100;
                $cal3 = $this->width * $cal2;
                $done_x2 = $done_x1 + $cal3;
      } else {
          $done_x2 = $done_x1 + ($this->_set_prog_width($done_objects, $sludge));
      }	
      imagefilledrectangle($this->im, $done_x1, $y1, $done_x2, $y2, $done_color);
      $tot_x1 = $done_x2;
    } 

    // write the "counts" on top of their colored rectangular box  -  bdr
    if ($rem_objects > 0)
        imagettftext($this->im,$pointsize,0,$rem_x1+(($rem_x2-$rem_x1-4)/2),$y2-3, $text_color, $fontfile, $rem_objects);

    if ($ask_objects > 0) 
        imagettftext($this->im,$pointsize,0,$ask_x1+(($ask_x2-$ask_x1-4)/2),$y2-3, $text_color, $fontfile, $ask_objects);

    if ($done_objects > 0)
        imagettftext($this->im,$pointsize,0,$done_x1+(($done_x2-$done_x1-4)/2),$y2-3, $text_color, $fontfile, $done_objects);

    $tot_x1 = $tot_x1 + 2;
    $tot_x2 = $tot_x1 + 20;
    imagefilledrectangle($this->im, $tot_x1, $y1 - 2, $tot_x2, $y2 + 2, $tot_color);
    imagettftext($this->im,$pointsize+2,0,$tot_x1+(($tot_x2-$tot_x1-16)/2),$y2-3, $text_color, $fontfile, $total_objects);
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
    imagepng($this->imtot);
  }


  /**
    * Generates a small colored square representing the CO status
    *
    * @access   public
    * @param    string the CO status (done, ask, rem)
    * @return   void
    */
  public function build_stat_key($co_status)
  {
    $key_rgb = NULL;

    // make the image canvas slightly larger than the image for borders
    $this->key_im =  imagecreatetruecolor(($this->key_width + 3), 
      ($this->key_height + 3));

    $border = imagecolorallocate($this->key_im, $this->border[0],
      $this->border[1], $this->border[2]);

    imagefill($this->key_im, 0, 0, $border);

    switch ($co_status) {
      case "done":
        $key_rgb = $this->done_rgb;
        break;
      case "ask":
        $key_rgb = $this->ask_rgb;
        break;
      case "rem":
        $key_rgb = $this->rem_rgb;
        break;
    }

    $key_color = imagecolorallocate($this->key_im, $key_rgb[0],
      $key_rgb[1], $key_rgb[2]);
      
    imagefilledrectangle($this->key_im, 2, 2, $this->key_width,
      $this->key_height, $key_color);
  }
  
  /**
    * Outputs the key square
    *
    * @access   public
    * @return   void
    */
  public function get_stat_key()
  {
    header("Content-type: image/png\n\n");
    imagepng($this->key_im);
    imagedestroy($this->key_im);
  }


  /**
   * Sets the width of a progress line in the bar
   *
   * @access  private
   * @param   int total number of objects, int number of objects 
   * @return  int width of the progress bar
   */
  private function _set_prog_width($num_objects, $sludge)
  {
        return(round(($this->width - 0)  * ($num_objects / $this->total_objects)));
  }


  /**
   * Sets the starting ordinates of the text
   *
   * @access  private
   * @param   int length of the text string
   * @param   int width of the bar
   * @return  array the x and y ordinates where the string should start
   */
   private function _place_text($text, $width)
   {
     /* TODO: we won't need $height_fudge if we can calculate things 
      * from the height of the font? */
     // fine tune vertical location of text, inelegant
     $height_fudge = 3;
     
     $text_start_point = array();
     
     $text_start_point["x"] = (($width - (strlen($text) * 
       imagefontwidth($this->font))) / 2);
     /* TODO: use imagefontheight in font placement. not all fonts 
      * are equally high */
     $text_start_point["y"] = $height_fudge;
     
     return($text_start_point);
   }
}
?>
