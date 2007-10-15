// Event rules for OCW Tool 
var Rules = {
	'.confirm' : function(element) {
		element.onclick = function() {
			var con = confirm('Are you sure?');
			return con;
		}
	},

	'#curriculum, #sequence, #category' : function(element) {
		element.onchange = function () {
			var id = this.id;
			var ninput = 'new_'+id;
			var c = $F(id);
			if (c == 'new') {
            	new Effect.Appear(ninput);
			} else {
				$(ninput).hide();
			}
		}
	},

	// utilized on the dscribe2 manage courses page 
	'.update_course' : function(element) {
		element.onclick = function () {
			var field = this.name; 
			if (field=='save') {
				var chkvars = Array('director','curriculum_id','sequence_id','title','start_date','end_date');
				var errors = '';
				for(var i=0; i < chkvars.length; i++) {
					r = this.check(chkvars[i], $F(chkvars[i]))
					errors += (r=='success') ? '' : r+'<br/>';
				}
				if (errors != '') {
                     $('editpanel_error').innerHTML = '<small>'+errors+'</small>';
            		Effect.Appear('editpanel_error');
            		Effect.Fade('editpanel_error',{duration:15.0});
				} else {
            		new Effect.Fade('editpanel_error');
            		$('editpanel').hide();
				}
			}
		}
		element.onchange = function () {
			var response;
			var course_id = $F('cid');
			var field = this.name; 
			var val = this.value;
			var url = $F('server')+'dscribe2/courses/update/'+course_id+'/';

            	var check = this.check(field, val);
				if (field=='curriculum_id' && val=='none') { val = 0; }
				if (field=='curriculum_id' && val=='new') { field='new_curriculum_id'; val = $('new_curriculum_id'); }
				if (field=='sequence_id' && val=='none') { val = 0; }
				if (field=='sequence_id' && val=='new') { field='new_sequence_id'; val = $('new_sequence_id'); }

            	if (check != 'success') {
                	$('editpanel_error').innerHTML = '<small>'+check+'</small>';
            		Effect.Appear('editpanel_error');
            		Effect.Fade('editpanel_error',{duration:10.0});
				} else {
					var postbody = 'field='+field+'&val='+val; 
					new Ajax.Updater('feedback', url,
                		{method:'post', postBody:postbody,
            	 		onLoading:function(request) { },
			 	 		onComplete:function(request){
                  			response = $('feedback').innerHTML;
                  			if (response != 'success') {
                        		$('editpanel_error').innerHTML = '<small>'+response+'</small>';
            					Effect.Appear('editpanel_error');
            					Effect.Fade('editpanel_error',{duration:15.0});
                  			}
            			} });
				}
		}
        element.check = function(field, val) {
            if (field=='director' && val=='') {
                return('Please enter the name of the sequence director or instructor of the course'); }
			if (field=='curriculum_id' && val=='0') { return('Please specify a curriculum'); }
			if (field=='sequence_id' && val=='0') { return('Please specify a sequence'); }
			if (field=='curriculum_id' && val=='new' && $('new_curriculum_id')=='') { return('Please enter the name of the curriculum'); }
			if (field=='sequence_id' && val=='new' && $('new_sequence_id')=='') { return('Please enter the name of the sequence'); }
            if (field=='title' && val=='') { return('Please enter the course title'); }
            if (field=='start_date') {
				if (val=='') { return('Please enter the course start date'); }
				val = val.replace(/\-/g,'');
				endval = ($F('end_date')=='') ? 9999999999 : $F('end_date').replace(/\-/g,'');
				if (parseInt(val,10) > parseInt(endval,10)) {
					return 'Start date cannot be after end date';
				}
			}
            if (field=='end_date') {
			 	if( val=='') { return('Please enter the course end date'); }
				val = val.replace(/\-/g,'');
				startval = ($F('start_date')=='') ? 0 : $F('start_date').replace(/\-/g,'');
				if (parseInt(val,10) < parseInt(startval,10)) {
					return 'End date cannot be before start date';
				}
			}
            return 'success';
        }
	},

	// add a new course
    '.do_add_new_course' : function (element) {
        element.onclick = function() {
            var errpanel = $('addpanel_error');
            var url = $F('server')+'dscribe2/courses/add/';

            var curriculum = $F('curriculum');
			var curriculum_text = (curriculum=='new') ? escape($F('new_curriculum')) : '';
            var sequence = escape($F('sequence'));
			var seq_text = (sequence=='new') ? escape($F('new_sequence')) : '';
            var director = escape($F('director'));
            var collabs = escape($F('collaborators'));
            var cnum = escape($F('cnumber'));
            var ctitle = escape($F('ctitle'));
            var sdate = escape($F('start_date'));
            var edate = escape($F('end_date'));
            var class = escape($F('class'));
            var dscribe = $F('dscribe');
            var check = this.check(director, curriculum, curriculum_text, sequence, seq_text, ctitle, sdate, edate);

            if (check != 'success') {
                errpanel.innerHTML = '<small>'+check+'</small>';
                new Effect.Appear('addpanel_error');
            	Effect.Fade('addpanel_error',{duration:10.0});
            } else {
                var fb = $('feedback');
                var response;
				var postbody = 'curriculum='+curriculum+'&newc='+curriculum_text+
							   '&sequence='+sequence+'&news='+seq_text+'&director='+director+
								'&collabs='+collabs+'&cnumber='+cnum+'&ctitle='+ctitle+
							   '&sdate='+sdate+'&edate='+edate+
							   '&class='+class+'&dscribe='+dscribe;

                new Ajax.Updater('feedback', url,
                    {method:'post', postBody:postbody,
					 onLoading:function(request){},
                     onComplete:function(request) {
                        response = fb.innerHTML;
                        if (response=='success') {
            				new Effect.Fade('addpanel_error');
            				new Effect.SlideUp('addpanel');
                            url = $F('server')+'dscribe2/courses/';
                            window.location.replace(url);
                        } else {
                            errpanel.innerHTML = '<small>'+response+'</small>';
                            new Effect.Appear('addpanel_error');
            				Effect.Fade('addpanel_error',{duration:10.0});
                        }
                }});
            }
        }
        element.check = function(director, c, ctext, s, stext, title, sdate, edate) {
            if (director=='') {
                return('Please enter the name of the sequence director or instructor of the course'); }
			if (c=='0') { return('Please specify a curriculum'); }
			if (s=='0') { return('Please specify a sequence'); }
			if (c=='new' && ctext=='') { return('Please enter the name of the curriculum'); }
			if (s=='new' && ctext=='') { return('Please enter the name of the sequence'); }
            if (title=='') { return('Please enter the course title'); }
            if (sdate=='') { return('Please enter the course start date'); }
            if (edate=='') { return('Please enter the course end date'); }

            return 'success';
        }
    },

	// add a new material
    '.do_add_material' : function (element) {
        element.onclick = function() {
			var course_id = $F('cid');
            var errpanel = $('addpanel_error');
            var url = $F('server')+'dscribe2/courses/add_material/'+course_id;

            var category = $F('category');
			var cat_text = $('category').options[$('category').selectedIndex].text;
			var new_cat = (category=='new') ? escape($F('new_category')) : '';
            var name = escape($F('mname'));
	
            var check = this.check(category, new_cat, name);

            if (check != 'success') {
                errpanel.innerHTML = '<small>'+check+'</small>';
                new Effect.Appear('addpanel_error');
            	Effect.Fade('addpanel_error',{duration:10.0});
            } else {
                var fb = $('feedback');
                var response;
				category = (category == 'new') ? new_cat : cat_text;
				var postbody = 'category='+category+'&name='+name+
							   '&name='+name+'&news='+seq_text+'&director='+director+
								'&collabs='+collabs+'&cnumber='+cnum+'&ctitle='+ctitle+
							   '&sdate='+sdate+'&edate='+edate+
							   '&class='+class+'&dscribe='+dscribe;
				/*
                new Ajax.Updater('feedback', url,
                    {method:'post', postBody:postbody,
					 onLoading:function(request){},
                     onComplete:function(request) {
                        response = fb.innerHTML;
                        if (response=='success') {
            				new Effect.Fade('addpanel_error');
            				new Effect.SlideUp('addpanel');
                            url = $F('server')+'dscribe2/courses/edit/'+course_id;
                            window.location.replace(url);
                        } else {
                            errpanel.innerHTML = '<small>'+response+'</small>';
                            new Effect.Appear('addpanel_error');
            				Effect.Fade('addpanel_error',{duration:10.0});
                        }
                }});
				*/
            }
        }
        element.check = function(category, ctext, name) {
			if (category==0) { return('Please specify a category'); }
			if (category=='new' && ctext=='') { return('Please enter a category'); }
			if (name=='') { return('Please enter a name for the material'); }

            return 'success';
        }
    },

	// utilized on the dscribe manage materials page for updating tags
	'.update_tag' : function(element) {
		element.onchange = function () {
			var response;
			var course_id = $F('cid');
			var tag_id = this.value;
			var spinner = load_spinner(this.id);
			var material_id = (this.name).replace(/selectname_/g,'');
			var url = $F('server')+'dscribe/materials/'+course_id+'/update_material/'+
					  material_id+'/tag_id/'+tag_id;	

			new Ajax.Updater('feedback', url,
            	{onLoading:function(request) { Element.show(spinner) },
			 	 onComplete:function(request){
					Element.hide(spinner);
                  	response = feedback.innerHTML;
                  	if (response != 'success') {
                		$('ippanel_error').innerHTML = '<small>'+response+'</small>';
            			Effect.Appear('ippanel_error');
            			Effect.Fade('ippanel_error',{duration:10.0});
                  	}
            	} });
		}
	},

	// update ip object info on manage ip holder page 
	'.update_material' : function(element) {
		element.onchange = function () {
			var response;
			var course_id = $F('cid');
			var material_id = 0;
			if ((this.id).indexOf('inocw_') > -1) {	
				material_id = (this.id).replace(/inocw_\w+_/g,'');
			} else {
				material_id = $F('mid'); 
			}
			var field = ''; 
			if ((this.name).indexOf('in_ocw_') > -1) {	
				field = (this.name).replace(/_\d+/g,'');
			} else {
				field = this.name; 
			}
			var value = this.value; 
			var spinner = load_spinner(this.id);

			if (field=='category_new') {
				if (value != 0) {
					$('category').value = $('category_new').options[$('category_new').selectedIndex].text;
				}
				field = 'category';
				value = $('category').value;
			}

			if (field=='embedded_ip') {
				if (value==0) { Element.hide('fs_emip');	} 
				else { Effect.Appear('fs_emip');	} 
			} 

			if (field=='author' && value=='') {
				value = $F('defcopy');	
				this.value = value;
			} 

			var check = this.check(field, value);

			if (check=='success') {
				var url = $F('server')+'dscribe/materials/'+course_id+'/update_material/'+
						   material_id+'/'+field+'/'+escape(value);	

				new Ajax.Updater('feedback', url,
            		{onLoading:function(request) { Element.show(spinner) },
			 	 	onComplete:function(request){
						Element.hide(spinner);
                  		response = feedback.innerHTML;
                  		if (response != 'success') {
                				$('ippanel_error').innerHTML = '<small>'+response+'</small>';
            					Effect.Appear('ippanel_error');
            					Effect.Fade('ippanel_error',{duration:10.0});
						}
            		} });
			} else {
                $('ippanel_error').innerHTML = '<small>'+check+'</small>';
            	Effect.Appear('ippanel_error');
            	Effect.Fade('ippanel_error',{duration:10.0});
			}
		}
        element.check = function(field, val) {
            if (field=='name' && val=='') {
                return('Please enter a descriptive name for the material'); }

            if (field=='author' && val=='') {
                return('Please specify an author'); }
            
			if (field=='category' && val=='') {
                return('Please specify a category name'); }

            return 'success';
        }
	},

	// manage comments 
	'.do_add_material_comment': function(element) {
		element.onclick = function() {
			var course_id = $F('cid');
			var material_id = $F('mid'); 
            var errpanel = $('addpanel_error');
            var url = $F('server')+'dscribe/materials/'+course_id+'/add_material_comment/'+material_id;

			var comments = escape($F('comments'));
	
			if (comments == '') {
                errpanel.innerHTML = '<small>Please enter a comment</small>';
                new Effect.Appear('addpanel_error');
            	Effect.Fade('addpanel_error',{duration:10.0});
			} else {
                var fb = $('feedback');
                var response;
				var postbody = 'comments='+comments;

                new Ajax.Updater('feedback', url,
                    {method:'post', postBody:postbody,
					 onLoading:function(request){},
                     onComplete:function(request) {
                        response = fb.innerHTML;
                        if (response=='success') {
            				new Effect.Fade('addpanel_error');
            				new Effect.SlideUp('addpanel');
                            url = $F('server')+'dscribe/materials/'+course_id+'/edit_material/'+material_id;
                            window.location.replace(url);
                        } else {
                            errpanel.innerHTML = '<small>'+response+'</small>';
                            new Effect.Appear('addpanel_error');
            				Effect.Fade('addpanel_error',{duration:10.0});
                        }
                }});
			}
		}
	},
	'.do_add_ip_comments': function(element) {
		element.onclick = function() {
			var course_id = $F('cid');
			var material_id = $F('mid'); 
			var ipobject_id = $F('oid'); 
            var errpanel = $('addpanel_error');
            var url = $F('server')+'dscribe/materials/'+course_id+'/add_ip_comment/'+material_id;

			var comments = escape($F('comments'));
	
			if (comments == '') {
                errpanel.innerHTML = '<small>Please enter a comment</small>';
                new Effect.Appear('addpanel_error');
            	Effect.Fade('addpanel_error',{duration:10.0});
			} else {
                var fb = $('feedback');
                var response;
				var postbody = 'comments='+comments+'&ipobject_id='+ipobject_id;

                new Ajax.Updater('feedback', url,
                    {method:'post', postBody:postbody,
					 onLoading:function(request){},
                     onComplete:function(request) {
                        response = fb.innerHTML;
                        if (response=='success') {
            				new Effect.Fade('addpanel_error');
            				new Effect.SlideUp('addpanel');
                            url = $F('server')+'dscribe/materials/'+course_id+'/edit_ip/'+material_id+'/'+ipobject_id;
                            window.location.replace(url);
                        } else {
                            errpanel.innerHTML = '<small>'+response+'</small>';
                            new Effect.Appear('addpanel_error');
            				Effect.Fade('addpanel_error',{duration:10.0});
                        }
                }});
			}
		}
	},

	// add an ip object to a material
    '#do_add_ip' : function (element) {
        element.onclick = function() {
			var course_id = $F('cid');
			var material_id = $F('mid'); 
            var errpanel = $('addpanel_error');
            var url = $F('server')+'dscribe/materials/'+course_id+'/add_ip/'+material_id;

            var name = escape($F('name'));
            var location = escape($F('location'));
            var ipotid = $F('ipobject_type_id');
            var subtype = escape($F('subtype'));
            var filetype = $F('filetype_id');
            var in_use = $F('instructor_use_id');
            var stu_use = $F('student_use_id');
            var holder = escape($F('copyright_holder'));
            var citation = escape($F('citation'));
            var publisher = escape($F('publisher'));
            var action = $F('action_type');
            var comments = escape($F('comments'));
            var check = this.check(name, filetype, action);

            if (check != 'success') {
                errpanel.innerHTML = '<small>'+check+'</small>';
                new Effect.Appear('addpanel_error');
            	Effect.Fade('addpanel_error',{duration:10.0});
            } else {
                var fb = $('feedback');
                var response;
				var postbody = 'action_type='+action+'&name='+name+'&filetype_id='+filetype+
							   '&copyright_holder='+holder+'&comments='+comments+'&citation='+
								citation+'&publisher='+publisher+'&location='+location+
							   '&ipobject_type_id='+ipotid+'&subtype='+subtype+
							   '&instructor_use_id='+in_use+'&student_use_id='+stu_use;

                new Ajax.Updater('feedback', url,
                    {method:'post', postBody:postbody,
					 onLoading:function(request){},
                     onComplete:function(request) {
                        response = fb.innerHTML;
                        if (response=='success') {
            				new Effect.Fade('addpanel_error');
            				new Effect.SlideUp('addpanel');
                            url = $F('server')+'dscribe/materials/'+course_id+'/view_ip/'+material_id;
                            window.location.replace(url);
                        } else {
                            errpanel.innerHTML = '<small>'+response+'</small>';
                            new Effect.Appear('addpanel_error');
            				Effect.Fade('addpanel_error',{duration:10.0});
                        }
                }});
            }
        }
        element.check = function(name, filetype, action) {
            if (name=='') {
                return('Please enter a descriptive name for the IP Object'); }

            if (filetype==0) {
                return('Please specify a file type'); }

            if (action==0) {
                return('Please specify an action type'); }

            return 'success';
        }
    },

	// utilized on the dscribe manage materials page for updating tags
	'.update_ip' : function(element) {
		element.onchange = function () {
			var response;
			var course_id = $F('cid');
			var material_id = $F('mid'); 
			var ipobject_id = $F('oid');
			var field = this.name; 
			var url = $F('server')+'dscribe/materials/'+course_id+'/update_ip/'+material_id;
			var val = this.value;
			if (field=='done') { if (this.checked) { val = 1; } else {val=0; }	}
			var postbody = 'oid='+ipobject_id+'&field='+field+'&val='+escape(val);
            var check = this.check(field, val);

            if (check != 'success') {
                $('ippanel_error').innerHTML = '<small>'+check+'</small>';
            	Effect.Appear('ippanel_error');
            	Effect.Fade('ippanel_error',{duration:10.0});
			} else {
				if (field=='name') { $('ip_name').innerHTML = val; }

				new Ajax.Updater('feedback', url,
                	{method:'post', postBody:postbody,
            	 	onLoading:function(request) { },
			 	 	onComplete:function(request){
                  		response = $('feedback').innerHTML;
                  		if (response != 'success') {
                        	$('ippanel_error').innerHTML = '<small>'+response+'</small>';
            				Effect.Appear('ippanel_error');
            				Effect.Fade('ippanel_error',{duration:10.0});
                  		}
            		} });
			}
		}
        element.check = function(field, val) {
            if (field=='filetype_id' && val==0) {
                return('Please specify a file type'); }

            if (field=='action_type' && val==0) {
                return('Please specify an action type'); }

            if (field=='name' && val=='') {
                return('Please enter a descriptive name for the IP Object'); }

            return 'success';
        }
	},

	// hide and show add panel
	'.do_show_editpanel' : function (element) {
        element.onclick = function() {
            new Effect.Appear('editpanel');
        }
    },

    '.do_hide_editpanel' : function (element) {
        element.onclick = function() {
            new Effect.Fade('editpanel_error');
            $('editpanel').hide();
        }
    },

	// hide and show add panel
	'.do_show_addpanel' : function (element) {
        element.onclick = function() {
            new Effect.Appear('addpanel');
        }
    },

    '.do_hide_addpanel' : function (element) {
        element.onclick = function() {
            new Effect.Fade('addpanel_error');
            $('addpanel').hide();
        }
    }
}

// Remove/Comment this if you do not wish to reapply Rules automatically
// on Ajax request.
Ajax.Responders.register({
 onComplete: function() { EventSelectors.assign(Rules);}
});
