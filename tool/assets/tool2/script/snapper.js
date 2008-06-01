var autoCaptureEnabled = true;
var autoSendEnabled = false;

function captureClipboard()
{
	// check for image on the clipboard
	var newImageExists = document.clipboard.checkClipboard();

	// update the display	
	var report = (String) (document.clipboard.getReport());
	$("#status").html(report);
		
	if (!newImageExists) return false;

	// get the image into the form field
	var image = (String) (document.clipboard.getBase64Jpeg());
	$("#image").val(image);

	return true;
}

function autoCapture()
{
	if (!autoCaptureEnabled) return;

	if (captureClipboard())
	{
		if (autoSendEnabled)
		{
			sendImage();
			return;
		}
	}

	setTimeout(autoCapture, 1000);
}

function sendImage()
{
	var cid = $('#cid').val();
	var mid = $('#mid').val();
	var url = $('#server').val()+'materials/snapper/'+cid+'/'+mid+'/submit';
	var check = validate();
	if (check != 'success') { sendDone(check); return false; }

	var data =
	{
		image: $("#image").val(),
		type: $("#type").val(),
		subtype_id: $("#subtype_id").val(),
		location: $("#location").val(),
	};
	$.post(url, data, sendDone, 'json');
}

function validate()
{
	if ($("#image").val() == '') {
			return {success: 'false', msg:'Please capture an image and fill out the meta info below before submitting.'};
	}
	if ($("#type").val() == 'object' && $('#subtype_id').val()=='22') {
			return {success: 'false', msg:'Please specify the content type.'};
	}
	if ($("#location").val() == '') {
			return {success: 'false', msg:'Please specify the slide or page number for this image.'};
	}
	return 'success';
}

function sendDone(data, textStatus)
{
	if (data.success=='true') {
			document.clipboard.clear();
			$("#status").html($("#status").html()+": sent");
			$("#image").val('');
	
			if (autoCaptureEnabled) { setTimeout(autoCapture, 1000); }

      parent.window.location.replace(data.url);
			
	} else {
			$("#status").html('<p style="padding: 2px; font-size:small; background: #FBE3E4; color: #D12F19; border-color: #FBC2C4;">'+data.msg+'</p>');
	}
}

function setAutoSend(e)
{
	autoSendEnabled = this.checked;
}

function setAutoCapture(e)
{
	autoCaptureEnabled = this.checked;
	if (autoCaptureEnabled)
	{
		setTimeout(autoCapture, 1000);
	}
}

function showHideContenttype() 
{
		if (this.value == 'object') {
				$('#contenttype').show('fast');
		} else {
				$('#contenttype').hide('fast');
		}
		$('#type').val(this.value);
}


$(document).ready(function()
{
	$("#snap").click(captureClipboard);
	$("#save").click(sendImage);
	$("#aSend").change(setAutoSend);
	$("#aCapture").change(setAutoCapture);
	$(".snapper_captype").click(showHideContenttype);
	
	if (autoCaptureEnabled)
	{
		setTimeout(autoCapture, 1000);
	}
});
