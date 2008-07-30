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
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],'orig',true);?>
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
	<?php echo $this->coobject->ask_instructor_report($cid, $mid, $obj, 'original');	?>
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
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['object_id'],$obj['location'],'orig',true);?>

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
	<?php echo $this->coobject->ask_instructor_report($cid, $mid, $obj, 'replacement');	?>
</td>
</tr>

<?php $count++; }}} ?> 
