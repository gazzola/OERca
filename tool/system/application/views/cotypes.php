<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Content Object Types</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 
	   echo style('blueprint/screen.css',array('media'=>"screen, projection"));
	   echo style('blueprint/print.css',array('media'=>"print"));
	   echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
	   echo style('style.css',array('media'=>"screen, projection"));
	   echo style('table.css',array('media'=>"screen, projection"));
     echo script('tablesort.js');
     
     	$server = base_url();
	?>
</head>

<body>

<h2>Content Object Types</h2>

<?php
/*
$select_html = "<select name=\"posted_type\">";
foreach ($co_types as $t) {
	//if ($co_type['id'] == $posted_type) $selected = 'SELECTED'; else $selected = '';
    $select_html .= "<option value=\"".$t['id']."\">".$t['name']."</option>";
}
$select_html .= "</select>";
*/
echo "| ";
foreach ($co_types as $t) {
    echo " <a href=".$server."/cotypes/cotypes/".$t['id'].">".$t['name']."</a> |";
}
?>
<!--
<form method="post">
<label for="posted_type\">Subtype to list: </label>
<?php echo $select_html; ?>
<br />
<br />
<input type="submit" name="submit" value="Show Results" />
</form>
-->
<div style="margin: 25px;">
   	<h1><?php echo $count; ?> Objects Found:</h1>
   	<table class="sortable-onload-1 rowstyle-alt no-arrow">
    <tr>
    	<th>Obj Name</th>
    	<th>Location</th>
    	<th width=50>Citation</th>
    	<th>Action_type</th>
    	<th>Done?</th>
    	<th>Link</th>
    </tr>

<?php

   function get_imgurl($path, $name, $pre_ext = '', $location = NULL) {
    $base_url = property('app_uploads_url') . $path . "/";
    $base_path = property('app_uploads_path') . $path . "/";
    
    $file_details;      
    if ($location) {
      $base_path .= "{$name}_" . $pre_ext . "_" . $location;
      $base_url .= "{$name}_" . $pre_ext . "_" . $location;
    } else {
      $base_path .= $name . "_" . $pre_ext;
      $base_url .= $name . "_" . $pre_ext;
    }
    
		// Prepare for failure
		$file_details['imgurl'] = '';
		$file_details['imgpath'] = '';
		$file_details['img_found'] = false;
		$file_details['thumburl'] = '';
		$file_details['thumbpath'] = '';
		$file_details['thumb_found'] = false;

		// Search for lower-case first, then upper-case.
		// These are also listed in order of likely-hood
		$supported_exts = array (".png", ".jpg", ".gif", ".tiff", ".svg",
		                         ".PNG", ".JPG", ".GIF", ".TIFF", ".SVG");

		foreach ($supported_exts as $ext) {
			$path = $base_path . $ext;
			if (is_readable($path)) {
				$file_details['imgpath'] = $path;
				$file_details['imgurl'] = $base_url . $ext;
				$file_details['img_found'] = true;
/*
				// See if there is a corresponding thumbnail file
				// If none is found, try to create one and look again
				$tried_create = 0;
				while ($pre_ext != "slide" && $file_details['thumb_found'] == false && $tried_create <= 1 ) {
					$thumb_extensions = array(".png", $ext);
					foreach ($thumb_extensions as $te) {
						$thumbpath = $base_path . "_thumb" . $te;
						if (is_readable($thumbpath)) {
							$file_details['thumbpath'] = $thumbpath;
							$file_details['thumburl'] = $base_url . "_thumb" . $te;
							$file_details['thumb_found'] = true;
							break;
						}
					}

				}
				break;
				*/
			}
		}

   	return $file_details;
  }
  
  
	$results_html = "";
		//echo "<pre>"; print_r($cos); echo "</pre>";
	if ($count > 0) {
		foreach ($cos as $c) {
			foreach ($c as $var=>$val) $$var = $val;
			
			$name = $this->coobject->object_filename($oid);
			$path = $this->coobject->object_path($cid, $mid,$oid);
			$defimg = ($type=='orig') ? 'noorig.png' : 'norep.png';
			$dflag = ($type=='orig') ? 'grab' : 'rep';
      		$image_details = get_imgurl($path, $name, $dflag);
      		$imgurl = $image_details['imgurl'];
      		$imgpath = $image_details['imgpath'];
      		$thumb_found = $image_details['thumb_found'];
	   		$imgurl = ($thumb_found) ? $imgurl : property('app_img').'/'.$defimg;
	   		$imgpath = ($thumb_found) ? $imgpath : property('app_img').'/'.$defimg;
    		$link = $server."/uploads/cdir_".$cfilename."/mdir_".$mfilename."/odir_".$ofilename."/".$ofilename."_grab";
    		$link = $imgurl;
    		$img_html = "<a href=$link><img src=$link width=150/></a>";
    
    		$results_html .= <<<htmleoq
    
    <tr>
    	<td>$oname</td>
    	<td>$location</td>
    	<td>$citation</td>
    	<td>$action_type</td>
    	<td>$done</td>
    	<td>$img_html</td>
    </tr>

htmleoq;
		}
	} else {
		$results_html .= "<tr><td colspan=6>No Content Objects of this Type</td></tr>";
	}

	echo $results_html;
?>

	</table>
</div>
    

</body>
</html>