<?php 
$count = 1;
$sliders = array();

foreach($cos as $obj) { 
  $questions = $obj['questions'];
	$questions = (!is_null($questions) && 
								 isset($questions['dscribe2']) && sizeof($questions['dscribe2'])>0) ? $questions['dscribe2'] : null;
?>
<tr>
	<!-- first column -->
	<td valign="top" style="vertical-align:top;"><?=$count?></td>

	<!-- second column -->
	<td valign="top" style="vertical-align:top;">

		<div>
			<?php foreach($questions as $question) { ?>
      	<p><strong>"<?=$question['question']?>"</strong></p>
    		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					<?= ($question['answer']=='') ? 'No answer provided yet' : $question['answer'] ?><br/><br/>
					<?php if ($question['answer']<>'' && $question['modified_by']<>'') { ?>
								<small>Answered by: <?=$this->ocw_user->username($question['modified_by'])?></small><br/>
					<?php } ?>
				</p>
       	<p><hr style="border: 1px solid #eee"/></p>
			<?php } ?>
		</div>

	</td>

	<!-- third column -->
	<td style="vertical-align:top">
		<div>
			<?php 
				 if ($obj['otype']=='original') { 
						 echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],false,false,true,true);
				 } else {
   					 echo $this->ocw_utils->create_corep_img($cid,$mid,$obj['id'],$obj['location'],false,false,true,true);
				 }
			?>
			<br/><br/>
				 
				<?php if ($obj['otype']=='original') { ?>
					<b>Content-Type:</b> <?=$this->coobject->get_subtype_name($obj['subtype_id'])?><br/><br/>
				<?php } ?>

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
						<b>Copyright Status:</b> <?=$c['status']?><br/><br/>
						<b>Copyright Holder:</b> <?=$c['holder']?><br/><br/>
						<b>Copyright Info URL:</b> <?=$c['url']?><br/><br/>
						<b>Copyright Notice:</b> <?=$c['notice']?>
				<?php } else { ?>
						<b>Copyright:</b> <span style="color:red">No copyright information</span>
				<?php } ?>

				<br/><br/>

				<b>dScribe Recommended Action:</b>
						<?php echo ($obj['action_taken']=='') ? '<span style="color:red">No action recommended</span>':$obj['action_taken']; ?>
				<br/><br/>	

		</div>
	</td>
</tr>	

<?php $count++; } ?>
