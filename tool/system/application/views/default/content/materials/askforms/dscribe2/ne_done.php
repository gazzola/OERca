<?php 
$count = 1;
unset($cos['all']);

foreach($cos as $type => $co) {
		if ($type <> $response_type && $response_type <> 'all') continue;
	
		if (!empty($co)) {
 				foreach($co as  $obj) {
?>

<tr>
	<td style="vertical-align:top"><?=$count?></td>
	
	<td style="vertical-align:top"><?=ucfirst($type)?></td>

	<td style="vertical-align:top; width: 300px; padding: 10px;">
  <p>
			<?php 
				 if ($obj['otype']=='original') { 
						 echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],false,true);
				 } else {
   					 echo $this->ocw_utils->create_corep_img($cid,$mid,$obj['id'],$obj['location'],false,true);
				 }
			?>
  </p>

	<!-- copyright -->
	<br/><br/>
	<p style="clear:both"><h3>Copyright:</h3> 
		<div><span>
				<?php if (is_array($obj['copyright'])) { $c = $obj['copyright'];?>
						<b>Copyright Status:</b> <?=$c['status']?><br/>
						<b>Copyright Holder:</b> <?=$c['holder']?><br/>
						<b>Copyright Info URL:</b> <?=$c['url']?><br/>
						<b>Copyright Notice:</b> <?=$c['notice']?><br/>
				<?php } else { ?>
						No copyright information
				<?php } ?>
		 </span></div>
	</p>

	<!-- subtype -->
	<?php if ($obj['otype']=='original') { ?>
	<br/><br/>
	<p style="clear:both"><h3>Content Type:</h3> 
		 <div><span><?=$this->coobject->get_subtype_name($obj['subtype_id'])?></span></div>
	</p>
	<?php } ?>

	<!-- description -->
	<br/><br/>
	<p style="clear:both"><h3>Description:</h3> 
		<div><span><?php echo ($obj['description']<>'') ? $obj['description']:' No description'?></span></div>
	</p>

	<!-- author -->
	<br/><br/>
	<p style="clear:both"><h3>Author:</h3> 
		<div><span><?php echo ($obj['author']<>'') ? $obj['author']:' No author'?></span></div>
	</p>

	<!-- contributor -->
	<br/><br/>
	<p style="clear:both"><h3>Contributor:</h3> 
		<div><span><?php echo ($obj['contributor']<>'') ? $obj['contributor']:' No contributor'?></span></div>
	</p>

	<!-- citation -->
	<br/><br/>
	<p style="clear:both"><h3>Citation:</h3> 
		<div><span><?php echo ($obj['citation']<>'') ? $obj['citation']:' No citation'?></span></div>
	</p>

	<!-- keywords -->
	<br/><br/>
	<p style="clear:both"><h3>Keywords:</h3> 
		<div><span><?php echo ($obj['tags']<>'') ? $obj['tags']:' No keywords'?></span></div>
	</p>

	<!-- action taken -->
	<?php if ($obj['otype']=='original') { ?>
	<br/><br/>
	<p style="clear:both"><h3>Action Taken:</h3> 
		<div><span><?php echo ($obj['action_taken']<>'') ? $obj['action_taken']:' No action'?></span></div>
	</p>
	<?php } ?>

</td>

<td style="vertical-align: top">
	<?php if ($type=='general') { ?>
			<h2>Here are the responses the dScribe2 provided to the dscribe1's questions:</h2>
			<br/>

			<?php foreach($obj['questions'] as $question) { ?>
				<fieldset>
					<label><strong><?=$question['question']?></strong></label>
    		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($question['answer']=='') ? 'No answer provided' : $question['answer'] ?>
				</p>
				</fieldset>
       	<p><hr style="border: 1px solid #eee"/></p>
			<?php } ?>

	<?php } elseif ($type=='commission') { ?>

			<h2>Here are the responses the dScribe2 provided to the dscribe1's commission claims:</h2>
			<?php foreach($obj['commission'] as $item) { ?>
				<fieldset>
					<label>Commission Claim</label>
     			<p><h3>dScribe's Rationale:</h3></p>
    			<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
						<?= ($item['rationale']=='') ? 'No rationale provided' : $item['rationale'] ?>
					</p>

					<p><h3>Actions Taken:</h3>                
					<?php
						if ($item['have_replacement']=='yes') {
								echo '<br>dScribe2 provided the dScribe with a replacement with the following action and comments:<br/>'; 
								echo '<br/>Action: <b>'.$item['action'].'</b><br/>';
								if ($item['comments']<>'') {
											echo 'Comments: <b>'.$item['comments'].'</b>';
								} else { 
											echo 'Comments: <b>no comments</b><br/><br/>';
								}
								$x = $this->coobject->replacement_exists($cid,$mid,$obj['id']);
        				if ($x) {
										echo '<p>Provided Replacement: </p>';
            				echo $this->ocw_utils->create_corep_img($cid,$mid,$obj['id'],$obj['location'],false,true);
        				}
						} elseif ($item['have_replacement']=='no') {
								echo '<br>dScribe2 could not provide the dScribe with a replacement<br/>'; 
								if ($item['recommend_commission']=='yes') {
										echo '<br>dScribe2 recommends commissioning the content object because: '.
									 (($item['comments']<>'') ? $item['comments'] : 'no rationale given'); 
								} else { 
										echo '<br>dScribe2 does not recommend commissioning the content object and suggests the following:<br/>';
										echo '<br/>Action: <b>'.$item['action'].'</b><br/>';
										if ($item['comments']<>'') {
												echo 'Comments: <b>'.$item['comments'].'</b>';
										} else { 
												echo 'Comments: <b>no comments</b><br/><br/>';
										}
								}
						}

						if($item['status']=='commission review') { echo '<br><br>The Commission Review team is reviewing this claim.'; }
					?>
				</fieldset>
       	<p><hr style="border: 1px solid #eee"/></p>
			<?php } ?>	


	<?php } elseif ($type=='retain') { ?>

			<h2>Here are the responses the dScribe2 provided to the dscribe1's No copyright claims:</h2>
			<br/>

			<?php foreach($obj['retain'] as $item) { ?>
				<fieldset>
					<label>No Copyright Claim</label>
     			<p><h3>dScribe's Rationale:</h3></p>
    			<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
						<?= ($item['rationale']=='') ? 'No rationale provided' : $item['rationale'] ?>
					</p>

					<p><h3>Actions Taken:</h3>                
					<?php
						if ($item['accept_rationale']=='yes') {
								echo '<br>dScribe2 accepted dscribe\'s rationale.';
						} elseif ($item['accept_rationale']=='no') {
								echo '<br>dScribe2 did not accept dscribe\'s rationale.';
											if ($item['action']<>'None') {
													echo '<br/><br/>dScribe2 recommends the following action:<b>'.$item['action'].'</b>';
											}
						} elseif ($item['accept_rationale']=='unsure') {
								echo '<br>dScribe2 is unsure about the dscribe\'s rationale.';
								if ($item['status']=='ip review') {
										echo '<br><br>dScribe2 has sent it to Legal & Policy team for review';
								}
						}
						if ($item['comments']<>'') {
								echo '<br/><br/>dScribe2 provided the following comments:<br/><br/>';
								echo '<p style="background-color:#ddd; padding:5px;">'.$item['comments'].'</p><br/><br/>';
						}
						if ($item['approved']=='yes') {
								echo '<br><br>Legal & Policy Review team have approved this claim.';
						} elseif ($item['approved']=='no') {
								echo '<br><br>Legal & Policy Review team have not approved this claim.';
						} elseif($item['status']=='ip review' && $item['approved']=='pending') {
								echo '<br><br>Legal & Policy Review team is reviewing this claim.';
					  }
					?>
				</fieldset>
       	<p><hr style="border: 1px solid #eee"/></p>
			  <?php } ?>	

	<?php } elseif ($type=='permission') { ?>

			<h2>Here are the responses the dScribe2 provided to the dscribe1's Permission claims:</h2>
			<br/>

			<?php foreach($obj['permission'] as $item) { ?>
			<fieldset>
					<label>Permission Claim</label>
     			<p><h3>Contact Information:</h3></p>
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

					<p><h3>Actions Taken:</h3>                
					<?php
						if ($item['info_sufficient']=='yes') {
								echo 'dScribe2 decided that a permission form can be sent for this content object.';
							
								if ($item['letter_sent']=='yes') {
										echo '<br/><br/>dScribe2 indicated that the permission form has been sent.';
										if ($item['response_received']=='yes') {
												echo '<br/><br/>dScribe2 indicated that a response has been received.';
												if ($item['approved']=='yes') {
														echo '<br/><br/>dScribe2 indicated that request for permission was approved';
												} elseif ($item['approved']=='no') {
														echo '<br/><br/>dScribe2 indicated that request for permission was not approved';
														if ($item['action']<>'None') {
																echo '<br/><br/>dScribe2 recommends the following action:<b>'.$item['action'].'</b>';
														}
														if ($item['comments']<>'') {
																echo '<br/><br/>dScribe2 provided the following comments:<br/><br/>';
																echo '<p style="background-color:#ddd; padding:5px;">'.$item['comments'].'</p><br/><br/>';
														}
												} else {
																echo 'dScribe2 did not specify whether this request was approved or not';
												}
										} else {
												echo '<br/><br/>dScribe2 indicated that a response has not been received.';
										}
								} else {
										echo '<br/><br/>dScribe2 indicated that the permission form has not been sent.';
								}

						} elseif ($item['info_sufficient']=='no') {
									echo 'dScribe2 decided that a permission form should not be sent for this content object';
									if ($item['action']<>'None') {
											echo '<br/><br/>dScribe2 recommends the following action:<b>'.$item['action'].'</b>';
									}
									if ($item['comments']<>'') {
											echo '<br/><br/>dScribe2 provided the following comments:<br/><br/>';
											echo '<p style="background-color:#ddd; padding:5px;">'.$item['comments'].'</p><br/><br/>';
									}
				 		} 
						if ($item['approved']=='yes') {
								echo '<br><br>Legal & Policy Review team have approved this claim.';
						} elseif ($item['approved']=='no') {
								echo '<br><br>Legal & Policy Review team have not approved this claim.';
						} elseif($item['status']=='ip review' && $item['approved']=='pending') {
								echo '<br><br>Legal & Policy Review team is reviewing this claim.';
					  }
					?>

				</fieldset>
       	<p><hr style="border: 1px solid #eee"/></p>
			  <?php } ?>	

	<?php } elseif ($type=='fairuse') { ?>

			<h2>Here are the responses the dScribe2 provided to the dscribe1's Fair Use claims:</h2>
			<br/>

			<?php foreach($obj['fairuse'] as $item) { ?>
				<fieldset>
					<label>Fair Use Claim</label>
     			<p><h3>dScribe's Rationale:</h3></p>
    			<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
						<?= ($item['rationale']=='') ? 'No rationale provided' : $item['rationale'] ?>
					</p>

					<p><h3>Actions Taken:</h3>                
					<?php
					if ($item['warrant_review']=='yes') {
							echo 'dScribe2 indicated that this object <i>warrants</i> a legal review for fair use.';
							if ($item['status']=='ip review') {
								echo '<br/><br/>dScribe2 has sent this to the Legal & Policy review team';
							}
							if ($item['additional_rationale']<>'') {
									echo '<br/><br/>dScribe2 provided the following additional rationale:<br/><br/>';
									echo '<p style="background-color:#ddd; padding:5px;">'.$item['additional_rationale'].'</p><br/><br/>';
							}

					} elseif ($item['warrant_review']=='no') {
							echo 'dScribe2 indicated that this object <i>does not warrant</i> a legal review for fair use.';
							if ($item['action']<>'None') {
								echo '<br/><br/>dScribe2 recommends the following action:<b>'.$item['action'].'</b>';
							}
							if ($item['comments']<>'') {
								echo '<br/><br/>dScribe2 provided the following comments:<br/><br/>';
								echo '<p style="background-color:#ddd; padding:5px;">'.$item['comments'].'</p><br/><br/>';
							}
					} elseif ($item['warrant_review']=='pending') {
							echo 'dScribe2 did not specify whether this object warrants a fair use review or not.';
					}
						if ($item['approved']=='yes') {
								echo '<br><br>Legal & Policy Review team have approved this claim.';
						} elseif ($item['approved']=='no') {
								echo '<br><br>Legal & Policy Review team not have approved this claim.';
						} elseif($item['status']=='ip review' && $item['approved']=='pending') {
								echo '<br><br>Legal & Policy Review team is reviewing this claim.';
					  }
				?>	
				</fieldset>
       	<p><hr style="border: 1px solid #eee"/></p>
				<?php } ?>

	<?php } ?>

</td>
</tr>	
<?php $count++; }}} ?>
