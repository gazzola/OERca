<div class="column span-24 first last">

	<div style="border-bottom: 1px solid #eee; margin-top: -10px; margin-left: 5px; padding-bottom: 5px;">
		<?php if ($section=='users') { ?>

    <div style="float: left">
		    <a href="<?=site_url('admin/');?>">Admin</a> &raquo; 
				<a href="<?=site_url('admin/users/');?>">Users</a> 
				<? if (isset($user) && is_array($user)) { ?> &raquo; <?=anchor(site_url('admin/users/view/'.$tab),ucfirst($tab))?> &raquo; <?=$user['name']?> <?php } ?>
    </div>


    <div id="materials_nav" style="float: right">
		  <ul>
			    <li class="normal"></li>
      </ul>
    </div>

		<?php } elseif ($section=='courses') { ?>

    <div style="float: left">
		    <a href="<?=site_url('admin/');?>">Admin</a> &raquo; 
				<a href="<?=site_url('admin/courses/');?>">Courses</a> &raquo; <?= $cname; ?> 
    </div>

    <?php } ?>

      
    <div style="clear:both"></div>
	</div>
<br/>
