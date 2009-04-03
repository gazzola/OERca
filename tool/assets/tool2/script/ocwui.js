var orig_com_ap, orig_q_ap, repl_com_ap, repl_q_ap; // references for add panel divs

// for edit content object page: sets up the tabbing between original and replacement
var myCOTabs;
var showreptab = false;

var Site = {
  start: function(){
    // if($('myTabs')) Site.setuptabs();

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

//  setuptabs: function () {
//    myCOTabs = new MorphTabs('myTabs',{height: '', width: '', changeTransition: 'none'});
//    if (showreptab) { myCOTabs.activate('Replacement'); }
//    
//    // set up morphtabs which replaces mootabs for mootools 1.2
//    var myMorphTabs = new MorphTabs('morphtabs_panel');
//  }
};
window.addEvent('domready', Site.start);
