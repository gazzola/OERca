<div class="column span-24 first last" style="margin-bottom: 10px;">
	<?php $att = ' id="active"'; $irep = 'Replacements ('.$num_repl.')'; $dn = 'Done ('.$num_done.')'; $iprov = 'Provenance ('.$num_prov.')';?>
    <div id="navlist">
		<ul id="navlist">
			<li<?=($view=='provenance')?$att:''?>><?=anchor("/materials/viewform/ask/$cid/$mid/provenance",$iprov)?></li>
			<li<?=($view=='replacement')?$att:''?>><?=anchor("/materials/viewform/ask/$cid/$mid/replacement",$irep)?></li>
			<li<?=($view=='done')?$att:''?>><?=anchor("/materials/viewform/ask/$cid/$mid/done",$dn)?></li>
			<!--
			<li<?=($view=='review')?$att:''?>><?=anchor("/materials/viewform/ask/$cid/$mid/review",'Review')?></li>
			-->
		</ul>
	</div>
</div>
<br/><br/
