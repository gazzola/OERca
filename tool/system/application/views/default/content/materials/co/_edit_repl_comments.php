<!-- COMMENTS -->

	<h2>Add Comment</h2>

   	<textarea name="repl_comments" id="repl_comments" cols="50"></textarea>
   	<p>
     <input type="button" value="Save" class="do_add_replacement_comment" />
     <br/><hr style="border: 1px dotted #555"/><br/>
   	</p>

<em style="color:black;">Note: Hold down the shift key to select multiple columns to sort</em>
<br/>
<table id="objectrtable" class="sortable-onload-3-reverse rowstyle-alt no-arrow" width="100%">
<thead>
	<tr>
		<th class="sortable">Comment</th>
		<th class="sortable">Posted by</th>
  	<th class="sortable-sortEnglishLonghandDateFormat">Posted On</th>
	</tr>
</thead>

<tbody id="replcomments">
<?php if ($repl_obj['comments'] == null) { ?>
	  <tr id="noreplcomments"><td colspan="3">No comments posted.</td></tr> 

<?php } else { foreach($repl_obj['comments'] as $comment) { ?>
		<tr>
			<td><?=$comment['comments']?></td>
		  <td><?=$this->ocw_user->username($comment['user_id'])?></td>
    	<td><?=mdate('%d %M, %Y %H:%i',mysql_to_unix($comment['modified_on']))?></td>
		</tr>
<?php }} ?>
</tbody>
</table>
