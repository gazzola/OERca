<?php 
 $count = 1;
if ($prov_objects != null) {
 foreach($prov_objects as  $obj) {
	if ($obj['ask_status'] == 'done') {
?>
<tr>
<td style="vertical-align:top"><?=$count?></td>

<td style="vertical-align:top">
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['name'],$obj['location'],false,true);?><br/>
</td>

<td style="vertical-align: top">
	<p><h3>Actions Taken:</h3>                
	<?php 
		if ($obj['instructor_owns']=='yes') { 
			echo 'You indicated that you <i>hold</i> the copyright to this object.';
		} else { 
			echo 'You indicated that you <em>do not hold</em> the copyright to this object.';
			echo '<br/><br/>'; 

			if ($obj['other_copyholder']=='') {
				echo 'You indicated that you do not know who holds the copyright.';
				echo '<br/><br/>';
				if ($obj['unique']=='yes') {
					echo 'You indicated that the representation of this information is unique';
				} else {
					echo 'You indicated that the representation of this information is not unique';
				}
		 	} else { 
				echo 'You indicated that <b>'.$obj['other_copyholder'].'</b> holds the copyright.';
		 	} 
		}
	?>
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
	<p style="clear:both"><h3>Tags:</h3> 
		<div id="holder_tags_<?=$obj['id']?>">
			<span id="txt_tags_<?=$obj['id']?>" class="ine_tip" title="Click to edit text">
				<?php echo ($obj['tags']<>'') ? $obj['tags']:' No tags'?>
			</span>
		</div>
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
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['name'],$obj['location'],false,true);?><br/>
</td>

<td style="vertical-align:top">
	<p><h3>Replaced With:</h3>                
		<?=$this->ocw_utils->create_corep_img($cid,$mid,$obj['name'],$obj['location'],false);?>
    </p>
	<!-- citation -->
	<p style="clear:both"><h3>Citation:</h3> 
		<div id="holder_citation_<?=$obj['id']?>">
			<span id="txt_citation_<?=$obj['id']?>" class="ine_tip" title="Click to edit text">
				<?php echo ($obj['citation']<>'') ? $obj['citation']:' No citation'?>
			</span>
		</div>
	</p>

	<!-- tags -->
	<p style="clear:both"><h3>Tags:</h3> 
		<div id="holder_tags_<?=$obj['id']?>">
			<span id="txt_tags_<?=$obj['id']?>" class="ine_tip" title="Click to edit text">
				<?php echo ($obj['tags']<>'') ? $obj['tags']:' No tags'?>
			</span>
		</div>
	</p>
</td>
</tr>

<?php $count++; }}} ?> 
