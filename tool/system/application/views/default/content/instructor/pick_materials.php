<?php $this->load->view(property('app_views_path').'/instructor/instructor_header.php', $data); ?>
<?php $this->load->view(property('app_views_path').'/instructor/OCWItem.php', $data); ?>
<?php $this->load->view(property('app_views_path').'/instructor/OCWItemList.php', $data); ?>
<div id="tool_content">
<script type="text/javascript">
function setTask(taskValue)
{
	document.getElementById('task').value=taskValue;
}
		
</script>
<?php
$this->CI = $this->freakauth_light;
$itemList = new OCWItemList;

//Get the linktool parameters
$user = $this->db_session->userdata('internaluser');

if ($user <> '') { 
	$euid = $this->db_session->userdata('user');
	$site = $this->db_session->userdata('site');
	$server = $this->db_session->userdata('serverurl');
	$sessionid = $this->db_session->userdata('sakaisession');
	$placement = $this->db_session->userdata('placement');
	$role = $this->db_session->userdata('role');
	$sign = $this->db_session->userdata('sign');
	$time = $this->db_session->userdata('time');
	$url = $server."/sakai-axis/";

	$client = new SoapClient($url."SiteItem.jws?wsdl");
	$assignmentListXML=$client->getAssignmentList($sessionid, $site, $user);
	$resourcesListXML=$client->getResourceList($sessionid, $site); 	
	
	$toolTitles = $itemList->getSupportedToolTitles();

	$TOOL_NAME="Instructor";
	$PAGE_NAME="Manager Course Materials";

	$hidden = array(//'task' => 'add',
				'cid' => $cid,
				'user' => $user,
				'euid' =>$euid,
				'site' => $site,
				'server' => $server,
				'sessionid' => $sessionid,
				'placement' => $placement,
				'role' => $role,
				'sign' => $sign,
				'time' => $time,
				'url' => $url);
	echo form_open('instructor/materials_option', '', $hidden);
?>
<input type='hidden' id='task' name='task' value='add' />

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
				foreach ($toolTitles as $title)
				{
					echo "<div class='paren'>";
					//$imageUrl=property('app_img').'/validated.gif';
					//echo "<img src=\"".$imageUrl."\""."  height='15' />"."&nbsp;&nbsp;";
					$imageUrl=property('app_img').'/page.png';
					echo "<img src=\"".$imageUrl."\""."  height='15' />"."&nbsp;&nbsp;";
					echo $title."<br /></div>";
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
							$assignmentTitles = $assignment->getElementsByTagName("AssignmentTitle");
							$assignmentTitle = $assignmentTitles->item(0)->nodeValue;
							echo "<div style='text-indent:1em'>";
						?>
							<div> &nbsp;&nbsp; 
							<input type='checkbox' name='chooseItem'>&nbsp;&nbsp; <?=$assignmentTitle?>
							<?=anchor(site_url('instructor/add_material/'.$cid.$this->ocw_utils->escapeUrl($assignmentId)),
					  			'<img src="'.property('app_img').'/add.png" title="Add only this item" />',
					 	 		array("title"=>"Add only this item"))?>&nbsp;&nbsp;
						<?php
							 echo "</div>";
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
							$entityDepth = $entityDepth;
							$width = "$entityDepth$unit";
							if ($entityIsCollection != 'true')
							{
							echo "<div style='text-indent:".$width."'>";
							?>
							<?php echo form_checkbox('chooseItem[]', $entityId, FALSE); 
								echo $entityTitle;
							?>
							<?=anchor(site_url('instructor/add_material/'.$cid.'/'.$this->ocw_utils->escapeUrl($entityId)),
					  			'<img src="'.property('app_img').'/add.png" title="Add only this item" />',
					 	 		array("title"=>"Add only this item"))?>&nbsp;&nbsp;
							<?php }
							else
							{
								echo "<div style='text-indent:$width'>$entityTitle";
							}
							
							echo "</div>";
						}
					}
				}
				unset($value); // break the reference with the last element
			?>
		</div>
	</td>
		
 <td style="width: 10%; vertical-align:top;">
	<br/><br/><br/><br/><br/>&nbsp;&nbsp;&nbsp;
	<?php
		$js = 'onClick="setTask(\'add\')"';
		echo form_submit('addbutton', 'Add >>', $js);
	?>
</td>


<td width=40% style="vertical-align:top; font-weight: normal; text-align: left; border:1px solid #ccc; ">
 <div class="materials_list">
 <!--<p class="instructions"><br/>To remove materials from this OCW materials list  - click on the <strong>remove</strong> link for that item.<br/><br/></p>-->
<div class="instructions"><br/>Check the course items below that you want to download to your desktop. 
			Then, click on <strong>Download</strong> to download the OCW materials. <br/><br/></div>
 <?php
		$js = 'onClick="setTask(\'download\')"';
		echo form_submit('downloadbutton', 'Download >>', $js);
	?>

<?php if ($categories == null) { ?>
	<p class="error">There is no category for you to process yet.</p> 
	<?php } else { 
	 foreach($categories as $category) { ?>
		<div class="parent">&nbsp; <img src="<?= property('app_img').'/page.png'?>"  height=15 /> <?=$category?>&nbsp;&nbsp;
			<?php $categoryMaterials = $categoriesMaterials[$category];
			if ($categoryMaterials == null) { 
			// this is no material inside
			?>
			<p class="error">There is no material for this category.</p> 
			<?php } else { 
				// there is material inside
			?>
				<div class="child"> 
	 			<?php foreach($categoryMaterials as $categoryMaterial) {?>
	 				<div><img src="<?= property('app_img').'/blank.gif'?>" height="15" /><img src="<?= property('app_img').'/ppt.jpg'?>" height="15" /> &nbsp;&nbsp; 
					<?php echo form_checkbox('chooseDownloadItem[]', $categoryMaterial['id'], FALSE); 
								echo $categoryMaterial['name'];
							?>
					</div>
				</div>
	 		<?php }} ?>	
	 	</div>
<?php }} ?>	
</table>
</form>

<?php } else { ?>

<p class="error">This option is only available when tool is accessed through CTools</p>

<?php } ?>
