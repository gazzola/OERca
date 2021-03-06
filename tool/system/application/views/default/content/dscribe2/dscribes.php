<?php
$select_box = '<select id="cid" name="cid" onchange="document.choosecourse.submit();" width="200px">';
$select_box .= '<option value="none">Choose a course</option>';
if ($courses<>null) {
    foreach($courses as $school => $curriculum) {
				$select_box .= '<optgroup label="'.$school.'">';
				foreach($curriculum as $crse) {
					foreach($crse as $c) {
									$sel = ($c['id']== $cid) ? 'selected' : '';
									$select_box .= '<option value="'.$c['id'].'" '.$sel.'>'.
																	$c['number'].' '.$c['title'].'</option>';
					}
        }
		    $select_box .= '</optgroup>';
    }
} 
$select_box .= '</select>';

$ds_select_box = '';
if ($all_dscribes != null) {
    $onteam = array();
    if ($dscribes<>null) { foreach($dscribes as $d) { array_push($onteam,$d['user_name']);} }
		$ds_select_box = '<select id="ds" name="ds" onchange="setds();" width="200px">';
		$ds_select_box .= '<option value="none">Choose a dScribe...</option>';
		foreach($all_dscribes as $d) {
            if (!in_array($d['user_name'],$onteam)) {
								$v = $d['email'].'|#|'.$d['name'].'|#|'.$d['user_name'];
								$ds_select_box .= '<option value="'.$v.'">'.
															$d['name'].' ('.$d['user_name'].')</option>';
						}
		}
		$ds_select_box .= '</select>';
}
?>

<?php if ($ds_select_box <> '')  { ?>
<script type="text/javascript">
	function setds() {
			if ($('ds').value=='none') {
					$('name').value = '';
					$('user_name').value = '';
					$('email').value = '';
			} else {
					var vals = ($('ds').value).split("|#|");
					$('email').value = vals[0];
					$('name').value = vals[1];
					$('user_name').value = vals[2];
			}
	}
</script>
<?php }?>

<div class="column span-24 first last">

<form name="choosecourse" action="<?=site_url("dscribe2/dscribes/")?>" method="POST">
<label for="cid">Course:</label>&nbsp;
<?php echo $select_box ?> 
</form>

<br/>

<?php if ($cid <> 'none') { ?>


<fieldset>
    <legend>Add a dScribe</legend>

    <form name="adminform" method="post" action="<?php echo site_url("dscribe2/dscribes/$cid")?>" style="margin:0px;">

    <input type="hidden" name="task" value="add_dscribe" />
    <input type="hidden" name="role" value="dscribe1" />

    <table>
			<?php if ($ds_select_box <> '')  { ?>
			<tr>
				<th style="text-align:right">Add an existing dScribe:</th>
				<td><?=$ds_select_box?></td>
			</tr>
			<?php } ?>
      <tr>
        <th style="text-align:right">
          <?php echo $this->lang->line('ocw_ins_dscribes_name')?>: &nbsp;&nbsp;
        </th>
        <td><input type="text" id="name"  name="name" tabindex="1" size="20" /></td>
      </tr>
      <tr>
        <th style="text-align: right">
          <?php echo $this->lang->line('ocw_ins_dscribes_email')?>: &nbsp;&nbsp;
        </th>
        <td>
          <input type="text" id="email" name="email" tabindex="2" size="20" />
        </td>
      </tr>
      <tr>
        <th style="text-align:right">
          <?php echo $this->lang->line('ocw_ins_dscribes_username')?>: &nbsp;&nbsp;
        </th>
        <td><input type="text" id="user_name" name="user_name" tabindex="3" size="20" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>
          <input id="submitbutton" type="submit"
               name="add_dscribe" value="<?php echo $this->lang->line('ocw_add')?>" />
        </td>
      </tr>
    </table>
  </form>
  </fieldset>

<br/>

<?php if ($dscribes==null) { ?>

<p class="error">No dScribes found for this course.</p>

<?php } else { ?>
<h3>
	<?php echo $this->lang->line('ocw_ins_dscribes_currentds').' for '.
							anchor(site_url('materials/home/'.$course['id']),$course['number'].' '.$course['title'],array('title'=>'Edit course materials'))?>
	</h3>

<table>
<tr>
  <th><?php echo $this->lang->line('ocw_ins_dscribes_name') ?></th>
  <th><?php echo $this->lang->line('ocw_ins_dscribes_username') ?></th>
  <th><?php echo $this->lang->line('ocw_ins_dscribes_email') ?></th>
  <th><?php echo $this->lang->line('ocw_ins_dscribes_level') ?></th>
  <th>&nbsp;</th>
</tr>
<?php foreach($dscribes as $dscribe): ?>
<tr>
  <td><?php echo $dscribe['name']?></td>
  <td><?php echo $dscribe['user_name']?></td>
  <td><?php safe_mailto($dscribe['email'])?><?php echo $dscribe['email'] ?></td>
  <td><?php echo $dscribe['role']?></td>  <td>
    <?php echo anchor(site_url("dscribe2/dscribes/$cid/remove/".$dscribe['id']),            '<img src="'.property('app_img').'/cross.png" title="Remove dScribe" />',
            array('title'=>'Remove dScribe', 'class'=>'confirm'))?>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php }} ?>

</div>
