if ($('addpanel')) {
	var collapsible = new Fx.Slide($('addpanel'), { 
						duration: 500, 
						transition: Fx.Transitions.linear });
	collapsible.hide();
}	

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

