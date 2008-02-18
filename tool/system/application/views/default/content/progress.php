<h1><?=$name . "'s"?> Status</h1><br />
<div class="column span-24 first last">
  <?php foreach ($courses as $key => $value) { 
       $params_url = $value['num']['total'].'/'.$value['num']['done']. 
              '/'.$value['num']['ask'].'/'.$value['num']['rem'];
   ?>
   <h2><?=$value['number'] ?> <?=$value['title'] ?></h2><br />
  <img src="<?= site_url("/progress/make_bar/$params_url") ?>" alt="Progress Bar">
  
  <?php } ?>
</div>
