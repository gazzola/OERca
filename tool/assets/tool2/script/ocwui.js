function update_edit_co_frame(id)
{
  // update frame url.
	var url = $('server').value+'materials/object_info/'+$('cid').value+'/'+$('mid').value+'/'+id;
  $('edit-co-frame').src = url;
}

function update_prev_next() {
	if (knobpos == 0) {
			$('up-arrow').src = '/tool/assets/tool2/images/up-disabled.gif';
			$('down-arrow').src = '/tool/assets/tool2/images/down-enabled.gif';
	}
	if (knobpos == numsteps) {
			$('up-arrow').src = '/tool/assets/tool2/images/up-enabled.gif';
			$('down-arrow').src = '/tool/assets/tool2/images/down-disabled.gif';
	}
	if (knobpos < numsteps && knobpos > 0) {
			$('up-arrow').src = '/tool/assets/tool2/images/up-enabled.gif';
			$('down-arrow').src = '/tool/assets/tool2/images/down-enabled.gif';
	}
}

function scroll_highlight(item)
{
   var imgs = $$('.car-li');
  
		imgs.each( function(img, i) {
        img.style.border = 'none';
        img.style.backgroundColor = '#f8f8f8';
    });
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

    if ($('do_open_courseinfo_pane')) Site.course_page_setup();
    if ($('do_open_matinfo_pane')) Site.co_page_setup();
	},

  course_page_setup: function() {
      var edit_course = new Fx.Slide($('pane_courseinfo')).hide();
      var upload_mat = new Fx.Slide($('pane_uploadmat')).hide();

      $('do_open_courseinfo_pane').addEvent('click', function(e) {
        e = new Event(e);

        // hide other panes
        upload_mat.hide();
        $('do_open_uploadmat_pane').parentNode.className= 'normal';

        edit_course.toggle();
        var addclass = ($('do_open_courseinfo_pane').parentNode.className=='active') ? 'normal' : 'active';
        var rmvclass = ($('do_open_courseinfo_pane').parentNode.className=='active') ? 'active' : 'normal';
        this.parentNode.removeClass(rmvclass).addClass(addclass);
        e.stop(); 
      });
      $('do_close_courseinfo_pane').addEvent('click', function(e) {
        e = new Event(e);
        edit_course.toggle();
        $('do_open_courseinfo_pane').parentNode.removeClass('active').addClass('normal');
        e.stop(); 
      });


      $('do_open_uploadmat_pane').addEvent('click', function(e) {
        e = new Event(e);

        // hide other pains
        edit_course.hide();
        $('do_open_courseinfo_pane').parentNode.className= 'normal';

        upload_mat.toggle();
        var addclass = ($('do_open_uploadmat_pane').parentNode.className=='active') ? 'normal' : 'active';
        var rmvclass = ($('do_open_uploadmat_pane').parentNode.className=='active') ? 'active' : 'normal';
        this.parentNode.removeClass(rmvclass).addClass(addclass);
        e.stop(); 
      });
      $('do_close_uploadmat_pane').addEvent('click', function(e) {
        e = new Event(e);
        upload_mat.toggle();
        $('do_open_uploadmat_pane').parentNode.removeClass('active').addClass('normal');
        e.stop(); 
      });

  },

  co_page_setup: function () {
      // toggle edit panes for material attributes
      var edit_mat = new Fx.Slide($('pane_matinfo')).hide();
      var view_comm = new Fx.Slide($('pane_matcomm')).hide();
      var upload_co = new Fx.Slide($('pane_uploadco')).hide();


      $('do_open_matinfo_pane').addEvent('click', function(e) {
        e = new Event(e);

        // close other panes
        view_comm.hide();
        upload_co.hide();
        $('do_open_matcomm_pane').parentNode.className= 'normal';
        $('do_open_uploadco_pane').parentNode.className= 'normal';

        edit_mat.toggle();
        var addclass = ($('do_open_matinfo_pane').parentNode.className=='active') ? 'normal' : 'active';
        var rmvclass = ($('do_open_matinfo_pane').parentNode.className=='active') ? 'active' : 'normal';
        this.parentNode.removeClass(rmvclass).addClass(addclass);
        e.stop(); 
      });
      $('do_close_matinfo_pane').addEvent('click', function(e) {
        e = new Event(e);
        edit_mat.toggle();
        $('do_open_matinfo_pane').parentNode.removeClass('active').addClass('normal');
        e.stop(); 
      });

      $('do_open_matcomm_pane').addEvent('click', function(e) {
        e = new Event(e);

        // close other panes
        edit_mat.hide();
        upload_co.hide();
        $('do_open_matinfo_pane').parentNode.className= 'normal';
        $('do_open_uploadco_pane').parentNode.className= 'normal';

        view_comm.toggle();
        var addclass = ($('do_open_matcomm_pane').parentNode.className=='active') ? 'normal' : 'active';
        var rmvclass = ($('do_open_matcomm_pane').parentNode.className=='active') ? 'active' : 'normal';
        this.parentNode.removeClass(rmvclass).addClass(addclass);
        e.stop(); 
      });
      $('do_close_matcomm_pane').addEvent('click', function(e) {
        e = new Event(e);
        view_comm.toggle();
        $('do_open_matcomm_pane').parentNode.removeClass('active').addClass('normal');
        e.stop(); 
      });

      $('do_open_uploadco_pane').addEvent('click', function(e) {
        e = new Event(e);

        // close other panes
        edit_mat.hide();
        view_comm.hide();
        $('do_open_matinfo_pane').parentNode.className= 'normal';
        $('do_open_matcomm_pane').parentNode.className= 'normal';

        upload_co.toggle();
        var addclass = ($('do_open_uploadco_pane').parentNode.className=='active') ? 'normal' : 'active';
        var rmvclass = ($('do_open_uploadco_pane').parentNode.className=='active') ? 'active' : 'normal';
        this.parentNode.removeClass(rmvclass).addClass(addclass);
        e.stop(); 
      });
      $('do_close_uploadco_pane').addEvent('click', function(e) {
        e = new Event(e);
        upload_co.toggle();
        $('do_open_uploadco_pane').parentNode.removeClass('active').addClass('normal');
        e.stop(); 
      });
  },


	setuptabs: function () {
		myTabs1 = new mootabs('myTabs',{height: '300px', width: '40%'});
	},

	filtertype: function() {
		$('filter-type').addEvent('change', function(e) {
			var url = $('server').value+'materials/edit/'+
					  $('cid').value+'/'+$('mid').value+'/'+$('caller').value+'/'+this.value;
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
				wait: false, transition: Fx.Transitions.Quad.easeInOut
			});

			var mySlide = new Slider($('area'), $('knob'), {	
					steps: numsteps, 
					mode: 'vertical',	
					onChange: function(step){
						knobpos = step;
						myScrollFx.toElement($('carousel-item-'+step));
            scroll_highlight('carousel-item-'+step);
            update_edit_co_frame($('carousel-item-'+step).parentNode.id);

            $('carousel-item-'+step).style.border='1px solid #222';
            $('carousel-item-'+step).style.backgroundColor = '#222';
						min = (step == 0) ? 1 : step + 1;
						max = min + 3;
						max = (step == numsteps) ? numitems : max;
						max = (max > numitems) ? numitems : max;
						min = (numitems == 0) ? 0 : min;
						min = (numitems == min) ? (numitems-3) : min;
           	//info = min+'-'+max+' of '+numitems;
           	info = min+' of '+numitems;
						$('upd').setHTML(info);
						update_prev_next();
					},
			}).set(0);

      var imglist = $$('.car-li');
		  imglist.each( function(litem, i) {
			    litem.addEvent('click', function() {
            update_edit_co_frame(this.parentNode.id);
						myScrollFx.toElement(this);
            scroll_highlight(this.id);
            this.style.border='1px solid #222';
            this.style.backgroundColor = '#222';
				  });

          litem.oldborder = ''; 
          litem.oldbgc = ''; 
          litem.addEvent('mouseover', function() {
            this.oldborder = this.style.border; 
            this.oldbgc = this.style.backgroundColor; 
            this.style.border='1px solid #ddd';
            this.style.backgroundColor = '#ddd';
          });
          litem.addEvent('mouseout', function() {
            this.style.border = this.oldborder;
            this.style.backgroundColor = this.oldbgc; 
          });
			});

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
		},
};
window.addEvent('domready', Site.start);
