<?php $inplaceeditors = array(); ?>	

			<!-- Subtype -->
			<?php 
					if ($obj['otype'] == 'original') { 
							$oid = $obj['id'];
							$types = '<select id="subtype_id_'.$oid.'" name="subtype_id_'.$oid.'" class="do_ask_object_update">';
							foreach($select_subtypes as $type => $subtype) {
											$types .= '<optgroup label="'.$type.'">';
											foreach($subtype as $st) {
															$sel = ($obj['subtype_id']== $st['id']) ? 'selected' : '';
															$types .= '<option value="'.$st['id'].'" '.$sel.'>'.$st['name'].'</option>';
    									}
											$types .= '</optgroup>';
							} 
							$types .= '</select>';
							
							echo '<br style="clear;:both"/><br/><h3>Content-Type:</h3>'.$types.'<br/><br/>';
					}
				?>

				<!-- Description -->
				<p style="clear:both"><h3>Description: <small>(click below to edit)</small></h3> 
				<div id="holder_desc_<?=$obj['id']?>"><span id="txt_desc_<?=$obj['id']?>" class="ine_tip" title="Click to edit text"><?php echo ($obj['description']<>'') ? $obj['description']:'No description'?></span></div>
				<?php 
						$n = $obj['otype'].'_desc_'.$obj['id']; 
						$ine_id = 'txt_desc_'.$obj['id'];
						$ine_holder = 'holder_desc_'.$obj['id'];
    				$ine_url = "materials/update_object/$cid/$mid/{$obj['id']}/description/";
						$inplaceeditors[]= "var editor_$n = new InPlaceEditor('$ine_id','$ine_holder',".
					  									 "'$ine_url','No description','get'); ".
					  									 "editor_$n.hover('$ine_id','$ine_holder','#ffffcc','#fff');";
				?>
				</p>
				<br/>

				<!-- Author -->
				<p style="clear:both"><h3>Author: <small>(click below to edit)</small></h3> 
				<div id="holder_author_<?=$obj['id']?>"><span id="txt_author_<?=$obj['id']?>" class="ine_tip" title="Click to edit text"><?php echo ($obj['author']<>'') ? $obj['author']:'No author'?></span></div>
				<?php 
						$n = $obj['otype'].'_author_'.$obj['id']; 
						$ine_id = 'txt_author_'.$obj['id'];
						$ine_holder = 'holder_author_'.$obj['id'];
    				$ine_url = "materials/update_object/$cid/$mid/{$obj['id']}/author/";
						$inplaceeditors[]= "var editor_$n = new InPlaceEditor('$ine_id','$ine_holder',".
					  									 "'$ine_url','No author','get'); ".
					  									 "editor_$n.hover('$ine_id','$ine_holder','#ffffcc','#fff');";
				?>
				</p>
				<br/>

				<!-- Contributor -->
				<p style="clear:both"><h3>Contributor: <small>(click below to edit)</small></h3> 
				<div id="holder_contrib_<?=$obj['id']?>"><span id="txt_contrib_<?=$obj['id']?>" class="ine_tip" title="Click to edit text"><?php echo ($obj['contributor']<>'') ? $obj['contributor']:'No contributor'?></span></div>
				<?php 
						$n = $obj['otype'].'_contrib_'.$obj['id']; 
						$ine_id = 'txt_contrib_'.$obj['id'];
						$ine_holder = 'holder_contrib_'.$obj['id'];
    				$ine_url = "materials/update_object/$cid/$mid/{$obj['id']}/contributor/";
						$inplaceeditors[]= "var editor_$n = new InPlaceEditor('$ine_id','$ine_holder',".
					  									 "'$ine_url','No contributor','get'); ".
					  									 "editor_$n.hover('$ine_id','$ine_holder','#ffffcc','#fff');";
				?>
				</p>
				<br/>

				<!-- Citation (Source Information) -->
				<p style="clear:both"><h3>Source Information: <small>(click below to edit)</small></h3> 
				<div id="holder_citation_<?=$obj['id']?>"><span id="txt_citation_<?=$obj['id']?>" class="ine_tip" title="Click to edit text"><?php echo ($obj['citation']<>'') ? $obj['citation']:'No source information'?></span></div>
				<?php 
						$n = $obj['otype'].'_citation_'.$obj['id']; 
						$ine_id = 'txt_citation_'.$obj['id'];
						$ine_holder = 'holder_citation_'.$obj['id'];
    				$ine_url = "materials/update_object/$cid/$mid/{$obj['id']}/citation/";
						$inplaceeditors[]= "var editor_$n = new InPlaceEditor('$ine_id','$ine_holder',".
					  									 "'$ine_url','No source information','get'); ".
					  									 "editor_$n.hover('$ine_id','$ine_holder','#ffffcc','#fff');";
				?>
				</p>
				<br/>

				<!-- Copyright -->
				<?php 
						$c = (is_array($obj['copyright'])) ? $obj['copyright'] 
							 : array('status'=>'','holder'=>'','url'=>'','notice'=>'');
						$cl = ($obj['otype']=='original') ? 'do_object_cp_update' : 'do_replacement_cp_update';
				?>
				<p style="clear:both"><h3>Copyright: <small>(click below to edit)</small></h3> 

				<!-- copyright status -->
				<label><b>Status:</b></label><br/>
				 <?= form_dropdown("copy_status_{$obj['id']}",$select_copystatus,$c['status'],'class="'.$cl.'"'); ?><br/>
				<br/>
				
				<!-- copyright holder -->
				<label><b>Holder:</b></label><br/>
				<div id="holder_copyholder_<?=$obj['id']?>"><span id="txt_copyholder_<?=$obj['id']?>" class="ine_tip" title="Click to edit text"><?php echo ($c['holder']<>'') ? $c['holder']:'No copyright holder'?></span></div>
				<?php 
						$n = $obj['otype'].'_copyholder_'.$obj['id']; 
						$ine_id = 'txt_copyholder_'.$obj['id'];
						$ine_holder = 'holder_copyholder_'.$obj['id'];
    				$ine_url = "materials/update_object_copyright/{$obj['id']}";
						$inplaceeditors[]= "var editor_$n = new InPlaceEditor('$ine_id','$ine_holder',".
					  									 "'$ine_url','No copyright holder','post','{$obj['otype']}','holder');".
					  									 "editor_$n.hover('$ine_id','$ine_holder','#ffffcc','#fff');";
				?>
				</p>
				<br/>

				<!-- copyright url -->
				<label><b>URL:</b></label><br/>
				<div id="holder_copyurl_<?=$obj['id']?>"><span id="txt_copyurl_<?=$obj['id']?>" class="ine_tip" title="Click to edit text"><?php echo ($c['url']<>'') ? $c['url']:'No copyright url'?></span></div>
				<?php 
						$n = $obj['otype'].'_copyurl_'.$obj['id']; 
						$ine_id = 'txt_copyurl_'.$obj['id'];
						$ine_holder = 'holder_copyurl_'.$obj['id'];
    				$ine_url = "materials/update_object_copyright/{$obj['id']}";
						$inplaceeditors[]= "var editor_$n = new InPlaceEditor('$ine_id','$ine_holder',".
					  									 "'$ine_url','No copyright url','post','{$obj['otype']}','url');".
					  									 "editor_$n.hover('$ine_id','$ine_holder','#ffffcc','#fff');";
				?>
				</p>
				<br/>

				<!-- copyright notice -->
				<label><b>Notice:</b></label><br/>
				<div id="holder_copynotice_<?=$obj['id']?>"><span id="txt_copynotice_<?=$obj['id']?>" class="ine_tip" title="Click to edit text"><?php echo ($c['notice']<>'') ? $c['notice']:'No copyright notice'?></span></div>
				<?php 
						$n = $obj['otype'].'_copynotice_'.$obj['id']; 
						$ine_id = 'txt_copynotice_'.$obj['id'];
						$ine_holder = 'holder_copynotice_'.$obj['id'];
    				$ine_url = "materials/update_object_copyright/{$obj['id']}";
						$inplaceeditors[]= "var editor_$n = new InPlaceEditor('$ine_id','$ine_holder',".
					  									 "'$ine_url','No copyright notice','post','{$obj['otype']}','notice'); ".
					  									 "editor_$n.hover('$ine_id','$ine_holder','#ffffcc','#fff');";
				?>
				</p>
				<br/>

				<?php if ($view == 'general' && $obj['otype']=='original') { ?>
				<p style="clear:both"><h3>dScribe Recommended Action:</small></h3> 
						<?php echo ($obj['action_type']=='') ? 'No action recommended':$obj['action_type']; ?>
				</p>
				<br/>	
				<?php } ?>

		<script type="text/javascript">
			window.addEvent('domready', function() {
    		<?php foreach($inplaceeditors as $editor) { echo $editor."\n"; } ?>
			});
		</script>
