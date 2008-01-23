<?php
echo style('smoothbox.css');
echo style('multiupload.css');
echo script('mootips.js');
echo script('smoothbox.js');
echo script('moo-ipe.js');
echo script('multiupload.js');

$sigs = array('high'=>'Very important', 'normal'=>'Important', 'low'=>'Not important'); 
$inplaceeditors = array();
$uploaders = array();
?>
<style>
.carousel-image { float: left; padding-right: 10px; padding-bottom: 10px;}
p.txt{
	margin: 0;
	padding: 5px;
}
textarea{
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	border: 1px solid #888;
	margin: 0 5px 5px 0;
}
</style>

<h1 style="margin-bottom: 5px"><?=$course['title']?>&nbsp;&raquo;&nbsp;<?=$material['name']?> Content Objects</h1>
<p style="border-top: 1px solid #ddd; background-color: #fff; padding: 2px 3px 0 0; color: #ddd">
<b>Instructor:</b> 
<?php echo $material['author']; echo ($material['collaborators']<>'') ? ' with '.$material['collaborators'] : ''?> &nbsp;|&nbsp;
<b>Date: </b><?=mdate('%d %M, %Y',mysql_to_unix($course['start_date'])).' - '.  mdate('%d %M, %Y',mysql_to_unix($course['end_date']))?> &nbsp;|&nbsp;
<b>CTools URL:</b>  <?php if ($material['ctools_url'] <> '') { ?>
	<a href="<?=$material['ctools_url']?>"><?= $material['name']?></a>
  <?php } else { ?>
	<span style="color: red">no URL found for resource</span>
  <?php } ?>
</p>
<br/>

<div class="column span-24 first last" style="background-color:#eee; padding:5px; margin-bottom: 20px;">
<p>
The following page lists some of the media used in one of your courses.  We are asking about it because we cannot determine its provenance. Please go through the list and for each media object indicate whether or not you created and hold the copyright to the media. 
<br/><br/>
Thanks for your cooperation!
</p>
</div>

<?php if ($numobjects == 0) { ?>

<div class="column span-24 first last"> 
<p class="error">Presently, none of the content objects in this material need copyright clarification.</p>
</div>

<?php } else { ?>
<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$mid?>" />

<h2>Content Objects (<?=$numobjects?> items)</h2>

<?php $this->load->view(property('app_views_path').'/materials/askform_header.php', $data); ?>


<div class="column span-24 first last"> 
<!--
<form name="askform" id="askform" action="<?=site_url("materials/processform/ask/$cid/$mid")?>" method="post">
-->

<table class="rowstyle-alt no-arrow" style="padding: 0">
    <thead>
    <tr>
        <th>&nbsp;</th>
        <th>Questions</th>
        <th>Content Object Information</th>
    </tr>
    </thead>
    <tbody>
<?php 
	$count = 1; 
	$st = array('in progress'=>'in_progress','save for later'=>'in_progress','done'=>'done');
	foreach($objects as  $obj) { 
		if ($st[$obj['ask_status']] == $view) {;
?>
	<tr>
		<td valign="top"><?=$count?></td>

		<td>
			<p>
			<b>Did you create and hold the copyright to this?</b><br/>
			<input type="radio" name="ask[<?=$obj['id']?>][own]" id="own_<?=$obj['id']?>" value="yes" class="do_askform_yesno"/>&nbsp; Yes&nbsp;
			<input type="radio" name="ask[<?=$obj['id']?>][own]" id="own_<?=$obj['id']?>" value="no" class="do_askform_yesno" />&nbsp; No
			</p>
			<?php if ($obj['action_type']=='Commission') { ?>
			<div id="other_<?=$obj['id']?>" style="display:none"> 
			<p class="error">If you know of another place to get a suitable replacement for this media object, please note it in the comment box below</p>
			</p>
			<br/>
			</div>
			<?php } ?>

			<p>
			<b>How Important is this media to the lesson?</b><br/>
			<?php echo form_dropdown('ask['.$obj['id'].'][significance]', $sigs, $obj['significance'],'id="significance_'.$obj['id'].'"'); ?>
			<br/>
			</p>
			<br/>
			<p>
			<b>Do you have any comments?</b><br/>
              <textarea name="ask[<?=$obj['id']?>][comments]" id="comments_<?=$obj['id']?>" cols="50"></textarea>
			</p>
		</td>

        <td valign="top">
			<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['name'],$obj['location'],false,false);?><br/>
			<p><b>Recommended action: </b> <?= $obj['action_type']?></p>
			<p><b>Location:</b> Page <?=$obj['location']?></p>
			<p><?=$this->ocw_utils->create_slide($cid,$mid,$obj['location'],'View slide for more context',true);?></p>
			
			<!-- upload replacement -->
			<p style="clear:both"><h3>Upload Replacement:</h3>
			<div style="float: left">
				Upload a suitable replacement image that you created and hold the copyright to:<br/><br/>
				<?php 
					$n = count($uploaders) + 1; 
					$uploadid = 'add_ip_rep'.$n;
 					$uploaders[] = "new MultiUpload($('userfile$n'), 1, null, true, true); ";
				?>
            	<form action="<?=site_url("materials/update_object/$cid/$mid/{$obj['id']}/irep")?>" enctype="multipart/form-data" id="<?=$uploadid?>" name="<?=$uploadid?>" method="post">
            		<div>
                		<input type="file" name="userfile" id="userfile<?=$n?>" size="45" />
            		</div>
					<div style="clear:both">
						<br/>
            			<input type="submit" name="submit" id="submit" value="Upload" />
            		</div>
            	</form>
			</div>
			<div style="float:right;">Replacement image:<br/><br/>
    			<?=$this->ocw_utils->create_corep_img($cid,$mid,$obj['name'],$obj['location'],false);?>
			</div>
			</p>
			

			<!-- citation -->
			<p style="clear:both"><h3>Citation: <small>(click below to edit)</small></h3> 
				<div id="holder_citation_<?=$obj['id']?>">
					<span id="txt_citation_<?=$obj['id']?>" class="ine_tip" title="Click to edit text"><?php echo ($obj['citation']<>'') ? $obj['citation']:' No citation'?></span>
				</div>
				<?php 
					$n = count($inplaceeditors) + 1; 
					$ine_id = 'txt_citation_'.$obj['id'];
					$ine_holder = 'holder_citation_'.$obj['id'];
				    $ine_url = "/{$obj['id']}/citation/";
					$inplaceeditors[]="var editor$n = new InPlaceEditor('$ine_id','$ine_holder',".
									  "'$ine_url','No citation'); ".
									  "editor$n.hover('$ine_id','$ine_holder','#ffffcc','#fff');";
				?>
			</p>

			<!-- tags -->
			<p style="clear:both"><h3>Tags: <small>(click below to edit)</small></h3> 
				<div id="holder_tags_<?=$obj['id']?>">
					<span id="txt_tags_<?=$obj['id']?>" class="ine_tip" title="Click to edit text"><?php echo ($obj['tags']<>'') ? $obj['tags']:' No tags'?></span>
				</div>
				<?php 
					$n = count($inplaceeditors) + 1; 
					$ine_id = 'txt_tags_'.$obj['id'];
					$ine_holder = 'holder_tags_'.$obj['id'];
				    $ine_url = "/{$obj['id']}/tags/";
					$inplaceeditors[]="var editor$n = new InPlaceEditor('$ine_id','$ine_holder',".
									  "'$ine_url','No tags'); ".
									  "editor$n.hover('$ine_id','$ine_holder','#ffffcc','#fff');";
				?>
			</p>

			<!-- Comments -->
			<p><h3>Comments:</h3></b></p>
            <?php 
				$comments = $obj['comments'];
				if ($comments == null) { ?>
                    <p id="nocomments">No comments posted</p>
                <?php } else { foreach($comments as $comment) { ?>
                        <p><?=$comment['comments']?></p>
                        <p>
                          <small>by&nbsp;<?=$this->ocw_user->username($comment['user_id'])?>&nbsp;
                          <?=strtolower($this->ocw_utils->time_diff_in_words($comment['modified_on']))?>
                          </small>
                        </p>
                        <p><hr style="border: 1px solid #eee"/></p>
           <?php  }  } ?>
		
			<!-- save options  -->	
			<p><br/>
 				<input type="submit" value="Save for later" />
				&nbsp;&nbsp;
 				<input type="submit" value="Done" />
			</p>
		</td>
	</tr>	
<?php $count++; }} ?>
	</tbody>
</table>

<!--
</form>
-->

</div>

<script type="text/javascript">
window.addEvent('domready', function() {
	<?php foreach($inplaceeditors as $editor) { echo $editor."\n"; } ?>
	<?php foreach($uploaders as $upl) { echo $upl."\n"; } ?>
	var myTips = new MooTips($$('.ine_tip'), { maxTitleChars: 50 });
});
</script>

<?php } ?>
