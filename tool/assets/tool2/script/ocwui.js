var orig_com_ap, orig_q_ap, repl_com_ap, repl_q_ap; // references for add panel divs

// for edit content object page: sets up the tabbing between original and replacement
var myCOTabs;
var showreptab = false;

var Site = {
  start: function(){
    if($('myTabs')) Site.setuptabs();

  	var myTips1 = new MooTips($$('.tooltip'), { maxTitleChars: 50 });

		if ($('edit_mat_cos')) {
				var cinfolist = $$('.coimginfo');
    		cinfolist.each( function(litem, i) { var info = litem.setOpacity(0.5); });
		}

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
