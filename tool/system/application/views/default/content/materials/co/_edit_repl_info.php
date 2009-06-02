  
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
					<th><span style="vertical-align:top; color:red">*</span> Source Information:</th>
          <td>
            <textarea name="citation_<?=$repl_obj['id']?>" id="citation" cols="6" rows="1" class="do_replacement_update"><?=$repl_obj['citation']?></textarea>
          </td>
        </tr>
        <tr>
          <th style="vertical-align: top">Keywords:</th>
          <td>
            <input type="text"  name="tags_<?=$repl_obj['id']?>" id="tags" size="50"  class="do_replacement_update" value="<?=$repl_obj['tags']?>" />
          </td>
        </tr>
			</table>	

			<dl>
			<dt class="accordion_toggler_2">Add more information (author, copyright, etc.)</dt>
			<dd class="accordion_content_2">
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
      </table>
			<?php $this->load->view(property('app_views_path').'/materials/co/_edit_repl_copy.php', $data); ?>
			</dd>
			</dl>