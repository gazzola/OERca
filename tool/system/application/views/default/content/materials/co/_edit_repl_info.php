  
			<span style="color:red">* Required information</span></br/>

<!-- INFORMATION -->
		<table style="border:none" width="558px">
        <tr>
						<th><span style="color:red">*</span> Location in material:</th>
            <td>
            <input type="text" name="location_<?=$repl_obj['id']?>" id="location" size="20" value="<?=$repl_obj['location']?>" class="do_replacement_update"/>
						&nbsp;<img src="<?=property('app_img')?>/info.gif" style="margin:0; padding:0" class="ine_tip" title="<?=$loc_tip?>" />
            </td>
        </tr>
        <tr>
					<th><span style="vertical-align:top; color:red">*</span> Citation:</th>
          <td>
            <textarea name="citation_<?=$repl_obj['id']?>" id="citation" cols="6" rows="1" class="do_replacement_update"><?=$repl_obj['citation']?></textarea>
          </td>
        </tr>
			</table>	

			<div style="text-align:left" id="replinfo-show">	
					<a href="javascript:void(0);" onclick="$('replinfo-other').style.display='block';$('replinfo-show').style.display='none';$('replinfo-hide').style.display='block'">More information (author, keywords, etc...) &raquo</a>
			</div>
			<div style="text-align:left; display:none" id="replinfo-hide">	
					<a href="javascript:void(0);" onclick="$('replinfo-other').style.display='none';$('replinfo-hide').style.display='none';$('replinfo-show').style.display='block'">Hide &raquo;</a>
			</div>

<div id="replinfo-other" style="display: none">
			<table style="border:none" width="558px;">
        <tr>
          <th>Author:</th>
          <td>
            <input type="text" name="author_<?=$repl_obj['id']?>" id="author" size="50" value="<?=$repl_obj['author']?>" class="do_replacement_update"/>
          </td>
        </tr>
        <tr>
          <th>Contributor:</th>
          <td>
            <input type="text" name="contributor_<?=$repl_obj['id']?>" id="contributor" size="50" value="<?=$repl_obj['contributor']?>" class="do_replacement_update"/>
          </td>
        </tr>
        <tr>
          <th style="vertical-align: top">Description:</th>
          <td>
            <input type="text" name="description_<?=$repl_obj['id']?>" id="description" size="50" class="do_replacement_update" value="<?=$repl_obj['description']?>" />
          </td>
        </tr>
        <tr>
          <th style="vertical-align: top">Keywords:</th>
          <td>
            <input type="text"  name="tags_<?=$repl_obj['id']?>" id="tags" size="50"  class="do_replacement_update" value="<?=$repl_obj['tags']?>" />
          </td>
        </tr>
      </table>
</div>
