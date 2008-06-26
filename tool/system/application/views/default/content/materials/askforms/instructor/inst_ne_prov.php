<?php 
$count = 1;
$ttip = "We're asking the instructor to do two things here:<br/><br/>1) Tell us a little about what".
		" this content object shows in relation to the lesson.<br/><br/>".
		"2) What are the most distinctive components of this content object?";

$ttip2 = "If the instructor  created this object, they most likely hold its copyright. However, sometimes faculty transfer copyrights to their work when they publish that work in books or articles.";

foreach($prov_objects as  $obj) {

  $questions = $obj['questions'];
	$questions = (!is_null($questions) && 
								 isset($questions['instructor']) && sizeof($questions['instructor'])>0) ? $questions['instructor'] : null;

  if ($obj['ask_status'] <> 'done') {
?>
<tr>
<td valign="top" style="vertical-align:top;"><?=$count?></td>

<td valign="top" style="vertical-align:top;">

<div id="new-col1-<?=$obj['id']?>">
	<!-- copyright questions -->
	<p>
		<b class="ine_tip" title="<?=$ttip?>">What does the content object show?</b>
		<img src="<?=property('app_img')?>/info.gif" style="width:auto;float:none" class="ine_tip" title="<?=$ttip?>" /><br/>
    <p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
			<?= ($obj['description']=='') ? 'No answer provided yet' : $obj['description'] ?>
		</p>
	</p>

	<p>
		<b>Do you hold the copyright to this object?</b>
		<img src="<?=property('app_img')?>/info.gif" style="width:auto;float:none" class="ine_tip" title="<?=$ttip2?>" /</b><br/>
    <p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
			<?= ($obj['instructor_owns']=='pending') ? 'No answer provided yet' : $obj['instructor_owns'] ?>
		</p>
	</p>

	<div id="other_<?=$obj['id']?>" style="display: <?= ($obj['instructor_owns']=='no') ? 'block':'none'?>"> 
	<p>
		<b>Do you know who holds the copyright to this object?</b><br/>
    <p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
			<?= ($obj['other_copyholder']<>'') ? "Yes: ". $obj['other_copyholder'] 
																			   : 
				(($obj['other_copyholder']=='' && $obj['is_unique']<>'pending')  ? 'No' : 'Waiting for a response...')
			?>
		</p>
	</p>

	<div id="who_no_other_<?=$obj['id']?>" style="display: <?= ($obj['instructor_owns']=='no') ? 'block':'none'?>"> 
		<b>Will a substitute object work or is this representation of this information unique?</b><br/>
		<input type="radio" name="unique_<?=$obj['id']?>" id="unq_<?=$obj['id']?>" 
			   value="yes" class="do_ask_object_update" 
				<?=($obj['is_unique']=='yes')  ? 'checked="checked"' : ''?>/>&nbsp; Yes, a substitute will work&nbsp;
		<input type="radio" name="unique_<?=$obj['id']?>" id="unq_<?=$obj['id']?>" 
			   value="no" class="do_ask_object_update" 
				<?=($obj['is_unique']=='no')  ? 'checked="checked"' : ''?>/>&nbsp; No, a substitute will not work; it's unique&nbsp;
	</div>
	</div>

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

	<!-- citation -->
	<br/>

	<p style="clear:both"><h3>Citation:</small></h3> 
   <p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
			<?php echo ($obj['citation']<>'') ? $obj['citation']:'No citation'?>
	 </p>
	</p>

	<!-- tags -->
	<br/>
	<p style="clear:both"><h3>Keywords:</small></h3> 
    <p style="margin-bottom:15px;border:1px solid #ccc; padding:5px; background-color:#eee">
			<?php echo ($obj['tags']<>'') ? $obj['tags']:'No keywords'?>
		</p>
	</p>

	<br/>
</div>

</td>



<td style="vertical-align:top">

<div id="new-col2-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'none':'block'?>;">
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],false,false);?>
<br/> <br/>
</div>


<div id="inprogress-col2-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'block':'none'?>;">
  <?=$this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],false,true);?><br/>
</div>
</td>
</tr>	

<?php $count++; } } ?>

<script type="text/javascript">
window.addEvent('domready', function() {
    var myTips = new MooTips($$('.ine_tip'), { maxTitleChars: 50 });
});
</script>
