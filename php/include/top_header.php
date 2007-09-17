<?php
/*
 * Created on March 8, 2006 by @author shardin
 * Susan Hardin (shardin@umich.edu)
 * This is the basic html header that goes above every page
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 <title><?= $TOOL_NAME ?> - <?= $PAGE_NAME ?></title>
<?php if ($CSS_FILE) { ?>
<link href="<?= $CSS_FILE ?>" rel="stylesheet" type="text/css"/>
<?php } ?>
