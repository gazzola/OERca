<?php 
$count = 1;

$accept = $yes = array();

$accept['Retain: Copyright Analysis'] = "Do you accept the dScribe's action recommendation to retain this content object based on your <em>copyright analysis</em>?";
$accept['Retain: Permission'] = "Do you accept the dScribe's action recommendation to retain this content object because OER has <em>permission</em>?";
$accept['Retain: Public Domain'] = "Do you accept the dScribe's action recommendation to retain this content object because it is in the <em>public domain</em>?";
//default actions
$default_actions = array('None', 'Permission', 'Search', 'Create', 'Commission', 'Fair Use', 'Remove and Annotate');
foreach ($default_actions as $default_action) $accept[$default_action] = 'The action type is not currently Retain: Copyright Analysis, Retain: Permission, or Retain: Public Domain.  Choose No below to change it.';

$yes['Retain: Copyright Analysis'] = "Please provide additional rationale for why this object has been retained based on your <em>copyright analysis</em>:";
$yes['Retain: Permission'] = "Please provide additional rationale for why OER has <em>permission</em> for this object:";
$yes['Retain: Public Domain'] = "Please provide additional rationale for why this object is in the <em>public domain</em>:";
foreach ($default_actions as $default_action) $yes[$default_action] = 'The action type is not currently Retain: Copyright Analysis, Retain: Permission, or Retain: Public Domain.  Choose No below to change it.';

foreach($cos as $obj) {
  			$items = $obj['retain'];
			
			  foreach($items as $item) {
					if ($item['status']<>'done') {
?>
<tr>
	<!-- first column -->
	<td valign="top" style="vertical-align:top;"><?=$count?></td>

	<!-- second column -->
	<td valign="top" style="vertical-align:top;">

		<div>
     	<h3><?=$this->ocw_user->username($item['user_id']) ?>'s (dScribe) Rationale:</h3>
   		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($item['rationale']=='') ? 'No rationale provided' : $item['rationale'] ?>
			</p>

			<!-- accept rationale? -->
			<p>
				<br/><br/>
				<strong><?=$accept[$item['action']]?></strong><br/>
    		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($item['accept_rationale']=='pending') ? 'No answer provided yet' : $item['accept_rationale'] ?>
				</p>
			</p>

			<!-- accept rationale -->
			<div style="display: <?= ($item['accept_rationale']=='yes') ? 'block':'none'?>"> 
				<br/><br/>
				<p>
					<strong><?=$accept[$item['action']]?></strong><br/></br/>
    			<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
							<?= ($item['comments']=='') ? 'No rationale provided yet' : $item['comments'] ?><br/><br/>
						<?php if ($item['comments']<>'' && $item['modified_by']<>'') { ?>
							<small>Provided by: <?=$this->ocw_user->username($item['modified_by'])?></small><br/>
						<?php } ?>
					</p>
				</p>
			</div>

			<!-- reject rationale -->
			<div id="accept_rationale_no_<?=$item['id']?>" style="display: <?= ($item['accept_rationale']=='no') ? 'block':'none'?>"> 
				<br/><br/>
				<p>
					<strong>Provide your reasoning to the dScribe and recommend a new action for this content object: </strong><br/>
    			<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
						<b>Action:</b> <?= ($item['action']=='None') ? 'No action specified yet' : $item['action'] ?><br/><br/>
	
						<b>Comments:</b> <?= ($item['comments']=='') ? 'No reason provided yet' : $item['comments'] ?><br/><br/>
						<?php if ($item['comments']<>'' && $item['modified_by']<>'') { ?>
							<small>Provided by: <?=$this->ocw_user->username($item['modified_by'])?></small><br/>
						<?php } ?>
					</p>
				</p>
			</div>

			<!-- reject rationale -->
			<div id="accept_rationale_unsure_<?=$item['id']?>" style="display: <?= ($item['accept_rationale']=='unsure') ? 'block':'none'?>"> 
				<br/><br/>
				<p>
					<strong>Please provide additional comments or rationale for the Legal and Policy Review team:</strong><br/><br/>
    			<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
							<?= ($item['comments']=='') ? 'No answer provided yet' : $item['comments'] ?><br/><br/>
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
			<?php echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],'retain','orig',false,true,true); ?>
			<br style="clear:both"/><br/>

				<b>Content-Type:</b> <?=$this->coobject->get_subtype_name($obj['subtype_id'])?><br/><br/>

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
