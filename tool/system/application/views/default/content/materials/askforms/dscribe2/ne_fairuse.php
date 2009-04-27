<?php 
$count = 1;
$sliders = array();

foreach($cos as $obj) {
  			$items = $obj['fairuse'];
			
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
     	<h3><?=$this->ocw_user->username($item['user_id']) ?>'s (dScribe) Rationale:</h3>
   		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($item['rationale']=='') ? 'No rationale provided' : $item['rationale'] ?>
			</p>

			<!-- warrant fair use review -->
			<p>
				<strong>Does this object warrant Legal and Policy review for fair use?</strong><br/>
    		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($item['warrant_review']=='pending') ? 'No answer provided yet' : $item['warrant_review'] ?>
				</p>
			</p>

			<!-- Additional rationale -->
			<div style="display: <?= ($item['warrant_review']=='yes') ? 'block':'none'?>"> 
				<p>
					<strong>Provide additional rationale or comments for Legal and Policy Review team:</strong><br/>
    			<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
						<?= ($item['additional_rationale']=='') ? 'No additional reason provided yet' : $item['additional_rationale'] ?><br/><br/>
						<?php if ($item['additional_rationale']<>'' && $item['modified_by']<>'') { ?>
								<small>Provided by: <?=$this->ocw_user->username($item['modified_by'])?></small><br/>
						<?php } ?>
					</p>
				</p>
			</div>

			<!-- Comments rationale -->
			<div style="display: <?= ($item['warrant_review']=='no') ? 'block':'none'?>"> 
				<p>
					<strong>Provide a comment and action recommendation for the dScribe:</strong><br/>
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
			<?php echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],'fairuse','orig',false,true,true); ?>
			<br style="clear:both"/><br/>

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

				<b>Source Information:</b> 
				<?php if ($obj['citation']=='') { ?><span style="color:red">No source information</span>
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
