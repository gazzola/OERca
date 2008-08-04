<?php 
$count = 1;
$sliders = array();

foreach($cos as $obj) {
  			$items = $obj['permission'];
			
			  foreach($items as $item) {
					if ($item['status']<>'done') {
?>
<tr>
	<!-- first column -->
	<td valign="top" style="vertical-align:top;"><?=$count?></td>

	<!-- second column -->
	<td valign="top" style="vertical-align:top;">

		<!-- new/unseen questions -->
		<div id="new-col1-<?=$item['id']?>" style="display: <?=($item['status']=='in progress') ? 'none':'block'?>;">

     	<h3>CO Description:</h3>
   		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($obj['description']=='') ? 'No description provided' : $obj['description'] ?>
			</p>

     	<h3>Contact Information:</h3>
   		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($item['contact_name']=='') ? '<span style="color:red">No contact name</span>' : $item['contact_name'] ?><br/>
					<?= ($item['contact_line1']=='') ? '<span style="color:red">No street address</span>' : $item['contact_line1'] ?><br/>
					<?= ($item['contact_line2']=='') ? '' : $item['contact_line1'].'<br/>'; ?>
					<?= ($item['contact_city']=='') ? '<span style="color:red">No city</span>' : $item['contact_city'] ?>,&nbsp;
					<?= ($item['contact_state']=='') ? '<span style="color:red">No state</span>' : $item['contact_state'] ?>&nbsp;&nbsp;
					<?= ($item['contact_postalcode']=='') ? '' : $item['contact_postalcode'] ?><br/>
					<?= ($item['contact_country']=='') ? '<span style="color:red">No country</span>' : $item['contact_state'] ?><br/><br/>
					<?= ($item['contact_fax']=='') ? '' : 'Fax: '.$item['contact_fax'].'<br/>' ?>
					<?= ($item['contact_phone']=='') ? '' : 'Phone: '.$item['contact_phone'].'<br/>' ?>
					<?= ($item['contact_email']=='') ? '' : 'Email: '.safe_mailto($item['contact_email'],$item['contact_email']).'<br/>' ?>
			</p>

			<!-- warrant permission -->
			<p>
				<br/><br/>
				<strong>Given this content object and the added information, do you wish to send a permission form to the copyright holder?</strong><br/>
				<?= form_radio($item['yes_info_data']) ?>	&nbsp; Yes&nbsp;
				<?= form_radio($item['no_info_data']) ?>	&nbsp; No&nbsp;
			</p>

			<!-- permission form sent? -->
			<div id="info_sufficient_yes_<?=$item['id']?>" style="display: <?= ($item['info_sufficient']=='yes') ? 'block':'none'?>"> 
				<br/><br/>
				<p>
					<strong>Has the form been sent to the copyright holder requesting permission to use this content object?</strong><br/>
					<?= form_radio($item['yes_sent_data']) ?>	&nbsp; Yes&nbsp;
					<?= form_radio($item['no_sent_data']) ?>	&nbsp; No&nbsp;
				</p>
			
				<!-- Any response from copyright holders? -->
				<div id="letter_sent_yes_<?=$item['id']?>" style="display: <?= ($item['letter_sent']=='yes') ? 'block':'none'?>"> 
					<br/><br/>
					<p>
						<strong>Has Open.Michigan received a response from the copyright holder of the content object?</strong><br/> 
						<?= form_radio($item['yes_received_data']) ?>	&nbsp; Yes&nbsp;
						<?= form_radio($item['no_received_data']) ?>	&nbsp; No&nbsp;
					</p>

						<!-- Received a response from copyright holders! -->
						<div id="response_received_yes_<?=$item['id']?>" style="display: <?= ($item['response_received']=='yes') ? 'block':'none'?>"> 
							<br/><br/>
							<p>
								<strong>Has the copyright holder granted permission to Open.Michigan to use this comtent object under a CC-By or similar license?</strong><br/> 
								<?= form_radio($item['yes_approved_data']) ?>	&nbsp; Yes&nbsp;
								<?= form_radio($item['no_approved_data']) ?>	&nbsp; No&nbsp;
							</p>
							<!-- negative response :( what should we do now? --> 
							<div id="approved_no_<?=$item['id']?>" style="display: <?= ($item['approved']=='no') ? 'block':'none'?>"> 
								<br/>
								<p>
									<strong>Provide a comment and action recommendation for the dScribe:</strong><br/><br>
									Action: <?= form_dropdown("{$obj['id']}_permission_{$item['id']}_action",
																		$select_actions,$item['action'],'class="do_d2_claim_update"'); ?><br/><br/>
									<?= form_textarea($item['comments_ta_data']); ?><br/>
									<?php if ($item['comments']<>'' && $item['modified_by']<>'') { ?>
											<small>Last modified by: <?=$this->ocw_user->username($item['modified_by'])?></small><br/>
									<?php } ?>
								</p>
							</div>
					 </div>
				</div>
			</div>

			<!-- Explain why letter is not being sent -->
			<div id="info_sufficient_no_<?=$item['id']?>" style="display: <?= ($item['info_sufficient']=='no') ? 'block':'none'?>"> 
				<br/><br/>
				<p>
					<strong>Please indicate to the dScribe why you are not sending a permission form to the
					       copyright holder and recommend a new action for this content object:</strong><br/><br/>
					Action: <?= form_dropdown("{$obj['id']}_permission_{$item['id']}_action",
																		$select_actions,$item['action'],'class="do_d2_claim_update"'); ?><br/><br/>
					<?= form_textarea($item['comments_ta_data']); ?><br/>
					<?php if ($item['comments']<>'' && $item['modified_by']<>'') { ?>
								<small>Last modified by: <?=$this->ocw_user->username($item['modified_by'])?></small><br/>
					<?php } ?>
				</p>
			</div>


			<!-- save options  -->	
			<br/><br/>
			<p><?= form_submit($item['save_data']) ?>&nbsp;&nbsp;<?= form_submit($item['send_data']) ?></p>
		</div>

		<!-- saved for later -->
		<div id="inprogress-col1-<?=$item['id']?>" style="display:<?=($item['status']=='in progress')?'block':'none'?>;">
			<b>Saved for further editing later</b><br/><br/>
			<input type="button" value="Continue editing" id="open_<?=$item['id']?>" />
		</div>
	</td>

	<!-- third column -->
	<td style="vertical-align:top">
		<div id="new-col2-<?=$item['id']?>" style="display: <?=($item['status']=='in progress') ? 'none':'block'?>;">
			<?php echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],'permission','orig',false,true,true); ?>
			<br/><br/>

			<?php 
				$data['obj'] = $obj;
				$this->load->view(property('app_views_path').'/materials/askforms/dscribe2/thirdcol.php', $data); 
			?> 
		</div>

		<!-- saved for later -->
		<div id="inprogress-col2-<?=$item['id']?>" style="display:<?=($item['status']=='in progress')?'block':'none'?>;">
			<?php echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],'permission','orig',true,true,true); ?>
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
}}} 
?>

<script type="text/javascript">
window.addEvent('domready', function() {
    <?php foreach($sliders as $slider) { echo $slider."\n"; } ?>
    var myTips = new MooTips($$('.ine_tip'), { maxTitleChars: 50 });
});
</script>
