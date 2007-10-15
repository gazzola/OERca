<?php 
	echo script('datepicker.js');
	echo style('datepicker.css');

	$this->load->view(property('app_views_path').'/dscribe2/dscribe2_header.php', $data); 
	$tags[0] = '-- select --';
	$filetypes[0] = '--- Select Filetype ---';
	$curriculum['new'] = 'Add new curriculum:';
	$curriculum['none'] = 'None';
	$curriculum[0] = '--- Select Curriculum ---';
	$sequences['new'] = 'Add new sequence:';
	$sequences['none'] = 'None';
	$sequences[0] = '--- Select Sequence ---';

	$categories['new'] = 'Add new category:';
	$categories[0] = '--- Select Category ---';
?>
<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />

<div id="tool_content">

<?php $this->load->view(property('app_views_path').'/dscribe2/_edit_course_details.php', $data); ?> 
&nbsp;&nbsp;|&nbsp;&nbsp;
<?php $this->load->view(property('app_views_path').'/dscribe2/_add_course_materials.php', $data); ?> 

<br/><br/>

<h1>Course Materials</h1>


<?php if ($materials == null) { ?>
<tr><td colspan="*"><br>No materials found<br></td></tr>
<?php } else { ?>

<?php foreach($materials as $category => $cmaterial) { ?> 
<table class="rowstyle-alt no-arrow" width="780px">
	<caption><?=$category?></caption>

	<thead>	
	<tr>
		<th>Name</th>
		<th class="sortable">Author(s)</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
	</tr>
	</thead>	

	<tbody>
<?php foreach($cmaterial as $material) { ?>
	<tr>
		<?php if ($material['mimetype'] == 'folder') { ?>
		<td colspan="4">
			<?= $this->ocw_utils->icon($material['mimetype'])?>&nbsp;&nbsp;<?= $material['name']?>
		</td>

		<?php } else { ?>
		<td>
			<?= $this->ocw_utils->icon($material['mimetype'])?>&nbsp;&nbsp;
			<a href="<?=site_url()."dscribe2/materials/$cid/view/".$material['id']?>"><?= $material['name']?></a>
		</td>
		<td><?= $material['author']?></td>
		<td>
			<a href="<?php echo site_url()."dscribe/materials/$cid/edit_material/".$material['id']?>">Edit</a>
		</td>
	 	<td>
			<a href="<?php echo site_url()."dscribe/materials/$cid/view_ip/".$material['id']?>">Modify IP</a>
		</td>

		<?php } ?>
	</tr>
	<?php 
		if (@is_array($material['childitems'])) { 
			$childitems = $material['childitems'];
			$depth = 1;
			include property('app_views_abspath').'/dscribe2/_childitems.php';
	    } 
	 ?>
<?php } } ?>
	<tbody>
</table>

<?php } ?>

</div>
