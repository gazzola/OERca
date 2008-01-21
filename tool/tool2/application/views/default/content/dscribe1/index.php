<?php $this->load->view(property('app_views_path').'/dscribe1/dscribe1_header.php', $data); ?>

	<p class="error">Under construction: home/status/notification page for dScribe</p>

   <div id="boxes">
        <div class="box">
           	<p>
              <a href="<?php echo site_url("dscribe1/materials/$cid") ?>"><img src="<?php echo property('app_img').'/materials.jpg' ?>" /><?php echo $this->lang->line('ocw_ds_menu_materials') ?></a><br/>
			  <?php echo $this->lang->line('ocw_ds_home_materialstext') ?>
			</p>
        </div>

        <div class="box">
           	<p>
              <a href="<?php echo site_url("dscribe1/profiles/$cid") ?>"><img src="<?php echo property('app_img').'/dscribes.jpg' ?>" /><?php echo $this->lang->line('ocw_ds_menu_profiles') ?></a><br/>
			  <?php echo $this->lang->line('ocw_ds_home_profilestext') ?>
			</p>
        </div>

        <div class="box">
           	<p>
              <a href="<?php echo site_url("dscribe1/copyright/$cid") ?>"><img src="<?php echo property('app_img').'/set_copyright.jpg' ?>" /><?php echo $this->lang->line('ocw_ds_menu_copyright') ?></a><br/>
			  <?php echo $this->lang->line('ocw_ds_home_copytext') ?>
			</p>
        </div>


        <div class="box">
           	<p>
              <a href="<?php echo site_url("dscribe1/review/$cid") ?>"><img src="<?php echo property('app_img').'/export.jpg' ?>" /><?php echo $this->lang->line('ocw_ds_menu_review') ?></a><br/>
			  <?php echo $this->lang->line('ocw_ds_home_reviewtext') ?>
			</p>
        </div>
   </div>

<?php $this->load->view(property('app_views_path').'/dscribe1/dscribe1_footer.php', $data); ?>
