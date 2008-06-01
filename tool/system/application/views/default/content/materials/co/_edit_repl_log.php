<!-- LOGS -->
<em style="color:black;">Note: Hold down the shift key to select multiple columns to sort</em>
<br/>
<table id="replacementlog" class="sortable-onload-3 rowstyle-alt no-arrow" width="100%">
<thead>
	<tr>
		<th class="sortable">Action</th>
		<th class="sortable">Performed by</th>
  	<th class="sortable-sortEnglishLonghandDateFormat">Performed On</th>
	</tr>
</thead>

<tbody>
<?php if ($repl_obj['log'] == null) { ?>
	  <tr><td colspan="3">No log items.</td></tr> 

<?php } else { foreach($repl_obj['log'] as $l) { ?>
		<tr>
			<td><?=$l['log']?></td>
		  <td><?=$this->ocw_user->username($l['user_id'])?></td>
    	<td><?=mdate('%d %M, %Y %H:%i',mysql_to_unix($l['modified_on']))?></td>
		</tr>
<?php }} ?>
</tbody>
</table>
