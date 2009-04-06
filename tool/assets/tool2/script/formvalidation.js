/******************************************************************************
(C) www.dhtmlgoodies.com, February 2006

This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.	

Terms of use:
You are free to use this script as long as the copyright message is kept intact. However, you may not
redistribute, sell or repost it without our permission.

Thank you!

www.dhtmlgoodies.com
Alf Magne Kalleland

******************************************************************************/	
	
	
// Patterns
var formValidationMasks = new Array();
var formValidationMsgs = new Array();

formValidationMasks['email'] = /admin|\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/gi;	
formValidationMsgs['email'] = 'Please enter a valid email address.';	

formValidationMasks['numeric'] = /^[0-9]+$/gi;
formValidationMsgs['numeric'] = 'Please enter a valid numeric value.';	

formValidationMasks['zip'] = /^[0-9]{5}\-[0-9]{4}$/gi;
formValidationMsgs['zip'] = 'Please enter a valid zip code.';	

formValidationMasks['password'] = /^\w{6}\w*$/gi;	
formValidationMsgs['password'] = 'Must be six (6) characters or more';	
formValidationMasks['password_confirm'] = /^\w{6}\w*$/gi;	
formValidationMsgs['password_confirm'] = 'Must be six (6) characters or more';	

formValidationMasks['membername'] = /^(.*?)$/gi;	
formValidationMsgs['membername'] = 'Cannot be more than a 100 characters';	

var urlrgx = "(?:http://(?:(?:(?:(?:(?:[a-zA-Z\\d](?:(?:[a-zA-Z\\d]|-)*[a-zA-Z\\d])?)\\.)*(?:[a-zA-Z](?:(?:[a-zA-Z\\d]|-)*[a-zA-Z\\d])?))|(?:(?:\\d+)(?:\\.(?:\\d+)){3}))(?::(?:\\d+))?)(?:/(?:(?:(?:(?:[a-zA-Z\\d$\\-_.+!*'(),]|(?:%[a-fA-F\\d]{2}))|[;:@&=])*)(?:/(?:(?:(?:[a-zA-Z\\d$\\-_.+!*'(),]|(?:%[a-fA-F\\d]{2}))|[;:@&=])*))*)(?:\\?(?:(?:(?:[a-zA-Z\\d$\\-_.+!*'(),]|(?:%[a-fA-F\\d]{2}))|[;:@&=])*))?)?)";
formValidationMasks['httpurl'] = new RegExp(urlrgx, 'gi');
formValidationMsgs['httpurl'] = 'Please enter a URL (start with \'http://\')';	


var formElementArray = new Array();

function validateInput(e,inputObj)
{
	if(!inputObj)inputObj = this;		
	var inputValidates = true;
	var msg = "";

	if(formElementArray[inputObj.name]['required'] && 
	   inputObj.tagName=='INPUT' && inputObj.value.length==0) {
	   inputValidates = false;
	   msg = 'This information is required';
	}

	else if(formElementArray[inputObj.name]['required'] && 
       		inputObj.tagName=='SELECT' && inputObj.selectedIndex==0) {
	   inputValidates = false;
	   msg = 'This is information is required';
	}

	else if(formElementArray[inputObj.name]['mask']) {
	  		if (!inputObj.value.match(formValidationMasks[formElementArray[inputObj.name]['mask']])) {
			  	inputValidates = false; 
	   		    msg=formValidationMsgs[formElementArray[inputObj.name]['mask']];
		   	} else {	
				if (inputObj.id == 'password_confirmation') {
					var orig = document.getElementById('password').value;
					if (orig != inputObj.value) {
			  			inputValidates = false; 
			   		    msg= 'This value must match the one above';
					}
				}
			}
	}

	else if(formElementArray[inputObj.name]['freemask']) {
		var tmpMask = formElementArray[inputObj.name]['freemask'];
		var tmpMask2 = formElementArray[inputObj.name]['freemask'];
		tmpMask = tmpMask.replace(/-/g,'\\-');
		tmpMask = tmpMask.replace(/S/g,'[A-Z]');
		tmpMask = tmpMask.replace(/N/g,'[0-9]');
		tmpMask = eval("/^" + tmpMask + "$/gi");
		if(!inputObj.value.match(tmpMask)) {
		   inputValidates = false;
	   	   msg = 'Please use a valid format: '+tmpMask2;
		}
	}	
	
	else if(formElementArray[inputObj.name]['regexpPattern']){
		var tmpMask = eval(formElementArray[inputObj.name]['regexpPattern']);
		if(!inputObj.value.match(tmpMask)) {
			inputValidates = false;
			msg =  (formValidationMsgs[inputObj.id]=='') 
				? 'Please enter a valid value' 
				: formValidationMsgs[inputObj.id];
		}
	}
	
	
	if(!formElementArray[inputObj.name]['required'] && 
			inputObj.value.length==0 && inputObj.tagName=='INPUT') {
			inputValidates = true;
	}

	var elem = document.getElementById('validatediv_'+inputObj.id);	
	if(inputValidates){
		elem.className= 'validInput';
		
		while ( elem.hasChildNodes() ) { elem.removeChild(elem.firstChild); }
		var d = document.createElement('div');
		d.innerHTML = '<small style="color:white;">passed</small>'; 
		elem.appendChild(d);
	}else{
		elem.style.width = (inputObj.offsetWidth > msg.length) 
						 ? inputObj.offsetWidth + 'px'
						 : (msg.length + 3) + 'px';
		elem.className= 'invalidInput';

		while ( elem.hasChildNodes() ) { elem.removeChild(elem.firstChild); }
		var d = document.createElement('div');
		d.innerHTML = '<small>'+msg+'</small>'; 
		elem.appendChild(d);
	}
}

function isFormValid()
{
	var divs = document.getElementsByTagName('DIV');
	for(var no=0;no<divs.length;no++){
		if(divs[no].className=='invalidInput') {
			alert('Please make sure fields are filled in correctly.');
			return false;
		}
	}
	return true;	
}

function initFormValidation()
{
	var inputFields = document.getElementsByTagName('INPUT');
	var selectBoxes = document.getElementsByTagName('SELECT');
	
	var inputs = new Array();
	
	for(var no=0;no<inputFields.length;no++){
		if (inputFields[no].getAttribute('validate')) {
			inputs[inputs.length] = inputFields[no];
		}
	}	
	for(var no=0;no<selectBoxes.length;no++){
		if (inputFields[no].getAttribute('validate')) {
			inputs[inputs.length] = selectBoxes[no];
		}
	}
	
	for(var no=0;no<inputs.length;no++) {
		var required = inputs[no].getAttribute('required');
		if(!required)required = inputs[no].required;		
		
		var mask = inputs[no].getAttribute('mask');
		if(!mask)mask = inputs[no].mask;
		
		var freemask = inputs[no].getAttribute('freemask');
		if(!freemask)freemask = inputs[no].freemask;
		
		var regexpPattern = inputs[no].getAttribute('regexpPattern');
		if(!regexpPattern)regexpPattern = inputs[no].regexpPattern;
		
		var div = document.createElement('DIV');
		div.id = 'validatediv_'+inputs[no].id;
		div.className = 'invalidInput';
		div.innerHTML = '<small style="color: white;">passed</small>';
		//inputs[no].parentNode.insertBefore(div,inputs[no]);
		insertAfter(inputs[no].parentNode, div, inputs[no]);	
		
		inputs[no].onblur = validateInput;
		inputs[no].onchange = validateInput;
		inputs[no].onpaste = validateInput;
		inputs[no].onkeyup = validateInput;
		
		formElementArray[inputs[no].name] = new Array();
		formElementArray[inputs[no].name]['mask'] = mask;
		formElementArray[inputs[no].name]['freemask'] = freemask;
		formElementArray[inputs[no].name]['required'] = required;
		formElementArray[inputs[no].name]['regexpPattern'] = regexpPattern;
		//validateInput(false,inputs[no]);
	}	
}
function insertAfter(parent, node, referenceNode) {
	parent.insertBefore(node, referenceNode.nextSibling);
}
window.onload = initFormValidation;
