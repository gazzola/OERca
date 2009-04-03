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
	.filterlist li.activeli {
		background-color: #FFF;
		border-bottom: 1px solid white;	
	}
</style>
</head>

<body>

<h2>Content Object Types</h2>

<?php

$select_html = "<select name=\"posted_type\" onChange=\"document.location=options[selectedIndex].value;\">";
foreach ($co_types as $t) {
	if ($t['id'] == $co_type_selected) $selected = 'SELECTED'; else $selected = '';
    $select_html .= "<option $selected value=\"".$server."cotypes/cotypes/".$t['id']."\">".$t['name']."</option>";
}
$select_html .= "</select>";

echo "<ul class=filterlist>";
foreach ($co_types as $t) {
	if ($t['id'] == $co_type_selected) $activeclass = 'class=activeli'; else $activeclass = '';
    echo "<li $activeclass><a href=".$server."cotypes/cotypes/".$t['id'].">".$t['name']."</a></li>";
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

  function get_imgurl($path, $name, $pre_ext = '') {
   	$base_url = property('app_uploads_url') . $path . "/";
    $base_path = property('app_uploads_path') . $path . "/";

    $file_details;    
    
    $base_path .= $name . "_" . $pre_ext;
    $base_url .= $name . "_" . $pre_ext;

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
			//echo $path."<br>";
			if (file_exists($path)) {
				$file_details['imgpath'] = $path;
				$file_details['imgurl'] = $base_url . $ext;
				$file_details['img_found'] = true;

				$thumb_extensions = array(".png", $ext);
				foreach ($thumb_extensions as $te) {
					$thumbpath = $base_path . "_thumb" . $te;
					if (@getimagesize($thumbpath)) {
						$file_details['thumbpath'] = $thumbpath;
						$file_details['thumburl'] = $base_url . "_thumb" . $te;
						$file_details['thumb_found'] = true;
						break;
					}
				}
			break;
			}
		}

   	return $file_details;
  }
    
	$results_html = "";
	$this->object =& get_instance();
	$this->object->load->model('coobject');
		//echo "<pre>"; print_r($cos); echo "</pre>";
	if ($count > 0) {
		foreach ($cos as $c) {
			foreach ($c as $var=>$val) $$var = $val;
    		//$name = $this->object->coobject->object_filename($oid);
			//$path = $this->object->coobject->object_path($cid, $mid,$oid);
			$name = $c['ofilename'];
			$path = "cdir_".$c['cfilename']."/mdir_".$c['mfilename']."/odir_".$c['ofilename'];
			$defimg = 'noorig.png';
			$dflag = 'grab';
    		$image_details = get_imgurl($path, $name, $dflag);
    		if ($image_details['thumb_found'] === true) {
				$imgurl = $image_details['thumburl'];
				$imgpath = $image_details['thumbpath'];
			} else  if ($image_details['img_found'] === true) {
				$imgurl = $image_details['imgurl'];
				$imgpath = $image_details['imgpath'];
			} else {
				$imgurl = property('app_img').'/'.$defimg;
				$imgpath = property('app_img').'/'.$defimg;
			}
			//echo "$imgurl <br>";
    		//$imgstyle = scalecoimage($imgurl, 150, 150);
    		$img_html = "<a href=\"$imgurl\"><img src=\"$imgurl\" width=150 /></a>";
    
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
	} elseif ($cos == NULL) {
		$results_html .= "<tr><td colspan=6>No Content Objects of this Type</td></tr>";
	} else {
		$results_html .= "<tr><td colspan=6>No Content Objects Type Selected</td></tr>";	
	}

	echo $results_html;
	
	?>

	</table>
</div>
    

</body>
</html>