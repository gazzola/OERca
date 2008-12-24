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
    log_message('debug', "OER_Filename Class Initialized");
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
    $arch_results = FALSE;
    $zip = new ZipArchive;
    $add_counter = 0; // keeps track of number of files added
    /* TODO: should we overwrite existing archives? the naming is 
     * relatively unique and the file should be unlinked after 
     * download */
    $arch_opened = $zip->open($archive_name, ZipArchive::OVERWRITE);
    if ($arch_opened === TRUE) {
      foreach ($archive_details as $arch_entry) {
        /* the mysterious check with $add_counter is required because
         * the operation can fail if it runs out of file descriptors */
        if ($add_counter == 253) {
          $zip->close();
          $arch_opened = $zip->open($archive_name);
          $add_counter = 0;
        }
        if ($arch_opened === TRUE) {
          $file_added = $zip->addFile($arch_entry['orig_name'], 
            $arch_entry['export_name']);
          if ($file_added === FALSE) {
            exit ("File wasn't added! Archive creation aborted!");
          }
          $add_counter++;
        }
      }
    }
    $zip->close();
    
    return(getcwd() . "/" . 
      pathinfo($archive_name, PATHINFO_BASENAME));
  }
}

?>