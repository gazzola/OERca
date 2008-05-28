<?php 
$count = 1;

foreach($repl_objects as  $obj) {

  $questions = $obj['questions'];
	if (!is_null($questions)) {
		  foreach($questions as $key => $val) { if ($val['role']<>'instructor') { unset($questions[$key]); } }
	}
	$questions = (sizeof($questions)) ? $questions : null;

  if ($obj['ask_status'] <> 'done') {
?>
<tr>
<td style="vertical-align:top"><?=$count?></td>

<td width="318" style="vertical-align:top">

<div id="new-col1-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'none':'block'?>;">
	<!-- replacement questions -->
	<p>
		<b>Is this a suitable replacement?</b><br/>
    <p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
			<?= ($obj['suitable']=='pending') ? 'No answer provided yet' : $obj['suitable'] ?>
		</p>
	</p>


	<div id="suit_yes_other_<?=$obj['id']?>" style="display: <?= ($obj['suitable']=='yes') ? 'block':'none'?>"> 

	<!-- citation -->
	<p style="clear:both"><h3>Replacement Citation:</small></h3> 
    <p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
			<?php echo ($obj['citation']<>'') ? $obj['citation']:'No citation'?>
		</p>
	</p>

	<!-- tags -->
	<br/><br/>
	<p style="clear:both"><h3>Replacement Keywords:</small></h3> 
    <p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
			<?php echo ($obj['tags']<>'') ? $obj['tags']:'No keywords'?>
		</p>
	</p>
</div>


<div id="suit_no_other_<?=$obj['id']?>" style="display: <?= ($obj['suitable']=='no') ? 'block':'none'?>"> 
	<p>
		<b>Why is this not a suitable replacement?</b><br/>
    <p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
      <?=$obj['unsuitable_reason']?>
		</p>
	</p>
</div>



	<!-- dScribe questions -->
	<br/><br/>
	<p style="clear:both"><h3>dScribe Questions:</h3>
		<b>The following are specific questions the dScribe has for you about this replacement object:</b>
		<br/><br/>
<?php if ($questions == null) { ?> 

		<p>No questions at this time.</p>

<?php } else { foreach($questions as $question) { ?>

      	<p><b><?=$question['question']?></b></p>
    		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
					  <?=$question['answer']?>
       	</p>
       	<p><hr style="border: 1px solid #eee"/></p>

<?php }} ?>
	</p>
</div>

</td>

<!-- Replacement -->
<td style="vertical-align:top">
<div id="new-col3-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'none':'block'?>;">
   		<?=$this->ocw_utils->create_corep_img($cid,$mid,$obj['object_id'],$obj['location'],false,false);?>
</div>
<div id="inprogress-col3-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'block':'none'?>;">
		<?=$this->ocw_utils->create_corep_img($cid,$mid,$obj['object_id'],$obj['location'],false);?>
</div>
</td>

<!-- Original -->
<td style="vertical-align:top">
<div id="new-col2-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'none':'block'?>;">
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['object_id'],$obj['location'],false,false);?>
</div>
<div id="inprogress-col2-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'block':'none'?>;">

<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['object_id'],$obj['location'],false,true);?><br/>

</div>
</td>


</tr>	
<?php $count++; } } ?>
