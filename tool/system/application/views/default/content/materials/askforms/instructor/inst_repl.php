<?php 
$count = 1;
$inplaceeditors = array();
$sliders = array();

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
		<input type="radio" name="rep_ok_<?=$obj['id']?>" id="repok_yes_<?=$obj['id']?>" 
			   value="yes" class="do_askform_suityesno do_replacement_update" 
				<?=($obj['suitable']=='yes')  ? 'checked="checked" ' : ''?>/>&nbsp; Yes&nbsp;
		<input type="radio" name="rep_ok_<?=$obj['id']?>" id="repok_no_<?=$obj['id']?>" 
			   value="no" class="do_askform_suityesno do_replacement_update" 
				<?=($obj['suitable']=='no')  ? 'checked="checked" ' : ''?>/>&nbsp; No&nbsp;
	</p>


	<div id="suit_yes_other_<?=$obj['id']?>" style="display: <?= ($obj['suitable']=='yes') ? 'block':'none'?>"> 
	<!-- citation -->
	<p style="clear:both"><h3>Replacement Citation: <small>(click below to edit)</small></h3> 
		<div id="holder_citation_<?=$obj['id']?>">
			<span id="txt_citation_<?=$obj['id']?>" class="ine_tip" title="Click to edit text"><?php echo ($obj['citation']<>'') ? $obj['citation']:'No citation'?></span>
		</div>
<?php 
	$n = count($inplaceeditors) + 1; 
	$ine_id = 'txt_citation_'.$obj['id'];
	$ine_holder = 'holder_citation_'.$obj['id'];
    $ine_url = "materials/update_replacement/$cid/$mid/{$obj['id']}/citation/";
	$inplaceeditors[]="var editor$n = new InPlaceEditor('$ine_id','$ine_holder',".
					  "'$ine_url','No citation'); ".
					  "editor$n.hover('$ine_id','$ine_holder','#ffffcc','#fff');";
?>
	</p>

	<!-- tags -->
	<br/><br/>
	<p style="clear:both"><h3>Replacement Keywords: <small>(click below to edit)</small></h3> 
		<div id="holder_tags_<?=$obj['id']?>">
			<span id="txt_tags_<?=$obj['id']?>" class="ine_tip" title="Click to edit text"><?php echo ($obj['tags']<>'') ? $obj['tags']:'No keywords'?></span>
		</div>
<?php 
	$n = count($inplaceeditors) + 1; 
	$ine_id = 'txt_tags_'.$obj['id'];
	$ine_holder = 'holder_tags_'.$obj['id'];
    $ine_url = "materials/update_replacement/$cid/$mid/{$obj['id']}/tags/";
	$inplaceeditors[]="var editor$n = new InPlaceEditor('$ine_id','$ine_holder',".
					  "'$ine_url','No keywords'); ".
					  "editor$n.hover('$ine_id','$ine_holder','#ffffcc','#fff');";
?>
	</p>
	</div>


	<div id="suit_no_other_<?=$obj['id']?>" style="display: <?= ($obj['suitable']=='no') ? 'block':'none'?>"> 
	<p>
		<b>Why is this not a suitable replacement?</b><br/>
       	<textarea name="notsuitable" id="c_<?=$obj['id']?>" rows="10" cols="50" class="do_replacement_update"><?=$obj['unsuitable_reason']?></textarea>
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
       	<p>
       		<textarea name="q_<?=$obj['id']?>_<?=$question['id']?>" rows="10" cols="50" 
					  class="do_replacement_question_update"><?=$question['answer']?></textarea>
       	</p>
       	<p><hr style="border: 1px solid #eee"/></p>

<?php }} ?>
	</p>

	<!-- save options  -->	
	<p><br/>
 		<input type="submit" name="status_<?=$obj['id']?>" value="Save for later" id="close_<?=$obj['id']?>"
			   class="do_replacement_status_update"/>
		&nbsp;&nbsp;
 		<input type="submit" name="status_<?=$obj['id']?>" value="Send to dScribe" class="do_replacement_status_update"/>
	</p>
</div>

<div id="inprogress-col1-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'block':'none'?>;">
<b>Saved for further editing later</b><br/><br/>
<input type="button" value="Continue editing" id="open_<?=$obj['id']?>"/>
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
<?php
	$sliders[] = "var mySlide_newcol1_{$obj['id']} = $('new-col1-{$obj['id']}');
				  var mySlide_newcol2_{$obj['id']} = $('new-col2-{$obj['id']}');
				  var mySlide_newcol3_{$obj['id']} = $('new-col3-{$obj['id']}');
				  var mySlide_inpcol1_{$obj['id']} = $('inprogress-col1-{$obj['id']}');
				  var mySlide_inpcol2_{$obj['id']} = $('inprogress-col2-{$obj['id']}');
				  var mySlide_inpcol3_{$obj['id']} = $('inprogress-col3-{$obj['id']}');

				  $('open_{$obj['id']}').addEvent('click', function(e) {
						e = new Event(e);
						mySlide_inpcol1_{$obj['id']}.style.display = 'none';
						mySlide_inpcol2_{$obj['id']}.style.display = 'none';
						mySlide_inpcol3_{$obj['id']}.style.display = 'none';
						mySlide_newcol2_{$obj['id']}.style.display = 'block';
						mySlide_newcol1_{$obj['id']}.style.display = 'block';
						mySlide_newcol3_{$obj['id']}.style.display = 'block';
						e.stop();
				  });
				  $('close_{$obj['id']}').addEvent('click', function(e) {
						e = new Event(e);
						mySlide_newcol1_{$obj['id']}.style.display = 'none';
						mySlide_newcol2_{$obj['id']}.style.display = 'none';
						mySlide_newcol3_{$obj['id']}.style.display = 'none';
						mySlide_inpcol1_{$obj['id']}.style.display = 'block';
						mySlide_inpcol2_{$obj['id']}.style.display = 'block';
						mySlide_inpcol3_{$obj['id']}.style.display = 'block';
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
