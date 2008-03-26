var orig_com_ap, orig_q_ap, repl_com_ap, repl_q_ap; // references for add panel divs
// boolean values to determine if to open add pane - used in case of errors
var open_uploadmat_pane, open_editcourse_pane, open_uploadco_pane, open_editinst_pane;

var btn_up_active = false;
var btn_down_active = true;

function update_edit_co_frame(id)
{
  // update frame url.
	var oid = (id).replace(/objspan_/g,'');
  var url = $('server').value+'materials/object_info/'+$('cid').value+'/'+$('mid').value+'/'+oid;
  $('edit-co-frame').src = url;
}


function update_prev_next() {
  if (knobpos == 0) {
    $('up-arrow').src = $('imgurl').value+'/up-disabled.gif';
    $('down-arrow').src = $('imgurl').value+'/down-enabled.gif';
    btn_up_active = false; btn_down_active = true;
  }
  if (knobpos >= (numsteps - 1)) {
    $('up-arrow').src = $('imgurl').value+'/up-enabled.gif';
    $('down-arrow').src = $('imgurl').value+'/down-disabled.gif';
    btn_up_active = true; btn_down_active = false;
  }
  if (knobpos < (numsteps - 1) && knobpos > 0) {
    $('up-arrow').src = $('imgurl').value+'/up-enabled.gif';
    $('down-arrow').src = $('imgurl').value+'/down-enabled.gif';
    btn_up_active = true; btn_down_active = true;
  }
}

function scroll_dehighlight(item)
{
  var imgs = $$('.car-li');
  imgs.each( function(img, i) {
    img.style.border = 'none';
    img.style.backgroundColor = '#f8f8f8';
  });
}


var Site = {
  start: function(){
    if($('imagebar')) Site.carousel();
    if($('filter-type')) Site.filtertype();
    if($('myTabs')) Site.setuptabs();

    if ($('do_open_courseinfo_pane')) Site.course_page_setup();

    if ($('do_open_matinfo_pane')) Site.co_page_setup();

    if ($('orig_com_addpanel')) {
      orig_com_ap = new Fx.Slide($('orig_com_addpanel'), {duration: 500, transition: Fx.Transitions.linear });
      orig_com_ap.hide();
    }	

    if ($('orig_q_addpanel')) {
      orig_q_ap = new Fx.Slide($('orig_q_addpanel'), {duration: 500, transition: Fx.Transitions.linear });
      orig_q_ap.hide();
    }

    if ($('repl_com_addpanel')) {
      repl_com_ap = new Fx.Slide($('repl_com_addpanel'), {duration: 500, transition: Fx.Transitions.linear });
      repl_com_ap.hide();
    }	

    if ($('repl_q_addpanel')) {
      repl_q_ap = new Fx.Slide($('repl_q_addpanel'), {duration: 500, transition: Fx.Transitions.linear });
      repl_q_ap.hide();
    }
  },

  course_page_setup: function() {
    var edit_course = new Fx.Slide($('pane_courseinfo')).hide();
    var upload_mat = new Fx.Slide($('pane_uploadmat')).hide();
    var edit_inst = new Fx.Slide($('pane_instinfo')).hide();

    $('do_open_courseinfo_pane').addEvent('click', function(e) {
      e = new Event(e);

      // hide other panes
      upload_mat.hide();
      edit_inst.hide();
      $('do_open_uploadmat_pane').parentNode.className= 'normal';
      $('do_open_instinfo_pane').parentNode.className= 'normal';

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
      edit_inst.hide();
      $('do_open_courseinfo_pane').parentNode.className= 'normal';
      $('do_open_instinfo_pane').parentNode.className= 'normal';

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

    $('do_open_instinfo_pane').addEvent('click', function(e) {
      e = new Event(e);

      //hide other panes
      upload_mat.hide();
      edit_course.hide();
      $('do_open_uploadmat_pane').parentNode.className= 'normal';
      $('do_open_courseinfo_pane').parentNode.className= 'normal';

      edit_inst.toggle();
      var addclass = ($('do_open_instinfo_pane').parentNode.className=='active') ? 'normal' : 'active';
      var rmvclass = ($('do_open_instinfo_pane').parentNode.className=='active') ? 'active' : 'normal';
      this.parentNode.removeClass(rmvclass).addClass(addclass);
      e.stop();
    });
    $('do_close_instinfo_pane').addEvent('click', function(e) {
      e = new Event(e);
      edit_inst.toggle();
      $('do_open_instinfo_pane').parentNode.removeClass('active').addClass('normal');
      e.stop();
    });

    if (open_uploadmat_pane) { upload_mat.toggle();
      $('do_open_uploadmat_pane').parentNode.removeClass('normal').addClass('active');
    }		
    if (open_editcourse_pane) { edit_course.toggle();
      $('do_open_courseinfo_pane').parentNode.removeClass('normal').addClass('active');
    }
    if (open_editinst_pane) { edit_inst.toggle();
      $('do_open_instinfo_pane').parentNode.removeClass('normal').addClass('active');
    }
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

    if (open_uploadco_pane) { upload_co.toggle();
      $('do_open_uploadco_pane').parentNode.removeClass('normal').addClass('active');
    }
  },


  setuptabs: function () {
    myTabs1 = new mootabs('myTabs',{height: '300px', width: '40%'});
  },

  filtertype: function() {
    $('filter-type').addEvent('change', function(e) {
      var c = ($('caller').value=='') ? 'dscribe1' : $('caller').value;
      var url = $('server').value+'materials/edit/'+
      $('cid').value+'/'+$('mid').value+'/'+c+'/'+this.value;
      window.location.replace(url);
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
        //update_prev_next();
      },
      }).set(0);

      $('upd').setHTML('1 of '+numitems);
      $('carousel-item-0').style.border='1px solid #222';
      $('carousel-item-0').style.backgroundColor = '#222';

      var imglist = $$('.car-li');
      imglist.each( function(litem, i) {
        litem.addEvent('click', function() {
          obj_clicked = true;
          update_edit_co_frame(this.parentNode.id);
          mySlide.set(knobpos);

          info = (i+1)+' of '+numitems;
          $('upd').setHTML(info);
          scroll_dehighlight(this.id);

          this.style.border='1px solid #222';
          this.style.backgroundColor = '#222';
          this.oldborder = this.style.border; 
          this.oldbgc = this.style.backgroundColor;
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
        if ((knobpos < numsteps) && btn_down_active) {
          knobpos += 1;
          mySlide.set(knobpos);
          update_prev_next();
        }
      });
      $('up-arrow').addEvent('click', function() {
        if ((knobpos > 0) && btn_up_active) {
          knobpos -= 1;
          mySlide.set(knobpos);
          update_prev_next();
        }
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
          if ((knobpos < numsteps) && btn_down_active) {
            knobpos += 1;
            mySlide.set(knobpos);
            update_prev_next();
          }
        },

        'wheelup': function(e) {
          e = new Event(e).stop();
          if ((knobpos > 0) && btn_up_active) {
            knobpos -= 1;
            mySlide.set(knobpos);
            update_prev_next();
          }
        }
      });

      var myTips1 = new MooTips($$('.tooltip'), {
        maxTitleChars: 50// long caption
      });
    },
  };
  window.addEvent('domready', Site.start);
