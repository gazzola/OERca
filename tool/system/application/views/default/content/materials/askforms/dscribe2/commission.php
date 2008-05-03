<?php 
$count = 1;
$sliders = array();

foreach($cos as $obj) {
  			$items = $obj['commission'];
			
			  foreach($items as $item) {
?>
<tr>
	<!-- first column -->
	<td valign="top" style="vertical-align:top;"><?=$count?></td>

	<!-- second column -->
	<td valign="top" style="vertical-align:top;">

		<!-- new/unseen questions -->
		<div id="new-col1-<?=$item['id']?>" style="display: <?=($item['status']=='in progress') ? 'none':'block'?>;">

     	<h3>dScribe Commission Rationale</h3>
   		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($item['rationale']=='') ? 'No rationale provided' : $item['rationale'] ?>
			</p>

			<!-- have a  replacement -->
			<p>
				<strong>Can you provide the dScribe with a replacement or a method for replacing this object?</strong><br/>
				<?= form_radio($item['yes_repl_data']) ?>	&nbsp; Yes&nbsp;
				<?= form_radio($item['no_repl_data']) ?>	&nbsp; No&nbsp;
			</p>

			<!-- I do have a replacement or know where to get it  -->
			<div id="have_replacement_yes_<?=$item['id']?>" style="display: <?= ($item['have_replacement']=='yes') ? 'block':'none'?>"> 
				<p>
					<strong>Please upload a replacement (if you can). Please provide the dScribe with any comments or suggestions for replacing
					the content object:</strong><br/><br/>
					Action: <?= form_dropdown("{$obj['id']}_commission_{$item['id']}_action",
																		$select_actions,$item['action'],'class="do_d2_claim_update"'); ?><br/><br/>
					<?= form_textarea($item['comments_ta_data']); ?>
				</p>
				<p><h3>Replacement</h3><p>
				<form action="<?=site_url("materials/update_object/$cid/$mid/{$obj['id']}/irep")?>" enctype="multipart/form-data" id="add_ip_rep" method = "post">
				<b>Upload Replacement Image:</b>
					<div class="formField" style="margin-right: 200px;">
      			<input type="file" name="userfile_0" id="userfile_0" size="30" />
						<input type="hidden" name="question" value="" />
						<input type="hidden" name="view" value="<?=$view?>" />
						<input type="hidden" name="comment" value="" />
						<input type="hidden" name="copyurl" value="" />
						<input type="hidden" name="copynotice" value="" />
						<input type="hidden" name="copyholder" value="" />
						<input type="hidden" name="copystatus" value="" />		       
		 				<small style="color:red">NB: any existing replacement image will be overwritten</small>	
      		</div>
					<div class="formField"><input type="submit" name="submit" id="submit" value="Upload" /></div>
			</form>
				<?php	
						$x = $this->coobject->replacement_exists($cid,$mid,$obj['id']);
        		if ($x) {
            		echo $this->ocw_utils->create_corep_img($cid,$mid,$obj['id'],$obj['location'],false,true);
        		} else {
            		echo '<img src="'.property('app_img').'/norep.png" width="85" height="85" />';
        		}
      	?>
       	<p><hr style="border: 1px solid #eee"/></p>
			</div>

			<!-- Don't have a replacement -->
			<div id="have_replacement_no_<?=$item['id']?>" style="display: <?= ($item['have_replacement']=='no') ? 'block':'none'?>"> 
				<p>
					<strong>Do you recommend commissioning a recreation of this object?</strong><br/><br/>
					<?= form_radio($item['yes_comm_data']) ?>	&nbsp; Yes&nbsp;
					<?= form_radio($item['no_comm_data']) ?>	&nbsp; No&nbsp;
				</p>

					<div id="recommend_commission_yes_<?=$item['id']?>" style="display: <?= ($item['recommend_commission']=='yes') ? 'block':'none'?>"> 
					<p>
						<strong>Please provide your rationale for this recommendation:</strong><br/><br/>
						<?= form_textarea($item['comments_ta_data']); ?>
					</p>
					</div>	

					<div id="recommend_commission_no_<?=$item['id']?>" style="display: <?= ($item['recommend_commission']=='no') ? 'block':'none'?>"> 
					<p>
						<strong>Please indicate your reasoning and recommend to the dScribe a new action for this content object:</strong><br/><br/>
						Action: <?= form_dropdown("{$obj['id']}_commission_{$item['id']}_action",
																		$select_actions,$item['action'],'class="do_d2_claim_update"'); ?><br/><br/>
					<?= form_textarea($item['comments_ta_data']); ?>
					</p>
					</div>	
			</div>

			<!-- save options  -->	
			<br/><br/ style="clear:both"><br/><br/>
			<p><?= form_submit($item['save_data']) ?>&nbsp;&nbsp;
				 <?= form_submit($item['send_data']) ?>&nbsp;&nbsp;
				 <?= form_submit($item['send_to_cr_data']) ?>&nbsp;&nbsp;
			</p>
		</div>

		<!-- saved for later -->
		<div id="inprogress-col1-<?=$item['id']?>" style="display:<?=($item['status']=='in progress')?'block':'none'?>;">
			<b>Saved for further editing later</b><br/><br/>
			<input type="button" value="Continue editing" id="open_<?=$item['id']?>" />
		</div>
	</td>

	<!-- third column -->
	<td style="vertical-align:top">
		<!-- new/unseen questions -->
		<div id="new-col2-<?=$item['id']?>" style="display: <?=($item['status']=='in progress') ? 'none':'block'?>;">
			<?php echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],false,false); ?>
			<br/><br/>

				<b>Content-Type:</b> <?=$this->coobject->get_subtype_name($obj['subtype_id'])?><br/><br/>

				<b>Description:</b> 
				<?php if ($obj['description']=='') { ?><span style="color:red">No description</span>
				<?php } else { echo $obj['description']; }?><br/><br/>

				<b>Author:</b> 
				<?php if ($obj['author']=='') { ?><span style="color:red">No author</span>
				<?php } else { echo $obj['author']; }?><br/><br/>

				<b>Contributor:</b> 
				<?php if ($obj['contributor']=='') { ?><span style="color:red">No contributor</span>
				<?php } else { echo $obj['contributor']; }?><br/><br/>

				<b>Citation:</b> 
				<?php if ($obj['citation']=='') { ?><span style="color:red">No citation</span>
				<?php } else { echo $obj['citation']; }?><br/><br/>

				<?php if (is_array($obj['copyright'])) { $c = $obj['copyright'];?>
						<b>Copyright Status:</b> <?=$c['status']?><br/>
						<b>Copyright Holder:</b> <?=$c['holder']?><br/>
						<b>Copyright Info URL:</b> <?=$c['url']?><br/>
						<b>Copyright Notice:</b> <?=$c['notice']?><br/>
				<?php } else { ?>
						<b>Copyright:</b> <span style="color:red">No copyright information</span>
				<?php } ?><br/><br/>

				<b>Action Taken:</b> 
				<?php if ($obj['action_taken']=='') { ?><span style="color:red">No action</span>
				<?php } else { echo $obj['action_taken']; }?><br/><br/>
		</div>

		<!-- saved for later -->
		<div id="inprogress-col2-<?=$item['id']?>" style="display:<?=($item['status']=='in progress')?'block':'none'?>;">
			<?php echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],false,true); ?>
  		<br/>
		</div>
	</td>
</tr>	

<?php
	// code to show and hide objects when saved for later
	$sliders[] = "var mySlide_newcol1_{$item['id']} = $('new-col1-{$item['id']}');
				  var mySlide_newcol2_{$item['id']} = $('new-col2-{$item['id']}');
				  var mySlide_inpcol1_{$item['id']} = $('inprogress-col1-{$item['id']}');
				  var mySlide_inpcol2_{$item['id']} = $('inprogress-col2-{$item['id']}');

				  $('open_{$item['id']}').addEvent('click', function(e) {
						e = new Event(e);
						mySlide_inpcol1_{$item['id']}.style.display = 'none';
						mySlide_inpcol2_{$item['id']}.style.display = 'none';
						mySlide_newcol2_{$item['id']}.style.display = 'block';
						mySlide_newcol1_{$item['id']}.style.display = 'block';
						e.stop();
				  });
				  $('close_{$item['id']}').addEvent('click', function(e) {
						e = new Event(e);
						mySlide_newcol1_{$item['id']}.style.display = 'none';
						mySlide_newcol2_{$item['id']}.style.display = 'none';
						mySlide_inpcol1_{$item['id']}.style.display = 'block';
						mySlide_inpcol2_{$item['id']}.style.display = 'block';
						e.stop();
				  });";
 	$count++; 
}} 
?>

<script type="text/javascript">
window.addEvent('domready', function() {
    <?php foreach($sliders as $slider) { echo $slider."\n"; } ?>
});
</script>
