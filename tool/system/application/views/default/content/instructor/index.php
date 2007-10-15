<?php $this->load->view(property('app_views_path').'/instructor/instructor_header.php', $data); ?>

<div id="tool_content">
   <div id="boxes">
        <div class="box">
           	<p>
              <a href="<?php echo site_url("instructor/dscribes/$cid") ?>"><img src="<?php echo property('app_img').'/dscribes.jpg' ?>" /><?php echo $this->lang->line('ocw_ins_menu_manage') ?></a><br/>
			  <?php echo $this->lang->line('ocw_ins_home_dscribetext') ?>
			</p>
        </div>

        <div class="box">
           	<p>
              <a href="<?php echo site_url("instructor/materials/$cid") ?>"><img src="<?php echo property('app_img').'/materials.jpg' ?>" /><?php echo $this->lang->line('ocw_ins_menu_materials') ?></a><br/>
			  <?php echo $this->lang->line('ocw_ins_home_materialstext') ?>
			</p>
        </div>

        <div class="box">
           	<p>
              <a href="<?php echo site_url("instructor/review/$cid") ?>"><img src="<?php echo property('app_img').'/export.jpg' ?>" /><?php echo $this->lang->line('ocw_ins_menu_review') ?></a><br/>
			  <?php echo $this->lang->line('ocw_ins_home_reviewtext') ?>
			</p>
        </div>
   </div>
</div>
