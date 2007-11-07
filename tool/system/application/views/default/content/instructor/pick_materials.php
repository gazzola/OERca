<?php $this->load->view(property('app_views_path').'/instructor/instructor_header.php', $data); ?>
<?php $this->load->view(property('app_views_path').'/OCWItem.php', $data); ?>
<?php $this->load->view(property('app_views_path').'/OCWItemList.php', $data); ?>

<div id="tool_content">
<?php
$this->CI = $this->freakauth_light;
$itemList = new OCWItemList;

//Get the linktool parameters
$user = $this->db_session->userdata('internaluser');
print $user;
$euid = $this->db_session->userdata('user');
$site = $this->db_session->userdata('site');
$server = $this->db_session->userdata('serverurl');
$sessionid = $this->db_session->userdata('session');
$placement = $this->db_session->userdata('placement');
$role = $this->db_session->userdata('role');
$sign = $this->db_session->userdata('sign');
$time = $this->db_session->userdata('time');
print $server;
$url = $server."/sakai-axis/";
print $url;


$client = new SoapClient($url."SiteItem.jws?wsdl");
print '<pre>'; print_r($client); print '</pre>';
print $client->echo('to be echoed');
$assignmentListXML=$client->getAssignmentList($sessionid, $site, $user);
print $assignmentListXML;
$resourcesListXML=$client->getResourceList($sessionid, $site); 	
	
$toolTitles = $itemList->getSupportedToolTitles();

$TOOL_NAME="Instructor";
$PAGE_NAME="Manager Course Materials";

?>
<link href="../include/ocw_tool.css" rel="stylesheet" type="text/css"/>
<div>&nbsp;&nbsp;&nbsp; <a href="index.php">Instructor Home</a>&nbsp;| &nbsp;<a href="dscribes.php">Manage dScribes</a>&nbsp; | &nbsp;Select OCW Course Materials &nbsp; | &nbsp;<a href="../preview_inst/course.php">Review for Export</a> | &nbsp; <a href="../dscribe/index.php">dScribe Tools</a></div>
<br/>

<div id="tool_content">
<div style="text-align:left; margin-bottom:20px;  ">

<br/>
<form name="chooseMaterialForm" method="post" action="">
<table border="0" cellpadding="0" cellspacing="0" class="course_materials">
<tr>
	<td width="40%" style="font-weight: bold; text-align: center; border:1px solid #ccc;padding: 5px; background: #E5EBFF;">
	Ctools Course Materials</td>
	<td style="width: 60px;">&nbsp;</td>
	<td style="font-weight: bold; text-align: center; border:1px solid #ccc;padding: 5px; background: #E5EBFF;" width="250">
		Materials Selected for OCW Course
	</td>
</tr>
<tr>
	<td style="font-weight: normal; text-align: left; padding: 10px; border:1px solid #ccc; height: 250px; padding:5px;">
		<div class="instructions"><br/>Check the course items below that you want published to OCW. 
			Then, click on <strong>Add</strong> to update the OCW materials list on the right. <br/><br/></div>
		<div class="materials_list">
			<?php 
				//echo "$result1";
				foreach ($toolTitles as $title)
				{
					echo "<div class='paren'><img src='../include/images/validated.gif' height='15' /> &nbsp;&nbsp;
						<img src='../include/images/page.png' height='15' /> &nbsp;&nbsp; $title<br />";
					if ($title == "Assignments")
					{
						// reading the assignment list xml string
						$doc = new DOMDocument();
						$doc->loadXML($assignmentListXML);
						$assignments = $doc->getElementsByTagName( "Assignment" );
						foreach($assignments as $assignment)
						{
							$assignmentIds = $assignment->getElementsByTagName("AssignmentId");
							$assignmentId = $assignmentIds->item(0)->nodeValue;
							//echo "id=$assignmentId    ";
							$assignmentTitles = $assignment->getElementsByTagName("AssignmentTitle");
							$assignmentTitle = $assignmentTitles->item(0)->nodeValue;
							echo "<div> &nbsp;&nbsp; <input type='checkbox' name='chooseItem'>&nbsp;&nbsp; $assignmentTitle <a href='#' title='add only this item'>( Add )</a><br />";
						}
					}
					else if ($title == "Resources")
					{
						// reading the assignment list xml string
						//echo "$resourcesListXML";
						$doc = new DOMDocument();
						// get the resource list
						$doc->loadXML($resourcesListXML);
						$entities = $doc->getElementsByTagName( "ResourceEntity" );
						foreach($entities as $entity)
						{
							$entityIds = $entity->getElementsByTagName("EntityId");
							$entityId = $entityIds->item(0)->nodeValue;
							$entityTitles = $entity->getElementsByTagName("EntityTitle");
							$entityTitle = $entityTitles->item(0)->nodeValue;
							$entityDepths = $entity->getElementsByTagName("EntityDepth");
							$entityDepth = $entityDepths->item(0)->nodeValue;
							$entityIsCollections = $entity->getElementsByTagName("EntityIsCollection");
							$entityIsCollection = $entityIsCollections->item(0)->nodeValue;
							$unit ="em";
							$width = "$entityDepth$unit";
							if ($entityIsCollection != 'true')
							{
								echo "<div style='text-indent:$width'><input type='checkbox' name='chooseItem'>$entityTitle <a href='#' title='add only this item'>( Add )</a>";
							}
							else
							{
								echo "<div style='text-indent:$width'>$entityTitle";
							}
						}
					}
					echo"</div>";
				}
				unset($value); // break the reference with the last element
			?>
			<?php
			/*<div class="parent"><img src="../include/images/validated.gif" height="15" /> &nbsp;&nbsp;<img src="../include/images/page.png" height="15" /> &nbsp;&nbsp; 	Syllabus</div>
			
		
		
		
			
			<div class="parent checked"> <input type="checkbox" name="checked[]" value="<?=$proposal['pk']?>" />	<img src="../include/images/folder.gif" height="18" /> &nbsp;&nbsp;	Lectures
			
				<div class="child checked">
					<div> <img src="../include/images/validated.gif" height="15" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L1 W2007.ppt</div>
					<div> <img src="../include/images/validated.gif" height="15" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L2 W2007.ppt</div>
					<div> <img src="../include/images/validated.gif" height="15" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L3 W2007.ppt</div>
					<div> <img src="../include/images/validated.gif" height="15" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L4 W2007.ppt</div>
					<div> <img src="../include/images/validated.gif" height="15" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L5 W2007.ppt</div>
					<div> <input type="checkbox" name="checked[]" value="<?=$proposal['pk']?>" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L6 W2007.ppt</div>
				<div> <input type="checkbox" name="checked[]" value="<?=$proposal['pk']?>" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L7 W2007.ppt</div>
				</div>
		   </div>
			<div class="parent">
				<img src="../include/images/blank.gif" height="3" /><input type="checkbox" name="checked[]" value="<?=$proposal['pk']?>" />
					 <img src="../include/images/page.png" height="15" />&nbsp;&nbsp;Readings</div>
				
			
			
			<div class="parent checked">
			<img src="../include/images/validated.gif" height="15" />&nbsp;&nbsp;&nbsp;&nbsp;Assignments</div>
			
			<div class="parent"><img src="../include/images/blank.gif" height="3" /><input type="checkbox" name="checked[]" value="<?=$proposal['pk']?>" />
				&nbsp;&nbsp;Schedule</div>	
			<div class="parent checked">	 
			<img  src="../include/images/exclaim.gif"> Lecture 1 video<span style="color:red; font-size: .95em;" title="this item has been changed">
				&nbsp;&nbsp;- item has been modified </span> </div>
				*/?>
			
		</div>
	</td>
		
 <td style="width: 10%; vertical-align:top;">
	
	<table border="0" style="font-weight: normal">
		<tr>
			<td class="text"></td>
			<td><br/><br/><br/><br/><br/>&nbsp;&nbsp;&nbsp;<input class="blue_submit" id="submitbutton" type="submit" name="login" value="Add >>>" tabindex="3" /></td>
		</tr>
	</table>
	</form><br/><br/>
</td>

<td width=40% style="vertical-align:top; font-weight: normal; text-align: left; border:1px solid #ccc; ">
 <div class="materials_list">
 <p class="instructions"><br/>To remove materials from this OCW materials list  - click on the <strong>remove</strong> link for that item.<br/><br/></p>
	
<div class="parent">&nbsp; <img src="../include/images/page.png" height=15  /> &nbsp;&nbsp;Syllabus <a href="#" title="remove this item">( remove )</a>
</div>

<div class="parent">&nbsp; <img src="../include/images/folder.gif" height=15  /> &nbsp;&nbsp;Lectures <a href="#" title="remove this item and all child items">( remove )</a>

<div class="child">
	<div><img src="../include/images/blank.gif" height="15" /><img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L1 W2007.ppt <a href="#" title="remove only this item">( remove )</a></div>
<div> <img src="../include/images/blank.gif" height="15" /><img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L2 W2007.ppt <a href="#" title="remove only this item">( remove )</a></div>
<div> <img src="../include/images/blank.gif" height="15" /><img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L3 W2007.ppt <a href="#" title="remove only this item">( remove )</a></div>
<div> <img src="../include/images/blank.gif" height="15" /><img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L4 W2007.ppt <a href="#" title="remove only this item">( remove )</a></div>
<div> <img src="../include/images/blank.gif" height="15" /><img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L5 W2007.ppt <a href="#" title="remove only this item">( remove )</a></div>
</div>
	</div>
<div class="parent">
<img src="../include/images/blank.gif" height="15" />&nbsp;&nbsp;&nbsp;&nbsp;Assignments
 <a href="#" title="remove only this item">( remove )</a></div>

	
<div class="parent">	 
<img  src="../include/images/exclaim.gif"> Lecture 1 video <span style="color:red; font-size: .95em;" title=" item has been changed">&nbsp;&nbsp;- item has been modified</span> </div>
	</div>
	</td></tr>
 
</table>
</div></div>
</div>

</div>
