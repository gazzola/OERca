<div class="column span-24 first last" style="margin-bottom: 10px;">
	<?php $att = ' id="active"'; $irep = 'Replacements ('.$num_repl.')'; $dn = 'View Sent items ('.$num_done.')'; $iprov = 'Provenance ('.$num_prov.')';?>
    <div id="navlist">
		<ul id="navlist">
			<li<?=($view=='provenance')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/provenance",$iprov)?></li>
			<li<?=($view=='replacement')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/replacement",$irep)?></li>
			<li<?=($view=='done')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/done",$dn)?></li>
		</ul>
	</div>
</div>
<br/><br/>
