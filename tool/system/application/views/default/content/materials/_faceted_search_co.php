<?php
$search_sections = array();

$search_sections[] = array(
	'label' => 'Recommended Action',
	'data' => $this->material->rec_action_list($mid),
	'uri_segment' => 5
);

$search_sections[] = array(
	'label' => 'Type',
	'data' => $this->material->co_type_list($mid),
	'uri_segment' => 6
);

$search_sections[] = array(
	'label' => 'Replacement',
	'data' => $this->material->replacement_list($mid),
	'uri_segment' => 7
);

$search_sections[] = array(
	'label' => 'Status',
	'data' => $this->material->status_list($mid),
	'uri_segment' => 8
);

//echo "<pre>"; print_r($this->material->rec_action_list($mid)); echo "</pre>";
$fscrumbs = array();
$view_uri_array = $this->uri->segment_array();
//echo "<pre>"; print_r($view_uri_array); echo "</pre>";

foreach ($search_sections as $ss) {
	$view_uri_array[$ss['uri_segment']] = array_key_exists($ss['uri_segment'], $view_uri_array) ? $view_uri_array[$ss['uri_segment']] : 0;
}
$view_uri_string = site_url().implode("/",$view_uri_array);
//echo "<pre>"; print_r($view_uri_array); echo "</pre>";

if (sizeof($this->uri->segment_array()) >= sizeof($search_sections)) {	 //less than number of params in url, no faceted search yet
	foreach ($search_sections as $ss) {
		if ($this->uri->segment($ss['uri_segment'])) {
			$filterid = $this->uri->segment($ss['uri_segment']);
	 		$segment_array = explode("z", $filterid);
	 		if (sizeof($segment_array) > 1) {			
	 			foreach ($segment_array as $key=>$filter) {
					$remove_uri_array = $this->uri->segment_array();
					$remove_segment_array = $segment_array;
					unset($remove_segment_array[$key]);
					$remove_segement_str = implode("z", $remove_segment_array);
					$remove_uri_array[$ss['uri_segment']] = $remove_segement_str;
					$remove_uri_string = site_url().implode("/",$remove_uri_array);
					$fscrumbs[] = array('id'=>$filter, 'val'=>$ss['data'][$filter], 'removeurl'=>$remove_uri_string, 'label'=>$ss['label']);
	 			}
	 		} else {
	 			$remove_uri_array = $this->uri->segment_array();
				$remove_uri_array[$ss['uri_segment']] = 0;
				$remove_uri_string = site_url().implode("/",$remove_uri_array);
				$fscrumbs[] = array('id'=>$filterid, 'val'=>$ss['data'][$filterid], 'removeurl'=>$remove_uri_string, 'label'=>$ss['label']);
	 		}
		}
	}
}

$ua = $this->uri->segment_array();
$ua[5] = 0; $ua[6] = 0; $ua[7] = 0; $ua[8] = 0;
$removeallurl = site_url().implode("/",$ua);
$fscrumbs_html = <<<htmleoq
	<li>
		<a href="$removeallurl" title="Remove all filters">Clear All</a>
	</li>
htmleoq;

foreach ($fscrumbs as $filterarray) {
	$filterid = $filterarray['id'];
	$filtervalue = $filterarray['val'];
	$filterremoveurl = $filterarray['removeurl'];
	$filterlabel = $filterarray['label'];
	$fscrumbs_html .= <<<htmleoq
	<li class="token-input-token">
		<input type="hidden" name="$filterlabel$filterid" id="$filterlabel$filterid" value="$filterid" />
		<p>$filtervalue</p>
		<a href="$filterremoveurl" title="Remove this filter">x</a>
	</li>
htmleoq;
}
?>

<div class="column span-24 first last" style="margin-bottom: 10px;">
	<h4 class="faceted_search_title">Content objects filtered by:</h4>
	<ul class="token-input-list">
		<?= $fscrumbs_html ?>
	</ul>
</div>

<div class="column span-5 first last">
     <div class="accordion">
  		<h4 class="faceted_search_title"><span id="material_count"><?= $num_objects; ?></span> content objects.</h4>
     	<h4 class="faceted_search_title">Filter objects below:</h4>

<?php
foreach ($search_sections as $fs_id=>$s) {
?>
		<h4 id="fs_<?php echo $fs_id; ?>_toggler" class="faceted_search_toggler" onclick="fs_<?php echo $fs_id; ?>.toggle()">
	    	<?php echo $s['label']; ?>
	 	</h4>
	 	<div class="faceted_search_element" id="fs_<?= $fs_id; ?>">
	 		<ul class="faceted_search_list">
	 		<?php 
	 		foreach($s['data'] as $dkey=>$d) {
	 			$custom_view_uri_array = $view_uri_array;
	 			$segment_array = explode("z", $custom_view_uri_array[$s['uri_segment']]);
	 			$selectedclass = (in_array($dkey,$segment_array)) ? 'class=selected' : 'class=unselected';
	 			if ($segment_array[0] > 0) {
	 				array_push($segment_array, $dkey);
	 				$custom_view_uri_array[$s['uri_segment']] = implode("z",$segment_array);
	 			} else {	
	 				$custom_view_uri_array[$s['uri_segment']] = $dkey;
	 			}
	 			$custom_view_uri_string = site_url().implode("/",$custom_view_uri_array);
	 			array_pop($segment_array);
		 		if (sizeof($segment_array) > 1) {			
					$remove_uri_array = $this->uri->segment_array();
					$remove_segment_array = $segment_array;
					$foundkey = array_search($dkey, $segment_array);
					unset($remove_segment_array[$foundkey]);
					$remove_segment_str = implode("z", $remove_segment_array);
					$remove_uri_array[$s['uri_segment']] = $remove_segment_str;
					$remove_uri_string = site_url().implode("/",$remove_uri_array);
		 		} else {
		 			$remove_uri_array = $this->uri->segment_array();
					$remove_uri_array[$s['uri_segment']] = 0;
					$remove_uri_string = site_url().implode("/",$remove_uri_array);
		 		}
	 			$link = (in_array($dkey,$segment_array)) ? $remove_uri_string : $custom_view_uri_string;
	 			$selectedx = (in_array($dkey,$segment_array)) ? "<a href=\"$remove_uri_string\" class=\"selectedx\">x</a>" : '';
	 		?>
	 			<li <?= $selectedclass ?>>
	 				<a href="<?= $link ?>"><?= $d; ?></a>
	 				<?= $selectedx ?>
	 			</li>
	 		<?php
	 		}
	 		?>
	 		</ul>
		</div>
		<script>
			fs_<?php echo $fs_id; ?> = new Fx.Slide($('fs_<?php echo $fs_id; ?>'), {
				duration: 200,
				onComplete: function(el) {
					toggler = $(el.id+'_toggler');
					if (toggler.getStyle('background-image') == "url(<?php echo property('app_img'); ?>/expand.gif)") toggler.setStyle('background-image', "url(<?php echo property('app_img'); ?>/collapse.gif)");
					else toggler.setStyle('background-image', "url(<?php echo property('app_img'); ?>/expand.gif)");
				},
				transition: Fx.Transitions.linear 
			}).show();
		</script>
		
<?php		
}
?>	

	</div>
</div>

<div class="column span-1 first last">
	&nbsp;
</div>