function update_prev_next() {
	if (knobpos == 0) {
			$('up-arrow').src = '/tool2/assets/default/images/up-disabled.gif';
			$('down-arrow').src = '/tool2/assets/default/images/down-enabled.gif';
	}
	if (knobpos == numsteps) {
			$('up-arrow').src = '/tool2/assets/default/images/up-enabled.gif';
			$('down-arrow').src = '/tool2/assets/default/images/down-disabled.gif';
	}
	if (knobpos < numsteps && knobpos > 0) {
			$('up-arrow').src = '/tool2/assets/default/images/up-enabled.gif';
			$('down-arrow').src = '/tool2/assets/default/images/down-enabled.gif';
	}
}


var Site = {
	start: function(){
		if($('add_co')) { 
			new MultiUpload( $('add_co').userfile, 1, null, true, true);


			$('add_co').addEvent('submit', function(e) {

    			var location = escape($('location').value);

 				if (location=='') { 
					alert('Please enter a location'); 
					return false; 
				}
			});
	  	}

		if($('infobar')) Site.infobar();
		if($('imagebar')) Site.carousel();
		if($('filter-type')) Site.filtertype();
		if($('myTabs')) Site.setuptabs();
	},


	setuptabs: function () {
		myTabs1 = new mootabs('myTabs',{height: '300px', width: '40%'});
	},

	filtertype: function() {
		$('filter-type').addEvent('change', function(e) {
			var url = $('server').value+'materials/content_objects/'+
					  $('cid').value+'/'+$('mid').value+'/'+this.value;
            window.location.replace(url);
		});
	},

	infobar: function(){
		var list = $$('div.collapse');
		var headings = $$('h3.collapsable');
		var collapsibles = new Array();
				
		headings.each( function(heading, i) {

					var collapsible = new Fx.Slide(list[i], { 
						duration: 500, 
						transition: Fx.Transitions.linear,
						onComplete: function(request){ 
							var open = request.getStyle('margin-top').toInt();
							if(open >= 0) new Fx.Scroll(window).toElement(headings[i]);
						}
					});
					
					collapsibles[i] = collapsible;
					
					heading.onclick = function(){
						var span = $E('span.sign', heading);
		
						var newHTML
						if(span){
							newHTML = span.innerHTML == '+' ? '-' : '+';
							span.setHTML(newHTML);
						}
						
						collapsible.toggle();
						if (newHTML == '+') {
							list[i].removeClass('add-overflow');
						} else {
							list[i].addClass('add-overflow');
						}
						return false;
					}
					
					collapsible.hide();
					
			});
		},

	  carousel: function () {
			var myScrollFx = new Fx.Scroll('imagebar', {
				wait: false,
	    		transition: Fx.Transitions.Quad.easeInOut
			});


	
			var mySlide = new Slider($('area'), $('knob'), {	
					steps: numsteps, 
					mode: 'vertical',	
					onChange: function(step){
						knobpos = step;
						pos = $('carousel-item-'+step).getPosition();
						myScrollFx.scrollTo(pos.x,pos.y-120);
						min = (step == 0) ? 1 : (step*12) + 1;
						max = min + 11;
						max = (step == numsteps) ? numitems : max;
						max = (max > numitems) ? numitems : max;
						min = (numitems == 0) ? 0 : min;
            			info = min+'-'+max+' of '+numitems;
						$('upd').setHTML(info);
						update_prev_next();
					},
			}).set(0);


			$('down-arrow').addEvent('click', function() {
				if (knobpos < numsteps) {
					knobpos += 1;
					mySlide.set(knobpos);
				}
				update_prev_next();
			});
			$('up-arrow').addEvent('click', function() {
				if (knobpos > 0) {
					knobpos -= 1;
					mySlide.set(knobpos);
				}
				update_prev_next();
			});

			Element.Events.extend({
				'wheelup': {
					type: Element.Events.mousewheel.type,
					map: function(event){
						event = new Event(event);
						if (event.wheel >= 0) this.fireEvent('wheelup', event)
					}
			},
 
			'wheeldown': {
					type: Element.Events.mousewheel.type,
					map: function(event){
						event = new Event(event);
						if (event.wheel <= 0) this.fireEvent('wheeldown', event)
					}
			}
			});
			
			$('ulu').addEvents({
				'wheeldown': function(e) {
						e = new Event(e).stop();
						if (knobpos < numsteps) {
							knobpos += 1;
							mySlide.set(knobpos);
						}
						update_prev_next();
				},
 
				'wheelup': function(e) {
						e = new Event(e).stop();
						if (knobpos > 0) {
							knobpos -= 1;
							mySlide.set(knobpos);
						}
						update_prev_next();
				}
			});

			var myTips1 = new MooTips($$('.tooltip'), {
				maxTitleChars: 50// long caption
			});
	/*
			var myTips1 = new MooTips($$('.toolTipImg'), {
				maxTitleChars: 50// long caption
			});
			var myTips2 = new MooTips($$('.toolTipImgDOM'), {
				showDelay: 500// Delay for 500 milliseconds
			});
			var myTips3 = new MooTips($$('.toolTipImgAJAX'), {
				evalAlways: true,		// always run the eval statement
				maxTitleChars: 100,		// very long caption
				fixed: true,			// fixed in place; note tip mouseover does not hide tip
				offsets: {'x':100,'y':100} // offset by 100,100
			});
			var myTips4 = new MooTips($$('.toolTipImgEVAL1'), {
				evalAlways: true,		// always run the eval statement
				showOnClick: true,		// click image to show tooltip
				showOnMouseEnter: false// do not show on mouse enter
			});
			var myTips5 = new MooTips($$('.toolTipImgEVAL2'), {});
		}
	*/
   
		},
};
window.addEvent('domready', Site.start);
