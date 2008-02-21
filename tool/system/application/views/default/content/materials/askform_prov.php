<?php 
$count = 1;
$inplaceeditors = array();
$sliders = array();
$ttip = "We're asking you to do two things here:<br/><br/>1) Tell us a little about what".
		" this content object shows in relation to the lesson.<br/><br/>".
		"2) What are the most distinctive components of this content object?";

$ttip2 = "If you created this object, you most likely hold its copyright. However, sometimes faculty transfer copyrights to their work when they publish that work in books or articles.";

foreach($prov_objects as  $obj) {

  $questions = $obj['questions'];

  if ($obj['ask_status'] <> 'done') {
?>
<tr>
<td valign="top" style="vertical-align:top;"><?=$count?></td>

<td valign="top" style="vertical-align:top;">

<div id="new-col1-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'none':'block'?>;">
	<!-- copyright questions -->
	<p>
		<b class="ine_tip" title="<?=$ttip?>">What does the content object show?</b>
		<img src="<?=property('app_img')?>/info.gif" style="float:left; display:inline; margin:-16px 190px" class="ine_tip" title="<?=$ttip?>" /><br/>

       	<textarea name="description_<?=$obj['id']?>" id="desc_<?=$obj['id']?>" rows="10" cols="50" 
				  class="do_ask_object_update"><?=$obj['description']?></textarea>
	</p>

	<p>
		<b>Do you hold the copyright to this object?</b>
		<img src="<?=property('app_img')?>/info.gif" style="float:left; display:inline; margin:-15px 210px" class="ine_tip" title="<?=$ttip2?>" /><br/>
		<input type="radio" name="instructor_owns_<?=$obj['id']?>" id="own_<?=$obj['id']?>" 
			   value="yes" class="do_askform_yesno do_ask_object_update" 
				<?=($obj['instructor_owns']=='yes')  ? 'checked="checked"' : ''?>/>&nbsp; Yes&nbsp;
		<input type="radio" name="instructor_owns_<?=$obj['id']?>" id="own_<?=$obj['id']?>" 
			   value="no" class="do_askform_yesno do_ask_object_update" 
				<?=($obj['instructor_owns']=='no')  ? 'checked="checked"' : ''?>/>&nbsp; No&nbsp;
	</p>

	<div id="other_<?=$obj['id']?>" style="display: <?= ($obj['instructor_owns']=='no') ? 'block':'none'?>"> 
	<p>
		<b>Do you know who holds the copyright to this object?</b><br/>
		<input type="radio" name="who_owns_<?=$obj['id']?>" id="who_owns_<?=$obj['id']?>" 
			   value="yes" class="do_askform_whoyesno" 
				<?=($obj['other_copyholder'] <> '')  ? 'checked="checked"' : ''?>/>&nbsp; Yes&nbsp;
		<input type="radio" name="who_owns_<?=$obj['id']?>" id="who_owns_<?=$obj['id']?>" 
			   value="no" class="do_ask_object_update do_askform_whoyesno" 
				<?=($obj['other_copyholder']=='' && $obj['is_unique']<>'pending')  ? 'checked="checked"' : ''?>/>&nbsp; No&nbsp;
	</p>

	<div id="who_yes_other_<?=$obj['id']?>" 
		style="margin-bottom: 20px; display: <?= ($obj['other_copyholder']=='') ? 'none':'block'?>"> 
		<b>Name of the copyright holder:</b><br/>
		<input type="text" name="other_copyholder_<?=$obj['id']?>" id="cpholder_<?=$obj['id']?>" 
			   value="<?=$obj['other_copyholder']?>"size="30" class="do_ask_object_update"/>
	</div>

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
		<b>The following are specific questions the dScribe has for you about this object:</b>
		<br/><br/>
<?php if ($questions == null) { ?> 

		<p>No questions at this time.</p>

<?php } else { foreach($questions as $question) { ?>

      	<p><b><?=$question['question']?></b></p>
       	<p>
       		<textarea name="q_<?=$obj['id']?>_<?=$question['id']?>" rows="10" cols="50" 
					  class="do_object_question_update"><?=$question['answer']?></textarea>
       	</p>
       	<p><hr style="border: 1px solid #eee"/></p>

<?php }} ?>
	</p>

	<!-- citation -->
	<br/><br/>
	<p style="clear:both"><h3>Citation: <small>(click below to edit)</small></h3> 
		<div id="holder_citation_<?=$obj['id']?>">
			<span id="txt_citation_<?=$obj['id']?>" class="ine_tip" title="Click to edit text"><?php echo ($obj['citation']<>'') ? $obj['citation']:'No citation'?></span>
		</div>
<?php 
	$n = count($inplaceeditors) + 1; 
	$ine_id = 'txt_citation_'.$obj['id'];
	$ine_holder = 'holder_citation_'.$obj['id'];
    $ine_url = "materials/update_object/$cid/$mid/{$obj['id']}/citation/";
	$inplaceeditors[]="var editor$n = new InPlaceEditor('$ine_id','$ine_holder',".
					  "'$ine_url','No citation'); ".
					  "editor$n.hover('$ine_id','$ine_holder','#ffffcc','#fff');";
?>
	</p>

	<!-- tags -->
	<br/><br/>
	<p style="clear:both"><h3>Keywords: <small>(click below to edit)</small></h3> 
		<div id="holder_tags_<?=$obj['id']?>">
			<span id="txt_tags_<?=$obj['id']?>" class="ine_tip" title="Click to edit text"><?php echo ($obj['tags']<>'') ? $obj['tags']:'No keywords'?></span>
		</div>
<?php 
	$n = count($inplaceeditors) + 1; 
	$ine_id = 'txt_tags_'.$obj['id'];
	$ine_holder = 'holder_tags_'.$obj['id'];
    $ine_url = "materials/update_object/$cid/$mid/{$obj['id']}/tags/";
	$inplaceeditors[]="var editor$n = new InPlaceEditor('$ine_id','$ine_holder',".
					  "'$ine_url','No keywords'); ".
					  "editor$n.hover('$ine_id','$ine_holder','#ffffcc','#fff');";
?>
	</p>

	<!-- save options  -->	
	<br/><br/>
	<p>
 		<input type="submit" name="status_<?=$obj['id']?>" value="Save for later" id="close_<?=$obj['id']?>"
			   class="do_object_status_update"/>
		&nbsp;&nbsp;
 		<input type="submit" name="status_<?=$obj['id']?>" value="Send to dScribe" class="do_object_status_update"/>
	</p>
</div>

<div id="inprogress-col1-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'block':'none'?>;">
<b>Saved for further editing later</b><br/><br/>
<input type="button" value="Continue editing" id="open_<?=$obj['id']?>"/>
</div>

</td>



<td style="vertical-align:top">

<div id="new-col2-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'none':'block'?>;">
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['name'],$obj['location'],false,false);?>
<br/> <br/>
</div>


<div id="inprogress-col2-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'block':'none'?>;">
  <?=$this->ocw_utils->create_co_img($cid,$mid,$obj['name'],$obj['location'],false,true);?><br/>
</div>
</td>
</tr>	
<?php
	$sliders[] = "var mySlide_newcol1_{$obj['id']} = $('new-col1-{$obj['id']}');
				  var mySlide_newcol2_{$obj['id']} = $('new-col2-{$obj['id']}');
				  var mySlide_inpcol1_{$obj['id']} = $('inprogress-col1-{$obj['id']}');
				  var mySlide_inpcol2_{$obj['id']} = $('inprogress-col2-{$obj['id']}');

				  $('open_{$obj['id']}').addEvent('click', function(e) {
						e = new Event(e);
						mySlide_inpcol1_{$obj['id']}.style.display = 'none';
						mySlide_inpcol2_{$obj['id']}.style.display = 'none';
						mySlide_newcol2_{$obj['id']}.style.display = 'block';
						mySlide_newcol1_{$obj['id']}.style.display = 'block';
						e.stop();
				  });
				  $('close_{$obj['id']}').addEvent('click', function(e) {
						e = new Event(e);
						mySlide_newcol1_{$obj['id']}.style.display = 'none';
						mySlide_newcol2_{$obj['id']}.style.display = 'none';
						mySlide_inpcol1_{$obj['id']}.style.display = 'block';
						mySlide_inpcol2_{$obj['id']}.style.display = 'block';
						e.stop();
				  });";
?>
<?php $count++; } } ?>

<script type="text/javascript">
window.addEvent('domready', function() {
    <?php foreach($inplaceeditors as $editor) { echo $editor."\n"; } ?>
    <?php foreach($sliders as $slider) { echo $slider."\n"; } ?>
    var myTips = new MooTips($$('.ine_tip'), { maxTitleChars: 50 });
});
</script>
