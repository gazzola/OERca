<h1><?=$cname?></h1><br/>

<div class="column span-24 first last">

<?php $ci_uri = trim($this->uri->uri_string(), '/'); $att = ' id="active"';?>
    <div id="navlist">
		<ul id="navlist">
			<li<?= (preg_match('|^instructor/home|', $ci_uri) > 0)? $att: ''?>><?=anchor("/instructor/home/$cid",$this->lang->line('ocw_ins_menu_home'))?></li>
			<li<?= (preg_match('|^instructor/manage_materials|', $ci_uri) > 0)? $att: ''?>><?=anchor("/materials/home/$cid/instructor",$this->lang->line('ocw_ins_menu_managematerials'))?></li>
			<li<?= (preg_match('|^instructor/review|', $ci_uri) > 0)? $att: ''?>><?=anchor("/instructor/review/$cid",$this->lang->line('ocw_ins_menu_review'))?></li>
			<li<?= (preg_match('|^instructor/materials|', $ci_uri) > 0)? $att: ''?>><?=anchor("/instructor/materials/$cid",$this->lang->line('ocw_ins_menu_materials'))?></li>
			<li<?= (preg_match('|^dscribe1/index|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe1/index/$cid",'View of dscribe1')?></li>
		</ul>
	</div>
<br/>
