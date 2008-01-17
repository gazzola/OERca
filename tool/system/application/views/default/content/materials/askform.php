<?php
echo style('smoothbox.css');
echo script('smoothbox.js');

$sigs = array('high'=>'Very important', 'normal'=>'Important', 'low'=>'Not important'); 
?>
<style>
img { float: left; padding-right: 10px; padding-bottom: 10px;}
</style>
<h1>Verify Media Objects Ownership</h1><br/>

<div class="column span-24 first last" style="background-color:#eee; padding:5px; margin-bottom: 20px;">
<p>
Below is a list of media found in the material listed below for which we 
cannot determine ownership. Please go through the list and for each media object
indicate whether or not you own the media (i.e. do you have a copyright for the media) and the importance of the media as it is used in the lecture (i.e. would replacing the image with a suitable replacement severly undermine the point the image is meant to convey?).
</p>
<p>
Thanks for your cooperation!
</p
</div>


<div class="column span-6 first">
<h2>Material Information</h2>
<p>
  <b>Instructor Name:</b><br> 
  <?php echo $material['author']; echo ($material['collaborators']<>'') ? ' with '.$material['collaborators'] : ''?> <br/><br/>

  <b>Sequence: </b><br><?php echo $course['title']?><br/><br/>

  <b>Lecture: </b><br><?php echo $material['name']?><br><br/>

  <b>Date: </b><br><?=mdate('%d %M, %Y',mysql_to_unix($course['start_date'])).' - '.  mdate('%d %M, %Y',mysql_to_unix($course['end_date']))?><br/><br/>

  <b>CTools URL: </b><br>
  <?php if ($material['ctools_url'] <> '') { ?>
	<a href="<?=$material['ctools_url']?>"><?= $material['name']?></a>
  <?php } else { ?>
	<span style="color: red">no URL found for resource</span>
  <?php } ?>
  <br/><br/>
</p>
<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$mid?>" />
</div>

<?php if ($numobjects == 0) { ?>

<div class="column span-18 last"> 
<p class="error">Presently, none of the media objects in this material need ownership clarification.</p>
</div>

<?php } else { ?>


<div class="column span-18 last"> 

<div class="column span-18">
<h2>Media (<?=$numobjects?> items)</h2>

<form name="askform" id="askform" action="<?=site_url("materials/processform/ask/$cid/$mid")?>" method="post">

<table class="rowstyle-alt no-arrow" style="padding: 0">
    <thead>
    <tr>
        <th>&nbsp;</th>
        <th>Media</th>
        <th>Questions</th>
    </tr>
    </thead>
    <tbody>

<?php $count = 1; foreach($objects as  $obj) { ?>
	<tr>
		<td valign="top"><?=$count?></td>
        <td valign="top">
			<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['name'],$obj['location'],false,false);?><br/>
			<p><b>Recommended action: </b> <?= $obj['action_type']?></p>
			<p><b>Location:</b> Page <?=$obj['location']?></p>
			<p><?=$this->ocw_utils->create_slide($cid,$mid,$obj['location'],'View slide for more context');?></p>
			
			<p style="clear:both"><h3>Citation: </h3> <?php echo ($obj['citation'] <> '') ? $obj['citation']:' No citation'?></p>
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
         	</div>
		</td>
		<td>
			<p>
			<b>Do you own this media?</b><br/>
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
	</tr>	
<?php $count++; } ?>
	</tbody>
</table>

<div style="text-align:center">
 <input type="submit" value="Save" class="do_askform_submit"/>
</div>
</form>

</div>


</div>

<?php } ?>
