<div class="column span-24 first last" style="background-color:#eee;padding:5px;margin-bottom:20px;">
<p>
The following page lists some of the media used in one of your courses. We are asking about specific objects within this course because we cannot determine their provenance or need your approval for a replacement we intend to use. Please go through the list and for each content object indicate whether or not you created and hold the copyright to the media or, in the 'Replacement' tab, indicate whether or not you approve of the proposed replacement.
<br/><br/>
Thanks for your cooperation!
</p>
</div>

<?php 
	if (isset($alert_missing_dscribe))
	{
		echo '<p class="error">'.$alert_missing_dscribe.'</p>';
	}
	
	if (!isset($numobjects))
		$numobjects=0;
	if (!isset($num_repl))
		$num_repl=0;
	if (!isset($num_done))
		$num_done=0;
	if (!isset($num_prov))
		$num_prov=0;
if ($numobjects == 0) { ?>
<div class="column span-24 first last"> 
	<p class="error">Presently, none of the content objects in this material need copyright clarification.</p>
</div>
<?php } else { ?>

<h2>Content Objects in need of Instructor input (<?=isset($need_input)?$need_input:0?> items)</h2>

<div class="column span-24 first last" style="margin-bottom: 10px;">
	<?php 
	$att = ' id="active"'; $irep = 'Replacements ('.$num_repl.')'; $dn = 'View Sent items ('.$num_done.')'; $iprov = 'Provenance ('.$num_prov.')';?>
    <div id="navlist">
		<ul id="navlist">
			<li<?=($view=='provenance')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/provenance",$iprov)?></li>
			<li<?=($view=='replacement')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/replacement",$irep)?></li>
			<li<?=($view=='done')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/done",$dn)?></li>
		</ul>
	</div>
</div>
<br/><br/>

<?php } ?>
