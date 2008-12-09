<?php
class OCWItemList 
{
	var $sampleItems;
	
	public function OCWItemList()
	{
		//$sampleItems = new OCWItem;
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
		// only support Resources tool for now
		$toolTitles = array(0=>"Resources"/*0=>"Assignments", 1=>"Resources", 2=>"Schedule", 3=>"Syllabus"*/);
		return $toolTitles;
	}
}	
?>