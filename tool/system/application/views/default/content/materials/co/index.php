<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>OERca &raquo; Edit <?php echo $obj['name'] ?></title>
<?php	
echo style('blueprint/screen.css',array('media'=>"screen, projection"));
echo style('blueprint/print.css',array('media'=>"print"));
echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
echo style('style.css',array('media'=>"screen, projection"));
echo style('table.css',array('media'=>"screen, projection"));
echo style('multiupload.css',array('media'=>"screen, projection"));
echo style('mootabs1.2.css',array('media'=>"screen, projection"));
echo style('sidetabs.css',array('media'=>"screen, projection"));
echo style('smoothbox.css',array('media'=>"screen, projection"));
echo '<style type="text/css">body { background-color:white; padding:0; margin:auto; width:800px; height:550px; color:#999}</style>';

echo script('mootools.js'); 
echo script('smoothbox.js'); 
echo script('tablesort.js');
echo script('mootabs1.2.js');
echo script('mootips.js'); 
echo script('event-selectors.js');
echo script('event-rules.js');
echo script('ocwui.js');
echo script('ocw_tool.js');
?>
<script type="text/javascript">
	var re = new RegExp(/\d+\/\d+\/0/);
	var re2 = new RegExp(/askforms/);
	var cpurl = parent.window.location.href; 
	var purl  = (cpurl.match(re) || cpurl.match(re2)) ?  parent.window.location.href 
																: '<?= site_url("materials/edit/$cid/$mid/0/all"); ?>';
</script>
</head>

<body>

<div id="mainPage" style="margin:0; padding:0; width:810;">

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$mid?>" />
<input type="hidden" id="oid" name="oid" value="<?=$obj['id']?>" />
<input type="hidden" id="user" name="user" value="<?=$user?>" />
	
<button id="donetop" onclick="parent.window.location.replace(purl); parent.TB_remove()">Close</button>

<div class="column span-20 first last" style="text-align:left; margin-bottom:10px;">
  <h3 style="font-size: 1.5em; color:#666;">OER Content Object: <?=$obj['name']?></h3>
</div>

<div id="myTabs" class="column span-20 first last">

	<div id="leftarrow" class="column span-1 first">
			<?= $this->coobject->prev_next($cid, $mid, $obj['id'], $filter,'prev','image');?>
	</div>

	<div id="edit-co-content" class="column span-18">
				<ul class="mootabs_title">
					<li title="Original" style="padding-left:0px; margin-left:0;width:200px;"><h2>Original</h2>
						<center>
				    	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],$filter,'orig',true,true,false,true,'','187');?>
						</center>
			      <br style="clear:both"/>
						<small>
			      	<a href="<?=site_url("materials/remove_object/$cid/$mid/{$obj['id']}/original")?>" title="delete content object" style="text-align: center" customprompt="You are about to permanently delete this Content Object. ARE YOU SURE?" class="confirm" target="_top">Delete content object &raquo;</a>
						</small>
			    </li>
			
					<li title="Replacement" style="margin-left: 13px;"><h2>Replacement</h2>
						<center>
			      <?php 
							$x = $this->coobject->replacement_exists($cid,$mid,$obj['id']);
			        if ($x) {
			            echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],$filter,'rep',true,false);
			        } else {
			            echo '<img src="'.property('app_img').'/norep.png" width="150px" height="150px" />';
			        }
			      ?>
						</center>
			     	<br style="clear:both"/>
						<small>
						<?php if ($x) { $r = $this->coobject->replacements($mid,$obj['id']); ?>
							<a href="<?=site_url("materials/remove_object/$cid/$mid/{$obj['id']}/replacement/{$r[0]['id']}")?>" style="text-align: center" title="delete replacement object" customprompt="You are about to permanently delete this Replacement Content Object. ARE YOU SURE?" class="confirm" target="_top">Delete &raquo;</a>&nbsp;&nbsp;|&nbsp;&nbsp;
						<?php } ?>
			      		<a href="#upload" style="text-align: center" title="upload replacements">Upload &raquo;</a>&nbsp;&nbsp;
			      		<?php if ($x) { ?>
							|&nbsp;&nbsp;<a href="<?=site_url("materials/download_rco/$cid/$mid/{$obj['id']}/{$r[0]['id']}")?>" style="text-align: center" title="download replacement object" >Download &raquo;</a>&nbsp;&nbsp;
						<?php } ?>
						</small>
			    </li>
			 </ul>
			
			 <!-- original form -->
			 <?php $this->load->view(property('app_views_path').'/materials/co/edit_orig.php', $data); ?>
			
			 <!-- replacement form -->
			 <?php $this->load->view(property('app_views_path').'/materials/co/edit_repl.php', $data); ?>
  </div>

	<div id="rightarrow" class="column span-1 last">
			<?= $this->coobject->prev_next($cid, $mid, $obj['id'], $filter,'next','image');?>
	</div>

</div>

<div class="column span-17 first last" style="text-align: center">
	<button id="donebot" onclick="parent.window.location.replace(purl); parent.TB_remove()">Close</button>
</div>

</div>

<script type="text/javascript">
	EventSelectors.start(Rules);
	<?php if($viewing=='replacement') {?>showreptab = true;<?php }?>
	window.addEvent('domready', function() { 
			var myTips = new MooTips($$('.ine_tip'), { maxTitleChars: 50 }); 
			var myTips2 = new MooTips($$('.tooltips'), { maxTitleChars: 50 });
			// force the ask dscribe2 to no by default
			$('ask_dscribe2_yes').style.display = 'none';
			$('ask_dscribe2r_yes').checked=false;
			$('ask_dscribe2r_no').checked=true;
			// force the ask dscribe2 on replacement to no by default
			if ($('dscribe2_repl_ask_q_pane')) {
				$('dscribe2_repl_ask_q_pane').style.display = 'none';
				$('dscribe2_repl_ask_q_yes').checked=false;
				$('dscribe2_repl_ask_q_no').checked=true;
			}
	});
</script>
<div id="feedback" style="display:none"></div>
<input type="hidden" id="imgurl" value="<?=property('app_img')?>" />
<input type="hidden" id="server" value="<?=site_url();?>" />

</body>
</html>
