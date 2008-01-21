<h1><?=$cname?></h1><br/>

<div class="column span-24 first last">

<?php $ci_uri = trim($this->uri->uri_string(), '/'); $att = ' id="active"';?>
    <div id="navlist">
		<ul id="navlist">
			<li<?= (preg_match('|^dscribe1/home|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe1/home/$cid",$this->lang->line('ocw_ds_menu_home'))?></li>
			<li<?= (preg_match('|^dscribe1/materials|', $ci_uri) > 0)? $att: ''?>><?=anchor("/materials/home/$cid/dscribe1",$this->lang->line('ocw_ds_menu_materials'))?></li>
			<li<?= (preg_match('|^dscribe1/profiles|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe1/profiles/$cid",$this->lang->line('ocw_ds_menu_profiles'))?></li>
			<li<?= (preg_match('|^dscribe1/copyright|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe1/copyright/$cid",$this->lang->line('ocw_ds_menu_copyright'))?></li>
			<li<?= (preg_match('|^dscribe1/tags|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe1/tags/$cid",$this->lang->line('ocw_ds_menu_tags'))?></li>
			<li<?= (preg_match('|^dscribe1/review|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe1/review/$cid",$this->lang->line('ocw_ds_menu_review'))?></li>
		</ul>
	</div>
<br/>
