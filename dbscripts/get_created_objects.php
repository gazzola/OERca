<?php
/* much of this code is lifted from http://devzone.zend.com/article/686 */

$DEBUG = TRUE; // print debug output if TRUE

$MAX_FILE_DESC = 250; // max number of file descriptors

$start_date = "20090601000000"; /* to restrict returned values by start date */

$replacement_suffix = "_rep";

$uploads_folder_abs_path = "/var/www/open.umich.edu_website/oerca/uploads";

$action_taken = "Create";

$archive_name = "created_replacement_images.zip";

$metadata_filename = "metadata.txt";

$md_sep = ";;";

/* Connect to a MySQL server */
$mysqli = new mysqli(
            $argv[1],  /* The host to connect to */
            $argv[2],  /* The user to connect as */
            $argv[3],  /* The password to use */
            $argv[4]); /* The default database to query */

if (mysqli_connect_errno()) {
   printf("Can't connect to MySQL Server. Errorcode: %s\n", 
     mysqli_connect_error());
   exit(1);
}

$query = "SELECT ocw_objects.id AS object_id,
ocw_object_replacements.id AS replacement_object_id,
ocw_object_replacements.tags,
ocw_course_files.filename AS course_filename,
ocw_material_files.filename AS material_filename,
ocw_object_files.filename AS object_filename

FROM ocw_objects,
ocw_object_replacements,
ocw_object_files,
ocw_material_files,
ocw_materials,
ocw_course_files

WHERE ocw_objects.action_taken = '%s'
AND ocw_object_replacements.object_id = ocw_objects.id
AND ocw_object_files.object_id = ocw_objects.id
AND ocw_objects.material_id = ocw_material_files.material_id
AND ocw_materials.id = ocw_material_files.material_id
AND ocw_materials.course_id = ocw_course_files.course_id
AND ocw_objects.done = 1\n\n";

$query = sprintf($query, $mysqli->real_escape_string($action_taken));

if (!empty($start_date)) {
  $query .= sprintf("AND ocw_object_replacements.modified_on >= %s",
    $mysqli->real_escape_string($start_date));
}

if ($result = $mysqli->query($query)) {
  $file_details = array();
  while($row = $result->fetch_assoc()) {
    
    if ($DEBUG) {
      printf("The replacment object %s replaces object %s.\n",
        $row['replacement_object_id'], $row['object_id']);
    }
    
    $obj_dir_loc = "$uploads_folder_abs_path/" . 
      "cdir_${row['course_filename']}/" .
      "mdir_${row['material_filename']}/" . 
      "odir_${row['object_filename']}/";
    
    if ($DEBUG) {
      print "$obj_dir_loc\n";
    }
    
    $obj_files = scandir($obj_dir_loc);
    
    foreach ($obj_files as $file) {
      if (preg_match("/$replacement_suffix\./", $file)) {
        $rep_file = $file;
      }
    }
    $file_details[] = array(
      'file' => $rep_file,
      'path' => $obj_dir_loc . $rep_file,
      'tags' => $row['tags']
      );
  }
  
  if ($DEBUG) {
    print "We did the query and returned " . $mysqli->affected_rows . 
      " results.\n";
  } // finish parsing the query results
  
  /* Destroy resultset and free memory */
  $result->close();
} // finished with the query

$mysqli->close(); // finished with the DB

if ($DEBUG) {
  print_r($file_details);
}

$md_file = fopen($metadata_filename, 'w');

$zip = new ZipArchive;
$add_counter = 0; // keeps track of number of files added
$total_files = 0; // track total number of files added for debugging
$zip_opened_num = 1; // number of times the archive has been opened

$arch_opened = $zip->open($archive_name, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);

if ($arch_opened !== TRUE) {
  exit("the zip archive didn't open $arch_opened so we abort");
} elseif ($arch_opened === TRUE) {
  if ($DEBUG) {
    print "opened the zip archive $archive_name.\n"; 
  }
  foreach ($file_details as $file) {
    /* the mysterious check with $add_counter is required because
     * the operation can fail if it runs out of file descriptors */
    if ($add_counter == $MAX_FILE_DESC) {
      if ($DEBUG) {
        print "hit the file number limit.\n";
      }
      $arch_closed = $zip->close();
      if ($DEBUG) {
        print "closed the archive and returned $arch_closed.\n";
      }
      $arch_opened = $zip->open($archive_name);
      if ($DEBUG) {
        print "re-opened the archive and returned $arch_opened.\n";
      }
      $add_counter = 0;
      $zip_opened_num++;
    }
    if ($arch_opened === TRUE) {
      $file_added = $zip->addFile($file['path'], 
        $file['file']);
      $md_string = $file['file'] . $md_sep . $file['tags'] . "\n";
      fwrite($md_file, $md_string);
      if ($file_added === FALSE) {
        exit ("File wasn't added! Archive creation aborted!");
      }
      $add_counter++;
      $total_files++;
      if ($DEBUG) {
        print "added file number $total_files which is $add_counter in loop $zip_opened_num.\n";
      }    
    } elseif ($arch_opened !== TRUE) {
      exit("the zip archive didn't open $arch_opened so we abort");
    }
  }
  
  fclose($md_file);
  $md_file_added = $zip->addFile($metadata_filename);
  if ($md_file_added === FALSE) {
    exit ("The metadata file wasn't added! Archive creation aborted!");
  }
  
  $total_files++;
  $arch_closed = $zip->close();
  unlink($metadata_filename);

  if ($DEBUG) { 
    print "closed the archive and returned $arch_closed. added $total_files files.\n";
  }
}
?>
