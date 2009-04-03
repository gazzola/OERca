<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>OERca &raquo; <?php echo $title ?></title>
	<?php 
	   echo style('blueprint/screen.css',array('media'=>"screen, projection"));
	   echo style('blueprint/print.css',array('media'=>"print"));
	   echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
	   echo style('style.css',array('media'=>"screen, projection"));
	   echo style('table.css',array('media'=>"screen, projection"));
	   echo style('multiupload.css',array('media'=>"screen, projection"));
	   echo style('smoothbox.css',array('media'=>"screen, projection"));
	   echo style('morphtabs.css',array('media'=>"screen, projection"));
	   echo style('dojo/dijit/themes/tundra/tundra.css');
     echo style('dojo/dojo/resources/dojo.css');
     
	   echo script('mootools.js'); 
	   echo script('smoothbox.js'); 
	   echo script('mootips.js'); 
     echo script('tablesort.js');

     echo script('event-selectors.js');
     echo script('event-rules.js');

	   echo script('ocwui.js');
     echo script('ocw_tool.js');
	   echo script('multiupload.js'); 

	   echo script('flash.js');
	   
	   // load morphtabs
	   echo script('morphtabs-compressed.js');

	  $ci_uri = trim($this->uri->uri_string(), '/'); 
	?>
	<script type="text/javascript" src="<?php site_url() ?>/tool/assets/tool2/script/dojo/dojo/dojo.js"
	  djConfig="parseOnLoad: true"></script>
	<script type="text/javascript">
	  dojo.require("dijit.layout.TabContainer");
	  dojo.require("dijit.layout.ContentPane");
	</script>
</head>

<body>
<div id="mainPage" class="container">

<div id="<?= (isValidUser()) ? 'header':'header_line'?>" class="column span-24 first last">
	<div class="column span-10 first last">
	  <a href="<?php echo base_url()?>"><h1><img src="<?php echo property('app_img').'/OERca.png'?>"></h1></a>
	</div>


	<?php if (isValidUser()) { ?>
  <div class="column span-24 first last">
    <ul id="topnavlist" >
	  <?php $ci_uri = trim($this->uri->uri_string(), '/'); $ci_uri = ($ci_uri=='') ? 'home' : $ci_uri; $att = ' id="active"';?>

    <?php if (getUserProperty('role') == 'dscribe1') { ?>

	  <li<?= (preg_match('/^(home)|(dscribe1\/home)|\s/', $ci_uri) > 0)? $att: ''?>><?=anchor("/home",$this->lang->line('ocw_ds_menu_home'))?></li>
	  <li<?= (preg_match('/^(dscribe1\/courses)/', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe1/courses",'Manage Courses')?></li>
		
    <?php } elseif (getUserProperty('role') == 'instructor') { ?>

		  <li<?= (preg_match('/^(home)|(instructor\/home)/', $ci_uri) > 0)? $att: ''?>><?=anchor("/home",$this->lang->line('ocw_ds_menu_home'))?></li>
	  	<li<?= (preg_match('/^(instructor\/courses)/', $ci_uri) > 0)? $att: ''?>><?=anchor("/instructor/courses",'Manage Courses')?></li>

			<?php if (isset($cid)) { ?>
      <li<?= (preg_match('/^(instructor\/materials)/', $ci_uri) > 0)? $att: ''?>><?=anchor("/instructor/materials/$cid",$this->lang->line('ocw_ins_menu_materials'))?></li>
			<?php } else { ?>
      <li id="inactive"><?=$this->lang->line('ocw_ins_menu_materials')?></li>
			<?php  } ?>


			<?php if (isset($cid)) { ?>
      <li<?= (preg_match('|^instructor/review|', $ci_uri) > 0)? $att: ''?>><?=anchor("/instructor/review/$cid",$this->lang->line('ocw_ins_menu_review'))?></li>
			<?php } else { ?>
      <li id="inactive"><?=$this->lang->line('ocw_ins_menu_review')?></li>
			<?php  } ?>

			<?php if (isset($cid)) { ?>
      <li<?= (preg_match('|^instructor/dscribes|', $ci_uri) > 0)? $att: ''?>><?=anchor("/instructor/dscribes/$cid",$this->lang->line('ocw_ins_menu_manage'))?></li>
			<?php } else { ?>
      <li id="inactive"><?=$this->lang->line('ocw_ins_menu_manage')?></li>
			<?php  } ?>

    <?php } elseif (getUserProperty('role') == 'dscribe2') { ?>

      <li<?= (preg_match('|^dscribe2/home|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe2/home/",'Home')?></li>
      <li<?= (preg_match('/^(dscribe2\/courses)|(materials)|(courses)/', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe2/courses",'Manage Courses')?></li>
      <li<?= (preg_match('|^dscribe2/dscribes|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe2/dscribes",'Manage dScribes')?></li>

    <?php } elseif (getUserProperty('role') == 'admin') { ?>

		  <li<?= (preg_match('/^(admin$)|(home$)|(admin\/home)/', $ci_uri) > 0)? $att: ''?>><?=anchor("/admin/home/",'Home')?></li>
      <li<?= (preg_match('|^admin/users|', $ci_uri) > 0)? $att: ''?>><?=anchor("/admin/users",'Manage Users')?></li>
			<li<?= (preg_match('/^(admin\/schools)|(schools)|(curriculum)|(subjects)/', $ci_uri) > 0)? $att: ''?>><?=anchor("/admin/schools",'Manage Schools')?></li>
      <li<?= (preg_match('/^(admin\/courses)|(materials)|(courses)/', $ci_uri) > 0)? $att: ''?>><?=anchor("/admin/courses",'Manage Courses')?></li>

    <?php } ?>
	  </ul>

	  <div style="text-align: right; margin-top: -20px;">
         <?php echo  'Welcome&nbsp;&nbsp;<b>'.getUserProperty('user_name').' ('.getUserProperty('role').')</b> | '.
         anchor_popup('helpfaq', 'Help/FAQ'). ' | '.
         anchor($this->config->item('FAL_logout_uri'), 'Logout'); ?>
	  </div>
  </div>
  <?php } ?>
	
</div>
<!-- end header -->

<?php 
  $flash=$this->db_session->flashdata('flashMessage');
  if (isset($flash) AND $flash!='') { 
?>

<!--START FLASH MESSAGE-->
<div id="statusmsg" class="column span-24 first last">
      <div id="flashMessage" style="display:none;"><?=$flash?></div>
      <br/>
</div>
<!--END FLASH-->
<?php } ?>
