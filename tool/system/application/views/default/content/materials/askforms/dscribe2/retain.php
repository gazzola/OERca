<?php 
$count = 1;
$sliders = array();

foreach($cos as $obj) {
  			$items = $obj['retain'];
			
			  foreach($items as $item) {
?>
<tr>
	<!-- first column -->
	<td valign="top" style="vertical-align:top;"><?=$count?></td>

	<!-- second column -->
	<td valign="top" style="vertical-align:top;">

		<!-- new/unseen questions -->
		<div id="new-col1-<?=$item['id']?>" style="display: <?=($item['status']=='in progress') ? 'none':'block'?>;">

     	<h3><?=$this->ocw_user->username($item['user_id']) ?>'s (dScribe) Rationale:</h3>
   		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($item['rationale']=='') ? 'No rationale provided' : $item['rationale'] ?>
			</p>

			<!-- accept rationale? -->
			<p>
				<br/><br/>
				<strong>
				Do you accept the dScribe's action recommendation to retain this content object because it has no
				copyright?
				</strong><br/>
				<?= form_radio($item['yes_rationale_data']) ?>	&nbsp; Yes&nbsp;
				<?= form_radio($item['no_rationale_data']) ?>	&nbsp; No&nbsp;
				<?= form_radio($item['unsure_rationale_data']) ?>	&nbsp; Unsure&nbsp;
			</p>

			<!-- accept rationale -->
			<div id="accept_rationale_yes_<?=$item['id']?>" style="display: <?= ($item['accept_rationale']=='yes') ? 'block':'none'?>"> 
				<br/><br/>
				<p>
					<strong>Please provide your rationale for why this object has no copyrights:</strong><br/><br/>
							<?= form_textarea($item['comments_ta_data']); ?><br/>
						<?php if ($item['comments']<>'' && $item['modified_by']<>'') { ?>
							<small>Last modified by: <?=$this->ocw_user->username($item['modified_by'])?></small><br/>
						<?php } ?>
				</p>

				<!-- save options  -->	
				<br/><br/>
				<p><?= form_submit($item['save_data']) ?>&nbsp;&nbsp;<?= form_submit($item['send_data']) ?>&nbsp;&nbsp;</p>
			</div>

			<!-- reject rationale -->
			<div id="accept_rationale_no_<?=$item['id']?>" style="display: <?= ($item['accept_rationale']=='no') ? 'block':'none'?>"> 
				<br/><br/>
				<p>
					<strong>Provide your reasoning to the dScribe and recommend a new action for this content object: </strong><br/><br/>
					Action: <?= form_dropdown("{$obj['id']}_retain_{$item['id']}_action",
																		$select_actions,$item['action'],'class="do_d2_claim_update"'); ?><br/><br/>
					<?= form_textarea($item['comments_ta_data']); ?><br/>
					<?php if ($item['comments']<>'' && $item['modified_by']<>'') { ?>
							<small>Last modified by: <?=$this->ocw_user->username($item['modified_by'])?></small><br/>
					<?php } ?>
				</p>

				<!-- save options  -->	
				<br/><br/>
				<p><?= form_submit($item['save_data']) ?>&nbsp;&nbsp;<?= form_submit($item['send_data']) ?>&nbsp;&nbsp;</p>
			</div>

			<!-- reject rationale -->
			<div id="accept_rationale_unsure_<?=$item['id']?>" style="display: <?= ($item['accept_rationale']=='unsure') ? 'block':'none'?>"> 
				<br/><br/>
				<p>
					<strong>Please provide additional comments or rationale for the Legal and Policy Review team:</strong><br/><br/>
					<?= form_textarea($item['comments_ta_data']); ?><br/>
					<?php if ($item['comments']<>'' && $item['modified_by']<>'') { ?>
							<small>Last modified by: <?=$this->ocw_user->username($item['modified_by'])?></small><br/>
					<?php } ?>
				</p>

				<!-- save options  -->	
				<br/><br/>
				<p><?= form_submit($item['save_data']) ?>&nbsp;&nbsp;<?= form_submit($item['send_to_ip_data']) ?></p>
			</div>

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
