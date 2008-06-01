  <!-- INFORMATION -->
		<table style="border:none" width="100%">
        <tr>
            <th>Location:</th>
            <td>
            <input type="text" name="location_<?=$repl_obj['id']?>" id="location" size="50" value="<?=$repl_obj['location']?>" class="do_replacement_update"/>
            </td>
        </tr>
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
          <th style="vertical-align:top">Citation:</th>
          <td>
            <textarea name="citation_<?=$repl_obj['id']?>" id="citation" cols="6" rows="1" class="do_replacement_update"><?=$repl_obj['citation']?></textarea>
          </td>
        </tr>
        <tr>
          <th style="vertical-align: top">Description:</th>
          <td>
            <textarea name="description_<?=$repl_obj['id']?>" id="description" cols="6" rows="1" class="do_replacement_update"><?=$repl_obj['description']?></textarea>
          </td>
        </tr>
        <tr>
          <th style="vertical-align: top">Keywords:</th>
          <td>
            <textarea name="tags_<?=$repl_obj['id']?>" id="tags" cols="6"  class="do_replacement_update"><?=$repl_obj['tags']?></textarea>
          </td>
        </tr>
      </table>
