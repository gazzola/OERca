<h1><?=$name . "'s"?> Status</h1><br />
<div class="column span-24 first last">
  
  <?php foreach ($courses as $key => $value) { 
      $value['image']->build_prog_bar(
        $value['num']['total'],
        $value['num']['done'],
        $value['num']['ask'],
        $value['num']['rem']
        ); ?>
    <h2><?=$value['number'] ?> <?=$value['title'] ?></h2><br />
    <img src="<?php $value['image']->get_prog_bar() ?>" alt="Progress Bar">
  
  <?php } ?>
</div>