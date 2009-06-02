var orig_com_ap, orig_q_ap, repl_com_ap, repl_q_ap; // references for add panel divs

// for edit content object page: sets up the tabbing between original and replacement
var myCOTabs;
var showreptab = false;

var Site = {
  start: function(){
    if($('myTabs')) Site.setuptabs();

  	var myTips1 = new MooTips($$('.tooltip'), { maxTitleChars: 50 });

		var cinfolist = $$('.coimginfo');
   	cinfolist.each( function(litem, i) { var info = litem.setOpacity(1); });
		
		var arrowlist = $$('.parrow');
   	arrowlist.each( function(item, i) { 
				if (item.id=='pno') { item.setOpacity(0.3); } else { item.setOpacity(0.8); }
				item.addEvent('mouseover',function() { if (this.id=='pyes') { this.setOpacity(1); } });
				item.addEvent('mouseout',function() {  if (this.id=='pyes') { this.setOpacity(0.8); }  });
		});
	
    if ($('orig_com_addpanel')) {
      orig_com_ap = new Fx.Slide($('orig_com_addpanel'), {duration: 500, transition: Fx.Transitions.linear });
      orig_com_ap.hide();
    }	

    if ($('orig_q_addpanel')) {
      orig_q_ap = new Fx.Slide($('orig_q_addpanel'), {duration: 500, transition: Fx.Transitions.linear });
			orig_q_ap.setrole = function (role) { 
							if ($('origrole')) { 
									var opts = $('origrole').options;
									for(var i=0; i < opts.length; i++) {
											if (opts[i].value==role) { $('origrole').selectedIndex = i; } 
									}
							}
			};
      orig_q_ap.hide();
    }
    
    if ($('repl_com_addpanel')) {
      repl_com_ap = new Fx.Slide($('repl_com_addpanel'), {duration: 500, transition: Fx.Transitions.linear });
      repl_com_ap.hide();
    }	

    if ($('repl_q_addpanel')) {
      repl_q_ap = new Fx.Slide($('repl_q_addpanel'), {duration: 500, transition: Fx.Transitions.linear });
			repl_q_ap.setrole = function (role) { 
							if ($('replrole')) { 
									var opts = $('replrole').options;
									for(var i=0; i < opts.length; i++) {
											if (opts[i].value==role) { $('replrole').selectedIndex = i; } 
									}
							}
			};
      repl_q_ap.hide();
    }
  },

  setuptabs: function () {
    myCOTabs = new mootabs('myTabs',{height: '300px', width: '40%'});
    if (showreptab) { myCOTabs.activate('Replacement'); }
  }
};
window.addEvent('domready', Site.start);

//
// Verschachteltes Mootools-Accordion
// Nested Mootools Accordion
// 
// von / by Bogdan Gunther
// http://www.medianotions.de
//

window.addEvent('domready', function() {

	// Adaption IE6
	if (window.ie6) var heightValue='100%';
	else var heightValue='';

	// Selectors of the containers for switches and content
	var togglerName='dt.accordion_toggler_';
	var contentName='dd.accordion_content_';


	// Position selector
	var counter;
	var toggler;
	var content;

	for (counter=1, toggler=$$(togglerName+counter); toggler.length >=1 ;)
	{
		toggler=$$(togglerName+counter);
		content=$$(contentName+counter);
			
		// Apply accordion
		new Accordion(toggler, content, {
			opacity: false,
			display: -1,
			alwaysHide: true,
			onComplete: function() {
				var element=$(this.elements[this.previous]);
				if(element && element.offsetHeight>0) element.setStyle('height', heightValue);
			},
			onActive: function(toggler, content) {
				toggler.addClass('open');
			},
			onBackground: function(toggler, content) {
				toggler.removeClass('open');
			}
		});
		counter++;
		toggler=$$(togglerName+counter);
		content=$$(contentName+counter);
	}
});