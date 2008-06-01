<html>
<head>
<?php 
	$server = site_url(); 
	echo style('style.css',array('media'=>"screen, projection"));
	echo script('jquery-1.2.3.js'); 
	echo script('snapper.js'); 
?>
  <style type="text/css">
		* {margin:0; padding:0;}
		body { background-color: white; height: 460px; width: 360px; padding:5px;}
  </style>
</head>

<body>
<input type="hidden" name="cid" id="cid" value="<?=$cid?>" />
<input type="hidden" name="mid" id="mid" value="<?=$mid?>" />
<input type="hidden" name="type" id="type" value="object" />

<div id="controls">
	<div>
		<input id="aCapture" type="checkbox" checked="checked"/>
		<label for="aCapture">auto capture</label>
	</div>
	<!--
	<div>
		<input id="aSend" type="checkbox" />
		<label for="aSend">auto send</label>
	</div>
	-->
</div>

<div id="capture">
	<applet id="clipboard" width="200" height="200" archive="<?=site_url()?>snapper/Ssnapper.jar,<?=site_url()?>snapper/commons-codec-1.3.jar" code="org.muse.snapper.Snapper" codebase="<?=site_url()?>snapper/">
	</applet>
	<br />
	<input id="snap" type="button" value="Capture" />
	<div id="status" style="margin-top: 5px;"></div>
	<input id="image" type="hidden" />
</div>

<div id="meta" style="margin-top: 10px;">
	<h2>Meta Data</h2>
	<label>What are you capturing?</label>

	&nbsp;&nbsp;

	<input class="snapper_captype" name="aCaptureType" id="aCaptureTypeObject" type="radio" value="object" checked="checked"/>
	<label for="aCaptureType">Original Object</label>

	&nbsp;&nbsp;

	<input class="snapper_captype" name="aCaptureType" id="aCaptureTypeSlide" type="radio" value="slide" />
	<label for="aCaptureType">Slide</label>

	<div id="contenttype" style="display:block">		
		<br />
		<label for="location">Content Type:</label>
		<?php
					$types = '<select id="subtype_id" name="subtype_id" class="do_object_update">';
					foreach($select_subtypes as $type => $subtype) {
									$types .= '<optgroup label="'.$type.'">';
									foreach($subtype as $st) {
													$sel = ($st['name']=='None') ? 'selected' : '';
													$types .= '<option value="'.$st['id'].'" '.$sel.'>'.$st['name'].'</option>';
    							}
									$types .= '</optgroup>';
					} 
					$types .= '</select>';
					echo $types; 
		?>
	</div>

	<div id="contentloc">
		<br />
		<label for="location">Slide number:</label>
		<input id="location" type="text" width="30" />
	</div>
</div>

<div id="action" style="margin-top:20px;">
	<input id="save" type="button" value="Save" />
	<input id="server" type="hidden" value="<?=$server?>" />
</div>

</body>
</html>
