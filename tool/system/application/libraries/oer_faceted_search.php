<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * OER Faceted Search Class
 * 
 * This class provides a system wide library to do faceted search. 
 * Controllers pass the heavy lifting on to it so we have a single
 * place where the actual faceted search work is being done.
 *
 * @package OER Tool
 * @subpackage Libraries
 * @category Search, Categorization, Keywords
 * @author Mike Bleed <mbleed@umich.edu> 
 * @author Ali Asad Lotia <lotia@umich.edu>
 * @author Kevin Coffman <kwc@umich.edu>
 */
class OER_faceted_search
{
  private $CI = NULL;
  
  /**
    * Constructor
    *
    * @access public
    * @return an instance of the class
    */
  public function __construct() 
  {
    $this->CI =& get_instance();
    $this->CI->load->library('ocw_utils');
  }


  /**
    * Gets the options for the faceted search by parsing the list of courses
    * retrieved for a particular user.
    *
    * @access public
    * @param list of courses in the format returned by 
    *   new_get_courses($uid, $role)
    * @return array containing subarrays with values for each search facet
    */
  public function get_facet_options($courses)
  {
    $instructors = array();
    $dscribe1s = array();
    $dscribe2s = array();
    $years = array();
    $schools = array();
    /* Pre-populate that terms array. We will later delete the ones that 
     * aren't found in the passed list of courses. This is so that the key
     * indexes remain a specific value for each term name. This is is 
     * required for faceted search since we passarguments as numeric values.
     */
    $terms = array(
      1 => "Fall",
      2 => "Winter",
      3 => "Spring",
      4 => "Summer"
      );
    $found_terms = array();
    
    $curr_school_id = NULL;
    $curr_school_name = NULL;
    
    foreach ($courses as $school => $curriculum) {
      foreach ($curriculum as $course) {
        foreach ($course as $c) {
          foreach ($c as $key => $property) {
            if ($key == 'school_id' && !empty($property)) {
              $curr_school_id = $property;
            }
            if ($key == 'school_name' && !empty($property)) {
              $curr_school_name = $property;
            }
            if ($key == 'term' && !empty($property)) {
              if (!array_search($property, $found_terms)) {
                $found_terms[] = $property;
              }
            }
            if ($key == 'year' && !empty($property)) {
              // We avoid listing years values of '0000'. 
              if (!array_key_exists($property, $years) && $property > 0) {
                $years[$property] = $property;
              }
            }
            if ($key == 'instructors' || $key == 'dscribe1s' ||
              $key == 'dscribe2s') {
              foreach ($property as $user_id => $user_details) {
                if (!array_key_exists($user_id, ${$key})) {
                  /* Use php variable variables here to select the appropriate
                   * array */
                  ${$key}[$user_id] = $user_details['name'];
                }
              }
            }
          }
          /* Since we need the values school_id and school_name, we
           * use curr_school_id and curr_school_name so we can have the
           * values outside the foreach loop. 
           */
          if (!empty($curr_school_id) && !empty($curr_school_name)) {
            if (!array_key_exists($curr_school_id, $schools)) {
              $schools[$curr_school_id] = $curr_school_name;
            }
            $curr_school_id = NULL;
            $curr_school_name = NULL;
          }
        }
      }
    }
    
    /* Compare found_terms with terms and delete any terms
     * that aren't present in the current set of courses */
    $absent_terms = array_diff($terms, $found_terms);
    if (count($absent_terms) > 0) {
      foreach ($absent_terms as $term_name) {
        unset($terms[array_search($term_name, $terms)]);
      }
    }
    
    // sort so the options always appear in the same order
    asort($instructors);
    asort($dscribe1s);
    asort($dscribe2s);
    arsort($years);
    asort($schools);
    
    $facet_options['instructors'] = $instructors;
    $facet_options['dscribe1s'] = $dscribe1s;
    $facet_options['dscribe2s'] = $dscribe2s;
    $facet_options['terms'] = $terms;
    $facet_options['years'] = $years;
    $facet_options['schools'] = $schools;
    
    return($facet_options);
  }
}
 
?>