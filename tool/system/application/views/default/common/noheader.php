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

	   echo script('mootools.js'); 
   	   echo script('tablesort.js');

       echo script('event-selectors.js');
       echo script('event-rules.js');

	   echo script('ocwui.js');
       echo script('ocw_tool.js');
	   echo script('multiupload.js'); 
	?>
</head>

<body>
