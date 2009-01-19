<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * OER_create_archive class
 * Works only with PHP5 and greater since it uses PHP5 syntax
 * Provides several methods for working with archives
 * 
 * @package     OERca
 * @subpackage  Libraries
 * @category    Utility, Archiving
 * @author     Ali Asad Lotia <lotia@umich.edu>
 */

// TODO: think about making some settings class variables and writing
// accessors and mutators for them
class OER_create_archive
{
  /**
   * Constructor
   *
   * @access  public
   * @return  an instance of the class
   */
  
  private $staging_dir = NULL;
  
  public function __construct()
  {
    log_message('debug', "OER_create_archive Class Initialized");
    return $this;
  }
  
  
  /**
   * Set the directory in which archives are created
   *
   * @access  public
   * @param   string the directory in which archives are creates
   * @return  void
   */
  // TODO: check to see if the passed directory actually exists
  // TODO: check to see if the passed directory is a directory
  // TODO: raise appropriate errors/exceptions if the passed value
  //       exists but is not a directory
  public function set_staging_dir($passed_dir)
  {
    $this->staging_dir = $passed_dir;
  }
  
  
  /**
   * Get the directory in which archives are created
   *
   * @access  public
   * @return  name of the directory in which archives are created
   */
  public function get_staging_dir()
  {
    return $this->staging_dir;
  }
  
  
  /**
   * Creates an archive that contains the specified materials
   * including any associated image files. Return the name of
   * the created file. To allow the creation of multiple archive
   * types, this calls a private function to build the actual
   * archive.
   *
   * @access   public
   * @param    array in which each element contains the original
   *           file name and the name to be used in the archive
   * @param    optional, type of archive to be created
   *           e.g. zip, tar.bz2, tar.gz
   */
  // TODO: break out setting of archive type to separate function?
  public function make_archive($archive_name, $archive_details,
    $archive_type = "zip")
  {
    log_message('debug', "in make_archive function");
    
    // specify the archive building function to be called
    $archive_builder = "_build_";
    
    if ($this->staging_dir == NULL) {
      $this->staging_dir = property('app_mat_download_path');
    }
    switch ($archive_type) {
    case "zip":
      $archive_builder = $archive_builder . "zip";
      $archive_name = $archive_name . ".zip";
      break;
    }
    
    if (!file_exists($this->staging_dir)) {
      mkdir($this->staging_dir, 0700, TRUE);
    }
    
    chdir($this->staging_dir);
    log_message('debug', "exiting make_archive function");
    return ($this->$archive_builder($archive_name, $archive_details));
  }
  
  
  // TODO: use exception handling instead of conditionals here
  /**
    * Builds a zip archive containing the specified files.
    * The number of files to be archived is important because
    * the archive needs to be closed and reopened if the number
    * of files is greater than the number of available file
    * descriptors.
    *
    * @access   private
    * @param    string name of the archive file
    * @param    array that specifies the file name on the server and
    *           the file name used in the archive file
    */
  private function _build_zip ($archive_name, $archive_details)
  { 
    log_message('debug', "in _build_zip function");
    $CI =& get_instance();
    $DEBUG = $CI->config->item('log_to_apache');
    
    if ($DEBUG === TRUE) {
      // load the oer_utils within our library
      $CI->load->library('ocw_utils');
    }
   
    $MAX_FILE_DESC = 253;
    
    $zip = new ZipArchive;
    $add_counter = 0; // keeps track of number of files added
    $total_files = 0; // track total number of files added for debugging
    $zip_opened_num = 1; // number of times the archive has been opened
    /* TODO: should we overwrite existing archives? the naming is 
     * relatively unique and the file should be unlinked after 
     * download */
    $arch_opened = $zip->open($archive_name, ZipArchive::OVERWRITE);
    if ($arch_opened !== TRUE) {
      if ($DEBUG === TRUE) {
        $CI->ocw_utils->log_to_apache("debug",
          "the zip archive didn't open $arch_opened so we abort");
      }
      exit("the zip archive didn't open $arch_opened so we abort");
    } elseif ($arch_opened === TRUE) {
      if ($DEBUG === TRUE) {
        $CI->ocw_utils->log_to_apache("debug", "opened the zip archive");
      }
      foreach ($archive_details as $arch_entry) {
        /* the mysterious check with $add_counter is required because
         * the operation can fail if it runs out of file descriptors */
        if ($add_counter == $MAX_FILE_DESC) {
          if ($DEBUG === TRUE) {
            $CI->ocw_utils->log_to_apache("debug", "hit the file number limit");
          }
          $arch_closed = $zip->close();
          if ($DEBUG === TRUE) {
            $CI->ocw_utils->log_to_apache("debug", "closed the archive and returned $arch_closed");
          }
          $arch_opened = $zip->open($archive_name);
          if ($DEBUG === TRUE) {
            $CI->ocw_utils->log_to_apache("debug", "re-opened the archive and returned $arch_opened");
          }
          $add_counter = 0;
          $zip_opened_num++;
        }
        if ($arch_opened === TRUE) {
          $file_added = $zip->addFile($arch_entry['orig_name'], 
            $arch_entry['export_name']);
          if ($file_added === FALSE) {
            if ($DEBUG === TRUE) {
              $CI->ocw_utils->log_to_apache("debug", 
                "ERROR. file ${arch_entry['orig_name']} named ${arch_entry['export_name']} wasn't added");
            }
            exit ("File wasn't added! Archive creation aborted!");
          }
          $add_counter++;
          $total_files++;
          if ($DEBUG === TRUE) {
            $CI->ocw_utils->log_to_apache("debug", 
              "added file number $total_files which is $add_counter in loop $zip_opened_num");
          }
          } elseif ($arch_opened !== TRUE) {
            if ($DEBUG === TRUE) {
              $CI->ocw_utils->log_to_apache("debug",
                "the zip archive didn't open $arch_opened so we abort");
            }
            exit("the zip archive didn't open $arch_opened so we abort");
        }
      }
      $arch_closed = $zip->close();
      if ($DEBUG === TRUE) {
        $CI->ocw_utils->log_to_apache("debug", "closed the archive and returned $arch_closed. added $total_files files");
      }
    }
    
    log_message('debug', "exiting _build_zip function");
    return(getcwd() . "/" . 
      pathinfo($archive_name, PATHINFO_BASENAME));
  }
}

?>