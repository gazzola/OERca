<div class="column span-24 first last" style="margin-bottom: 10px;">
	<?php $att = ' id="active"'; $inp = 'In Progress ('.$num_inprogress.')'; $dn = 'Done ('.$num_done.')';?>
    <div id="navlist">
		<ul id="navlist">
			<li<?=($view=='in_progress')?$att:''?>><?=anchor("/materials/viewform/$cid/$mid/in_progress",$inp)?></li>
			<li<?=($view=='done')?$att:''?>><?=anchor("/materials/viewform/$cid/$mid/done",$dn)?></li>
			<li<?=($view=='review')?$att:''?>><?=anchor("/materials/viewform/$cid/$mid/review",'Review')?></li>
		</ul>
	</div>
</div>
<br/><br/
