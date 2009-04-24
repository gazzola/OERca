<?php $this->load->view(property('app_views_path').'/admin/admin_header.php', $data); ?>

<div class="column span-24 first last" style="margin-bottom: 20px;">
   <div id="navlist">
      <ul id="navlist">
      <li <?=($tab=='instructor')? 'id="active"':''?>><?=anchor("/admin/users/view/instructor/",$inst_title)?></li>
      <li <?=($tab=='dscribe1')  ? 'id="active"':''?>><?=anchor("/admin/users/view/dscribe1/",$d1_title)?></li>
      <li <?=($tab=='dscribe2')  ? 'id="active"':''?>><?=anchor("/admin/users/view/dscribe2/",$d2_title)?></li>
      <li <?=($tab=='admin')  	 ? 'id="active"':''?>><?=anchor("/admin/users/view/admin/",$admin_title)?></li>
    </ul>
  </div>
</div>

<br/><br/>

<div class="column span-24 first last" style="margin-bottom: 10px;">
<?php if ($users == null || !isset($users[$tab]) || count($users[$tab])==0) { ?>

		 <p class="error">No users found: <a href="<?=site_url("admin/users/add_user/$tab")?>?TB_iframe=true&height=650&width=550" class="smoothbox" title="Add User" style="color:blue">Add User</a></p>

<?php } else { ?>

	<table class="sortable-onload-1 rowstyle-alt no-arrow">
    <thead>
    <tr>
        <th class="sortable">Name</th>
        <th class="sortable">User Name</th>
        <th class="sortable">Email</th>
        <th>Delete</th>
		</tr>
		</thead>

		<tbody>
 		<?php foreach($users[$tab] as $user) { ?>
			<tr>
  			<td>
					<?php if ($tab<>'admin') { ?>

						<a href="<?=site_url("admin/users/edit/$tab/{$user['id']}")?>"><?php echo $user['name']?></a>

					<?php } else {  echo $user['name']; } ?>
				<br/>
				<span style="font-size:9px; clear:both; margin-top:20px;">
						<a href="<?=site_url("admin/users/editinfo/$tab/{$user['id']}")?>?TB_iframe=true&height=500&width=400" class="smoothbox" title="Editing <?=$user['name']?> Info">Edit Info</a>&nbsp;|&nbsp;
						<a href="<?=site_url("admin/users/editprofile/$tab/{$user['id']}")?>?TB_iframe=true&height=650&width=400" class="smoothbox" title="Editing <?=$user['name']?> Profile">Edit Profile</a>
				</span>
				</td>
  			<td><?php echo $user['user_name']?></td>
  			<td><?php echo safe_mailto($user['email'])?></td>
				<td>
    		<?php echo anchor(site_url("admin/users/remove_user/$tab/".$user['id']),  
												 '<img src="'.property('app_img').'/cross.png" title="Remove '.$tab.'" />',
            							array('customprompt'=>"You are about to completely remove $tab {$user['name']} ({$user['email']}) from the system.  ARE YOU SURE???", 'title'=>"Remove $tab", 'class'=>'confirm'))?>
			</tr>
		<?php } ?>
		</tbody>
</table>

<?php } ?>
</div>


<?php $this->load->view(property('app_views_path').'/admin/admin_footer.php', $data); ?>
