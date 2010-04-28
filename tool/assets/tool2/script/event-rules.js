function valid_recommendation(oid,recommendation)
{
    if (recommendation=='') { return 'Please recommend an action'; }
    var response = null;
    var url = $('server').value+'materials/valid_recommendation/'+oid+'/'+recommendation;
    var xhr = new XHR({ method: 'get', async: false }).send(url);
    return (response || xhr.transport.responseText);
}

// Event rules for OCW Tool
var Rules = {
    '.confirm' : function(element) {
        element.onclick = function() {
            var conf_prompt;
            var cp_attr_node = this.getAttributeNode("customprompt");
            var cp_attr_dbl = this.getAttributeNode("doublecheck");
            if (cp_attr_node && cp_attr_node.specified) {
                conf_prompt = cp_attr_node.value;
            } else {
                conf_prompt = "Are you sure?";
            }
            var con = confirm(conf_prompt);
            if (con && cp_attr_dbl && cp_attr_dbl.specified && cp_attr_dbl.value != "no") {
                conf_prompt = "This is important, so I'm going to ask again ...   " + conf_prompt;
                con = confirm(conf_prompt);
            }
            return con;
        }
    },

    // utilized on the dscribe manage materials page for updating tags
    '.update_tag' : function(element) {
        element.onchange = function () {
            var response;
            var course_id = $('cid').getValue();
            var tag_id = this.value;
            var material_id = (this.name).replace(/selectname_/g,'');
            var url = $('server').value+'materials/update/'+course_id+'/'+
                material_id+'/tag_id/'+tag_id;

            new Ajax(url,
                     {
                         method: 'get',
                         update: $('feedback'),
                         onComplete:function(request){
                             response = $('feedback').innerHTML;
                             if (response != 'success') { alert(response); }
			 } }).request();
        }
    },

    // update ip object info on manage ip holder page
    '.update_material' : function(element) {
        element.onchange = function () {
            var response;
            var course_id = $('cid').getValue();
            var material_id = 0;
            if ((this.id).indexOf('inocw_') > -1) {
                material_id = (this.id).replace(/inocw_\w+_/g,'');
            } else {
                material_id = $('mid').getValue();
            }
            var field = '';
            if ((this.name).indexOf('in_ocw_') > -1) {
                field = (this.name).replace(/_\d+/g,'');
            } else {
                field = this.name;
            }
            var value = this.value;

            if (field=='author' && value=='') {
                value = $('defcopy').getValue();
                this.value = value;
            }

            var check = this.check(field, value);

            if (check=='success') {
                var url = $('server').value+'materials/update/'+course_id+'/'+material_id;

                new Ajax(url,
                         { method: 'post',
                           postBody: 'field='+field+'&val='+value,
                           update: $('feedback'),
                           onComplete:function(request){
                               response = $('feedback').innerHTML;
                               if (response != 'success') { alert(response); }
                           } }).request();
            } else {
                alert(check);
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
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var url = $('server').value+'materials/add_comment/'+course_id+'/'+material_id;

            var comments = escape($('comments').value);
            if (comments == '') {
                alert('Please enter a comment');
            } else {
                var fb = $('feedback');
                var response;

                new Ajax(url,
			 {
                             method: 'post',
                             postBody: 'comments='+comments,
                             update: fb,
                             onComplete:function() {
				 response = fb.innerHTML;
				 if (response=='success') {
				     url = $('server').value+'materials/editcomments/'+course_id+'/'+material_id;
				     window.location.replace(url);
				 } else {
				     alert(response);
				 }
			     }}).request();
            }
        }
    },


    // object functions


    '.do_add_object_comment': function(element) {
        element.onclick = function(e) {
            new Event(e).stop();
            var object_id = $('oid').value;
	    var url = $('server').value+'materials/add_object_comment/'+object_id;

            var comments = escape($('comments').value);
            if (comments == '') {
		alert('Please enter a comment');
            } else {
		var fb = $('feedback');
		var response;
                var once = true;

		new Ajax(url,
			 {
                             method: 'post',
                             postBody: 'comments='+comments,
                             update: fb,
			     onComplete:function() {
				 response = fb.innerHTML;
                                 if (once) {
				     if (response=='success') {
                                         var tr = new Element('tr');
                                         var td1 = new Element('td').setText(unescape(comments));
                                         var td2 = new Element('td').setText($('user').value);
                                         var td3 = new Element('td').setText('Today');
                                         tr.adopt(td1); tr.adopt(td2); tr.adopt(td3);
                                         tr.injectTop( $('objectcomments') );
                                         $('comments').value = '';
                                         if ($('nocomments')) { $('nocomments').remove(); }
                                         // display a confirmation on submit
                                         if($('comment_added')){
                                             var div = $('comment_added').setStyles({ display:'block', opacity: 1 });
                                             var fx = new Fx.Style(div, 'opacity', {duration: 5000 } ).addEvent("onComplete", function() {
                                                 var hidediv = $('comment_added').setStyles({display:'none'}); });
                                             fx.start(0);
                                         }

				     } else {
					 alert(response);
				     }
                                     once = false;
                                 }
			     }}).request();
            }
        }
    },

    '.do_add_object_question': function(element) {
        element.onclick = function(e) {
            new Event(e).stop();
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var object_id = $('oid').value;
	    var url = $('server').value+'materials/add_object_question/'+course_id+'/'+material_id+'/'+object_id;
            var role = $('origrole').value;

            var qs = escape($('question').value);
            if (qs == '') {
		alert('Please enter a question');
            } else {
		var fb = $('feedback');
		var response;
                var once = true;
                url += '/'+role;

		new Ajax(url,
			 {
                             method: 'post',
                             postBody: 'question='+qs,
                             update: fb,
			     onComplete:function() {
				 response = fb.innerHTML;
                                 if (once) {
				     if (response=='success') {
                                         //orig_q_ap.toggle();
                                         var tr = new Element('tr');
                                         var td1 = new Element('td').setText(role);
                                         var td2 = new Element('td').setText(unescape(qs));
                                         var td3 = new Element('td').setText('No answer');
                                         var td4 = new Element('td').setText($('user').value);
                                         var td5 = new Element('td');
                                         var td6 = new Element('td').setText('Today');
                                         var td7 = new Element('td').setText('Today');
                                         tr.adopt(td1); tr.adopt(td2); tr.adopt(td3); tr.adopt(td4);
                                         tr.adopt(td5); tr.adopt(td6); tr.adopt(td7);
                                         tr.injectTop( $('objectqs') );
                                         $('question').value = '';
                                         /* if (role=='dscribe2') {
                                            $('ask_dscribe2r_yes').checked=true;
                                            $('ask_dscribe2r_no').checked=false;
                                            $('ask_dscribe2_yes').style.display='block';
                                            }
                                            if (role=='instructor') {
                                            $('ask_inst_yes').checked=true;
                                            $('ask_inst_no').checked=false;
                                            $('ask_yes').style.display='block';
                                            } */
                                         if ($('noquestions')) { $('noquestions').remove(); }
                                         // display a confirmation on submit
                                         if($('question_add_msg')){
                                             var div = $('question_add_msg').setStyles({ display:'block', opacity: 1 });
                                             var fx = new Fx.Style(div, 'opacity', {duration: 5000 } ).addEvent("onComplete", function() {
                                                 var hidediv = $('question_add_msg').setStyles({display:'none'});
                                                 $('ask_dscribe2_yes').style.display = 'none';
                                                 $('ask_dscribe2r_yes').checked=false;
                                                 $('ask_dscribe2r_no').checked=true; });
                                             fx.start(0);
                                         }
				     } else {
					 alert(response);
				     }
                                     once = false;
                                 }
			     }}).request();
            }
        }
    },

    '.do_object_update' : function(element) {
        element.onchange = function () {
            var response;
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var object_id = $('oid').value;
            var field = this.name;
            var url = $('server').value+'materials/update_object/'+course_id+'/'+material_id+'/'+object_id;
            var val = this.value;
            var fb = $('feedback');
            var proceed = true;

            if (field=='done') {
                if (val == 1) {
                    var finalact = document.getElementsByName('action_taken');
                    if (finalact[0].value == '') {
                        alert('You must select a Final Action before the object can be marked as cleared.');
                        // return the display to have 'no' selected
                        var done = document.getElementsByName('done');
                        done[0].checked = false;
                        done[1].checked = true;
                        proceed=false;
                        return false;
                    }
                }

                /* Do this unconditionally for now
                   if (val == 1)
                   {
                   var ask = document.getElementsByName('ask_inst');
                   if (ask[0].value=='yes' && ask[0].checked && $('ask_status').value=='false') {
                   alert('You cannot mark the object as cleared. It is still under review by the Instructor.');
                   proceed=false;
                   return false;
                   }
                   }
                */
                new Ajax(url, { method: 'post', postBody: 'field='+field+'&val='+val, update: fb }).request();

            } else if (field=='ask_inst') {
                if(val == 'yes')
                {
                    // if going to ask instructor and the CO is already marked as cleared, generate a message to remind user to unclear the CO first
                    var done = document.getElementsByName('done');
                    if (done[0].value==1 && done[0].checked)
                    {
                        alert('The Content Object is currently marked as "Cleared". Please reset the CLEARED status first.');
                        proceed=false;
                        return false;
                    }
                }
                new Ajax(url, { method: 'post', postBody: 'field='+field+'&val='+val, update: fb }).request();

                // show the div
                var id = this.id;
                id = id.replace(/\w+_/g,'');
                if (this.value == 'yes') {
                    if ($('ask_yes')) {
                        $('ask_yes').style.display = 'inline';
                    }
                } else {
                    if ($('ask_yes')) { $('ask_yes').style.display = 'none';     }
                }

                // show or hide relavent panels
            } else if (field=='action_type') {

                result = valid_recommendation(object_id, val);

                if (result!='success') {
                    if (confirm(result+'\n\nOverride the recommended action to "'+ val +'"?')) {
                        // they chose to override previous recommendation, update the recommended action
                        var url = $('server').value+'materials/override_action_type/'+object_id+'/'+val;
                        new Ajax(url, { method: 'post',
                                        postBody: 'action_type='+val,
                                        //onComplete: function(responseText) { alert(responseText); },
                                        update: fb
                                      }).request();
                        // also update claim status to 'new'
                        var claimstatus = 'new';
                        var url = $('server').value+'materials/update_claim_status/'+object_id+'/'+claimstatus;
                        new Ajax(url, { method: 'post',
                                        postBody: 'status=new',
                                        //onComplete: function(responseText) { alert(responseText); },
                                        update: fb
                                      }).request();
                    } else {
                        // go back to previous recommended action
                        var options = $('action_type').options;
                        for(var i=0; i < options.length; i++) {
                            if (options[i].value == $('raction').value) {
                                $('action_type').selectedIndex = options[i].index;
                            }
                        }

                        return false;
                    }
                }
                // If we make it this far, update the panels
                if (val == 'Search' || val == 'Create' || val=='Remove and Annotate') {
                    new Ajax(url, { method: 'post', postBody: 'field='+field+'&val='+val, update: fb }).request();
                    var ask_dscrib2 = document.getElementsByName('ask_dscribe2');
                    for ( var i = 0 ; i < ask_dscrib2.length ; i++ ) {
                        ask_dscrib2[i].checked = (ask_dscrib2[i].value == 'no') ? 'checked' : '';
                    }
                    var fb1 = $('feedback');
                    new Ajax(url, { method: 'post', postBody: 'field=ask_dscribe2&val=no', update: fb1 }).request();

                    if ($('Fair Use')) { $('Fair Use').style.display = 'none';      }
                    if ($('Permission')) { $('Permission').style.display = 'none';}
                    if ($('Commission')) { $('Commission').style.display = 'none';}
                    if ($('Retain')) { $('Retain').style.display = 'none';}
                    // don't scroll

                } else if (val == 'Fair Use') {
                    if ($('Fair Use')) { $('Fair Use').style.display = 'block';}
                    if ($('Permission')) { $('Permission').style.display = 'none';}
                    if ($('Commission')) { $('Commission').style.display = 'none';}
                    if ($('Retain')) { $('Retain').style.display = 'none';}
                    this.scrollIntoView();
                } else if (val == 'Permission') {
                    if ($('Fair Use')) { $('Fair Use').style.display = 'none';      }
                    if ($('Permission')) { $('Permission').style.display = 'block';}
                    if ($('Commission')) { $('Commission').style.display = 'none';}
                    if ($('Retain')) { $('Retain').style.display = 'none';}
                    this.scrollIntoView();
                } else if (val == 'Commission') {
                    if ($('Fair Use')) { $('Fair Use').style.display = 'none';      }
                    if ($('Permission')) { $('Permission').style.display = 'none';}
                    if ($('Commission')) { $('Commission').style.display = 'block';}
                    if ($('Retain')) { $('Retain').style.display = 'none';}
                    this.scrollIntoView();
                } else if (val.substring(0, 6) == 'Retain' && val != 'Retain: Instructor Created') {
                    if ($('Fair Use')) { $('Fair Use').style.display = 'none';      }
                    if ($('Permission')) { $('Permission').style.display = 'none';}
                    if ($('Commission')) { $('Commission').style.display = 'none';}
                    if ($('Retain')) { $('Retain').style.display = 'block';}
                    this.scrollIntoView();
                } else {
                    if ($('Fair Use')) { $('Fair Use').style.display = 'none';      }
                    if ($('Permission')) { $('Permission').style.display = 'none';}
                    if ($('Commission')) { $('Commission').style.display = 'none';}
                    if ($('Retain')) { $('Retain').style.display = 'none';}
                    this.scrollIntoView();
                }
                $('raction').value=val;
            } else if (field=='action_taken') {
                if (val == '') {
                    // When resetting final action to 'none', assure that object is not cleared ('done')
                    var done = document.getElementsByName('done');
                    if (done[0].value == 1) {
                        var fb1 = $('feedback');
                        new Ajax(url, { method: 'post', postBody: 'field=done&val=0', update: fb1 }).request();
                        done[0].checked = false;
                        done[1].checked = true;
                    }
                }
                new Ajax(url, { method: 'post', postBody: 'field='+field+'&val='+val, update: fb }).request();
            } else {
                field = escape(field);
                val = escape(val);
                new Ajax(url, { method: 'post', postBody: 'field='+field+'&val='+val, update: fb }).request();
            }
        }
        //element.onclick = element.onchange;
    },

    '.do_update_action_type' : function (element) {
        element.onclick = function() {
            var response;
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var object_id = $('oid').value;
            var field = 'action_type';
            var url = $('server').value+'materials/update_object/'+course_id+'/'+material_id+'/'+object_id;
            var val = $('action_type').value;
            var fb = $('feedback');

            var check = this.validate(val);
            if (check != 'success') { alert(check); return false; }

            // They clicked the send button, so update the claim status to indicate that fact (do this in controller instead?)
            //var url2 = $('server').value+'materials/update_claim_status/'+object_id+'/request+sent';
            //new Ajax(url2, { method: 'post', postBody: 'status=request sent', update: fb } ).request();

            new Ajax(url, { method: 'post', postBody: 'field='+field+'&val='+val, update: fb,
                            onComplete:function() {
                                response = fb.innerHTML;
                                if (response=='success') {
                                    if($('update_msg')){
                                        var div = $('update_msg').setStyles({ display:'block', opacity: 1 });
                                        var fx = new Fx.Style(div, 'opacity', {duration: 5000 } ).addEvent("onComplete", function() {
                                            var hidediv = $('update_msg').setStyles({display:'none'}); });
                                        fx.start(0);
                                    }
                                    // if the selected action is other than "Search"
                                    // and "Remove", the ask dscribe2 should be checked
                                    if (val != 'Search' && val != 'Remove and Annotate' &&
                                        val != 'Retain: Instructor Created') {
                                        var ask_dscrib2 = document.getElementsByName('ask_dscribe2');
                                        for ( var i = 0 ; i < ask_dscrib2.length ; i++ ) {
                                            if (ask_dscrib2[i].value == 'yes') {
                                                ask_dscrib2[i].checked = 'checked';
                                            }
                                        }
                                        // reset url
                                        var fb1 = $('feedback');
                                        new Ajax(url, { method: 'post',
                                                        postBody: 'field=ask_dscribe2&val=yes',
                                                        update: fb1 }).request();
                                    }
                                } else {
                                    alert(response); return false;
                                }
                            }
                          }
                    ).request();

        }
        element.validate = function(action) {
            if (action=='') { return 'Please recommend an action'; }
            if (action=='Fair Use' && $('fairuse_rationale').value=='') {
                return 'Please fill out required field for this action'; }
            if (action=='Commission' && $('commission_rationale').value=='') {
                return 'Please fill out required field for this action'; }
            if (action=='Permission' && ($('description').value=='' || $('contact_name').value=='' || $('contact_phone').value=='' || $('contact_email').value=='')) {
                return 'Please fill out required fields for this action'; }
            if ((action=='Retain: Permission' || action=='Retain: Copyright Analysis' || action=='Retain: Public Domain') && $('retain_rationale').value=='') {
                return 'Please fill out required field for this action'; }
            return 'success';
        }
    },

    '.do_replacement_ask_yesno' : function(element) {
        element.onclick = function() {
            var id = this.id;
            id = id.replace(/\w+_/g,'');
            if (this.value == 'yes') {
                if ($('repl_ask_yes')) {
                    $('repl_ask_yes').style.display = 'block';
                }
            } else {
                if ($('repl_ask_yes')) {$('repl_ask_yes').style.display = 'none';    }
            }
        }
    },

    '.do_dscribe2_replacement_ask_q' : function(element) {
        element.onclick = function() {
            var id = this.id;
            id = id.replace(/\w+_/g,'');
            if (this.value == 'yes') {
                if ($('dscribe2_repl_ask_q_pane')) {
                    $('dscribe2_repl_ask_q_pane').style.display = 'block';
                }
            } else {
                if ($('dscribe2_repl_ask_q_pane')) {$('dscribe2_repl_ask_q_pane').style.display = 'none';    }
            }
        }
    },

    '.do_object_ask_dscribe2_yesno' : function(element) {
        element.onclick = function() {
            var id = this.id;
            id = id.replace(/\w+_/g,'');
            if (this.value == 'yes') {
                if ($('ask_dscribe2_yes')) {
                    $('ask_dscribe2_yes').style.display = 'inline';
                    //orig_q_ap.setrole('dscribe2');
                    //orig_q_ap.show();
                    //$('orig_q_addpanel').scrollIntoView();
                }
            } else {
                if ($('ask_dscribe2_yes')) {
                    $('ask_dscribe2_yes').style.display = 'none';   }
            }
        }
    },

    '.do_object_rationale' : function(element) {
        element.onchange = function () {
            var response;
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var object_id = $('oid').value;
            var field = this.name;
            var url = $('server').value+'materials/update_object/'+course_id+'/'+material_id;
            var val = this.value;
            url += '/'+object_id;

            var fb = $('feedback');
            new Ajax(url, { method: 'post', postBody: 'field='+field+'&val='+val, update: fb }).request();
        }
    },

    '.do_update_description' : function(element) {
        element.onchange = function () {
            var response;
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var object_id = $('oid').value;
            var field = this.name;
            var url = $('server').value+'materials/update_object/'+course_id+'/'+material_id;
            var val = this.value;
            url += '/'+object_id;

            var fb = $('feedback');
            new Ajax(url, { method: 'post', postBody: 'field='+field+'&val='+val, update: fb }).request();
        }
    },

    '.do_update_contact' : function(element) {
        element.onchange = function () {
            var response;
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var object_id = $('oid').value;
            var field = this.name;
            var val = escape(this.value);
            var url = $('server').value+'materials/update_contact/'+course_id+'/'+material_id+'/'+object_id;


            var fb = $('feedback');
            var response;

            new Ajax(url, { method: 'post',
                            postBody: 'field='+field+'&val='+val,
                            update: fb,
                            onComplete:function() {
                                response = fb.innerHTML;
                                if (response=='success') {
                                    //url = $('server').value+'materials/askforms/'+course_id+'/'+material_id;
                                    //window.location.replace(url);
                                } else {
                                    alert(response);
                                }
                            }
                          }).request();
        }
    },

    '.do_object_cp_update' : function(element) {
        element.onchange = function () {
            var val = this.value;
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var object_id = this.name.replace(/copy_\w+_/g,'');
            var field = this.name.replace(/copy_/g,'');
            field = field.replace(/_\d+$/g,'');
            var url = $('server').value+'materials/update_object_copyright/'+
                object_id+'/original';
	    var fb = $('feedback');
	    new Ajax(url, {   method: 'post', postBody: 'field='+field+'&val='+val, update: fb}).request();
        }
    },


    '.do_ask_object_update' : function(element) {
        element.onchange = function () {
            var response;
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var object_id = this.id.replace(/\w+_/g,'');
            var field = this.name.replace(/_\d+$/g,'');
            var val = this.value;

            if (field == 'who_owns') {
                object_id = this.id;
                object_id = object_id.replace(/\w+_\w+_/g,'');
                field = 'other_copyholder';
                val = '';
            }
            if (field == 'unique') { field = 'is_unique'; }

            var url = $('server').value+'materials/update_object/'+course_id+'/'+material_id+'/'+object_id;
	    var fb = $('feedback');
            new Ajax(url, { method: 'post', postBody: 'field='+field+'&val='+val, update: fb }).request();
        }
    },

    '.do_object_status_update' : function(element) {
        element.onclick = function () {
            var val = (this.value).toLowerCase();
            val = (val == 'save for later') ? 'in progress' : val;
            val = (val == 'send to dscribe') ? 'done' : val;
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var view = $('view').value;
            var object_id = this.name.replace(/status_/g,'');
            var url = $('server').value+'materials/update_object/'+course_id+'/'+material_id+'/'+object_id;
            var fb = $('feedback');
            var response;
            new Ajax(url, { method: 'post',
                            postBody: 'field=ask_status&val='+val,
                            update: fb,
                            onComplete:function() {
                                response = fb.innerHTML;
                                if (response=='success') {
                                    url = $('server').value+'materials/askforms/'+
                                        course_id+'/'+material_id+'/'+view;
                                    window.location.replace(url);
                                } else {
                                    alert(response);
                                }
                            }
                          }).request();
        }
    },

    '.do_object_question_update' : function(element) {
        element.onchange = function () {
            var val = this.value;
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var object_id = this.name.replace(/q_/g,'');
            var question_id = object_id;
            object_id = object_id.replace(/_\d+$/g,'');
            question_id = question_id.replace(/^\d+_/g,'');
            var url = $('server').value+'materials/update_object_question/'+object_id+'/'+question_id;
	    var fb = $('feedback');
            new Ajax(url, { method: 'post', postBody: 'answer='+val, update: fb }).request();
        }
    },

    '.do_askform_yesno' : function(element) {
        element.onclick = function() {
            var id = this.id;
            id = id.replace(/\w+_/g,'');
            if ($('other_'+id) && this.value == 'no') {
                $('other_'+id).style.display = 'block';
            }
            if ($('other_'+id) && this.value == 'yes') {
                $('other_'+id).style.display = 'none';
            }
        }
    },

    '.do_askform_suityesno' : function(element) {
        element.onclick = function() {
            var id = this.id;
            id = id.replace(/\w+_/g,'');
            if (this.value == 'yes') {
                if ($('suit_yes_other_'+id)) {
                    $('suit_yes_other_'+id).style.display = 'block';
                }
                if ($('suit_no_other_'+id)) { $('suit_no_other_'+id).style.display = 'none';}
            } else {
                if ($('suit_no_other_'+id)) {
                    $('suit_no_other_'+id).style.display = 'block';
                }
                if ($('suit_yes_other_'+id)) { $('suit_yes_other_'+id).style.display = 'none';       }
            }
        }
    },

    '.do_askform_whoyesno' : function(element) {
        element.onclick = function() {
            var id = this.id;
            id = id.replace(/\w+_\w+_/g,'');
            if (this.value == 'yes') {
                if ($('who_yes_other_'+id)) {
                    $('who_yes_other_'+id).style.display = 'block';
                }
                if ($('who_no_other_'+id)) {
                    $('who_no_other_'+id).style.display = 'block';
                }
            } else {
                if ($('who_no_other_'+id)) {
                    $('who_no_other_'+id).style.display = 'block';
                }
                if ($('who_yes_other_'+id)) { $('who_yes_other_'+id).style.display = 'none'; }
            }
        }
    },

    // replacement form
    '.do_replacement_update' : function(element) {
        element.onchange = function () {
            var fb = $('feedback');
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var repl_id =  ($('rid')) ?  $('rid').value : this.name.replace(/^\w+_/g,'');
            var object_id = ($('oid')) ? $('oid').value : $('oid-'+repl_id).value;
            var field = this.name.replace(/_\d+/g,'');
            var val = this.value;

            if (field=='rep_ok') { field = 'suitable'; }
            if (field == 'notsuitable') { field = 'unsuitable_reason'; }

            var url = $('server').value+'materials/update_replacement/'+course_id+'/'+material_id+'/'+object_id+'/'+repl_id;
            new Ajax(url, { method: 'post', postBody: 'field='+field+'&val='+val, update: fb }).request();
        }
    },

    '.do_add_replacement_comment': function(element) {
        element.onclick = function(e) {
            new Event(e).stop();
            var object_id = $('rid').value;
	    var url = $('server').value+'materials/add_object_comment/'+object_id;

            var comments = escape($('repl_comments').value);
            if (comments == '') {
		alert('Please enter a comment');
            } else {
		var fb = $('feedback');
		var response;
                var once = true;
                url += '/replacement';

		new Ajax(url,
			 {
                             method: 'post',
                             postBody: 'comments='+comments,
                             update: fb,
			     onComplete:function() {
				 response = fb.innerHTML;
                                 if (once) {
				     if (response=='success') {
                                         var tr = new Element('tr');
                                         var td1 = new Element('td').setText(unescape(comments));
                                         var td2 = new Element('td').setText($('user').value);
                                         var td3 = new Element('td').setText('Today');
                                         tr.adopt(td1); tr.adopt(td2); tr.adopt(td3);
                                         tr.injectTop( $('replcomments') );
                                         $('repl_comments').value = '';
                                         if ($('noreplcomments')) { $('noreplcomments').remove(); }
                                         if($('repl_comment_added')){
                                             var div = $('repl_comment_added').setStyles({ display:'block', opacity: 1 });
                                             var fx = new Fx.Style(div, 'opacity', {duration: 5000 } ).addEvent("onComplete", function() {
                                                 var hidediv = $('repl_comment_added').setStyles({display:'none'}); });
                                             fx.start(0);
                                         }
				     } else {
					 alert(response);
				     }
                                     once = false;
                                 }
			     }}).request();
            }
        }
    },

    '.do_add_replacement_question': function(element) {
        element.onclick = function(e) {
            new Event(e).stop();
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var object_id = $('rid').value;
	    var url = $('server').value+'materials/add_object_question/'+course_id+'/'+material_id+'/'+object_id;
            var role = $('replrole').value;

            var qs = escape($('repl_question').value);
            if (qs == '') {
		alert('Please enter a question');
            } else {
		var fb = $('feedback');
		var response;
                var once = true;
                url += '/'+role+'/replacement';

		new Ajax(url,
			 {
                             method: 'post',
                             postBody: 'question='+qs,
                             update: fb,
			     onComplete:function() {
				 response = fb.innerHTML;
                                 if (once) {
				     if (response=='success') {
                                         var tr = new Element('tr');
                                         var td1 = new Element('td').setText(role);
                                         var td2 = new Element('td').setText(unescape(qs));
                                         var td3 = new Element('td').setText('No answer');
                                         var td4 = new Element('td').setText($('user').value);
                                         var td5 = new Element('td');
                                         var td6 = new Element('td').setText('Today');
                                         var td7 = new Element('td').setText('Today');
                                         tr.adopt(td1); tr.adopt(td2); tr.adopt(td3); tr.adopt(td4);
                                         tr.adopt(td5); tr.adopt(td6); tr.adopt(td7);
                                         tr.injectTop( $('replqs') );
                                         /* if (role=='instructor') {
                                            $('ask_yes').checked=true;
                                            $('ask_no').checked=false;
                                            $('ask_yes').style.display='block';
                                            $('repl_ask_yes').style.display='block';
					    } */
                                         $('repl_question').value = '';
                                         if($('noreplquestions')) { $('noreplquestions').remove(); }
                                         if($('repl_question_conf')){
                                             var div = $('repl_question_conf').setStyles({ display:'block', opacity: 1 });
                                             var fx = new Fx.Style(div, 'opacity', {duration: 5000 } ).addEvent("onComplete", function() {
                                                 var hidediv = $('repl_question_conf').setStyles({display:'none'});
                                                 if ($('dscribe2_repl_ask_q_pane')) {$('dscribe2_repl_ask_q_pane').style.display = 'none';}
                                                 $('dscribe2_repl_ask_q_yes').checked=false;
                                                 $('dscribe2_repl_ask_q_no').checked=true; });
                                             fx.start(0);
                                         }
				     } else {
					 alert(response);
				     }
                                     once = false;
                                 }
			     }}).request();
            }
        }
    },

    '.do_replacement_question_update' : function(element) {
        element.onchange = function () {
            var newstatus;
            var val = this.value;
            if (val == '') {
                newstatus = '/new';
            } else {
                newstatus = '/done';
            }
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var object_id = this.name.replace(/q_/g,'');
            var question_id = object_id;
            object_id = object_id.replace(/_\d+$/g,'');
            question_id = question_id.replace(/^\d+_/g,'');
            var url = $('server').value+'materials/update_object_question/'+object_id+'/'+question_id+'/replacement'+newstatus;
	    var fb = $('feedback');
            new Ajax(url, { method: 'post', postBody: 'answer='+val, update: fb }).request();
        }
    },

    '.do_replacement_cp_update' : function(element) {
        element.onchange = function () {
            var val = this.value;
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var object_id = this.name.replace(/copy_\w+_/g,'');
            var field = this.name.replace(/copy_/g,'');
            field = field.replace(/_\d+$/g,'');
            var url = $('server').value+'materials/update_object_copyright/'+
                object_id+'/replacement';
	    var fb = $('feedback');
	    new Ajax(url, {   method: 'post', postBody: 'field='+field+'&val='+val, update: fb}).request();
        }
    },

    '.do_replacement_status_update' : function(element) {
        element.onclick = function () {
            var val = (this.value).toLowerCase();
            val = (val == 'save for later') ? 'in progress' : val;
            val = (val == 'send to dscribe') ? 'done' : val;
            var course_id = $('cid').value;
            var material_id = $('mid').value;
            var view = $('view').value;
            var repl_id = this.name.replace(/status_/g,'');
            var object_id = ($('oid')) ? $('oid').value : $('oid-'+repl_id).value;

            var url = $('server').value+'materials/update_replacement/'+course_id+'/'+material_id+'/'+object_id+'/'+repl_id;
	    var fb = $('feedback');
            var response;
            new Ajax(url, {
                method: 'post',
                postBody: 'field=ask_status&val='+val,
                update: fb,
                onComplete:function() {
                    response = fb.innerHTML;
                    if (response=='success') {
                        url = $('server').value+'materials/askforms/'+
                            course_id+'/'+material_id+'/'+view;
                        window.location.replace(url);
                    } else {
                        alert(response);
                    }
                }
            }).request();
        }
    },

    // hide and show add panel
    '.do_show_hide_panel' : function (element) {
        element.onclick = function() {
            var panel = $('addpanel');
            var disp = panel.style.display;
            panel.style.display = (disp=='none') ? 'block' : 'none';
        }
    },

    // ASKFORM actions
    '#questions_to' : function (element) {
        element.onchange = function() {
            var cid = $('cid').value;
            var mid = $('mid').value;
            var view = $('view').value;
            var val = this.value;
            view = (val=='instructor') ? 'provenance' : 'general';
            url = $('server').value+'materials/askforms/'+cid+'/'+mid+'/'+view+'/'+val;
            window.location.replace(url);
        }
    },
    '#response_type' : function (element) {
        element.onchange = function() {
            var cid = $('cid').value;
            var mid = $('mid').value;
            var val = this.value;
            url = $('server').value+'materials/askforms/'+cid+'/'+mid+'/aitems/dscribe2/'+val;
            window.location.replace(url);
        }
    },

    '.do_d2_claim_update' : function(element) {
        element.onchange = function () {
            var cid = $('cid').value;
            var mid = $('mid').value;
            var fb = $('feedback');
            var url = $('server').value+'materials/';

            var field = this.name.replace(/^\d+_\w+_\d+_(\w+)$/,"$1");
            var clm_id = this.name.replace(/^\d+_\w+_(\d+)_\w+$/,"$1");
            var clm_type = this.name.replace(/^\d+_(\w+)_\d+_\w+$/,"$1");
            var object_id = this.name.replace(/^(\d+)_\w+_\d+_\w+$/,"$1");
            var val = this.value;

            // update question answer
            if (field != 'status') {
                url = url+'update_object_claim/'+cid+'/'+mid+'/'+object_id+'/'+clm_type+'/'+clm_id;
                new Ajax(url, { method: 'post', postBody: 'field='+field+'&val='+val, update: fb}).request();
            }
        }
        element.onclick = function () {
            var cid = $('cid').value;
            var mid = $('mid').value;
            var view = $('view').value;
            var fb = $('feedback');
            var url = $('server').value+'materials/';

            var field = this.name.replace(/^\d+_\w+_\d+_(\w+)$/,"$1");
            var clm_id = this.name.replace(/^\d+_\w+_(\d+)_\w+$/,"$1");
            var clm_type = this.name.replace(/^\d+_(\w+)_\d+_\w+$/,"$1");
            var object_id = this.name.replace(/^(\d+)_\w+_\d+_\w+$/,"$1");
            var val = this.value;

            if (field=='status') {
                val = (val.toLowerCase() == 'save for later') ? 'in progress' : val;
                val = (val.toLowerCase() == 'send to dscribe') ? 'done' : val;
                val = (val.toLowerCase() == 'send to legal & policy review') ? 'ip review' : val;
                val = (val.toLowerCase() == 'send to commission review') ? 'commission review' : val;
                url = url+'update_object_claim/'+cid+'/'+mid+'/'+object_id+'/'+clm_type+'/'+clm_id;

                var check = this.validate(object_id, clm_id, clm_type, field, val);
                if (check != 'success') { alert(check); return false; }

                new Ajax(url, { method: 'post', postBody:'field='+field+'&val='+val, update: fb,
                                onComplete: function() {
                                    response = fb.innerHTML;
                                    if (response=='success') {
                                        if(field=='status') {
                                            if (val != 'in progress')
                                            {
                                                // at this time, if the val is not the "in progress one"
                                                // clear the ask d2 setting to 'No'
                                                url = $('server').value +'materials/update_object/'+cid+'/'+ mid+'/'+object_id;
                                                new Ajax(url, { method: 'post', postBody: 'field=ask_dscribe2&val=no', update: fb}).request();
                                            }
                                        }

                                        // refresh the page

                                        url = $('server').value+'materials/askforms/'+cid+'/'+mid+'/'+view;
                                        window.location.replace(url);
                                    } else {
                                        alert(response);
                                    }
                                } }).request();
            }
        }
        element.validate = function(oid, clm_id, clm_type, field, val) {
            var check = 'success';
            if (clm_type == 'fairuse' && field=='status') {
                var ta = document.getElementsByName(oid+'_fairuse_'+clm_id+'_additional_rationale')[0];
                if (ta.value=='' && val=='ip review') {
                    check = 'You must provide additional rationale in order to send the Content Object to the LPR team';
                }
            }
            if (clm_type == 'retain' && field=='status') {
                var ta = document.getElementsByName(oid+'_retain_'+clm_id+'_comments')[2];
                if (ta.value=='' && val=='ip review') {
                    check = 'You must provide additional rationale or comments in order to send the Content Object to the LPR team';
                }
            }
            if (clm_type == 'commission' && field=='status') {
                var ta = document.getElementsByName(oid+'_commission_'+clm_id+'_comments')[1];
                if (ta.value=='' && val=='commission review') {
                    check = 'You must provide additional rationale in order to send the Content Object to the Commission review team';
                }
            }
            if (clm_type == 'permission' && field=='status') {
                var idx = ($('info_sufficient_no_'+clm_id).style.display=='none') ? 0 : 1;
                var notapproved = document.getElementsByName(oid+'_permission_'+clm_id+'_approved')[1]; // no value
                if (idx==0 && notapproved.checked && $('response_received_yes_'+clm_id).style.display=='none') {
                    return 'Cannot submit to dscribe until we receive word from publishers';
                }
                var ta = document.getElementsByName(oid+'_permission_'+clm_id+'_comments')[idx];
                var act = document.getElementsByName(oid+'_permission_'+clm_id+'_action')[idx];
                if (ta.value=='' || act.value=='None') { check = 'Please recommend an action and a reason why...'}
            }
            return check;
        }
    },

    '.do_d2_askform_yesno' : function(element) {
        element.onclick = function () {
            var id = this.name.replace(/^\d+_\w+_(\d+)_\w+$/,"$1");
            var field = this.name.replace(/^\d+_\w+_\d+_(\w+)$/,"$1");
            var clm_type = this.name.replace(/^\d+_(\w+)_\d+_\w+$/,"$1");
            var no_div = field+'_no_'+id;
            var yes_div = field+'_yes_'+id;
            var unsure_div = field+'_unsure_'+id;

            // show and hide logic for dscribes askforms
            if (this.value == 'yes') {
                if ($(no_div)) { $(no_div).style.display = 'none';      }
                if ($(unsure_div)) { $(unsure_div).style.display = 'none';      }
                if ($(yes_div)) { $(yes_div).style.display = 'block';}
            } else if (this.value =='no') {
                if ($(yes_div)) { $(yes_div).style.display = 'none';}
                if ($(unsure_div)) { $(unsure_div).style.display = 'none';      }
                if ($(no_div)) { $(no_div).style.display = 'block';     }
            } else {
                if ($(yes_div)) { $(yes_div).style.display = 'none';}
                if ($(no_div)) { $(no_div).style.display = 'none';      }
                if ($(unsure_div)) { $(unsure_div).style.display = 'block';     }
            }
        }
    },

    '.do_d2_question_update' : function(element) {
        element.onchange = function () {
            var fb = $('feedback');
            var url = $('server').value+'materials/';

            var val = this.value;
            val = (val.toLowerCase() == 'save for later') ? 'in progress' : val;
            val = (val.toLowerCase() == 'send to dscribe') ? 'done' : val;

            // update question answer
            if (val!='done' && val!='in progress') {
                var question_id = this.name.replace(/\w+_\d+_/g,'');
                var object_type = this.name.replace(/_\d+_\d+$/g,'');
                var object_id = this.name.replace(/^(original|replacement)_/g,'');
                object_id = object_id.replace(/_\d+$/g,'');

                url = url+'update_object_question/'+object_id+'/'+question_id+'/'+object_type;
                new Ajax(url, { method: 'post', postBody: 'answer='+val, update: fb }).request();
            }
        }
        element.onclick = function () {
            var cid = $('cid').value;
            var mid = $('mid').value;
            var view = $('view').value;
            var fb = $('feedback');
            var url = $('server').value+'materials/';

            var val = this.value;
            val = (val.toLowerCase() == 'save for later') ? 'in progress' : val;
            val = (val.toLowerCase() == 'send to dscribe') ? 'done' : val;

            // update status
            if (val=='done' || val=='in progress') {
                var object_id = this.name.replace(/^\w+_status_/g,'');
                var object_type = this.name.replace(/_status_\d+$/g,'');
                url = url+'update_questions_status/'+cid+'/'+mid+'/'+object_id+'/'+val+'/dscribe2/'+object_type;
                new Ajax(url, { method: 'get', update: fb,
                                onComplete: function() {
                                    response = fb.innerHTML;
                                    if (response=='success') {
                                        url = $('server').value+'materials/askforms/'+cid+'/'+mid+'/'+view;
                                        window.location.replace(url);
                                    } else {
                                        alert(response);
                                    }
                                } }).request();
            }
        }
    },

    '.do_curriculum_subject_update' : function(element) {
        element.onchange = function () {
            var fb = $('feedback');
            var url = $('server').value+'courses/';
            var school_id = this.value;
            var currbox = document.getElementById("curriculum_id");
            var subjbox = document.getElementById("subj_id");
            var once = true;

            // If they change back to nothing, (re-)disable the other boxes
            if (school_id == 0) {
                currbox.disabled = true;
                subjbox.disabled = true;
                return;
            }
            url += 'return_values_for_school/' + school_id;
            new Ajax(url,
                     {
                         method: 'post',
                         postBody: 'sid='+school_id,
                         update: fb,
                         onComplete:function(jsonObj, xml) {
                             response = Json.evaluate(jsonObj);
                             if (once) {
                                 if (response.success == true) {
                                     var options;
                                     var newstate;

                                     // Set up curriculum selection
                                     options = '';
                                     for (c in response.curriculum_data) {
                                         options += '<option value="' + c + '">' + response.curriculum_data[c] + '</option>';
                                     }
                                     if (options == '') {
                                         options = "<option value=\"\">Add a curriculum first</option>";
                                         newstate = true;
                                     } else {
                                         newstate = false;
                                     }
                                     currbox.setHTML(options);
                                     currbox.disabled = newstate;

                                     // Set up subject selection
                                     options = '';
                                     for (s in response.subject_data) {
                                         options += '<option value="' + s + '">' + response.subject_data[s] + '</option>';
                                     }
                                     if (options == '') {
                                         options = "<option value=\"\">Add a subject first</option>";
                                         newstate = true;
                                     } else {
                                         newstate = false;
                                     }
                                     subjbox.setHTML(options);
                                     subjbox.disabled = newstate;
                                 } else {
                                     alert(response.error_message);
                                 }
                                 once = false;
                             }
                         }}).request();
        }
    }
}

// Remove/Comment this if you do not wish to reapply Rules automatically
// on Ajax request.
//Ajax.Responders.register({
// onComplete: function() { EventSelectors.assign(Rules);}
//});
