<?php 
 $count = 1;
if ($prov_objects != null) {
 foreach($prov_objects as  $obj) {
	if ($obj['ask_status'] == 'done') {
?>
<tr>
<td valign="top"><?=$count?></td>

<td valign="top">
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['name'],$obj['location'],false,false);?><br/>
	<p><b>Recommended action: </b> <?= $obj['action_type']?></p>
	<p><b>Location:</b> Page <?=$obj['location']?></p>
	<p><?=$this->ocw_utils->create_slide($cid,$mid,$obj['location'],'View slide for more context',false);?></p>
</td>

<td valign="top">
	<!-- upload replacement -->
	<p><h3>Replacement Image:</h3>                
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
<?php $count++; }}} 

if ($repl_objects != null) {
 foreach($repl_objects as  $obj) {
	if ($obj['ask_status'] == 'done') {
?>

<tr>
<td valign="top"><?=$count?></td>

<td valign="top">
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['name'],$obj['location'],false,false);?><br/>
</td>

<td valign="top">
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
