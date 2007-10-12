<?php
/*
 * Created on Apr 13, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
// this start the session
session_start();

//Get the linktool parameters and set the session variables
$user = strip_tags($_GET['internaluser']);
$_SESSION['internaluser'] = $user;
$euid = strip_tags($_GET['user']);
$_SESSION['user'] = $euid;
$site = strip_tags($_GET['site']);
$_SESSION['site'] = $site;
$server = strip_tags($_GET['serverurl']);
$_SESSION['serverurl'] = $server;
$sessionid = strip_tags($_GET['session']);
$_SESSION['session'] = $sessionid;
$placement = strip_tags($_GET['placement']);
$_SESSION['placement'] = $placement;
$role = strip_tags($_GET['role']);
$_SESSION['role'] = $role;
$sign = strip_tags($_GET['sign']);
$_SESSION['sign'] = $sign;
$time = strip_tags($_GET['time']);
$_SESSION['time'] = $time;

?>

<link href="../include/ocw_tool.css" rel="stylesheet" type="text/css"/>

	
		<div>&nbsp;&nbsp;&nbsp;Instructor Home&nbsp;| &nbsp;<a href="dscribes.php">Manage dScribes</a>&nbsp; 
		| &nbsp;<a href="materials.php">Select OCW Course Materials</a>&nbsp; | &nbsp;<a href="../preview_inst/course.php">Review for Export</a>| &nbsp;<a href="../dscribe/index.php">dScribe Tools</a></div>
		<br/>
		<div id="tool_content" style="border:0;">
			<p>	<span style="font-weight:normal; color:#333">
			Welcome to the Open Courseware (OCW) site tool.<br/> You can prepare your course materials for OCW here. </span></p>
			<div id="boxes">
				<div class="box">
					<p class="heading">
					<a href="dscribes.php"><img width=40 border=0 src="../include/images/dscribes.jpg">
					<br/>Manage Course DScribes</a></p>
					<p style="margin-bottom:30px; ">
					<span style="font-weight:normal; color:#929292">Add and Remove DScribes for this course </span></p>
				</div>
				<div class="box">
					<p class="heading" >
					<a href="materials.php"><img width=40   border=0 src="../include/images/materials.jpg">
					<br/>Select OCW Course Materials</a></p>
					<p style="margin-bottom:30px;  ">
					<span style="font-weight:normal; color:#929292">Select course materials for inclusion in OCW </span></p>
				</div>
				<div class="box">
					<p class="heading" >
					<a href="../preview_inst/course.php"><img  width=40  border=0 src="../include/images/export.jpg">
					<br/>Review for Export to OCW</a></p>
					<p>
					<span style="font-weight:normal; color:#929292">
					Review materials prepared for OCW </span></p>
				</div>
			</div>
			
	</div>
</div><div class="clear">&nbsp;</div>
<div class="padding50">&nbsp;</div>