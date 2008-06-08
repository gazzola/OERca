var autoCaptureEnabled = true;
var autoSendEnabled = false;

function captureClipboard()
{
	// check for image on the clipboard
	var newImageExists = document.clipboard.checkClipboard();

	// update the display	
	var report = (String) (document.clipboard.getReport());
	$('snap_status').setHTML(report);
		
	if (!newImageExists) return false;

	// get the image into the form field
	var image = (String) (document.clipboard.getBase64Jpeg());
	$('snap_image').value = image;

	return true;
}

function autoCapture()
{
	if (!autoCaptureEnabled) return;

	if (captureClipboard())
	{
		if (autoSendEnabled) { sendImage(); return; }
	}

	setTimeout(autoCapture, 1000);
}

function sendImage()
{
	var cid = $('cid').value;
	var mid = $('mid').value;
	var url = $('server').value+'materials/snapper/'+cid+'/'+mid+'/submit';
	var check = validate();
	if (check != 'success') { sendDone(check); return false; }

	$('snapper-form').send({onComplete: function(jsonObj,xml){ sendDone(Json.evaluate(jsonObj)); } });
}

function validate()
{
	if ($("snap_image").value == '') {
			return {success: 'false', msg:'Please capture an image and fill out the meta info below before submitting.'};
	}
	if ($("snap_location").value == '') {
			return {success: 'false', msg:'Please specify the slide or page number for this image.'};
	}
	return 'success';
}

function sendDone(data, textStatus)
{
	if (data.success=='true') {
			document.clipboard.clear();
			$("snap_status").setHTML($("snap_status").innerHTML+": sent");
			$("snap_image").value='';
	
      window.location.replace(data.url);
			if (autoCaptureEnabled) { setTimeout(autoCapture, 1000); }
			
	} else {
			$("snap_status").setHTML('<p style="padding: 2px; font-size:small; background: #FBE3E4; color: #D12F19; border-color: #FBC2C4;">'+data.msg+'</p>');
	}
}

function setAutoSend(e) { autoSendEnabled = this.checked; }

function setAutoCapture(e)
{
	autoCaptureEnabled = this.checked;
	if (autoCaptureEnabled) { setTimeout(autoCapture, 1000); }
}

function setType() { $('snap_type').value=this.value; }


var SNAP = {
snapper: function() {
	if ($('do_open_uploadco_pane')) {
		$("snap").addEvent('click',captureClipboard);
		$("snap_save").addEvent('click',sendImage);
		$("snap_aCapture").addEvent('change',setAutoCapture);
		$("snap_aCaptureTypeObject").addEvent('change',setType);
		$("snap_aCaptureTypeSlide").addEvent('change',setType);
		if (autoCaptureEnabled) { setTimeout(autoCapture, 1000); }
	}
},
};
 window.addEvent('domready', SNAP.snapper);
