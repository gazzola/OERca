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

	<h3 class="collapsable">Add Content Object <span class="sign">+</span></h3>
	<div  class="collapse">
	<div  class="collapse-container">
	<form id="add_co" name="add_co" enctype="multipart/form-data" action="<?=site_url("materials/add_object/$cid/{$material['id']}")?>" method="post">

		<div class="formLabel">Location:</div>
		<div class="formField">
          		<input type="text" name="location" id="location" size="30"/>
        </div>

		<div class="formLabel">Thumbnail:</div>
		<div class="formField">
			<input type="file" name="userfile" size="20" />
		</div>

		<div class="formLabel">Subtype:</div>
		<div class="formField"><?=$types?></div>

		<div class="formField">Ask Instructor:
		<?php 
			echo form_radio('ask', 'yes', FALSE).'&nbsp;Yes&nbsp;&nbsp'; 
			echo form_radio('ask', 'no', TRUE) .'&nbsp;No';
		?>
        </div>

		<div class="formLabel">Action:</div>
		<div class="formField">
			<?php echo form_dropdown('action_type', $action_types, 'Search','id="action_type"'); ?>
        </div>

		<div class="formLabel">Comment:</div>
		<div class="formField">
		<textarea name="comment" id="comment" cols="40" rows="1"></textarea>
        </div>

		<div class="formLabel">Citation:</div>
		<div class="formField">
		<textarea name="citation" id="citation" cols="40" rows="1"></textarea>
        </div>

		<br/>

		<div class="formField">
            <input id="co_request" name="co_request" type="submit" value="Add" />
        </div>
	</form>
	</div>
	</div>

	<div class="clear"><br/></div>
</div>

<div class="column span-15 last">
	<?php if ($numobjects > 0) { ?>
	<iframe src="<?=site_url("materials/content_objects/$cid/{$material['id']}")?>" width="650px" height="600px"></iframe>
	<?php } else { ?>
	<div class="column span-15 first last">
		<p><br/>No content objects recorded for this material.</p>
	</div>
	<?php } ?>
</div>

<?php $this->load->view(property('app_views_path').'/materials/materials_footer.php', $data); ?>
