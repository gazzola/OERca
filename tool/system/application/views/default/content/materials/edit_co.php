<?php	
echo style('blueprint/screen.css',array('media'=>"screen, projection"));
echo style('blueprint/print.css',array('media'=>"print"));
echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
echo style('style.css',array('media'=>"screen, projection"));
echo style('table.css',array('media'=>"screen, projection"));
echo style('multiupload.css',array('media'=>"screen, projection"));
echo style('mootabs1.2.css',array('media'=>"screen, projection"));
echo '<style type="text/css">body { padding: 0; margin:0; width: 700px; border:1px solid blue}</style>';

echo script('mootools.js'); 
echo script('tablesort.js');
echo script('mootabs1.2.js');
echo script('event-selectors.js');
echo script('event-rules.js');
echo script('ocwui.js');
echo script('ocw_tool.js');
echo script('multiupload.js'); 

  $action_types = array('Fair Use'=>'Fair Use', 
					    'Search'=>'Search',
						'Commission'=>'Commission',
						'Permission'=>'Permission',
						'Retain'=>'Retain',
					    'Remove'=>'Remove');

  $types = '<select id="subtype_id" name="subtype_id" class="do_object_update">';
  foreach($subtypes as $type => $subtype) {
		$types .= '<optgroup label="'.$type.'">';
		foreach($subtype as $st) {
			$sel = ($obj['subtype_id']== $st['id']) ? 'selected' : '';
			$types .= '<option value="'.$st['id'].'" '.$sel.'>'.$st['name'].'</option>';
		}
		$types .= '</optgroup>';
  } 
  $types .= '</select>';

  $comments = $obj['comments'];
  $log = $obj['log'];
?>

<div id="mainPage" class="container" style="width: 400px; border:1px solid red;">

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$mid?>" />
<input type="hidden" id="oid" name="oid" value="<?=$obj['id']?>" />
<input type="hidden" id="user" name="user" value="<?=$user?>" />


<div class="column span-24 first last">
  <div class="column span-10 first colborder">
	    <?=$this->ocw_utils->create_co_img($cid,$mid,$obj['name'],$obj['location'],false,false);?>
  </div>
  <div class="column span-12 last">
	    <?=$this->ocw_utils->create_corep_img($cid,$mid,$obj['name'],$obj['location'],false,true);?>
  </div>
  
</div>

<br/><br/>

<div class="column span-2 first last colborder">
	<div class="formLabel">Name:</div>
	<h2 style="color:black"><?=$obj['name']?></h2>

	<br/>

	<div class="formLabel">CO image:</div>
	<?=$this->ocw_utils->create_co_img($cid,$mid,$obj['name'],$obj['location'],false);?>

	<br/> <br/>

	<div class="formLabel">Replace image:</div>
	<?=$this->ocw_utils->create_corep_img($cid,$mid,$obj['name'],$obj['location'],false);?>
	
	<br/><br/>
	<span id="saved"></span>

</div>


<div class="column span-9 last">
	<div id="myTabs">
		<ul class="mootabs_title">
			<li title="Information">Information</li>
			<li title="Comments">Comments</li>
			<li title="Upload">File Upload</li>
			<li title="Log">Log</li>
		</ul>

		<div id="Information" class="mootabs_panel">
			<div class="formField">Cleared:
			<?php 
				$yes = ($obj['done']=='1') ? true : false;
				$no = ($obj['done']=='1') ? false : true;
				echo form_radio('done', '1', $yes, 'class="do_object_update"')
				.'&nbsp;Yes&nbsp;'; 
				echo form_radio('done', '0', $no, 'class="do_object_update"') 
				.'&nbsp;No';
			?>
    		</div>

			<div class="formField">Ask Instructor:
			<?php 
				$yes = ($obj['ask']=='yes') ? true : false;
				$no = ($obj['ask']=='yes') ? false : true;
				echo form_radio('ask', 'yes', $yes, 'class="do_object_update"')	
				.'&nbsp;Yes&nbsp;'; 
				echo form_radio('ask', 'no', $no, 'class="do_object_update"') 
				.'&nbsp;No';
			?>
    		</div>

			<div class="formLabel">Action:</div>
			<div class="formField">
			<?php echo form_dropdown('action_type', 
								$action_types, $obj['action_type'] ,'id="action_type" class="do_object_update"'); ?>
        	</div>

			<div class="formLabel">Action Taken:</div>
			<div class="formField">
      			<input type="text" name="action_taken" id="action_taken" size="30" value="<?=$obj['action_taken']?>" class="do_object_update"/>
        	</div>

			<div class="formLabel">Location:</div>
			<div class="formField">
      			<input type="text" name="location" id="location" size="30" value="<?=$obj['location']?>" class="do_object_update"/>
        	</div>

			<div class="formLabel">Subtype:</div>
			<div class="formField"><?=$types?></div>

			<div class="formLabel">Citation:</div>
			<div class="formField">
			<textarea name="citation" id="citation" cols="4" rows="1" class="do_object_update"><?=$obj['citation']?></textarea>
        	</div>
		</div>

		<div id="Comments" class="mootabs_panel">
			<small>
				<a href="javascript:void(0);" class="do_show_hide_panel">Add Comment</a>
			</small>

        	<br/>

			<div id="addpanel" style="display:none">
              <textarea name="comments" id="comments" cols="50"></textarea>
           	  <p>
               <input type="button" value="Save" class="do_add_object_comment" />
               <input type="button" value="Cancel" class="do_show_hide_panel" />
               <br/><hr style="border: 1px solid #336699"/><br/>
              </p>
         	</div>

		 	<div class="clear"><br/></div>

         	<div id="objectcomments" style="height: 300px; min-height:300px;">
                <?php if ($comments == null) { ?>
                    <p id="nocomments">No comments posted</p>
                <?php } else { foreach($comments as $comment) { ?>
                        <p><?=$comment['comments']?></p>
                        <p>
                          <small>by&nbsp;<?=$this->ocw_user->username($comment['user_id'])?>&nbsp;
                          <?=strtolower($this->ocw_utils->time_diff_in_words($comment['modified_on']))?>
                          </small>
                        </p>
                        <p><hr style="border: 1px solid #336699"/></p>
                <?php  }  } ?>
         	</div>
		</div>

		<div id="Upload" class="mootabs_panel">
			<form action="<?=site_url("materials/update_object/$cid/$mid/{$obj['id']}/rep")?>" enctype="multipart/form-data" id="add_ip_rep" method = "post">
			<b>New Replacement Image:</b>
			<div class="formField">
      			<input type="file" name="userfile" id="userfile" size="30" />
		    <small style="color:red">NB: any existing replacement image will be overwritten</small>	
        	</div>
			<div class="formField">
      			<input type="submit" name="submit" id="submit" value="Upload" />
        	</div>
			</form>
		</div>

		<div id="Log" class="mootabs_panel">
         	<div id="objectlog" style="height: 300px; min-height:300px;">
				<br/>
                <?php if ($log == null) { ?>
                    <p id="nocomments">No log items.</p>
                <?php } else { foreach($log as $l) { ?>
                        <p><?=$l['log']?></p>
                        <p>
                          <small>by&nbsp;<?=$this->ocw_user->username($l['user_id'])?>&nbsp;
                          <?=strtolower($this->ocw_utils->time_diff_in_words($l['modified_on']))?>
                          </small>
                        </p>
                        <p><hr style="border: 1px solid #336699"/></p>
                <?php  }  } ?>
         	</div>
		</div>
	</div>
</div>

<div class="column span-12 first last" style="text-align: center">
	<br/><?= $this->coobject->prev_next($cid, $mid, $obj['id']);?>
</div>
</div>

<script type="text/javascript">EventSelectors.start(Rules);</script>
<script type="text/javascript">
 new MultiUpload( $('add_ip_rep').userfile, 1, null, true, true);
</script>
<div id="feedback" style="display:none"></div>
<input type="hidden" id="imgurl" value="<?=property('app_img')?>" />
<input type="hidden" id="server" value="<?=site_url();?>" />

