
<?php $this->load->view(property('app_views_path').'/instructor/instructor_header.php', $data); ?>

<p class="error">Under construction: A place for clearing status messages, notifications and messages for the instructor</p> 

<!--
   <div id="boxes">
        <div class="box">
           	<p>
              <a href="<?php echo site_url("instructor/dscribes/") ?>"><img src="<?php echo property('app_img').'/dscribes.jpg' ?>" /><?php echo $this->lang->line('ocw_ins_menu_manage') ?></a><br/>
			  <?php echo $this->lang->line('ocw_ins_home_dscribetext') ?>
			</p>
        </div>

        <div class="box">
           	<p>
              <a href="<?php echo site_url("instructor/materials/") ?>"><img src="<?php echo property('app_img').'/materials.jpg' ?>" /><?php echo $this->lang->line('ocw_ins_menu_materials') ?></a><br/>
			  <?php echo $this->lang->line('ocw_ins_home_materialstext') ?>
			</p>
        </div>

        <div class="box">
           	<p>
              <a href="<?php echo site_url("instructor/review/") ?>"><img src="<?php echo property('app_img').'/export.jpg' ?>" /><?php echo $this->lang->line('ocw_ins_menu_review') ?></a><br/>
			  <?php echo $this->lang->line('ocw_ins_home_reviewtext') ?>
			</p>
        </div>
   </div>
-->


<?php $this->load->view(property('app_views_path').'/instructor/instructor_footer.php', $data); ?>
