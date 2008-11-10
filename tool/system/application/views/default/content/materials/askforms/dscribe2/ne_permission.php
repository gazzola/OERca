<?php 
$count = 1;

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
		<div>

     	<h3>Content Object Description:</h3>
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
    		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($item['info_sufficient']=='pending') ? 'No answer provided yet' : $item['info_sufficient'] ?>
				</p>
			</p>

			<!-- permission form sent? -->
			<div style="display: <?= ($item['info_sufficient']=='yes') ? 'block':'none'?>"> 
				<br/><br/>
				<p>
					<strong>Has the form been sent to the copyright holder requesting permission to use this content object?</strong><br/>
    			<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
						<?= $item['letter_sent'] ?>
					</p>
				</p>
			
				<!-- Any response from copyright holders? -->
				<div style="display: <?= ($item['letter_sent']=='yes') ? 'block':'none'?>"> 
					<br/><br/>
					<p>
						<strong>Has Open.Michigan received a response from the copyright holder of the content object?</strong><br/> 
    				<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
							<?= $item['response_received'] ?>
						</p>
					</p>

						<!-- Received a response from copyright holders! -->
						<div style="display: <?= ($item['response_received']=='yes') ? 'block':'none'?>"> 
							<br/><br/>
							<p>
								<strong>Has the copyright holder granted permission to Open.Michigan to use this comtent object under a CC-By or similar license?</strong><br/> 
    						<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
										<?= ($item['approved']=='pending') ? 'No answer provided yet' : $item['approved'] ?>
								</p>
							</p>

							<!-- negative response :( what should we do now? --> 
							<div style="display: <?= ($item['approved']=='no') ? 'block':'none'?>"> 
								<br/>
								<p>
									<strong>Please send comments to the dScribe recommending a new action for this content object:</strong><br/><br/>
    							<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
										<b>Action:</b> <?= ($item['action']=='None') ? 'No action specified yet' : $item['action'] ?><br/><br/>
										<b>Comments:</b> <?= ($item['comments']=='') ? 'No comments provided yet' : $item['comments'] ?><br/><br/>
										<?php if ($item['comments']<>'' && $item['modified_by']<>'') { ?>
													<small>Provided by: <?=$this->ocw_user->username($item['modified_by'])?></small><br/>
										<?php } ?>
									</p>
								</p>
							</div>
					 </div>
				</div>
			</div>

			<!-- Explain why letter is not being sent -->
			<div style="display: <?= ($item['info_sufficient']=='no') ? 'block':'none'?>"> 
				<br/><br/>
				<p>
					<strong>Please indicate to the dScribe why you are not sending a permission form to the
					       copyright holder and recommend a new action for this content object:</strong><br/><br/>
    				<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
								<b>Action:</b> <?= ($item['action']=='None') ? 'No action specified yet' : $item['action'] ?><br/><br/>
								<b>Comments:</b> <?= ($item['comments']=='') ? 'No comments provided yet' : $item['comments'] ?><br/><br/>
										<?php if ($item['comments']<>'' && $item['modified_by']<>'') { ?>
													<small>Provided by: <?=$this->ocw_user->username($item['modified_by'])?></small><br/>
										<?php } ?>
						</p>
				</p>
			</div>
		</div>
	</td>

	<!-- third column -->
	<td style="vertical-align:top">
		<div> 
			<?php echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],'permission','orig',false,true,true); ?>
			<br style="clear:both"/><br/>

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
						<b>Copyright Status:</b> <?=$c['status']?><br/><br/>
						<b>Copyright Holder:</b> <?=$c['holder']?><br/><br/>
						<b>Copyright Info URL:</b> <?=$c['url']?><br/><br/>
						<b>Copyright Notice:</b> <?=$c['notice']?>
				<?php } else { ?>
						<b>Copyright:</b> <span style="color:red">No copyright information</span>
				<?php } ?><br/><br/>

				<b>Action Taken:</b> 
				<?php if ($obj['action_taken']=='') { ?><span style="color:red">No action</span>
				<?php } else { echo $obj['action_taken']; }?><br/><br/>
		</div>
	</td>
</tr>	

<?php $count++; }}} ?>
