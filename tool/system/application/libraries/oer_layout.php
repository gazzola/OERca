<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * OER Layout Library Class
 *
 * @package		OER Tool
 * @subpackage	Libraries
 * @category	Template
 * @author		Ali Asad Lotia
 * @date      March 02 2008
 * @copyright	Copyright (c) 2006, University of Michigan
 */
class OER_Layout extends Layout 
{
  /**
   * Constructor
   *
   * @access  public
   */
  public function __construct()
  {
    parent::Layout();
    log_message('debug', "OER_Layout Class Initialized");
  }
  
  
  /**
   * Build a page using a custom header and footer
   *
   * @access  public
   * @param   string the name of the view to be loaded
   * @param   mixed array with the output data
   * @param   string name of the header file
   * @param   string name of the footer file
   * @param   string name of the loader file
   * @return  void
   */
  public function build_custom_page($view, $data = NULL, 
    $header = "newheader.php", $footer = "footer.php", $loader = "oer_loader")
  {
    $data['settings'] = $this->settings;
    foreach ($this->settings['elements'] as $key => $item)
    {
      $data[$key] = $this->layout->layoutmodel->$key($item);
    }
    
    $data['cust_header'] = $header;
    $data['cust_footer'] = $footer;
    $this->layout->load->view($loader, array('view' => $view, 
      'data' => $data));
  }
}

?>