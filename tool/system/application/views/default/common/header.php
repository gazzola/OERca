<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>OER Work Tool &raquo; <?php echo $title ?></title>

	<?php 
	   echo style('blueprint/screen.css',array('media'=>"screen, projection"));
	   echo style('blueprint/print.css',array('media'=>"print"));
	   echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
	   echo style('style.css',array('media'=>"screen, projection"));
	   echo style('table.css',array('media'=>"screen, projection"));
	   echo style('multiupload.css',array('media'=>"screen, projection"));
	   echo style('carousel.css',array('media'=>"screen, projection"));
	   echo style('smoothbox.css',array('media'=>"screen, projection"));

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

    if (isset($material['name'])) {
        echo '<script type="text/javascript">';
        echo 'var numitems = '.$numobjects.';';
        echo 'var numsteps = numitems;';
        echo 'var knobpos = 0;';
        echo '</script>';
    }
	?>
</head>

<body>

<div id="mainPage" class="container">

<div id="<?= (isValidUser()) ? 'header':'header_line'?>" class="column span-24 first last">
	<div class="column span-10 first last">
	  <a href="<?php echo base_url()?>"><h1>OER Work Tool</h1></a>
	</div>


	<?php if (isValidUser()) { ?>
  <div class="column span-24 first last">
    <ul id="topnavlist" >
	  <?php $ci_uri = trim($this->uri->uri_string(), '/'); $ci_uri = ($ci_uri=='') ? 'home' : $ci_uri; $att = ' id="active"';?>

    <?php if (getUserProperty('role') == 'dscribe1') { ?>

		<li<?= (preg_match('/^(home)|(dscribe\/home)|\s/', $ci_uri) > 0)? $att: ''?>><?=anchor("/home",$this->lang->line('ocw_ds_menu_home'))?></li>
		<li<?= (preg_match('/^(manage|materials)/', $ci_uri) > 0)? $att: ''?>><?=anchor("/manage",'Manage Courses')?></li>

    <?php } elseif (getUserProperty('role') == 'instructor') { ?>

		  <li<?= (preg_match('/^(instructor|instructor\/home)/', $ci_uri) > 0)? $att: ''?>><?=anchor("/home",$this->lang->line('ocw_ds_menu_home'))?></li>

		  <li<?= (preg_match('/^(manage|materials)/', $ci_uri) > 0)? $att: ''?>><?=anchor("/manage",'Manage Courses')?></li>

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
      <li<?= (preg_match('/^(dscribe2\/courses)|(materials)/', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe2/courses",'Manage Courses')?></li>
      <li<?= (preg_match('|^dscribe2/dscribes|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe2/dscribes",'Manage dScribes')?></li>


    <?php } ?>
	  </ul>

	  <div style="text-align: right; margin-top: -20px;">
         <?php echo  'Welcome&nbsp;&nbsp;<b>'.getUserProperty('user_name').' ('.getUserProperty('role').')</b> | '.
         anchor(site_url('auth/changepassword'), 'Change Password'). ' | '.
                     ((isAdmin())    ?
         anchor($this->config->item('FAL_admin_uri'), 'Admin Panel').' | ' : '').
         anchor_popup(site_url('helpfaq'), 'Help/FAQ'). ' | '.
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

<!--STAR FLASH MESSAGE-->
<div id="statusmsg" class="column span-24 first last">
      <div id="flashMessage" style="display:none;"><?=$flash?></div>
      <br/>
</div>
<!--END FLASH-->
<?php } ?>
