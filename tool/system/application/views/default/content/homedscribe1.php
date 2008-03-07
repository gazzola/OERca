<h1><?=$name . "'s"?> Status</h1><br />

  <?php if ($courses) {
    foreach ($courses as $key => $value) { 
       $params_url = $value['num']['total'].'/'.$value['num']['done']. 
              '/'.$value['num']['ask'].'/'.$value['num']['rem'];
   ?> 
  <div class="column span-16 first">
    <img src="<?= site_url("/home/make_bar/$params_url") ?>" alt=
      "Progress Bar">
  </div>
  <div class="column span-8 last" >
    <br />
    <h2><?=$value['number'] ?> <?=$value['title'] ?></h2>
  </div>
  <?php }} else { ?>
  <div class="column span-24 first last">
    You have no courses at present. Ask one of the staff to assign a course.
  </div>
  <?php } ?>
