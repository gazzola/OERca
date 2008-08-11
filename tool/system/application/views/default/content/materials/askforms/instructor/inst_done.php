<?php 
 $count = 1;
if ($prov_objects != null) {
 foreach($prov_objects as  $obj) {
 	$questions = $obj['questions'];
 	$questions = (!is_null($questions) && isset($questions['instructor']) && sizeof($questions['instructor'])>0) ? $questions['instructor'] : null;
	if ($obj['ask_status'] == 'done') {
?>
<tr>
<td style="vertical-align:top"><?=$count?></td>

<td style="vertical-align:top; width: 300px; padding: 10px;">
  <p>
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],'done','orig',true);?>
  </p>

	<!-- citation -->
	<br/><br/>
	<p style="clear:both"><h3>Citation:</h3> 
		<div id="holder_citation_<?=$obj['id']?>">
			<span id="txt_citation_<?=$obj['id']?>" class="ine_tip" title="Click to edit text">
				<?php echo ($obj['citation']<>'') ? $obj['citation']:' No citation'?>
			</span>
		</div>
	</p>

	<!-- tags -->
	<br/><br/>
	<p style="clear:both"><h3>Keywords:</h3> 
		<div id="holder_tags_<?=$obj['id']?>">
			<span id="txt_tags_<?=$obj['id']?>" class="ine_tip" title="Click to edit text">
				<?php echo ($obj['tags']<>'') ? $obj['tags']:' No keywords'?>
			</span>
		</div>
	</p>
</td>

<td style="vertical-align: top">
	<p><h3>Actions Taken:</h3>                
	<?php echo $this->coobject->ask_instructor_report($cid, $mid, $obj, 'original','done');	?>
  </p>
    <!-- dScribe questions -->
	<br/><br/>
	<p style="clear:both"><h3>dScribe Questions:</h3>
		<b>The following are specific questions the dScribe has about this object:</b>
		<br/><br/>
		<?php if ($questions == null) { ?> 
		
				<p>No questions at this time.</p>
		
		<?php } else { foreach($questions as $question) { ?>
		
		      	<p><b><?=$question['question']?></b></p>
		    		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
							<?= ($question['answer']=='') ? 'No answer provided yet' : $question['answer'] ?>
						</p>
		       	<p><hr style="border: 1px solid #eee"/></p>
		
		<?php }} ?>
	</p>
</td>
</tr>	
<?php $count++; }}} 

if ($repl_objects != null) {
 foreach($repl_objects as  $obj) {
 	$questions = $obj['questions'];
	$questions = (!is_null($questions) && isset($questions['instructor']) && sizeof($questions['instructor'])>0) ? $questions['instructor'] : null;
	if ($obj['ask_status'] == 'done') {
?>

<tr>
<td style="vertical-align:top"><?=$count?></td>

<td style="vertical-align:top">
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['object_id'],$obj['location'],'done','orig',true);?>

	<!-- citation -->
	<br/><br/>
	<p style="clear:both"><h3>Citation:</h3> 
		<div id="holder_citation_<?=$obj['id']?>">
			<span id="txt_citation_<?=$obj['id']?>" class="ine_tip" title="Click to edit text">
				<?php echo ($obj['citation']<>'') ? $obj['citation']:' No citation'?>
			</span>
		</div>
	</p>

	<!-- tags -->
	<br/><br/>
	<p style="clear:both"><h3>Keywords:</h3> 
		<div id="holder_tags_<?=$obj['id']?>">
			<span id="txt_tags_<?=$obj['id']?>" class="ine_tip" title="Click to edit text">
				<?php echo ($obj['tags']<>'') ? $obj['tags']:' No keywords'?>
			</span>
		</div>
	</p>
</td>

<td style="vertical-align:top">
	<?php echo $this->coobject->ask_instructor_report($cid, $mid, $obj, 'replacement','done');	?>
	<!-- dScribe questions -->
	<br/><br/>
	<p style="clear:both"><h3>dScribe Questions:</h3>
		<b>The following are specific questions the dScribe has about this object:</b>
		<br/><br/>
		<?php if ($questions == null) { ?> 
		
				<p>No questions at this time.</p>
		
		<?php } else { foreach($questions as $question) { ?>
		
		      	<p><b><?=$question['question']?></b></p>
		    		<p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
							<?= ($question['answer']=='') ? 'No answer provided yet' : $question['answer'] ?>
						</p>
		       	<p><hr style="border: 1px solid #eee"/></p>
		
		<?php }} ?>
	</p>
</td>
</tr>

<?php $count++; }}} ?> 
