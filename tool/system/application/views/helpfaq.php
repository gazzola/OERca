<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>OER Work Tool &raquo; Help/FAQ</title>

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
<div id="header_line">
    <h1>OER Work Tool</h1>
    <br />
    <h1>Help/FAQ</h1>
</div>
<div class="column span-24 first last">
  <br />
  <b>This page is under construction</b>
</div>
<div id="footer" class="column span-24 first last">
     <script type="text/javascript">EventSelectors.start(Rules);</script>
</div>
<div id="feedback" style="display:none"></div>
<input type="hidden" id="imgurl" value="<?=property('app_img')?>" />
<input type="hidden" id="server" value="<?=site_url();?>" />
</div>
</body>
</html>
