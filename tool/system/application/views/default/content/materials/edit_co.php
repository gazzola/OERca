<?php	
echo style('blueprint/screen.css',array('media'=>"screen, projection"));
echo style('blueprint/print.css',array('media'=>"print"));
echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
echo style('style.css',array('media'=>"screen, projection"));
echo style('table.css',array('media'=>"screen, projection"));
echo style('multiupload.css',array('media'=>"screen, projection"));
echo style('mootabs1.2.css',array('media'=>"screen, projection"));
echo '<style type="text/css">body { padding: 0; margin:0; width: 600px; border:0px solid blue}</style>';

echo script('mootools.js'); 
echo script('tablesort.js');
echo script('mootabs1.2.js');
echo script('event-selectors.js');
echo script('event-rules.js');
echo script('ocwui.js');
echo script('ocw_tool.js');
//echo script('multiupload.js'); 
?>

<div id="mainPage" class="container" style="margin:0; padding:0; width: 600px;">

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$mid?>" />
<input type="hidden" id="oid" name="oid" value="<?=$obj['id']?>" />
<input type="hidden" id="user" name="user" value="<?=$user?>" />

<div class="column span-17 first last" style="text-align: left">
  <h3 style="font-size: 1.5em; color:#666;">OER Content Object: <?=$obj['name']?></h3>
</div>

<div id="myTabs" class="column span-17 first last">
	<ul class="mootabs_title">
		<li title="Original" style="padding-left:10px; margin-left:0;"><h2>Original</h2>
	    <?=$this->ocw_utils->create_co_img($cid,$mid,$obj['name'],$obj['location'],false,false,false);?>
      <br/>
      <a href="<?=site_url("materials/remove_object/$cid/$mid/{$obj['id']}/original")?>" title="delete content object" style="text-align: center" class="confirm" target="_top">Delete content object &raquo;</a>
    </li>

		<li title="Replacement" style="margin-left: 13px;"><h2>Replacement</h2>
      <?php 
				$x = $this->ocw_utils->replacement_exists($obj['name']);
        if ($x) {
            echo $this->ocw_utils->create_corep_img($cid,$mid,$obj['name'],$obj['location'],false,false);
        } else {
            echo '<img src="'.property('app_img').'/norep.png" width="300" height="300" />';
        }
      ?>
     	<br/>
			<?php if ($x) { 
								$r = $this->coobject->replacements($mid,$obj['id']);
			?>
				<a href="<?=site_url("materials/remove_object/$cid/$mid/{$r[0]['id']}/replacement/{$r[0]['name']}")?>" style="text-align: center" title="delete replacement objects" class="confirm" target="_top">Delete replacement &raquo;</a>&nbsp;&nbsp;|&nbsp;&nbsp;
			<?php } ?>
      	<a href="#upload" style="text-align: center" title="upload replacements">Upload replacement &raquo;</a>
    </li>
  </ul>

  <!-- original form -->
  <?php $this->load->view(property('app_views_path').'/materials/_edit_co_orig.php', $data); ?>

  <!-- replacement form -->
  <?php $this->load->view(property('app_views_path').'/materials/_edit_co_repl.php', $data); ?>
</div>

<div class="column span-17 first last" style="text-align: center">
 <br/><?= $this->coobject->prev_next($cid, $mid, $obj['id']);?>
</div>

</div>

<script type="text/javascript">EventSelectors.start(Rules);</script>
<div id="feedback" style="display:none"></div>
<input type="hidden" id="imgurl" value="<?=property('app_img')?>" />
<input type="hidden" id="server" value="<?=site_url();?>" />
