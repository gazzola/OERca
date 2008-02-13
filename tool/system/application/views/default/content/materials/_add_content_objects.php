<?php	
  $action_types = array('Fair Use'=>'Fair Use', 
					    'Search'=>'Search',
						'Commission'=>'Commission',
						'Permission'=>'Permission',
						'Retain'=>'Retain',
					    'Remove'=>'Remove');
	
  $types = '<select id="subtype_id" name="subtype_id">';
  foreach($subtypes as $type => $subtype) {
		$types .= '<optgroup label="'.$type.'">';
		foreach($subtype as $st) { $types .= '<option value="'.$st['id'].'">'.$st['name'].'</option>'; }
		$types .= '</optgroup>';
  } 
  $types .= '</select>';
?>

<div id="pane_uploadco" class="editpane">

<div class="column span-7 first colborder">
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
</div>

<div class="column span-7 colborder">
		<div class="formLabel">Action:</div>
		<div class="formField">
			<?php echo form_dropdown('action_type', $action_types, 'Search','id="action_type"'); ?>
        </div>

		<div class="formLabel">Comment:</div>
		<div class="formField">
		<textarea name="comment" id="comment" cols="40" rows="1"></textarea>
        </div>
 </div>

<div class="column span-7 last">
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

<div class="column span-21 firstlast" style="padding: 40px; text-align: center;">
  <input type="button" value="Done" id="do_close_uploadco_pane"/>
</div>

</div>
