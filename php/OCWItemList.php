<?php
	require_once("OCWItem.php");
class OCWItemList 
{
	var $sampleItems;
	
	public function OCWItemList()
	{
		$sampleItems = new OCWItem;
		//("id", "context", "type", "displayName", "url", "copyright", "tag", "metadata", "depth", "mimeType", false));
	}
	
	public function getAllItems()
	{
		return $sampleItems;
	}
	
	public function getOCWItemByTool($toolTitle)
	{
		return;
	}
	
	public function getSupportedToolTitles()
	{
		$toolTitles = array(0=>"Assignments", 1=>"Resources", 2=>"Schedule", 3=>"Syllabus");
		return $toolTitles;
	}
}	
?>