<div class="column span-24 first last">

	<div style="border-bottom: 1px solid #eee; margin-top: -10px; margin-left: 5px; padding-bottom: 5px;">
		<?php if ($section=='users') { ?>

			<div style="float: right"> 
				<a href="<?=site_url("admin/users/add_user/$tab")?>?TB_iframe=true&height=650&width=550" class="smoothbox" title="Add User">Add a User</a>
			</div>
			<div style="float: left">
		    <a href="<?=site_url('admin/home');?>">Admin</a> &raquo; 
				<a href="<?=site_url('admin/users/');?>">Users</a> 
				<? if (isset($user) && is_array($user)) { ?> &raquo; <?=anchor(site_url('admin/users/view/'.$tab),ucfirst($tab))?> &raquo; <?=$user['name']?> <?php } ?>
    </div>


		<?php } elseif ($section=='courses') { ?>

			<div style="float: right"> 
				<?=anchor(site_url("courses/add_new_course/").'?TB_iframe=true&height=675&width=875', 'Add a Course', array('class'=>'smoothbox', 'title'=>'Add a new course')) ?>
			</div>
	    <div style="float: left">
		    <a href="<?=site_url('admin/home');?>">Admin</a> &raquo;
				<a href="<?=site_url('admin/courses/');?>">Courses</a><?php if (isset($cname)) echo " &raquo; $cname"; ?>
    	</div>

		<?php } elseif ($section=='schools') { ?>

			<div style="float: right">
		<?php		if (!isset($sname)) { ?>
					<?=anchor(site_url("admin/schools/add_school").'?TB_iframe=true&height=500&width=600', 'Add a School', array('class'=>'smoothbox', 'title'=>'Add a new school')) ?>
		<?php		} else { ?>
					<?=anchor(site_url("admin/curriculum/add_curriculum/$sid").'?TB_iframe=true&height=500&width=600', 'Add a Curriculum&nbsp;&nbsp;', array('class'=>'smoothbox', 'title'=>'Add new curriculum for ' . $sname)) ?>
					<?=anchor(site_url("admin/subjects/add_subject/$sid").'?TB_iframe=true&height=500&width=600', 'Add a Subject', array('class'=>'smoothbox', 'title'=>'Add new subject for ' . $sname)) ?>
		<?		} ?>
			</div>
			<div style="float: left">
		    <a href="<?=site_url('admin/home');?>">Admin</a> &raquo;
				<a href="<?=site_url('admin/schools/');?>">Schools</a><?php if (isset($sname)) echo " &raquo; $sname"; ?>
			</div>
			<? } ?>

    <div style="clear:both"></div>
	</div>
<br/>
