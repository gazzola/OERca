<?php	
echo style('blueprint/screen.css',array('media'=>"screen, projection"));
echo style('blueprint/print.css',array('media'=>"print"));
echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
echo style('style.css',array('media'=>"screen, projection"));
echo '<style type="text/css">body { background-color: #222; padding: 15px; margin:auto; width: 340px; border:0px solid blue; height:450px; color:#999}</style>';

echo script('mootools.js');
echo script('event-selectors.js');
echo script('event-rules.js');

$comments = $material['comments']; 
?>

<div  class="column span-7 first last">
	<small><a href="javascript:void(0);" class="do_show_hide_panel">Add Comment</a></small>

  <br/>

	<div id="addpanel" style="display:none">
            <textarea name="comments" id="comments" style="width:290px;height:100px;"></textarea>
            <p>
                       <input type="button" value="Save" class="do_add_material_comment" />
                       <input type="button" value="Cancel" class="do_show_hide_panel" />
             </p>
  </div>

	<div class="clear"><br/></div>

  <div id="materialcomments">
				<h2>Comments</h2>
                <?php if ($comments == null) { ?>
                    <p id="nocomments">No comments posted</p>
                <?php } else { foreach($comments as $comment) { ?>
                        <p><?=$comment['comments']?></p>
                        <p>
                          <small>by&nbsp;<?=$this->ocw_user->username($comment['user_id'])?>&nbsp;
                          <?=strtolower($this->ocw_utils->time_diff_in_words($comment['modified_on']))?>
                          </small>
                        </p>
                        <p><hr noshode style="border: 1px dashed #eee"/><br/></p>
                <?php  }  } ?>
  </div>

	<div class="clear"><br/></div>

	<input type="button" value="Close" onclick="parent.window.location.reload(); parent.TB_remove();"/>

  <div id="feedback" style="display:none"></div>
  <input type="hidden" id="cid" name="cid" value="<?=$cid?>" />  <input type="hidden" id="mid" name="mid" value="<?=$mid?>" />
  <input type="hidden" id="imgurl" value="<?=property('app_img')?>" />
  <input type="hidden" id="server" value="<?=site_url();?>" />
  <script type="text/javascript">EventSelectors.start(Rules);</script>
</div>
