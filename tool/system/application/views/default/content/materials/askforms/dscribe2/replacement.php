<?php 
$count = 1;
$inplaceeditors = array();
$sliders = array();

foreach($repl_objects as  $obj) {
  $questions = $obj['questions'];
	$questions = (!is_null($questions) && 
								 isset($questions['dscribe2']) && sizeof($questions['dscribe2'])>0) ? $questions['dscribe2'] : null;

  /* if ($obj['ask_status'] <> 'done') {   KWC OERDEV-250 */
?>
<input type="hidden" id="oid-<?=$obj['id']?>" name="oid-<?=$obj['id']?>" value="<?=$obj['object_id']?>" />
<tr>
<td style="vertical-align:top"><?=$count?></td>

<td width="318" style="vertical-align:top">

<div id="new-col1-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'none':'block'?>;">
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

<!-- Original -->
<td width="350" style="vertical-align:top">
<div id="new-col2-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'none':'block'?>;">
		<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['object_id'],$obj['location'],'ask:rco','orig',false,true,true);?>
</div>
<div id="inprogress-col2-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'block':'none'?>;">
		<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['object_id'],$obj['location'],'ask:rco','orig',true,true,true,true,'','187');?><br/>

</div>
</td>

<!-- Replacement -->
<td width="320" style="vertical-align:top">
<div id="new-col3-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'none':'block'?>;">
		<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['object_id'],$obj['location'],'ask:rco','rep',false);?>
</div>
<div id="inprogress-col3-<?=$obj['id']?>" style="display: <?=($obj['ask_status']=='in progress') ? 'block':'none'?>;">
		<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['object_id'],$obj['location'],'ask:rco','rep');?>
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
<?php $count++; } /* } KWC OERDEV-250 */ ?>

<script type="text/javascript">
window.addEvent('domready', function() {
    <?php foreach($inplaceeditors as $editor) { echo $editor."\n"; } ?>
    <?php foreach($sliders as $slider) { echo $slider."\n"; } ?>
    var myTips = new MooTips($$('.ine_tip'), { maxTitleChars: 50 });
});
</script>
