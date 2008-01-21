var showmsg = function(){
	var div = $('flashMessage').setStyles({
		display:'block',
		opacity: 0
	});
	new Fx.Style(div, 'opacity', {duration: 5000, 
								  onComplete: function(){
										new Fx.Style(div, 'opacity', {duration: 1000, 
																	  onComplete: function() {
																			$('flashMessage').setStyles({display: 'none'});
																	    }}).start(0);
								  } }).start(1);
};
 
window.addEvent('domready', function(){
	if ($('flashMessage')) {
			showmsg();
	}
});
