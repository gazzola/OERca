  <!-- COMMENTS -->
 	
			<h2>Add Comment</h2>
		   	<textarea name="comments" id="comments" style="width: 90%; height: 50px;"></textarea>
		   	<p>
		     <input type="button" value="Save" class="do_add_object_comment" />
		   	</p>

<em style="color:black;">Note: Hold down the shift key to select multiple columns to sort</em>
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
