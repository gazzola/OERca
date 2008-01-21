
<div class="column span-24 first last">

<?php $ci_uri = trim($this->uri->uri_string(), '/'); $att = ' id="active"';?>
    <div id="navlist">
		<ul id="navlist">
			<li<?= (preg_match('|^dscribe2/home|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe2/home/",'Home')?></li>
			<li<?= (preg_match('|^dscribe2/courses|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe2/courses",'Manage Courses')?></li>
			<li<?= (preg_match('|^dscribe2/dscribes|', $ci_uri) > 0)? $att: ''?>><?=anchor("/dscribe2/dscribes",'Manage dScribes')?></li>
		</ul>
	</div>
<br/>
