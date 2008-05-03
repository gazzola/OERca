<?php 
 $count = 1;
if ($prov_objects != null) {
 foreach($prov_objects as  $obj) {
	if ($obj['ask_status'] == 'done') {
?>
<tr>
<td style="vertical-align:top"><?=$count?></td>

<td style="vertical-align:top; width: 300px; padding: 10px;">
  <p>
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],false,true);?>
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
	<?php 
		if ($obj['description']) {
				echo 'Instructor provided the following description:<br/><br/>';
				echo '<p style="background-color:#ddd; padding:5px;">'.$obj['description'].'</p><br/><br/>';
		}
		if ($obj['instructor_owns']=='yes') { 
			echo 'Instructor indicated that they <i>hold</i> the copyright to this object.';
		} else { 
			echo 'Instructor indicated that they <em>do not hold</em> the copyright to this object.';
			echo '<br/><br/>'; 

			if ($obj['other_copyholder']=='') {
				echo 'Instructor indicated that they <em>do not know</em> who holds the copyright.';
				echo '<br/><br/>';
				if ($obj['is_unique']=='yes') {
					echo 'Instructor indicated that the representation of this information <em>is unique</em>';
				} else {
					echo 'Instructor indicated that the representation of this information <em>is not unique</em>';
				}
		 	} else { 
				echo 'Instructor indicated that <em>'.$obj['other_copyholder'].'</em> holds the copyright.';
		 	} 
		}
	?>
    </p>

</td>
</tr>	
<?php $count++; }}} 

if ($repl_objects != null) {
 foreach($repl_objects as  $obj) {
	if ($obj['ask_status'] == 'done') {
?>

<tr>
<td style="vertical-align:top"><?=$count?></td>

<td style="vertical-align:top">
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],false,true);?>

	<!-- citation -->
	<br/><br/>
	<p style="clear:both"><h3>Citation:</h3> 
		<div>
			<span><?php echo ($obj['citation']<>'') ? $obj['citation']:' No citation'?></span>
		</div>
	</p>

	<!-- tags -->
	<br/><br/>
	<p style="clear:both"><h3>Keywords:</h3> 
		<div>
			<span><?php echo ($obj['tags']<>'') ? $obj['tags']:' No keywords'?></span>
		</div>
	</p>
</td>

<td style="vertical-align:top">
  <?php if ($obj['suitable']=='yes') { ?>
    <h3>Replaced With:</h3>                
		<?=$this->ocw_utils->create_corep_img($cid,$mid,$obj['id'],$obj['location'],false);?>
  <?php } else { ?>
    <h3>Rejected Replacement:</h3>                
		<?=$this->ocw_utils->create_corep_img($cid,$mid,$obj['id'],$obj['location'],false);?>
    <br/><br/>
    <h3>Reason:</h3>                
    <?= ($obj['unsuitable_reason']=='') ? 'No reason provided' : $obj['unsuitable_reason']; ?>
  <?php } ?>

</td>
</tr>

<?php $count++; }}} ?> 
