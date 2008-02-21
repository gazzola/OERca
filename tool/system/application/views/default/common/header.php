<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>OCW Work Tool &raquo; <?php echo $title ?></title>

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

<div id="header" class="column span-24 first last">
	<div class="column span-15 first">
	<a href="<?php echo base_url()?>"><h1>OER Work Tool</h1></a>
	</div>

	<div class="column span-9 last" style="text-align: right; padding-top: 15px;">
	<?php if (isValidUser()) { ?>
         <?php echo  'Welcome&nbsp;&nbsp;<b>'.getUserProperty('user_name').' ('.getUserProperty('role').')</b> | '.
					anchor(site_url('auth/changepassword'), 'Change Password').' | '.
                     ((isAdmin())    ?
         anchor($this->config->item('FAL_admin_uri'), 'Admin Panel').' | ' : '').
         anchor($this->config->item('FAL_logout_uri'), 'Logout'); ?>
     
	</div>
	
	  <div id="navlist">
    	<ul id="navlist">
    	<?php $ci_uri = trim($this->uri->uri_string(), '/'); $att = ' id="active"';?>
    		<li<?= (preg_match('|^home|', $ci_uri) > 0)? $att: ''?>><?=anchor("/home",$this->lang->line('ocw_ds_menu_home'))?></li>
    		<li<?= (preg_match('|^manage|', $ci_uri) > 0)? $att: ''?>><?=anchor("/manage",'Manage Courses')?></li>
    		</ul>
    <?php } ?>
    </div>
  
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
