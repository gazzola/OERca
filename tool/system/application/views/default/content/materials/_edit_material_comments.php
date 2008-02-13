<?php	$comments = $material['comments']; ?>

<div id="pane_matcomm" class="editpane">

<div  class="column span-21 first last">
	<small><a href="javascript:void(0);" class="do_show_hide_panel">Add Comment</a></small>

   <br/>

	<div id="addpanel" style="display:none">
            <textarea name="comments" id="comments" cols="50" rows="9"></textarea>
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
</div>

<div class="column span-21 first last">
  <input type="button" value="Done" id="do_close_matcomm_pane"/>
</div>

</div>
