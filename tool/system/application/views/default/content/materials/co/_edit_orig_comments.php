  <!-- COMMENTS -->
 	
			<a href="javascript:void(0);" onclick="orig_com_ap.toggle();">Add Comment</a>

		  <br/>

			<div id="orig_com_addpanel">
		   	<textarea name="comments" id="comments" cols="50"></textarea>
		   	<p>
		     <input type="button" value="Save" class="do_add_object_comment" />
		     <input type="button" value="Cancel" onclick="orig_com_ap.hide()" />
		     <br/><hr style="border: 1px dotted #555"/><br/>
		   	</p>
		  </div>

<br/>
<em style="color:black;">Note: Hold down the shift key to select multiple columns to sort</em>
<br/>
<table id="objectctable" class="sortable-onload-3-reverse rowstyle-alt no-arrow" width="100%">
<thead>
	<tr>
		<th class="sortable">Comment</th>
		<th class="sortable">Posted by</th>
  	<th class="sortable-sortEnglishLonghandDateFormat">Posted On</th>
	</tr>
</thead>

<tbody id="objectcomments">
<?php if ($comments == null) { ?>
	  <tr id="nocomments"><td colspan="3">No comments posted.</td></tr> 

<?php } else { foreach($comments as $comment) { ?>
		<tr>
			<td><?=$comment['comments']?></td>
		  <td><?=$this->ocw_user->username($comment['user_id'])?></td>
    	<td><?=mdate('%d %M, %Y %H:%i',mysql_to_unix($comment['modified_on']))?></td>
		</tr>
<?php }} ?>
</tbody>
</table>
