<?php $this->load->view(property('app_views_path').'/dscribe2/dscribe2_header.php', $data); ?>

<div id="tool_content">
   <div id="boxes">

        <div class="box">
           	<p>
              <a href="<?php echo site_url("dscribe2/courses") ?>"><img src="<?php echo property('app_img').'/materials.jpg' ?>" /><?php echo 'Manage Courses' ?></a><br/>
			  <?php echo 'Add courses and manage courses you\'re assigned to'; ?>
			</p>
        </div>

        <div class="box">
           	<p>
              <a href="<?php echo site_url("dscribe2/dscribes") ?>"><img src="<?php echo property('app_img').'/dscribes.jpg' ?>" /><?php echo $this->lang->line('ocw_ins_menu_manage') ?></a><br/>
			  <?php echo 'Add and remove dScribes from a course' ?>
			</p>
        </div>
   </div>
</div>
