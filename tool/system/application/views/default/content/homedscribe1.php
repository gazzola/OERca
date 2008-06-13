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
          <img src="<?= site_url("/home/make_stat_key/done") ?>" class="prog-key"> Cleared
          &nbsp; &nbsp; &nbsp;
          <img src="<?= site_url("/home/make_stat_key/ask") ?>" class="prog-key"> In Progress
          &nbsp; &nbsp; &nbsp;
          <img src="<?= site_url("/home/make_stat_key/rem") ?>" class="prog-key"> Not Cleared
        </h2>  
      </div>
  <?php } ?>
  <? foreach ($courses as $value) {
        $params_url = $value['num']['total'].'/'.$value['num']['done']. 
              '/'.$value['num']['ask'].'/'.$value['num']['rem'];
      if ($value['num']['total'] > 0) { ?> 
    <div class="column span-24 first last prog-link">     
      <a href="<?php echo site_url("materials/home/{$value['id']}"); ?>">       
        <div class="column span-15 first">
          Total Objects: <?=$value['num']['total'] ?>
          &nbsp; &nbsp;
          Cleared Objects: <?=$value['num']['done'] ?>
          &nbsp; &nbsp;
          Objects in progress: <?=$value['num']['ask'] ?>
          &nbsp; &nbsp;
          Remaining Objects: <?=$value['num']['rem'] ?>
              <img src="<?= site_url("/home/make_bar/$params_url") ?>" 
              alt="Progress Bar: 
              Total Objects=<?=$value['num']['total'] ?>
              Cleared Objects=<?=$value['num']['done'] ?> 
              Objects in progress=<?=$value['num']['ask'] ?> 
              Remaining Objects=<?=$value['num']['rem'] ?>"
              class="prog-bar">
        </div>
        <div class="column span-8 last prog-title">
            <?=$value['number'] ?> <?=$value['title'] ?>
        </div>
      </a>
    </div>
  <?php } else { ?>
  <div class="column span-24 first last prog-no-CO">
    <h2><a href="<?php echo site_url("materials/home/{$value['id']}"); ?>" >
        <?=$value['number'] ?> <?=$value['title'] ?>
        </a> does not contain any content objects.<br />
    </h2>
  </div>
  <?php  }}} else { ?>
  <div class="column span-24 first last">
    You have no courses at present. Ask one of the staff to assign a course.
  </div>
  <?php } ?>

