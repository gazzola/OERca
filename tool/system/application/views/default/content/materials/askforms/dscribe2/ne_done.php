<?php 
$count = 1;
unset($cos['all']);
//$this->ocw_utils->dump($cos);

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
				?>	
				</fieldset>
       	<p><hr style="border: 1px solid #eee"/></p>
			<?php } ?>
	<?php } ?>

</td>
</tr>	
<?php $count++; }}} ?>
