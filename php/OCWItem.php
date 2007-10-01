<?php

class OCWItem
{
	// the id of Entity object
	var $m_id;
	
	// the context of Entity object
	var $m_context;
	
	// the type of Entity object
	var $m_type;
	
	// the display name
	var $m_displayName = "";
	
	// the item url
	var $m_url = "";
	
	// the item copyright
	var $m_copyright = "";
	
	// the item copyright holder
	var $m_copyrightHolder = "";
	
	// the item copyright license
	var $m_copyrightLicense = "";
	
	// the item Creative Commons license
	var $m_copyrightCCUrl = "";
	var $m_copyrightCCName = "";
	var $m_copyrightCCButton= "";
	
	// setting for navigation tag
	var $m_tag = "";
	
	// chosen for export?
	var $m_selected = false;
	
	// depth
	var $m_depth = 0;
	
	// mime type
	var $m_mimeType= "";
	
	// can be selected for OCW?
	var $m_ocwAble = false;
	
	// the metadata set
	var $m_metadata;
	
	// the level of object in terms of OCW process
	var $m_level = 0;
	
	// is it ok to publish the resource
	var $m_ok = false;
	
	// content length
	var $m_contentLength = 0;
	
	// is there any OCWItem associated with it
	var $m_objects;
	
	public function OCWItem()
	{
		// constructor
	}
	
	/*public function OCWItem($id, $context, $type, $displayName, $url, $copyright, $tag, $metadata, $depth, $mimeType, $OCWAble)
	{
		// constructor
		$m_id = $id;
		$m_context = $context;
		$m_type = $type;
		$m_displayName = $displayName;
		$m_url = $url;
		$m_copyright = $copyright;
		$m_tag = $tag;
		$m_metadata = $metadata;
		$m_depth = $depth;
		$m_mimeType = $mimeType;
		$m_ocwAble = $OCWAble;
	}*/
	
	/**
	 * get item id
	 * @return The item id String
	 */
	public function getId()
	{
		return $m_id;
	}
	
	/**
	 * set item id
	 * @param id The item id
	 */
	function setId($id)
	{
		$m_id = $id;
	}
	
	/**
	 * get item context
	 * @return The item context String
	 */
	function getContext()
	{
		return $m_context;
	}
	
	/**
	 * set item context
	 * @param contexxt The item context
	 */
	function setContext($context)
	{
		$m_context = $context;
	}
	
	/**
	 * get item type
	 * @return The item type String
	 */
	function getType()
	{
		return $m_type;
	}
	
	/**
	 * set item type
	 * @param id The item type
	 */
	function setType($type)
	{
		$m_type = $type;
	}
	
	/**
	 * get item URL
	 * @return The item URL String
	 */
	function getUrl()
	{
		return $m_url;
	}
	
	/**
	 * set item URL
	 * @param displayName The item URL
	 */
	function setUrl($url)
	{
		$m_url = $url;
	}
	
	/**
	 * get item display name
	 * @return The item display name String
	 */
	function getDisplayName()
	{
		return $m_displayName;
	}
	
	/**
	 * set item display name
	 * @param displayName The item display name
	 */
	function setDisplayName($displayName)
	{
		$m_displayName = $displayName;
	}
	
	/**
	 * the item's copyright
	 * @return copyright
	 */
	function getCopyright()
	{
		return $m_copyright;
	}
	
	/**
	 * set the copyright
	 * @param copyright The copyright
	 */
	function setCopyright($copyright)
	{
		$m_copyright = $copyright;
	}
	
	/**
	 * the item's copyright holder
	 * @return copyright holder
	 */
	function getCopyrightHolder()
	{
		return $m_copyrightHolder;
	}
	
	/**
	 * set the copyright holder
	 * @param copyrightHolder The copyrightHolder
	 */
	function setCopyrightHolder($copyrightHolder)
	{
		$m_copyrightHolder = $copyrightHolder;
	}
	
	/**
	 * the item's copyright license
	 * @return copyright license
	 */
	function getCopyrightLicense()
	{
		return $m_copyrightLicense;
	}
	
	/**
	 * set the copyright license
	 * @param copyrightLicense The copyright license
	 */
	function setCopyrightLicense($copyrightLicense)
	{
		$m_copyrightLicense = $copyrightLicense;
	}
	
	/************* Creative Commons License information ******************/
	/**
	 * the item's CC URL
	 * @return CC license URL
	 */
	function getCopyrightCCUrl()
	{
		return $m_copyrightCCUrl;
	}
	
	/**
	 * set the item CC URL
	 * @param copyrightCCUrl The CC URL
	 */
	function setCopyrightCCUrl($copyrightCCUrl)
	{
		$m_copyrightCCUrl = $copyrightCCUrl;
	}
	
	/**
	 * the item's CC name
	 * @return CC license name
	 */
	function getCopyrightCCName()
	{
		return $m_copyrightCCName;
	}
	
	/**
	 * set the item CC name
	 * @param copyrightCCName the CC name
	 */
	function setCopyrightCCName($copyrightCCName)
	{
		$m_copyrightCCName = $copyrightCCName;
	}
	
	/**
	 * the item's CC button
	 * @return CC license button
	 */
	function getCopyrightCCButton()
	{
		return $m_copyrightCCButton;
	}
	
	/**
	 * set the item CC button
	 * @param copyrightCCButton the CC button
	 */
	function setCopyrightCCButton($copyrightCCButton)
	{
		$m_copyrightCCButton = $copyrightCCButton;
	}
	
	
	/**
	 * get item navigation tag
	 * @return The item navigation tag
	 */
	function getTag()
	{
		return $m_tag;
	}
	
	/**
	 * set item navigation tag
	 * @param navCategory The navigation tag
	 */
	function setTag($tag)
	{
		$m_tag = $tag;
	}
	
	/**
	 * has the item been selected for exporting?
	 * @return The item URL String
	 */
	function getSelected()
	{
		return $m_selected;
	}
	
	/**
	 * set item to be selected for exporting
	 * @param selected Whether the item is selected
	 */
	function setSelected($selected)
	{
		$m_selected = $selected;
	}
	
	/**
	 * get item metadata table
	 * @return The Hashtable holds item metadata
	 */
	function getMetadata()
	{
		return $m_metadata;
	}
	
	/**
	 * set item metadata Hashtable
	 * @param metadata The item metadata Hashtable
	 */
	function setMetadata($metadata)
	{
		$m_metadata = $metadata;
	}
	
	/**
	 * get item depth
	 * @return The depth int
	 */
	function getDepth()
	{
		return $m_depth;
	}
	
	/**
	 * set item depth
	 * @param depth The item depth
	 */
	function setDepth($depth)
	{
		$m_depth = $depth;
	}
	
	/**
	 * get item mime type
	 * @return The mime type
	 */
	function getMimeType()
	{
		return $m_mimeType;
	}
	
	/**
	 * set item mimeType
	 * @param mimeType The item mimeType
	 */
	function setMimeType($mimeType)
	{
		$m_mimeType = $mimeType;
	}
	
	/**
	 * is item able for OCW
	 * @return True if item could be selected for OCW
	 */
	function getOcwAble()
	{
		return $m_ocwAble;
	}
	
	/**
	 * set whether the item is able for OCW
	 * @param OCWAble The item is able for OCW
	 */
	function setOcwAble($ocwAble)
	{
		$m_ocwAble = $ocwAble;
	}

	/**
	 * get ok value
	 * @return
	 */
	function getOk() 
	{
		return $m_ok;
	}

	/**
	 * set ok value
	 * @param n_ok
	 */
	function setOk($n_ok) 
	{
		$m_ok = $n_ok;
	}

	/**
	 * get related objects
	 * @return
	 */
	function getObjects() 
	{
		return $m_objects;
	}

	/**
	 * set related objects
	 * @param $m_objects
	 */
	function setObjects($objects) 
	{
		$m_objects = $objects;
	}

	function getContentLength() {
		return $m_contentLength;
	}

	function setContentLength($length) {
		$m_contentLength = $length;
	}
	
}
 ?>
