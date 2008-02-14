<h1><?=$cname?></h1><br/>
<?php $ci_uri = trim($this->uri->uri_string(), '/'); $att = ' id="active"';?>

<div class="column span-24 first last">
    <div id="navlist">
		<ul id="navlist">
			<li<?= (preg_match('|^dscribe1/home|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe1/home/$cid",$this->lang->line('ocw_ds_menu_home'))?></li>
			<li<?= (preg_match('|^dscribe1/materials|', $ci_uri) > 0)? $att: ''?>><?=anchor("/materials/home/$cid/dscribe1",'Manage Courses')?></li>
			<li<?= (preg_match('|^dscribe1/profiles|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe1/profiles/$cid",$this->lang->line('ocw_ds_menu_profiles'))?></li>
			<li<?= (preg_match('|^dscribe1/copyright|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe1/copyright/$cid",$this->lang->line('ocw_ds_menu_copyright'))?></li>
			<li<?= (preg_match('|^dscribe1/tags|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe1/tags/$cid",$this->lang->line('ocw_ds_menu_tags'))?></li>
			<li<?= (preg_match('|^dscribe1/review|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe1/review/$cid",$this->lang->line('ocw_ds_menu_review'))?></li>
            <li<?= (preg_match('|^dscribe1/export|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe1/export/$cid",$this->lang->line('ocw_ds_menu_export'))?></li>
            <?php if (getUserProperty('role')== 'instructor') {?>
				<li<?= (preg_match('|^instructor/home|', $ci_uri) > 0)? $att: ''?>><?=anchor("/instructor/home/$cid",'View of Instructor')?></li>
			<?php } ?>
		</ul>
	</div>

<br/>
