<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Navtab {
  /**
   * TODO: allow multiple ways to create the $tabs array. Passing it in the
   * required format may be a pain.
   */

  // to allow loading of CodeIgniter resources
  private $CI = NULL;
  
  // the CSS style of the navigation tabs
  private $style_id = "topnavlist";
  
  // the tab number to be set active in case no arguments are present in
  // the uri
  private $def_active_tab = 1;
  
  // the tab bar elements
  private $tabs = array(array(
                              "arg" => array("foo", "bar", "baz"),
                              "name" => "foo page",
                              "url" => "http://www.foo.org/bar"
                              ));


  /**
   * Constructor
   *
   * @access  public
   * @return  an instance of this class
   */
  public function __construct()
  {
    // allow loading of CI support modules
    $this->CI =& get_instance();
    
    return($this);
  }


  /**
   * Set navigation tab names and urls
   *
   * @access  public
   * @param   array keys as tab names and values as urls
   * @return  void
   */
  public function set_tabs($tabs)
  {
    $this->tabs = $tabs;
  }


  /**
   * Get navigation tab names and urls
   *
   * @access  public
   * @return  array return the tabs names as keys and
   *                urls as values
   */
  public function get_tabs()
  {
    return($this->tabs);
  }


  /**
   * Add more tabs to the list
   *
   * @access  public
   * @param   array keys as tab names and values as urls
   * @return  void
   */
  public function add_tabs($moretabs)
  {
    $this->tabs = $this->tabs + $moretabs;
  }


  /**
   * Set the style of the tabs
   *
   * @access  public
   * @param   string name (id) of the <li> css style to be used for the tabs
   * @return  void
   */
  public function set_style($id)
  {
    $this->style_id = $id;
  }


  /**
   * Get the style of the tabs
   *
   * @access  public
   * @return  string the id of the css style of the navlist
   */
  public function get_style()
  {
    return($this->style_id);
  }


  /**
   * Set the default active tab, this is used when no controller is specified
   * in the url, and the user is routed to the default page defined in
   * index.php
   * 
   * @access  public
   * @param   int the number of the element in the list to be set active
   * @return  void
   */
  public function set_def_active_tab($active_tab_num)
  {
    $this->def_active_tab = $active_tab_num;
  }


  /**
   * Get the default active tab, this is used when no controller is specified
   * in the url, and the user is routed to the default page defined in
   * index.php
   * 
   * @access  public
   * @return  int the number of the default active tab
   */
  public function get_def_active_tab($active_tab_num)
  {
    return($this->def_active_tab);
  }


  /**
   * Output the html for the tabbed list
   *
   * @access  public
   * @return  string that contains formatted HTML for
   *                 the navigation tabs
   */
  public function make_tabs()
  {
    $uri_seg = $this->CI->uri->segment(1);
    if ($uri_seg) {
      $uri_seg = "{$uri_seg}/{$this->CI->uri->segment(2)}";
    }
    
    $active_tab = "<li id=\"active\">"; // html to mark the active tab
    
    // start the unordered list with the specified style
    $tab_markup = "<ul id=\"{$this->style_id}\">\n";
    
    $i = 0;
    // output the unordered list elements
    while ($i < count($this->tabs)) {
      // check for the default controller routing
      if (!$uri_seg && ($i == ($this->$def_active_tab_num - 1))) {
        $tab_markup .= "  $active_tab";
      } else {
        // compare url with possible arguments for each list element
        foreach ($this->tabs[$i]["arg"] as $argument) {
          if (strncmp($argument, $uri_seg, strlen($uri_seg)) == 0) {
            $tab_markup .= "  $active_tab";
            break 2;
          } 
        }
        $tab_markup .= "  <li>"; // if there is no match for the active tab
      }
      $tab_markup .= "<a href=\"{$this->tabs[$i]["url"]}\"";
      $tab_markup .= " title=\"{$this->tabs[$i]["name"]}\">";
      $tab_markup .= "{$this->tabs[$i]["name"]}</a></li>\n";
      $i++;
    }
    
    // end the unordered list
    $tab_markup .= "</ul>\n";
    return($tab_markup);
  }
}