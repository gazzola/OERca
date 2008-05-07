<?php 
$count = 1;
foreach($cos as $obj) {
  			$items = $obj['commission'];
			
			  foreach($items as $item) {
?>
<tr>
	<!-- first column -->
	<td valign="top" style="vertical-align:top;"><?=$count?></td>

	<!-- second column -->
	<td valign="top" style="vertical-align:top;">

		<div style="display: <?=($item['status']=='in progress') ? 'none':'block'?>;">

     	<h3><?=$this->ocw_user->username($item['user_id']) ?>'s (dScribe) Rationale:</h3>
   		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($item['rationale']=='') ? 'No rationale provided' : $item['rationale'] ?>
			</p>

			<!-- have a  replacement -->
			<p>
				<strong>Can you provide the dScribe with a replacement or a method for replacing this object?</strong><br/>
   			<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($item['have_replacement']=='pending') ? 'No answer provided yet' : $item['have_replacement'] ?>
				</p>
			</p>

			<!-- I do have a replacement or know where to get it  -->
			<div id="have_replacement_yes_<?=$item['id']?>" style="display: <?= ($item['have_replacement']=='yes') ? 'block':'none'?>"> 
				<p>
					<strong>Please upload a replacement (if you can). Please provide the dScribe with any comments or suggestions for replacing
					the content object:</strong><br/><br/>
    			<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
						<b>Action:</b> <?= ($item['action']=='None') ? 'No action specified yet' : $item['action'] ?><br/><br/>
	
						<b>Comments:</b> <?= ($item['comments']=='') ? 'No comments provided yet' : $item['comments'] ?><br/><br/>
						<?php if ($item['comments']<>'' && $item['modified_by']<>'') { ?>
							<small>Provided by: <?=$this->ocw_user->username($item['modified_by'])?></small><br/>
						<?php } ?>
					</p>
				</p>
				<p><h3>Replacement</h3><p>
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
   				<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
						<?= ($item['recommend_replacement']=='pending') ? 'No answer provided yet' : $item['recommend_replacement'] ?>
					</p>
				</p>

					<div id="recommend_commission_yes_<?=$item['id']?>" style="display: <?= ($item['recommend_commission']=='yes') ? 'block':'none'?>"> 
					<p>
						<strong>Please provide your rationale for this recommendation:</strong><br/><br/>
						<?= form_textarea($item['comments_ta_data']); ?>
    				<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
								<b>Comments:</b> <?= ($item['comments']=='') ? 'No rationale provided yet' : $item['comments'] ?><br/><br/>
								<?php if ($item['comments']<>'' && $item['modified_by']<>'') { ?>
									<small>Provided by: <?=$this->ocw_user->username($item['modified_by'])?></small><br/>
								<?php } ?>
						</p>
					</p>
					</div>	

					<div id="recommend_commission_no_<?=$item['id']?>" style="display: <?= ($item['recommend_commission']=='no') ? 'block':'none'?>"> 
					<p>
						<strong>Please indicate your reasoning and recommend to the dScribe a new action for this content object:</strong><br/><br/>
    				<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
							<b>Action:</b> <?= ($item['action']=='None') ? 'No action specified yet' : $item['action'] ?><br/><br/>
	
							<b>Comments:</b> <?= ($item['comments']=='') ? 'No reason provided yet' : $item['comments'] ?><br/><br/>
							<?php if ($item['comments']<>'' && $item['modified_by']<>'') { ?>
								<small>Provided by: <?=$this->ocw_user->username($item['modified_by'])?></small><br/>
							<?php } ?>
						</p>
					</p>
					</div>	
			</div>

		</div>
	</td>

	<!-- third column -->
	<td style="vertical-align:top">
		<div style="display: <?=($item['status']=='in progress') ? 'none':'block'?>;">
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

	</td>
</tr>	

<?php $count++; }} ?>
