	<!-- INFORMATION -->
			<table style="border:none" width="100%">
				<tr>
						<th>Content Type:</th>
	    			<td><?=$types?></td>
				</tr>
				<tr>
						<th>Location:</th>
	    			<td>
      			<input type="text" name="location" id="location" size="50" value="<?=$obj['location']?>" class="do_object_update"/>
						</td>
				</tr>
				<tr>
					<th>Author:</th>
					<td>
      			<input type="text" name="author" id="author" size="50" value="<?=$obj['author']?>" class="do_object_update"/>
					</td>
				</tr>
				<tr>
					<th>Contributor:</th>
					<td>
      			<input type="text" name="contributor" id="contributor" size="50" value="<?=$obj['contributor']?>" class="do_object_update"/>
					</td>
				</tr>
				<tr>
					<th style="vertical-align:top">Citation:</th>
					<td>
		    		<textarea name="citation" id="citation" cols="6" rows="1" class="do_object_update"><?=$obj['citation']?></textarea>
					</td>
				</tr>
				<tr>
					<th style="vertical-align: top">Description:</th>
					<td>
		    		<textarea name="description" id="description" cols="6" rows="1" class="do_object_update"><?=$obj['description']?></textarea>
					</td>
				</tr>
				<tr>
					<th style="vertical-align: top">Keywords:</th>
					<td>
		    		<textarea name="tags" id="tags" cols="6"  class="do_object_update"><?=$obj['tags']?></textarea>
					</td>
				</tr>
			</table>
