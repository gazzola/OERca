
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
            <div style="float:left; width:90%;"><textarea name="citation_<?=$repl_obj['id']?>" id="citation" class="do_replacement_update" style="height: 50px; width: 97%;"><?=$repl_obj['citation']?></textarea></div>
            <div style="float:left; width:10%; margin-top: 5px">&nbsp;<img src="<?=property('app_img')?>/info.gif" style="margin:0; padding:0" class="ine_tip" title="<?=$cit_tip?>" /></div>
        </td>
    </tr>
    <tr>
        <th style="vertical-align: top">Keywords:</th>
        <td>
            <input type="text"  name="tags_<?=$repl_obj['id']?>" id="tags" size="50"  class="do_replacement_update" value="<?=$repl_obj['tags']?>" />
        </td>
    </tr>
</table>
