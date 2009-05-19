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
						 echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],$type.':'.$response_type,'orig',true,true,true,true,'','187');
				 } else {
   					 echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],$type.':'.$response_type,'rep',true,true,true,true,'','187');
				 }
			?>
  </p>

	<!-- copyright -->
	<p style="clear:both">
		<br/><br/><b>Copyright:</b> 
		<span>
				<?php if (is_array($obj['copyright'])) { $c = $obj['copyright'];?>
						<br/><b>Copyright Status:</b> <?=$c['status']?><br/>
						<b>Copyright Holder:</b> <?=$c['holder']?><br/>
						<b>Copyright Info URL:</b> <?=$c['url']?><br/>
						<b>Copyright Notice:</b> <?=$c['notice']?><br/>
				<?php } else { ?>
						No copyright information
				<?php } ?>
		 </span>
	</p>

	<!-- subtype -->
	<?php if ($obj['otype']=='original') { ?>
	<p style="clear:both"><b>Content Type:</b> 
		 <span><?=$this->coobject->get_subtype_name($obj['subtype_id'])?></span>
	</p>
	<?php } ?>

	<!-- description -->
	<p style="clear:both"><b>Description:</b> 
		<span><?php echo ($obj['description']<>'') ? $obj['description']:' No description'?></span>
	</p>

	<!-- author -->
	<p style="clear:both"><b>Author:</b> 
		<span><?php echo ($obj['author']<>'') ? $obj['author']:' No author'?></span>
	</p>

	<!-- contributor -->
	<p style="clear:both"><b>Contributor:</b> 
		<span><?php echo ($obj['contributor']<>'') ? $obj['contributor']:' No contributor'?></span>
	</p>

	<!-- citation (source information) -->
	<p style="clear:both"><b>Source Information:</b> 
		<span><?php echo ($obj['citation']<>'') ? $obj['citation']:' No source information'?></span>
	</p>

	<!-- keywords -->
	<p style="clear:both"><b>Keywords:</b> 
		<span><?php echo ($obj['tags']<>'') ? $obj['tags']:' No keywords'?></span>
	</p>

</td>

<td style="vertical-align: top">
	<?php if ($type=='general') { ?>
			<h2>Here are the responses the dScribe2 provided to the dscribe1's questions:</h2>
			<br/>

			<?php 
  					$questions = $obj['questions'];
						$questions = (!is_null($questions) && 
								 isset($questions['dscribe2']) && sizeof($questions['dscribe2'])>0) ? $questions['dscribe2'] : null;

						foreach($questions as $question) { ?>
				<fieldset>
					<label><strong><?=$question['question']?></strong></label><br/>
    		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($question['answer']=='') ? 'No answer provided' : $question['answer'] ?><br/><br/>
					<?php if ($question['answer']<>'' && $question['modified_by']<>'') { ?>
								<small>Answered by: <?=$this->ocw_user->username($question['modified_by'])?></small><br/>
					<?php } ?>
				</p>
				</fieldset>
       	<p><hr style="border: 1px solid #eee"/></p>
			<?php } ?>

	<?php } elseif ($type=='commission') { ?>

			<h2>Here are the responses the dScribe2 provided to the dscribe1's commission claims:</h2>
			<?php foreach($obj['commission'] as $item) { 
						if ($item['status']=='done') {
			?>

				<fieldset>
					<label>Commission Claim</label>
     			<p><h3><?=$this->ocw_user->username($item['user_id']) ?>'s (dScribe) Rationale:</h3></p>
    			<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
						<?= ($item['rationale']=='') ? 'No rationale provided' : $item['rationale'] ?>
					</p>

					<p><h3>Actions Taken:</h3>                
					<?php echo $this->coobject->claim_report($cid,$mid,$obj,$item['id'],'commission',$type.':'.$response_type); ?>
				</fieldset>

       	<p><hr style="border: 1px solid #eee"/></p>

			<?php }} ?>	


	<?php } elseif ($type=='retain') { ?>

			<h2>Here are the responses the dScribe2 provided to the dscribe1's Retain claims:</h2>
			<br/>

			<?php foreach($obj['retain'] as $item) { 
						if ($item['status']=='done') {
			?>

				<fieldset>
					<label>Current Recommendation: "<?php echo $item['action'];?>"</label>
     			<p><h3><?=$this->ocw_user->username($item['user_id']) ?>'s (dScribe) Rationale:</h3></p>
    			<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
						<?= ($item['rationale']=='') ? 'No rationale provided' : $item['rationale'] ?>
					</p>

					<p><h3>Actions Taken:</h3>                
					<?php echo $this->coobject->claim_report($cid,$mid,$obj,$item['id'],'retain',$type.':'.$response_type); ?>
				</fieldset>

       	<p><hr style="border: 1px solid #eee"/></p>

			<?php }} ?>	

	<?php } elseif ($type=='permission') { ?>

			<h2>Here are the responses the dScribe2 provided to the dscribe1's Permission claims:</h2>
			<br/>

			<?php foreach($obj['permission'] as $item) { 
						if ($item['status']=='done') {
			?>
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
					<?php echo $this->coobject->claim_report($cid,$mid,$obj,$item['id'],'permission',$type.':'.$response_type); ?>
			</fieldset>
      <p><hr style="border: 1px solid #eee"/></p>
			<?php }} ?>	

	<?php } elseif ($type=='fairuse') { ?>

			<h2>Here are the responses the dScribe2 provided to the dscribe1's Fair Use claims:</h2>
			<br/>

			<?php foreach($obj['fairuse'] as $item) { 
						if ($item['status']=='done') {
			?>
				<fieldset>
					<label>Fair Use Claim</label>
     			<p><h3><?=$this->ocw_user->username($item['user_id']) ?>'s (dScribe) Rationale:</h3></p>
    			<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
						<?= ($item['rationale']=='') ? 'No rationale provided' : $item['rationale'] ?>
					</p>

					<p><h3>Actions Taken:</h3>                
					<?php echo $this->coobject->claim_report($cid,$mid,$obj,$item['id'],'fairuse',$type.':'.$response_type); ?>
				</fieldset>
       	<p><hr style="border: 1px solid #eee"/></p>
				<?php }} ?>

	<?php } ?>

</td>
</tr>	
<?php $count++; }}} ?>
