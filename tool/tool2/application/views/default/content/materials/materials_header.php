<h1><?=$cname?><?php if (isset($material['name'])) { echo '&nbsp;&raquo;&nbsp;'. $material['name']; } ?></h1><br/>

<div class="column span-24 first last">

    <div id="navlist">
		<ul id="navlist">
<?php if ($caller=='instructor') { ?> 
			<li><?=anchor("/instructor/home/$cid",$this->lang->line('ocw_ins_menu_home'))?></li>
			<li id="active"><a href=""><?=$this->lang->line('ocw_ins_menu_managematerials')?></a></li>
			<li><?=anchor("/instructor/review/$cid",$this->lang->line('ocw_ins_menu_review'))?></li>
			<li><?=anchor("/instructor/materials/$cid",$this->lang->line('ocw_ins_menu_materials'))?></li>
			<li><?=anchor("/instructor/dscribes/$cid",$this->lang->line('ocw_ins_menu_manage'))?></li>
<?php } elseif ($caller=='dscribe1') { ?>
			<li><?=anchor("/dscribe1/home/$cid",$this->lang->line('ocw_ds_menu_home'))?></li>
			<li id="active"><a href=""><?=$this->lang->line('ocw_ds_menu_materials')?></a></li>
			<li><?=anchor("/dscribe1/profiles/$cid",$this->lang->line('ocw_ds_menu_profiles'))?></li>
			<li><?=anchor("/dscribe1/copyright/$cid",$this->lang->line('ocw_ds_menu_copyright'))?></li>
			<li><?=anchor("/dscribe1/tags/$cid",$this->lang->line('ocw_ds_menu_tags'))?></li>
			<li><?=anchor("/dscribe1/review/$cid",$this->lang->line('ocw_ds_menu_review'))?></li>
<?php } ?>
		</ul>
	</div>
<!--
	<br/>

<?php if (isset($material['name'])) { ?>
	<div style="border-bottom: 1px solid #eee; margin-top: -10px; margin-left: 5px; padding-bottom: 5px;">
		<a href="<?=site_url('materials/home/'.$cid.'/'.$caller)?>"><?=$cname?> Materials</a>
		&nbsp;&raquo;&nbsp;<b><?=$material['name']?></b>&nbsp;&raquo;&nbsp;Content objects
	</div>
<?php } ?>
-->
<br/>
