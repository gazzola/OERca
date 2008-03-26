<?php
/**
 * This script renames files from the old file naming
 * scheme (c[course id].m[material id].o[object id]) to
 * the new naming scheme which is based on sha1 digests
 * of either random strings or file digests when files
 * are available.
 */ 

mysql_connect('localhost','ocwuser','0p3ni5c00l') or die(mysql_error());
mysql_select_db('oerdev') or die(mysql_error());

define('DEBUG', false);
define('UPLOADS_DIR',dirname(__FILE__).'/uploads/');

$courses = fix_courses();
$materials = fix_materials($courses);
$cos = fix_cos($materials);
fix_material_contents($materials);
fix_co_contents($cos);

#--------- don't edit below this line -------------#

function fix_courses()
{
	$courses = array();

	# convert courses
	print "Creating course directories..";
	$sql = "SELECT id, CONCAT(title,start_date,end_date) as str FROM ocw_courses";
	$res = mysql_query($sql) or die (mysql_error());

	if (mysql_num_rows($res) > 0) {
		while ($course = mysql_fetch_object($res)) {
					 $dirname = create_course_fileinfo($course->id, $course->str);
					 $courses[$course->id] = $dirname;
		}
	}
	print "done!<br/>";

	return $courses;
}

function fix_materials($courses)
{
	$materials = array();

	# convert materials
	print "<br/><br/>Creating material directories...";
	foreach($courses as $cid => $cdir) {
				$sql = "SELECT id, name FROM ocw_materials WHERE course_id=$cid";
				$res = mysql_query($sql) or die (mysql_error());

				if (mysql_num_rows($res) > 0) {
						while ($mat = mysql_fetch_object($res)) {
					 				 $dirname = create_mat_fileinfo($mat->id, $mat->name, $cid, $cdir);
					 				 $materials[$mat->id] = $dirname;
						}
				}
	}
	print "done!<br/>";

	return $materials;
}

function fix_cos($materials)
{
	$cos = array();

	# convert materials
	print "<br/><br/>Creating CO directories...";
	foreach($materials as $mid => $mdir) {
				$sql = "SELECT id, name FROM ocw_objects WHERE material_id=$mid";
				$res = mysql_query($sql) or die (mysql_error());

				if (mysql_num_rows($res) > 0) {
						while ($obj = mysql_fetch_object($res)) {
					 				 $dirname = create_co_fileinfo($obj->id, $obj->name, $mid, $mdir);
					 				 $cos[$obj->id] = $dirname;
						}
				}
	}
	print "done!<br/>";

	return $cos;
}

function fix_material_contents($materials)
{
	print "<br/><br/>Fixing material contents...";
	foreach($materials as $mid => $mdir) {
					$of_pattern = "c\d+\.m$mid\.version_1\.(\w+)$";
					$os_pattern = "c\d+\.m$mid\.slide_(\d+)\.(\w+)$";
					$mname = material_dirname($mid); 
					if (is_dir($mdir)) {
    					if ($dh = opendir($mdir)) {
        				while (($file = readdir($dh)) !== false) {
											# convert material names
											if (preg_match("/$of_pattern/", $file,$match)) {
													$ext = $match[1];
													rename($mdir."/$file", "$mdir/$mname.$ext");
													print "<br> renaming $file to $mname.$ext";
											}
											# convert material names
											if (preg_match("/$os_pattern/", $file,$match)) {
													$loc = $match[1];
													$ext = $match[2];
													rename($mdir."/$file", "$mdir/{$mname}_slide_$loc.$ext");
													print "<br> renaming $file to {$mname}_slide_$loc.$ext";
											}
				
        				}
        				closedir($dh);
    					}
					}
		}
		print "done!<br/>";
}


function fix_co_contents($cos)
{
	print "<br/><br/>Fixing co contents...";
	foreach($cos as $oid => $odir) {
	//print "<br> $oid => $odir";
					$of_pattern = "c\d+\.m\d+\.o{$oid}_grab\.(\w+)$";
					$or_pattern = "c\d+\.m\d+\.o{$oid}_rep\.(\w+)$";
					$oname = co_dirname($oid); 
					if (is_dir($odir)) {
    					if ($dh = opendir($odir)) {
        				while (($file = readdir($dh)) !== false) {
											# convert co names
											if (preg_match("/$of_pattern/", $file,$match)) {
													$ext = $match[1];
													rename($odir."/$file", "$odir/{$oname}_grab.$ext");
													#print "<br> renaming $file to {$oname}_grab.$ext";
											}
											# convert material names
											if (preg_match("/$or_pattern/", $file,$match)) {
													$ext = $match[1];
													rename($odir."/$file", "$odir/{$oname}_rep.$ext");
													#print "<br> renaming $file to {$oname}_rep.$ext";
											}
				
        				}
        				closedir($dh);
    					}
					}
		}
		print "done!<br/>";
}

function create_co_fileinfo($oid, $uniq_str, $mid, $mdir)
{
	$dirname = '';
	if (!co_added($oid)) {
			$of = false;
			if (($of = old_co_exists($oid, $mid, $mdir)) !== false) {
					 $dirname = file_digest($of,'co');	
			} else {
					 $dirname = random_name($uniq_str,'co');
			}

			if (DEBUG) {
					print "<br>DEBUG: Creating co info($mid-$oid): $dirname";
			} else {
		  		$sql = "INSERT INTO ocw_object_files VALUES('',$oid,'$dirname',NOW(),NOW());";
					mysql_query($sql) or die(mysql_error());

					if ($of===false) {
							if (!is_dir($mdir."/o$oid")) {
									create_dirs($mdir.'/odir_'.$dirname);
							} else {
									rename($mdir."/o$oid", $mdir.'/odir_'.$dirname);
							}
					} else {
							# rename old directory
							@rename($mdir."/o$oid", $mdir.'/odir_'.$dirname);
					}
			}
	} else {
			$dirname = $mdir.'/odir_'.co_dirname($oid);
			if (!is_dir($dirname)) {
					if (DEBUG) { 
							print "<br>DEBUG: This object needs a directory..creating it";
					} else {
							if (!is_dir($mdir."/o$oid")) {
									create_dirs($dirname);
							} else {
									rename($mdir."/o$oid", $dirname);
							}
					}
			} else {
					if (DEBUG) { print "<br>DEBUG: This object is set"; }
			}
	}
	return $dirname;
}

function old_co_exists($oid, $mid, $mdir)
{
  $match = false;
  $of_pattern = "c\d+\.m$mid\.o{$oid}_grab\.";
  $dir =  $mdir."/o$oid";

  if (is_dir($dir)) {
      if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if (preg_match("/$of_pattern/", $file)) {
                return $mdir."/o$oid/$file";
            }
        }
        closedir($dh);
    }
  }

  return $match;
}

function co_added($oid)
{
  $sql = "SELECT * FROM ocw_object_files WHERE object_id=$oid";
  $res = mysql_query($sql);
  return (mysql_num_rows($res)>0) ? true : false;
}

function co_dirname($oid)
{
  $sql = "SELECT filename FROM ocw_object_files WHERE object_id=$oid";
  $res = mysql_query($sql);
  $r = mysql_fetch_object($res);
  return $r->filename;
}



function create_mat_fileinfo($mid, $uniq_str, $cid, $cdir)
{
	$dirname = '';
	if (!material_added($mid)) {
			$of = false;
			if (($of = old_material_exists($mid, $cid, $cdir)) !== false) {
					 $dirname = file_digest($of,'materials');	
			} else {
					 $dirname = random_name($uniq_str,'materials');
			}

			if (DEBUG) {
					print "<br>DEBUG: Creating material info($cid-$mid): $dirname";
			} else {
		  		$sql = "INSERT INTO ocw_material_files VALUES('',$mid,'$dirname',NOW(),NOW());";
					mysql_query($sql) or die(mysql_error());

					if ($of===false) {
							if (!is_dir($cdir."/m$mid")) {
									create_dirs($cdir.'/mdir_'.$dirname);
							} else {
									rename($cdir."/m$mid", $cdir.'/mdir_'.$dirname);
							}
					} else {
							# rename old directory
							rename($cdir."/m$mid", $cdir.'/mdir_'.$dirname);
					}
			}
	} else {
			$dirname = $cdir.'/mdir_'.material_dirname($mid);
			if (!is_dir($dirname)) {
					if (DEBUG) { 
							print "<br>DEBUG: This material needs a directory..creating it";
					} else {
							if (!is_dir($cdir."/m$mid")) {
									create_dirs($dirname);
							} else {
									rename($cdir."/m$mid", $dirname);
							}
					}
			} else {
					if (DEBUG) { print "<br>DEBUG: This material is set"; }
			}
	}
	return $dirname;
}

function old_material_exists($mid, $cid, $cdir)
{
	$match = false;
	$of_pattern = "c$cid\.m$mid\.version_1\.";
	$dir =  $cdir."/m$mid";

	if (is_dir($dir)) {
    	if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
						if (preg_match("/$of_pattern/", $file)) {
								return $cdir."/m$mid/$file";
						}
        }
        closedir($dh);
    }
	}

	return $match;
}

function material_added($mid)
{
	$sql = "SELECT * FROM ocw_material_files WHERE material_id=$mid";
	$res = mysql_query($sql);
	return (mysql_num_rows($res)>0) ? true : false;
}

function material_dirname($mid)
{
	$sql = "SELECT filename FROM ocw_material_files WHERE material_id=$mid";
	$res = mysql_query($sql);
	$r = mysql_fetch_object($res);
	return $r->filename; 
}

function create_course_fileinfo($cid, $uniq_str) 
{
	$dirname = '';
	if (!course_added($cid)) {
			$dirname = random_name($uniq_str,'courses');
			if (DEBUG) {
					print "<br>DEBUG: Creating  course info: $uploads_dir$dirname<br>";
			} else {
		  		$sql = "INSERT INTO ocw_course_files VALUES('',$cid,'$dirname',NOW(),NOW());";
					mysql_query($sql) or die(mysql_error());
					if (old_course_exists($cid)) {
							rename(UPLOADS_DIR."c$cid", UPLOADS_DIR.'cdir_'.$dirname);
					} else {
							create_dirs(UPLOADS_DIR.'cdir_'.$dirname);
					}
			}
	} else {
			$dirname = UPLOADS_DIR.'cdir_'.course_dirname($cid);
			if (!is_dir($dirname)) {
					if (DEBUG) { 
							print "<br>DEBUG: This course needs a directory..";
							if (old_course_exists($cid)) { print "....RENAMING FILE"; } 
							else { print "....CREATING FILE"; }

					} else {
							if (old_course_exists($cid)) {
									rename(UPLOADS_DIR."c$cid", $dirname);
							} else {
									create_dirs($dirname);
							}
					}
			} else {
					if (DEBUG) { print "<br>DEBUG: This course is set ($dirname)"; }
			}
	}
	return $dirname;
}

function old_course_exists($cid)
{
	$dir =  UPLOADS_DIR."c$cid";
	return (is_dir($dir)) ? true : false; 
}

function course_added($cid)
{
	$sql = "SELECT * FROM ocw_course_files WHERE course_id=$cid";
	$res = mysql_query($sql);
	return (mysql_num_rows($res)>0) ? true : false;
}

function course_dirname($cid)
{
	$sql = "SELECT filename FROM ocw_course_files WHERE course_id=$cid";
	$res = mysql_query($sql);
	$r = mysql_fetch_object($res);
	return $r->filename; 
}


function in_table($file, $ent)
{
		switch($ent) {
			case 'courses': $table = 'ocw_course_files'; break;
			case 'materials': $table = 'ocw_material_files'; break;
			case 'co': $table = 'ocw_object_files'; break;
			default: exit('Cannot recoginze entity');				
		}

		$sql = "SELECT filename FROM $table WHERE filename='$file'";
		$res = mysql_query($sql);
		return (mysql_num_rows($res)>0) ? true : false;
}

function random_name($name='', $caller)
{
	$digest = '';
	do {
  			$str = time().rand(1,10000000000).$name;
				$digest = sha1($str);
	} while (in_table($digest, $caller));

	return $digest;
}

function file_digest($filename, $caller)
{
	$digest = '';
	$make_ourown = false;
	do {
			if ($make_ourown) {
					$digest = random_name($digest,$caller); 
			} else {
					$digest = sha1_file($filename);
			}
			$make_ourown = true;
	} while (in_table($digest, $caller));

	return $digest;
}

function create_dirs($path)
{
   if (!is_dir($path)) {
      	$directory_path = "";
      	$directories = explode("/",$path);
      	//array_pop($directories);

      	foreach($directories as $directory) {
        				$directory_path .= $directory."/";
								//print "$direcotry_path<br>";
        				if (!is_dir($directory_path)) {
         						mkdir($directory_path);
          					chmod($directory_path, 0777);
        				}
      	}
    }
}
?>
