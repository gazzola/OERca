
<?php if (isset($message) AND $message!='') { echo $message; }?>

<!--START INCLUDED CONTENT-->
<?= isset($fal) ? $fal : null;?>
<?php isset($page) ? $this->load->view($page) : null;?>
<!--END INCLUDED CONTENT-->
