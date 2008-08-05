</div>

<script type="text/javascript">
window.addEvent('domready', function() { 
	var myTips = new MooTips($$('.ine_tip'), { maxTitleChars: 50 }); 
	<?php if (isset($oid)) { ?>
	// open window to edit CO information
	$('edit-<?=$oid?>').addEvent('openeditor', function() {
  		this.blur();
  		var caption = this.title || this.name || "";
  		var group = this.rel || false;
  		TB_show(caption, this.href, group);
  		this.onclick=TB_bind;
	}); 	
	$('edit-<?=$oid?>').fireEvent('openeditor');	
	<?php } ?>
});
</script>
