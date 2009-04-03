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
	
<style>
	.filterlist { list-style-type:none; }
	.filterlist li {
		padding: 3px 5px;
		background-color: #EFEFEF;
		border: 1px solid #998;
		display: inline;
	}
	.filterlist li:hover {
		background-color: #AAA;
		color: #EEE;	
	}
</style>
</head>

<body>

<h2>Content Object Types</h2>

<?php

$select_html = "<select name=\"posted_type\" onChange=\"document.location=options[selectedIndex].value;\">";
foreach ($co_types as $t) {
	//if ($co_type['id'] == $posted_type) $selected = 'SELECTED'; else $selected = '';
    $select_html .= "<option value=\"".$server."cotypes/cotypes/".$t['id']."\">".$t['name']."</option>";
}
$select_html .= "</select>";

echo "<ul class=filterlist>";
foreach ($co_types as $t) {
    echo "<li><a href=".$server."cotypes/cotypes/".$t['id'].">".$t['name']."</a></li>";
}
echo "</ul>";
?>

<form method="post">
<label for="posted_type\">Subtype to list: </label>
<?php echo $select_html; ?>
</form>

<div style="margin: 25px;">
   	<h1><?php echo $count; ?> Objects Found:</h1>
   	<table class="sortable-onload-1 rowstyle-alt no-arrow">
    <tr>
    	<th>Obj Name</th>
    	<th>Location</th>
    	<th width=250>Citation</th>
    	<th>Action_type</th>
    	<th>Done?</th>
    	<th>Link</th>
    </tr>

<?php

	function scalecoimage($location, $maxw=NULL, $maxh=NULL){
		    $img = @getimagesize($location);
		    if($img){
		        $w = $img[0];
		        $h = $img[1];
		
		        $dim = array('w','h');
		        foreach($dim AS $val){
		            $max = "max{$val}";
		            if(${$val} > ${$max} && ${$max}){
		                $alt = ($val == 'w') ? 'h' : 'w';
		                $ratio = ${$alt} / ${$val};
		                ${$val} = ${$max};
		                ${$alt} = ${$val} * $ratio;
		            }
		        }
		        $hoffset = ($maxh - $h)/2;
		        $woffset = ($maxw - $w)/2;
				$style_line = "width: ".$w."px; height: ".$h."px; margin-top: ".$hoffset."px; margin-bottom: ".$hoffset."px; margin-left: ".$woffset."px; margin-right: ".$woffset."px;";
		    } else {
		    	$style_line = "width: 150px; height: 150px; margin-top: 0px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px;";
		    }
		    return($style_line);
	}

   function get_imgurl($imgpath) {
		global $server;
		$file_details = array();
		$supported_exts = array (".png", ".jpg", ".gif", ".tiff", ".svg",".PNG", ".JPG", ".GIF", ".TIFF", ".SVG");
		$imgurl = property('app_img').'/noorig.png';
		foreach ($supported_exts as $ext) {
			$path = $server.$imgpath . $ext;
			if (@file_get_contents($path,0,NULL,0,1)) {
				$imgurl = $path;
				$thumb_extensions = array(".png", $ext);
				foreach ($thumb_extensions as $te) {
					$thumbpath = $path . "_thumb" . $te;
					if (@file_get_contents($thumbpath,0,NULL,0,1)) {
						$imgurl = $thumbpath;
						break;
					}
				}
				break;
			}
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
    		$imgstyle = scalecoimage($imgurl, 150, 150);
    		$img_html = "<a href=\"$imgurl\"><img class=\"bot\" src=\"$imgurl\" style=\"$imgstyle\" /></a>";
    
    		$results_html .= <<<htmleoq
    
    <tr>
    	<td style="vertical-align: top;">$oname</td>
    	<td style="vertical-align: top;">$location</td>
    	<td style="vertical-align: top;">$citation</td>
    	<td style="vertical-align: top;">$action_type</td>
    	<td style="vertical-align: top;">$done</td>
    	<td style="vertical-align: top;">$img_html</td>
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