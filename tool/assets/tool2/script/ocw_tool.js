/**         
 * Load a spinning image next to a recently modified element
 * to indicate activity while the data is being proccessed.
 * The initial display for the image is set to none.
 */
function load_spinner(id)
{
    var spinnerid = 'spinner';
    var el = document.getElementById(id);
    var container = el.parentNode;
    var imgurl = document.getElementById('imgurl').value+'/spinner.gif';
    var img = document.createElement('img');
    img.setAttribute('id', spinnerid);
    img.setAttribute('src', imgurl);
    img.setAttribute('style', 'display:none;');
    container.appendChild(img);
    return spinnerid;
}


function showAddComment(num) {
    var commentItem = document.getElementById('addComment'+num);
    if (commentItem != null) {
        commentItem.style.display = "";
    }
    var triggerItem = document.getElementById('onComment'+num);
    if (triggerItem != null) {
        triggerItem.style.display = "none";
    }
}
function orderBy(newOrder) {
    if (document.voteform.sortorder.value == newOrder) {
        if (newOrder.match("^.* desc$")) {
            document.voteform.sortorder.value = newOrder.replace(" desc","");
        } else {
            document.voteform.sortorder.value = newOrder;
        }
    } else {
        document.voteform.sortorder.value = newOrder;
    }
    document.voteform.submit();
    return false;
}


// These are the voting functions
function setAnchor(num) {

    document.voteform.action += "#anchor"+num;
    document.voteform.submit();
    return false;
}


// javascript code lifted from http://www.somacon.com/p117.php
// looked simple enough to copy without too much risk
function SetAllCheckBoxes(FormName, FieldName, CheckValue)
{
	if(!document.forms[FormName])
		return;
	var objCheckBoxes = document.forms[FormName].elements[FieldName];
	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = CheckValue;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = CheckValue;
}
