<?php $ci_uri = trim($this->uri->uri_string(), '/'); $att = ' id="active"';?>
    <div id="navlist">
		<ul id="navlist">
			<li<?= (preg_match('|^instructor/home|', $ci_uri) > 0)? $att: ''?>><?=anchor("/instructor/home/$cid",$this->lang->line('ocw_ins_menu_home'))?></li>
			<li<?= (preg_match('|^instructor/dscribes|', $ci_uri) > 0)? $att: ''?>><?=anchor("/instructor/dscribes/$cid",$this->lang->line('ocw_ins_menu_manage'))?></li>
			<li<?= (preg_match('|^instructor/materials|', $ci_uri) > 0)? $att: ''?>><?=anchor("/instructor/materials/$cid",$this->lang->line('ocw_ins_menu_materials'))?></li>
			<li<?= (preg_match('|^instructor/review|', $ci_uri) > 0)? $att: ''?>><?=anchor("/instructor/review/$cid",$this->lang->line('ocw_ins_menu_review'))?></li>
			<li<?= (preg_match('|^dscribe/index|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe/index/$cid",$this->lang->line('ocw_ins_menu_tools'))?></li>
		</ul>
	</div>
<br/>
