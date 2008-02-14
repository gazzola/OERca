<?php $this->load->view(property('app_views_path').'/materials/materials_header.php', $data); ?>

<?php	
  $tags[0] = '-- select --';
  $comments = $material['comments'];
  $copyholder = ($material['author']=='') ? $course['director'] : $material['author'];

  $action_types = array('Fair Use'=>'Fair Use', 
					    'Search'=>'Search',
						'Commission'=>'Commission',
						'Permission'=>'Permission',
						'Retain'=>'Retain',
					    'Remove'=>'Remove');
	
  $types = '<select id="subtype_id" name="subtype_id">';
  foreach($subtypes as $type => $subtype) {
		$types .= '<optgroup label="'.$type.'">';
		foreach($subtype as $st) {
			$types .= '<option value="'.$st['id'].'">'.$st['name'].'</option>';
		}
		$types .= '</optgroup>';
  } 
  $types .= '</select>';
?>

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$material['id']?>" />
<input type="hidden" id="defcopy" name="defcopy" value="<?=$course['director']?>" />

<!--
<div id="infobar" class="column span-7 first colborder">
	
	<h3>Content Object Stats</h3>
	<div class="collapsable">
	<div  class="collapse-container">
		<br>
		<div class="formField"><h2 style="display: inline; color:#ccc">Total Objects:</h2>
			<b>
			<?= nbs(2).$objstats['total']?>
			</b>
		</div>
		<br><br>

		<div class="formField"><h2 style="display: inline; color:#ccc">Cleared:</h2>
			<b>
			<?= nbs(2).$objstats['cleared']?>
			</b>
		</div>
		<br><br>

		<div class="formField">
			<h2 style="display: inline; color:#ccc">Ask Instructor:</h2>
			<b>
			 <?php echo nbs(2).$objstats['ask']; 
				if ($objstats['ask'] > 0) { echo nbs(2).'<small><a href="'.site_url("materials/viewform/ask/$cid/".$material['id']).'">view ASK form</a></small>'; } ?> 
			</b>
		</div>
		<br><br>

		<div class="formField"><h2 style="display: inline; color:#ccc">Fair Use:</h2>
			<b>
			<?php 
				if (isset($objstats['Fair Use'])) {
					echo nbs(2).$objstats['Fair Use']; 
					#echo nbs(2).'<small><a href="">view Fair Use form</a></small>'; 
				} else { echo nbs(2).'0'; }
			?> 
			</b>
		</div>
		<br><br>

		<div class="formField">
			<h2 style="display: inline; color:#ccc">Searching:</h2>
			<b>
			<?php 
				if (isset($objstats['Search'])) { echo nbs(2).$objstats['Search']; } 
				else { echo nbs(2).'0'; }
			?> 
			</b>
		</div>
		<br><br>

		<div class="formField">
			<h2 style="display: inline; color:#ccc">Commissioning:</h2>
			<b>
			<?php 
				if (isset($objstats['Commission'])) {
					echo nbs(2).$objstats['Commission']; 
				} else { echo nbs(2).'0'; }
			?> 
			</b>
		</div>
		<br><br>

		<div class="formField">
			<h2 style="display: inline; color:#ccc">Permission:</h2>
			<b>
			<?php 
				if (isset($objstats['Permission'])) {
					echo nbs(2).$objstats['Permission']; 
					#echo nbs(2).'<small><a href="">view Permission form</a></small>'; 
				} else { echo nbs(2).'0'; }
			?> 
			</b>
		</div>
		<br><br>

		<div class="formField">
			<h2 style="display: inline; color:#ccc">Retaining:</h2>
			<b>
			<?php 
				if (isset($objstats['Retain'])) {
					echo nbs(2).$objstats['Retain']; 
				} else { echo nbs(2).'0'; }
			?> 
			</b>
		</div>
		<br><br>

		<div class="formField">
			<h2 style="display: inline; color:#ccc">Removing:</h2>
			<b>
			<?php 
				if (isset($objstats['Remove'])) {
					echo nbs(2).$objstats['Remove']; 
				} else { echo nbs(2).'0'; }
			?> 
			</b>
		</div>
	</div>
	</div>

	<div class="clear"><br/></div>
</div>
-->

<div class="column span-21 first last">
	<?php if ($numobjects > 0) { ?>
	<iframe src="<?=site_url("materials/content_objects/$cid/{$material['id']}")?>" width="650px" height="600px"></iframe>
	<?php } else { ?>
	<div class="column span-15 first last">
		<p><br/>No content objects recorded for this material.</p>
	</div>
	<?php } ?>
</div>

<?php $this->load->view(property('app_views_path').'/materials/materials_footer.php', $data); ?>
