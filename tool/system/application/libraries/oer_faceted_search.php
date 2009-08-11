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
    
    if (count($courses) > 0) {
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
    }
    
    /* Compare found_terms with terms and delete any terms
     * that aren't present in the current set of courses */
    $absent_terms = array_diff($terms, $found_terms);
    if (count($absent_terms) > 0) {
      foreach ($absent_terms as $term_name) {
        unset($terms[array_search($term_name, $terms)]);
      }
    }
    
    // Sort arrays so the options always appear in the same order.
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
  
  
  /**
    * Filters the course information returned by the new_get_courses function 
    * from the course model according to the faceted search parameters.
    * 
    * The current logic used is that any of the parameters within the same
    * facet category must be satisfied which implies an OR relationship
    * within a facet category. However at least one filter from each facet
    * category MUST be satisfied which implies an AND relationship between
    * categories. If all specified filter categories don't match the course
    * it is removed from the list.
    * 
    * This function calls filter specific functions to do the filtering.
    * The sub functions are called based on the filter names. So to add more
    * filters it is necessary to also add an appropriate filtering function.
    * 
    * The course array is passed by reference so it is edited in place.
    *
    * @access public
    * @param Course information in the format returned by new_get_course
    * @param Array of filter parameters. The each filter value within a 
    *        category is separated by a 'z' which we use as the token
    *        across which to split filters.
    * @return void. Since the courses array is passed by reference which is
    *         allowing editing in place.
    */
  public function do_course_facet_filtering(&$courses, $filters)
  {
    /* List of valid filter names. These values match up to the allowed
     * filter facets for the facted search. This filter is a check to prevent
     * invalid "facets" in the filters parameter array from messing up the 
     * result set.
     *
     * The legal facet names in turn match up with the keys in the
     * course level subarray of the $courses parameter. See the 
     * new_get_courses function in the course model for more information.
     *
     * If a filter "facet"/category is added to the course faceted search
     * the equivalent key name should be added to this array. Otherwise the
     * "facet"/category will not work.
     */
    $valid_filters = array(
      "school_id",
      "term",
      "year",
      "dscribe2s",
      "dscribe1s"
      );
      
      // filter arguments are passed as strings separated by this character
      $param_separator = "z";
      
      $current_filters = array();
      
      $props_to_check = array();
      
    /* Narrow the "facet" filtering to only those that have non-zero values.
     * Also check to see if it is an "allowed" filter by checking against
     * the array above. 
     */
    foreach ($filters as $filter_name => $filter_value) {
      if (!empty($filter_value) && in_array($filter_name,
        $valid_filters)) {
        $current_filters[$filter_name] = $filter_value;
      }
    }
    
    /*
     * Traverse the courses array, match the filter keys against the key names
     * of the course properties so we only match against those. If we don't get
     * a match, delete the course from the $courses array.
     *
     * The appropriate filter function is called using the property name value
     * to formulate the function call statement.
     */
    if (count($courses) > 0) {
      foreach ($courses as $school => $curriculum) {
        foreach ($curriculum as $curr_name => $curr_courses) {
          foreach ($curr_courses as $cid => $cprops) {
            $props_to_check = array_intersect_key($cprops, $current_filters);
            foreach ($props_to_check as $prop_name => $prop_vals) {
              $filter_function = "__do_{$prop_name}_filter";
              if ($this->$filter_function($prop_vals, 
                split($param_separator, $current_filters[$prop_name])) == 1) {
                unset($courses[$school][$curr_name][$cid]);
                break 1;
              }
            }
          }
          if (count($courses[$school][$curr_name]) == 0) {
            unset($courses[$school][$curr_name]);
          }
        }
        if (count($courses[$school]) == 0) {
          unset($courses[$school]);
        }
      }
    }
  }
  
    
  /**
    * Filter on dscribe1 user ids.
    *
    * @access private
    * @param array of dscribe1s where the user id is the key.
    * @param array of numeric dscribe1 user ids to match against.
    * @return 0 on successful match. 1 otherwise.
    */ 
  private function __do_dscribe1s_filter($dscribe1s, $chosen_dscribe1s)
  {
    return $this->__do_multi_key_filter($dscribe1s, $chosen_dscribe1s);
  }
  
  
  /**
    * Filter on dscribe2 user ids.
    *
    * @access private
    * @param array of dscribe1s where the user id is the key.
    * @param array of numeric dscribe2 user ids to match against.
    * @return 0 on successful match. 1 otherwise.
    */ 
  private function __do_dscribe2s_filter($dscribe2s, $chosen_dscribe2s)
  {
    return $this->__do_multi_key_filter($dscribe2s, $chosen_dscribe2s);
  }
  
  
  /**
    * Filter on array key. If a match is found, return 0 otherwise return 1.
    * 
    * @access private
    * @param array of users where the user id is the key.
    * @param array of numeric user ids to filter against.
    * @return 0 on successful match. 1 otherwise.
    */
  private function __do_multi_key_filter($property_list, $filter_vals)
  {
    if (count(array_intersect(array_keys($property_list), $filter_vals)) > 0) {
      return 0;
    } else {
      return 1;
    }
  }
  
  
  /**
    * Filter on year. If one of selected year values match the year of the
    * course return 0 otherwise return 1.
    *
    * @access private
    * @param year
    * @param array of years against which to match.
    * @return 0 on successful match. 1 otherwise.
    */
  private function __do_year_filter($year, $chosen_years)
  {
    return $this->__do_single_prop_filter($year, $chosen_years);
  }
  
  
  /**
    * Filter on school id. If the school id of one of the selected schools
    * matches the passed school id return 0 otherwise return 1.
    *
    * @access private
    * @param numerical school id
    * @param array of school ids against which to match
    * @return 0 on successful match. 1 otherwise.
    */
  private function __do_school_id_filter($school_id, $selected_schools) {
    return $this->__do_single_prop_filter($school_id, $selected_schools);
  }
  
  
  /**
    * Filter on matching property. If one of the filter values matches the 
    * property, return 0 otherwise return 1. Does a strict match where types
    * are also compared.
    *
    * @access private
    * @param property to match.
    * @param array of values to match against property.
    * @return 0 on successful match. 1 otherwise.
    */
  private function __do_single_prop_filter($property, $filter_vals)
  {
    if (in_array($property, $filter_vals, TRUE)) {
      return 0;
    } else {
      return 1;
    }
  }
  
  
  /**
    * Filter on term names. The term names are text values in the DB while
    * the passed filter arguments are numeric. We first grab the term names
    * for which the keys match against the passed filter arguments and then
    * match against the term name property for the course.
    */
  private function __do_term_filter($term, $selected_terms)
  {
    /* Standard encoding of term names. Used for filtering by Term because
     * passed arguments are numerical values corresponding to the array
     * inidices of Terms below. We need to provide the names because the
     * DB only contains the term names and no numerical Term ids.
     */
    $terms = array(
      1 => "Fall",
      2 => "Winter",
      3 => "Spring",
      4 => "Summer"
      );
    
    if (in_array($term, array_intersect_key($terms, 
      array_flip($selected_terms)))) {
      return 0;
    } else {
      return 1;
    } 
  }
}
 
?>
