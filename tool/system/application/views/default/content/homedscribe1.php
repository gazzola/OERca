<h1><?=$name . "'s"?> Content Object Clearance Status</h1><br />

  <?php
  if ($courses) { 
			//$bhs='chco=4d89f9,c6d9fd'; 
			$data = array();	
			$max = $min = 0;
			foreach ($courses as $value) {
        $params_url = $value['num']['total'].'/'.$value['num']['done']. 
              '/'.$value['num']['ask'].'/'.$value['num']['rem'];
      if ($value['num']['total'] > 0) { 
					$max = ($value['num']['total']>$max) ? $value['num']['total']:$max;		
					$course = $value['title'];
					$data[$course] = array($value['num']['done'],$value['num']['ask'],$value['num']['rem']);
			?> 
    <div class="column span-24 first last">     
			<h2><?= $value['number'].' '.$value['title'].' ('.$value['num']['total'].' content objects)';?></h2>
        <div class="column span-15 first" style="margin-bottom: 20px;">
							<?php
									$cl = $value['num']['done'] / $value['num']['total'];
									$inp = $value['num']['ask'] / $value['num']['total'];
									$rem = $value['num']['rem'] / $value['num']['total'];
									$chd = "chd=t:$cl,$inp,$rem";
									$chl = 'chl=Cleared|In Progress|Not Cleared';
									$chco = 'chco=00FF00,FFFF00,FF0000';
									$chdl = "chl=Not+Cleared+({$value['num']['rem']})|In+Progress+({$value['num']['ask']})|Done+({$value['num']['done']})";
									$params = "&$chd&$chdl&$chco&chdlp=r";
              		$title="Progress Chart: {$value['num']['total']} Total objects, {$value['num']['done']} Cleared Objects, {$value['num']['ask']} In progress objects, {$value['num']['rem']} Remaining objects";
							?>
      <a class="prog-link" href="<?php echo site_url("materials/home/{$value['id']}"); ?>"><img src="http://chart.apis.google.com/chart?cht=p&chs=450x100&<?=$params?>" title="<?=$title?>"/></a>
							<br/><br/><br/>
        </div>
    </div>
  <?php } else { ?>
  <div class="column span-24 first last prog-no-CO">
    <h2><a href="<?php echo site_url("materials/home/{$value['id']}"); ?>" ><?=$value['number'] ?> <?=$value['title'] ?></a> does not contain any content objects.<br />
    </h2>
  </div>
  <?php  }
    }
		$c = $s = '';
		$chco = '00FF00,FFFF00,FF0000';
		for ($i=0; $i < 3; $i++) {
				if ($s<>'') { $s .= '|'; }
		foreach ($data as $course => $d) {
				if ($c<>'' && $i==0) { $c .= '|'; }
				if ($s<>'' && !preg_match('/\|$/',$s)) { $s .= ','; }
				 if ($i==0) { $c .= $course; }
				 $s .= $d[$i];
		} 
	 }
?>
<!-- bar graph -->
    <img src="http://chart.apis.google.com/chart?cht=bhs&chs=550x300&chco=<?=$chco?>&chd=t:<?=$s?>&chtx=y,x&chxl=0:|<?=$c?>|1:|<?=$min.'|'.$max?>&chds=<?=$min.','.$max?>" />

<?php
		} else { ?>
  <div class="column span-24 first last">
    You have no courses at present. Ask one of the staff to assign a course.
  </div>
  <?php } ?>

