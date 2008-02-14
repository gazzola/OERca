<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>OCW Content Objects</title>
	<link rel="stylesheet" href="<?=$css?>/blueprint/screen.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="<?=$css?>/blueprint/print.css" type="text/css" media="print">
	<!--[if IE]><link rel="stylesheet" href="<?=$css?>blueprint/lib/ie.css" type="text/css" media="screen, projection"><![endif]-->
	<link rel="stylesheet" href="<?=$css?>/style.css" type="text/css" />
	<link rel="stylesheet" href="<?=$css?>/carousel.css" type="text/css" />
	<link rel="stylesheet" href="<?=$css?>/smoothbox.css" type="text/css" />

	<script type="text/javascript" src="<?=$script?>/mootools.js"></script>
	<script type="text/javascript" src="<?=$script?>/smoothbox.js"></script>
	<script type="text/javascript" src="<?=$script?>/mootips.js"></script>
	<script type="text/javascript">
	<?php 
		echo 'var numitems = '.$numobjects.';'; 
		echo 'var numsteps = Math.round(numitems / 12);';
		echo 'var knobpos = 0;';
		
		$filter_types = array('Any'=>'Show All',
						'Ask'=>'Ask Instructor', 
						'Done'=>'Cleared', 
						'Fair Use'=>'Fair Use', 
					    'Search'=>'Search',
						'Commission'=>'Commission',
						'Permission'=>'Permission',
						'Retain'=>'Retain',
					    'Remove'=>'Remove');
	?>
	</script>
	<script type="text/javascript" src="<?=$script?>/ocwui.js"></script>
</head>

<body>

<div id="mainPage" class="container" style="width: 600px;">
<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$mid?>" />

<div class="last">
	<div class="first last" style="text-align:center; padding:5px;">
        <h2>Viewing <span id="upd">XX</span>  marked as 
			<?php echo form_dropdown('filter-type', $filter_types, $filter,'id="filter-type"'); ?> </h2>
		
	</div>

	<div id="imagebar" class="column span-14 first">

		<div style="width: 508px; display: block;" class="carousel-component">
			<div id="ulu" class="carousel-clip-region">
    		<ul  style="position: relative; top: 7px; padding-left:50px" 
                class="carousel-list carousel-vertical">
				<?php echo $list; ?>
			   </ul>
       </div>
     </div>
	</div>

	<div id="imageknob" class="column span-1 last">
	<img id="up-arrow" src="<?=$img?>/up-disabled.gif" alt="Previous Button"/>
	<div id="area"><div id="knob"></div></div>
	  <img id="down-arrow" src="<?=$img?>/down-enabled.gif" alt="Next Button"/>
	</div>
</div>

</div>
<input type="hidden" id="server" value="<?=site_url();?>" />

</body>
</html>
