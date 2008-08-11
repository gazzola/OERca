<h2>Content Objects in need of Instructor input (<?=$need_input?> items)</h2>

<div class="column span-24 first last" style="margin-bottom: 10px;">
	<?php 
			$att = ' id="active"'; 
			$general = 'General Questions ('.$num_general.')'; 
			$irep = 'Replacements ('.$num_repl.')'; 
			$dn = 'View Sent items ('.$num_done.')'; 
			$iprov = 'Provenance ('.$num_prov.')';
	?>
  <div id="navlist">
		<ul id="navlist">
			<li<?=($view=='provenance')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/provenance/instructor",$iprov)?></li>
			<li<?=($view=='replacement')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/replacement/instructor",$irep)?></li>
			<li<?=($view=='done')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/done/instructor",$dn)?></li>
		</ul>
	</div>
</div>
<br/><br/>
