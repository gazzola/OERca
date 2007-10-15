<?php $ci_uri = trim($this->uri->uri_string(), '/'); $att = ' id="active"';?>
    <div id="navlist">
		<ul id="navlist">
			<li<?= (preg_match('|^dscribe/home|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe/home/$cid",$this->lang->line('ocw_ds_menu_home'))?></li>
			<li<?= (preg_match('|^dscribe/materials|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe/materials/$cid",$this->lang->line('ocw_ds_menu_materials'))?></li>
			<li<?= (preg_match('|^dscribe/profiles|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe/profiles/$cid",$this->lang->line('ocw_ds_menu_profiles'))?></li>
			<li<?= (preg_match('|^dscribe/copyright|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe/copyright/$cid",$this->lang->line('ocw_ds_menu_copyright'))?></li>
			<li<?= (preg_match('|^dscribe/tags|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe/tags/$cid",$this->lang->line('ocw_ds_menu_tags'))?></li>
			<li<?= (preg_match('|^dscribe/review|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe/review/$cid",$this->lang->line('ocw_ds_menu_review'))?></li>
		</ul>
	</div>
<br/>
