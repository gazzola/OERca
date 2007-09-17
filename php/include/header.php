<?php
/*
 * Created on Apr 13, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
?>

<?php 

?>
<script type="text/javascript">
<!--

function showAddComment(num) {
	var commentItem = document.getElementById('addComment'+num);
	if (commentItem != null) {
		commentItem.style.display = "";
	}
	var triggerItem = document.getElementById('onComment'+num);
	if (triggerItem != null) {
		triggerItem.style.display = "none";
	}
}
function orderBy(newOrder) {
	if (document.voteform.sortorder.value == newOrder) {
		if (newOrder.match("^.* desc$")) {
			document.voteform.sortorder.value = newOrder.replace(" desc","");
		} else {
			document.voteform.sortorder.value = newOrder;
		}
	} else {
		document.voteform.sortorder.value = newOrder;
	}
	document.voteform.submit();
	return false;
}


// These are the voting functions
function setAnchor(num) {

	document.voteform.action += "#anchor"+num;
	document.voteform.submit();
	return false;
}



// -->
</script>
<script type="text/javascript" src="/accounts/ajax/validate.js"></script>
</head>
<body class="portalBody">
<div id="portalOuterContainer">

	<div id="portalContainer">
		<div id="skipNav">

			<a href="#tocontent"  class="skip" title="jump to content" accesskey="c">
			jump to content
			</a>
			<a href="#totoolmenu"  class="skip" title="jump to tools list" accesskey="l">
				jump to tools list
			</a>
			<a href="#sitetabs" class="skip" title="jump to worksite list" accesskey="w">

				jump to worksite list
			</a>
		</div>
		<div id="headerMax">
<div id="siteNavWrapper" class="workspace">
	<div id="mastHead">
		<div id="mastLogo">
			<img title="Logo" alt="Logo" src="../include/images/logo_inst.gif" height=50 />
		</div>

		<div id="mastBanner">

			
		</div>
		<div id="mastLogin">
	<div id="loginLinks">
		<a href="http://dev-ocw.dmc.dc.umich.edu:8080/portal/logout" title="Logout">
			Logout
		</a>
	</div>
		</div>

	</div>
</div>
<!-- start includeTabs -->
<div class="siteNavWrap workspace">
	<div id="siteNav">
		<div id="linkNav">
			<a id="sitetabs" class="skip" name="sitetabs"></a>
			<h1 class="skip">Worksites begin here</h1>
			<ul id="siteLinkList">

				<li><a href="#"><span>My Workspace</span></a></li>

				<li><a href="http://dev-ocw.dmc.dc.umich.edu:8080/portal/site/88574453-5820-4ef7-8021-e15ea6f38e43" title="SI 110 Winter 2004 My Workspace">
					<span>SI 110 Winter 2004</span>
				</a></li>
				<li><a href="http://dev-ocw.dmc.dc.umich.edu:8080/portal/site/20bf71fc-b371-47a1-8023-87ab3088116c" title="SI 155 Winter 2004 My Workspace">
					<span>SI 155 Winter 2004</span>
				</a></li>

				<li class="selectedTab"><a href="http://dev-ocw.dmc.dc.umich.edu:8080/portal/site/d5e25bd7-281e-4e4a-80f4-d972e32aa64e" title="SI 514 OCW Semantic My Workspace">
					<span>SI 514 OCW Semantic</span>

				</a></li>
				<li><a href="http://dev-ocw.dmc.dc.umich.edu:8080/portal/site/93f5f26b-51da-4127-80b5-527f7baf53ca" title="SI 557 Winter 2004 My Workspace">
					<span>SI 557 Winter 2004</span>
				</a></li>
				<li style="display:none;border-width:0" class="fixTabsIE">

					<a href="javascript:void(0);">#x20;</a>
				</li>
			</ul>

		</div>
		<div id="selectNav">
			<span class="skip">Press alt + up and down arrows to scroll through menu </span>
			<select onchange="if (this.options[this.selectedIndex].value != '') { parent.location = this.options[this.selectedIndex].value; } else { this.selectedIndex = 0; }">

				<option value="" selected="selected">- more -</option>
				<option title="SI 607 Global" value="http://dev-ocw.dmc.dc.umich.edu:8080/portal/site/799deaf2-dabc-46d9-00fb-500411401608">SI 607 Global</option> 
				<option title="SI 646  Winter 2005" value="http://dev-ocw.dmc.dc.umich.edu:8080/portal/site/560d7ad1-cada-4dcd-800c-4a21786d516f">SI 646  Winter 2005</option> 
			</select>

		</div>
	</div>
	<div class="divColor" id="tabBottom">
	</div>

</div>
<!-- end includeTabs -->
		</div>
<!-- start includePage -->		
<div id="container" class="workspace" >
<!-- start includePageNav -->
	<div class="divColor" id="toolMenuWrap">

		<div id="worksiteLogo">
		</div>
		<a id="totoolmenu" class="skip" name="totoolmenu"></a>
		<h1 class="skip">Tools begin here</h1>

		<div id="toolMenu">
			<ul>
     			<li>
			<a href="/ocw_tool/instructor/index.php" ><span>Home</span></a>

			</li>
       				<li>
				<a href="#" ><span>Profile</span></a>

				</li>
       			<li>
			<a href="#" ><span>Membership</span></a>
			</li>
          			<li>

			<a href="#" ><span>Schedule</span></a>
			</li>

          			<li>
			<a href="#" ><span>Resources</span></a>
			</li>
          			<li>
			<a href="#" ><span>Announcements</span></a>

			</li>
          	<li><a style="color:#666;" href="/ocw_tool/instructor/index.php">
<span>OCW Tool</span></a>

			</li>		<li>

			<a href="#" ><span>Worksite Setup</span></a>
			</li>
          			<li>
			<a href="#" ><span>Preferences</span></a>
			</li>
          			<li>

			<a href="#" ><span>Account</span></a>

			</li>			
     				<li>
				<a  accesskey="6" href="http://dev-ocw.dmc.dc.umich.edu:8080/portal/help/main" target="_blank" 
					onclick="openWindow('http://dev-ocw.dmc.dc.umich.edu:8080/portal/help/main', 
					'Help', 'resizable=yes,toolbar=no,scrollbars=yes,menubar=yes,width=800,height=600'); 
					return false">
					<span>Help</span>
				</a>
				</li>
			</ul>

		</div>

		<div id="presenceWrapper">
			<div id="presenceTitle"></div>
			
		</div>
	</div>
	<h1 class="skip">Content begins here</h1>

	<a id="tocontent" class="skip" name="tocontent"></a>
	
<div id="ocwtools">
<a href="/ocw_tool/instructor/index.php"><IMG border=0 width=740 height=25 style="display:block;float:none;" src="../include/images/greybar.jpg"></a>
<!-- end includePageNav -->
<!-- start includePageBody -->