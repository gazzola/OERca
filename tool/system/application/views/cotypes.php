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
    echo " <a href=".$server."cotypes/cotypes/".$t['id'].">".$t['name']."</a> |";
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

   function get_imgurl($imgpath) {
		global $server;
		$file_details = array();
		$supported_exts = array (".png", ".jpg", ".gif", ".tiff", ".svg",".PNG", ".JPG", ".GIF", ".TIFF", ".SVG");
		$imgurl = property('app_img').'/noorig.png';
		foreach ($supported_exts as $ext) {
			$path = $server.$imgpath . $ext;
			if (is_readable($path)) {
				$imgurl = $path;
				$file_details['img_found'] = true;
				$thumb_extensions = array(".png", $ext);
				foreach ($thumb_extensions as $te) {
					$thumbpath = $base_path . "_thumb" . $te;
					if (is_readable($thumbpath)) {
						$imgurl = $thumbpath;
					}
					break;
				}
			}
			break;
		}
   	return $imgurl;
  }
  
  
	$results_html = "";
		//echo "<pre>"; print_r($cos); echo "</pre>";
	if ($count > 0) {
		foreach ($cos as $c) {
			foreach ($c as $var=>$val) $$var = $val;
    		$link = $server."/uploads/cdir_".$cfilename."/mdir_".$mfilename."/odir_".$ofilename."/".$ofilename."_grab";
    		$imgurl = get_imgurl($link);
    		
    		$img_html = "<a href=\"$imgurl\"><img src=\"$imgurl\" width=150 /></a>";
    
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