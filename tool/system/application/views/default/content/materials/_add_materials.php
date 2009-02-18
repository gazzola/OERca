<?php		
echo style('blueprint/screen.css',array('media'=>"screen, projection"));
echo style('blueprint/print.css',array('media'=>"print"));
echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
echo style('style.css',array('media'=>"screen, projection"));
echo style('mootabs1.2.css',array('media'=>"screen, projection"));
echo '<style type="text/css">body { background-color: #222; padding: 15px; margin:auto; width: 340px; border:0px solid blue; height:450px; color:#999}</style>';

echo script('mootools.js');
echo script('mootabs1.2.js');
echo script('event-selectors.js');
echo script('event-rules.js');

echo script('flash.js'); 

$tags[0] = '-- select --';

$flash=$this->db_session->flashdata('flashMessage');
if (isset($flash) AND $flash!='') { 
?>
<!--START FLASH MESSAGE-->
<div id="statusmsg" class="column span-8 first last">
  <div id="flashMessage" style="display:none;"><?=$flash?></div>
</div>
<!--END FLASH-->
<?php } ?>

<div id="myTabs" class="column span-8 first last">

  <ul class="mootabs_title">
    <li title="Single" style="margin-left:0;"><h2>Single Upload</h2></li>
    <li title="Bulk" style="margin-left: 13px;"><h2>Bulk Upload</h2></li>
  </ul>

	<div id="Single" class="mootabs_panel">
		<form action="<?=site_url("materials/add_material/$cid/single")?>" enctype="multipart/form-data" method="post"  id="add_new_material_single">
			<input type="hidden" name="category" value="Materials" />
			<input type="hidden" name="in_ocw" value="1" />
			<input type="hidden" name="nodetype" value="parent" />

			<div class="formLabel">Author: (required)</div>
			<div class="formField">
					<input type="text" name="author" id="author" class="input" size="40px"  />
			</div>

			<br/>

			<div class="formLabel">Collaborators:</div>
			<div class="formField">
					<input type="text" name="collaborators" id="collaborators" class="input" size="40px"  />
			</div>

			<br/>

			<div class="formLabel">CTools URL:</div>
			<div class="formField">
					<input type="text" name="ctools_url" id="ctools_url" class="input" size="40px"  />
			</div>

			<br/>

			<div class="formLabel">Material Type: (required)</div>
			<div class="formField">
					<?php echo form_dropdown('tag_id', $tags, '','id="tag_id"'); ?>
			</div>

			<br/>

			<div class="formLabel">File Type:</div>
			<div class="formField">
					<?php echo form_dropdown('mimetype_id', $mimetypes, ''); ?>
			</div>

			<br/>

			<div class="formField">Automatically Extract Embedded Content Objects?:<br/>
		        <input type="radio" name="embedded_co" id="emip_yes" class="update_material" value="1" checked="checked" />&nbsp;Yes
		        <input type="radio" name="embedded_co" id="emip_no" class="update_material" value="0" />&nbsp;No
			</div>

			<br/>

			<div class="formLabel">Material: (required)</div>
			<div class="formField">
		      	<input type="file" name="single_userfile" id="single_userfile" size="30" />
		  </div>

	  	<div class="formField"><br/><input type="submit" value="Add" /></div>		
	</form>
</div>
	
<div id="Bulk" class="mootabs_panel"> 
	<form action="<?=site_url("materials/add_material/$cid/bulk")?>" enctype="multipart/form-data" method="post" id="add_new_material_bulk">
		<input type="hidden" name="category" value="Materials" />
		<input type="hidden" name="in_ocw" value="1" />
		<input type="hidden" name="nodetype" value="parent" />
		<input type="hidden" name="ctools_url" value="" />
		<input type="hidden" name="mimetype_id" value="6" />
		<input type="hidden" name="tag_id" value="15" />
		<input type="hidden" name="embedded_co" value="1" />
		
		
		<div class="formLabel">Author: (required)</div>
		<div class="formField">
			<input type="text" name="author" id="author" class="input" size="40px"  />
		</div>

			<br/>

		<div class="formLabel">Collaborators:</div>
		<div class="formField">
			<input type="text" name="collaborators" id="collaborators" class="input" size="40px"  />
		</div>
				
			<br/>

		<div class="formField">Zip file of Materials: (required)</div>
		<div class="formField">
	      	<input type="file" name="zip_userfile" id="zip_userfile" size="30" />
	  </div>

  	<div class="formField"><br/><input type="submit" value="Add" /></div>		
	</form>
</div>

<br style="clear:both"/>
<input type="button" style="float:right" value="Close" onclick="parent.window.location.reload(); parent.TB_remove();"/>
</div>
  

<div id="feedback" style="display:none"></div>
<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="imgurl" value="<?=property('app_img')?>" />
<input type="hidden" id="server" value="<?=site_url();?>" />
<script type="text/javascript">
 	EventSelectors.start(Rules);
	myCOTabs = new mootabs('myTabs',{height: '450px', width: '340px'});
	<?php if($view=='bulk') {?>myCOTabs.activate('Bulk');<?php }?>
</script>
