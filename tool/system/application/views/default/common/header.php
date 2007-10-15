<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="en-us" />
		<meta name="ROBOTS" content="NONE" />
		<meta name="MSSmartTagsPreventParsing" content="true" />
		<meta name="Keywords" content="<?php echo property('app_keywords')?>" />
		<meta name="Description" content="<?php echo property('app_description')?>" />
		<meta name="Copyright" content="<?php echo property('app_copyright')?>" />
		<title><?php echo property('app_title')?><?php echo $title ?></title>

		<?php echo style('layout.css')?>
		<?php echo style('ocw_tool.css')?>
		<?php echo style('table.css')?>

		<?php echo script('scriptaculous/prototype.js')?>
		<?php echo script('scriptaculous/scriptaculous.js')?>
		<?php echo script('event-selectors.js')?>
		<?php echo script('event-rules.js')?>
		<?php echo script('tablesort.js')?>
		<?php echo script('ocw_tool.js')?>

		<?php echo script('jquery.js')?>
		<?php echo script('flash.js')?>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<div>	  
					<a href="<?php echo  base_url()?>"><h1>OCW Tool<?php if(@isset($cname)) {echo ' &raquo; '.$cname; }?></h1></a>
				</div>
				<?php if (isValidUser()) { ?>
				<div class="loggedin">
					<?php echo  anchor(base_url(), 'Home').' | '.
							anchor(site_url('auth/changepassword'), 'Change Password').' | '.
							((isAdmin())	? 
							anchor($this->config->item('FAL_admin_uri'), 'Admin Panel').' | ' : '').
							anchor($this->config->item('FAL_logout_uri'), 'Logout ('.getUserProperty('user_name').')'); ?>
				</div>
				<?php } ?>
			</div>


			<div id="content">
			<!--STAR FLASH MESSAGE-->
			<?php 
				$flash=$this->db_session->flashdata('flashMessage');
				if (isset($flash) AND $flash!='') { 
			?>
				<div id="flashMessage" style="display:none;"><?=$flash?></div>
				<br/>
			<?php } ?>
			<!--END FLASH-->
