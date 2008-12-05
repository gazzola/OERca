<h1><?=$name . "'s"?> Content Object Clearance Status</h1><br />

  <?php
  $printkey = FALSE; 
  
  if ($courses) { 
    foreach ($courses as $value) {
      if ($value['num']['total'] > 0) {
        $printkey = TRUE;
        break;
      }
    }
    if ($printkey == TRUE) { // print the key only if we have COs ?> 
      <div class="column span-24 first last">
        <h2>
	  <img src="<?= site_url("/home/make_stat_key/rem") ?>" class="prog-key"> No Action Assigned
          &nbsp; &nbsp; &nbsp;
          <img src="<?= site_url("/home/make_stat_key/ask") ?>" class="prog-key"> In Progress
          &nbsp; &nbsp; &nbsp;
	  <img src="<?= site_url("/home/make_stat_key/done") ?>" class="prog-key"> Cleared
        </h2>  
      </div>
  <?php } ?>
  <? foreach ($courses as $value) {
        $params_url = $value['num']['total'].'/'.$value['num']['done']. 
              '/'.$value['num']['ask'].'/'.$value['num']['rem'];
        if ($value['num']['total'] > 0) { ?> 
        <div class="column span-24 first last">     
        <a class="prog-link" href="<?php echo site_url("materials/home/{$value['id']}"); ?>">
          <div class="column span-15 first">
	    <span style="font-size:16px; font-weight:bold; clear:both; margin-top:10px;">
            <?=$value['number'] ?> <?=$value['title'] ?>
            </span>
            <span style="font-size:8px; clear:both; margin-top:10px;">
            <?=   anchor(site_url("courses/edit_course_info/{$value['id']}").'?TB_iframe=true&height=675&width=875','Edit Info &raquo;',array('style'=>'font-size:10px','class'=>'smoothbox','title'=>'Edit Course')) ?>
            </span>
	    <br>
	    <a class="prog-link" href="<?php echo site_url("materials/home/{$value['id']}"); ?>">
            <img src="<?= site_url("/home/make_bar/$params_url") ?>" 
              alt="Progress Bar: 
              Total Objects=<?=$value['num']['total'] ?>
              Cleared Objects=<?=$value['num']['done'] ?> 
              Objects in progress=<?=$value['num']['ask'] ?> 
              Remaining Objects=<?=$value['num']['rem'] ?>"
              class="prog-bar">
           </a>
           </div>
         </a>
         </div>
  <?php } else { ?>
        <div class="column span-24 first last">
        <a class="prog-link" href="<?php echo site_url("materials/home/{$value['id']}"); ?>">
          <div class="column span-15 first">
            <span style="font-size:16px; font-weight:bold; clear:both; margin-top:10px;">
            <?=$value['number'] ?> <?=$value['title'] ?>
            </span>
            <span style="font-size:8px; clear:both; margin-top:10px;">
            <?=   anchor(site_url("courses/edit_course_info/{$value['id']}").'?TB_iframe=true&height=675&width=875','Edit Info &raquo;',array('style'=>'font-size:10px','class'=>'smoothbox','title'=>'Edit Course')) ?>
            <p class="error">No content objects recorded for this course.</p>
	    </span>
            </div>
          </div>
  <?php  }}} else { ?>
                 <div class="column span-24 first last">
            <p class="error">You have no courses at present. Ask one of the staff to assign a course.</p>
                 </div>
  <?php } ?>

