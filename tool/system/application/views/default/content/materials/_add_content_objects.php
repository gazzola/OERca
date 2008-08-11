<?php
echo style('blueprint/screen.css',array('media'=>"screen, projection"));
echo style('blueprint/print.css',array('media'=>"print"));
echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
echo style('style.css',array('media'=>"screen, projection"));
echo style('mootabs1.2.css',array('media'=>"screen, projection"));
echo '<style type="text/css">body { background-color: #222; padding: 15px; margin:auto; width: 400px; border:0px solid blue; height:550px; color:#999}</style>';

echo script('mootools.js');
echo script('mootabs1.2.js');
echo script('mootips.js');
echo script('event-selectors.js');
echo script('event-rules.js');

echo script('snapper.js'); 

echo script('flash.js');

$types = '<select id="subtype_id" name="subtype_id" class="do_object_update">';
foreach($subtypes as $type => $subtype) {
		$types .= '<optgroup label="'.$type.'">';
		foreach($subtype as $st) { $types .= '<option value="'.$st['id'].'">'.$st['name'].'</option>'; }
		$types .= '</optgroup>';
} 
$types .= '</select>';
$snapper_tip = "Copy any image to the clipboard using a screen capture application or copy command. Once the image is on the clipboard, click Capture in the Snapper uploader to see the image.";
$loc_tip = "For textual materials like Powerpoints or PDFs, please enter the slide or page number. For videos, please enter a time stamp.";

$flash=$this->db_session->flashdata('flashMessage');
if (isset($flash) AND $flash!='') {
?>

<!--START FLASH MESSAGE-->
<div id="statusmsg" class="column span-10 first last">
  <div id="flashMessage" style="display:none;"><?=$flash?></div>
</div>
<!--END FLASH-->

<?php } ?>

<div id="myTabs" class="column span-10 first last">

  <ul class="mootabs_title">
    <li title="Snapper" style="margin-left:0;"><h2>Snapper Upload</h2></li>
    <li title="Single" style="margin-left:13px;"><h2>Single Upload</h2></li>
    <li title="Bulk" style="margin-left: 13px;"><h2>Bulk Upload</h2></li>
  </ul>


  <div id="Snapper" class="mootabs_panel"> 
			
				<?php print form_open_multipart("materials/snapper/$cid/$mid/submit",array('id'=>'snapper-form')) ?>	
				<div id="capture">
					<applet id="clipboard" width="200" height="200" archive="<?=site_url()?>snapper/Ssnapper.jar,<?=site_url()?>snapper/commons-codec-1.3.jar" code="org.muse.snapper.Snapper" codebase="<?=site_url()?>snapper/">
					</applet>
					<br />
					<input id="snap" type="button" value="Capture" />&nbsp;<img src="<?=property('app_img')?>/info.gif" style="margin:0; padding:0" class="tooltip" title="<?=$snapper_tip?>" />
					<div id="snap_status" style="margin-top: 5px;"></div>
					<input id="snap_image" type="hidden" name="image"/>
					<input id="snap_st" type="hidden" name="subtype_id" value="6"/>
				</div>
				
				<div id="meta" style="margin-top: 10px;">
					<h2>Meta Data</h2>
					<label>What are you capturing?</label>
				
					&nbsp;&nbsp;
			
					<input type="hidden" id="snap_type" value="object" />	
					<input class="snapper_captype" name="type" id="snap_aCaptureTypeObject" type="radio" value="object" checked="checked"/>
					<label for="aCaptureType">Original Object</label>
				
					&nbsp;&nbsp;
				
					<input class="snapper_captype" name="type" id="snap_aCaptureTypeSlide" type="radio" value="slide" />
					<label for="aCaptureType">Context Image</label>
				
					<div id="contentloc">
						<br/>
						<label for="snap_location" class="tooltip" title="<?=$loc_tip?>">Location in material:</label>
						<input id="snap_location" name="location" type="text" width="30" />
						&nbsp;<img src="<?=property('app_img')?>/info.gif" style="margin:0; padding:0" class="tooltip" title="<?=$loc_tip?>" />
					</div>
				</div>
				
				<div id="action" style="margin-top:20px;">
					<input id="snap_save" type="button" value="Save" />
				</div>
				<?php print form_close(); ?>
  </div>

  <div id="Single" class="mootabs_panel"> 
		<form action="<?=site_url("materials/add_object/$cid/$mid/single/add")?>" enctype="multipart/form-data" id="add_co_single" method="post">
			<input type="hidden" name="citation" value="none" />
			<input type="hidden" name="contributor" value="" />
			<input type="hidden" name="question" value="" />
			<input type="hidden" name="comment" value="" />
			<input type="hidden" name="copyurl" value="" />
			<input type="hidden" name="copynotice" value="" />
			<input type="hidden" name="copyholder" value="" />
			<input type="hidden" name="copystatus" value="" />
			
			<div class="formLabel">Ask Instructor:</div>
			<div class="formField">
				<input type="radio" name="ask" value="yes"/>&nbsp;Yes&nbsp;
		  	<input type="radio" name="ask" value="no" checked="checked"/>&nbsp;No
			</div>
	
			<br/>		

			<div class="formLabel">Content Type:</div>
    	<div class="formField"><?=$types?></div>
		
			<br/>		

			<div class="formField" class="tooltip" title="<?=$loc_tip?>">Location in material (required):</div>
    	<div class="formField">
    			<input type="text" name="location" id="location" size="50" class="input" />
&nbsp;<img src="<?=property('app_img')?>/info.gif" style="margin:0; padding:0" class="tooltip" title="<?=$loc_tip?>" />
			</div>
		
			<br/>		

 			<div class="formField">Upload Content Object: (required)</div>
			<div class="formField">
	     	<input type="file" name="userfile_0" id="userfile_0" size="30" />
			</div>
			
			<div class="formField"><br/><input type="submit" value="Add" /></div>
		</form>
	</div>
	
  <div id="Bulk" class="mootabs_panel"> 

		<div class="formField">
			<form action="<?=site_url("materials/add_object/$cid/$mid/bulk/add")?>" enctype="multipart/form-data" id="add_co_zip" method="post">
			  Upload Content Objects ZIP file: (required)
	     	<input type="file" name="userfile" id="userfile" size="30" />
	       <br/><br/>
	     	<input type="submit" name="submit" id="submit" value="Add" />
			</form>
	  </div>
	</div>

<br style="clear:both"/><input type="button" style="float:right" value="Done" onclick="parent.window.location.reload(); parent.TB_remove();"/>
</div>

<div id="feedback" style="display:none"></div>
<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$mid?>" />
<input type="hidden" id="imgurl" value="<?=property('app_img')?>" />
<input type="hidden" id="server" value="<?=site_url();?>" />
<script type="text/javascript">
  EventSelectors.start(Rules);
	window.addEvent('domready', function() {
  		myCOTabs = new mootabs('myTabs',{height: '450px', width: '340px'});
			var myTips1 = new MooTips($$('.tooltip'), { maxTitleChars: 100 });
  		<?php if($view=='single') {?>myCOTabs.activate('Single');<?php }?>
  		<?php if($view=='bulk') {?>myCOTabs.activate('Bulk');<?php }?>

      var appv =  ($('snapper-form')) ? true : false;
      var appletview = (appv)  ? document.clipboard : '';
      if (appv) appletview.style.display='block';
	});
</script>
