<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Recommended Action Decision (RAD) Form</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">

.endorsement
{
	background-color:orange;
	visibility:hidden; 
	display:none;
}

.action
{
	visibility:hidden; 
	display:none;
	background-color:yellow;
}

.copyright
{
	background-color:teal;
	visibility:hidden; 
	display:none;

}

.privacy
{
	background-color: green;
	visibility:hidden; 
	display:none;

}
	
.ver {
    font-family:Verdana, Arial, Helvetica, sans-serif;
    font-size:16px;
}

.shown
{
	display: block;
	visibility: visible;
}

</style>

<script type="text/javascript">
var pathTakenStack = new Array();
var alternateChoiceStack = new Array();

function show(id)
{
	document.getElementById(id).style.display = 'block';
	document.getElementById(id).style.visibility ='visible';
	pathTakenStack.push(id);
	window.location = '#bottom';
}

function disable(id)
{
	document.getElementById(id).style.display = 'none';
	document.getElementById(id).style.visibility ='hidden';
	alternateChoiceStack.push(id);
}

function undo()
{
	var step = pathTakenStack.pop();
	document.getElementById(step).style.display = 'none';
	document.getElementById(step).style.visibility ='hidden';
	
	var alt = alternateChoiceStack.pop();
	document.getElementById(alt).style.display = 'inline';
	document.getElementById(alt).style.visibility ='visible';	
	
	window.location = '#bottom';
}


</script>
</head>

<body class="ver">
<h2>Recommended Action Decision (RAD) Tree Navigator</h2>
<h4>Link back to <a href="https://open.umich.edu/wiki/">Open.Michigan wiki</a></h4>

<table border="1" cellpadding="5" cellspacing="5" bordercolor="#000000">
<tr>
<td><strong>Legend</strong></td>
<td style="background-color:orange">Endorsement</td>
<td style="background-color:teal">Copyright</td>
<td style="background-color:green">Privacy</td>
<td style="background-color:yellow">dScribe Action</td>
</tr>
</table>

<div id="step2" class="endorsement" style="display:block;visibility:visible" >

<p>
<b>2. </b>
Does the CO contain a word, logo, slogan, or product shape ("trademark") that identifies a particular company, product, organization, or person? (As examples, the word "Raytheon," the logo of the NBC peacock, the slogan "Can You Hear Me Now?," and the product design of the iPod.)</step>

</p>
<button type="button" id="yes_2" onClick="show('step3');disable('no_2')">Yes</button>
<button type="button"  id="no_2" onClick="show('step8');disable('yes_2')">No</button>
</div>

<div id="step3" class="endorsement" >
<p>
<b>3. </b>

Does it appear that the
trademark is used to
refer to, comment upon, or educate the
audience about the company or product
that is directly associated with the
trademark?</p>
<button type="button" id="yes_3" onClick="show('step16');disable('no_3')">Yes</button>
<button type="button"  id="no_3" onClick="show('step5');disable('yes_3')">No</button>
</div>

<div id="step5"  class="endorsement"> 
<p><b>5. </b>Does the use of the
trademark cause you to think
that there is an affiliation,
sponsorship, or endorsement of the
University of Michigan (or the professor)
by the company whose trademark
appears in the CO?
</p>
<button type="button" id="yes_5" onClick="show('step6');disable('no_5')">Yes</button>
<button type="button"  id="no_5" onClick="show('step16');disable('yes_5')">No</button>

</div>

<div id="step6"  class="action"> 
<p><b>6. </b>
Send to dScribe2
for review</p>
</div>

<div id="step8"  class="endorsement"> 
<p><b>8. </b>
Does the CO
 contain the face (or 
profile of a face) or name or
 voice that you recognize as a famous 
individual (a celebrity such as Elvis, a 
newsmaker such as Barack Obama, a 
famous scientist such as 
Einstein)?
</p>
<button type="button" id="yes_8" onClick="show('step9');disable('no_8')">Yes</button>
<button type="button"  id="no_8" onClick="show('step16');disable('yes_8')">No</button>

</div>

<div id="step9"  class="endorsement"> 
<p><b>9. </b>
Does the CO containing
 the famous face, name, or voice  
appear to refer to, comment upon, or
educate the audience about some 
aspect directly associated to that 
famous individual?
</p>
<button type="button" id="yes_9" onClick="show('step16');disable('no_9')">Yes</button>
<button type="button"  id="no_9" onClick="show('step11');disable('yes_9')">No</button>
</div>

<div id="step11"  class="endorsement"> 
<p><b>11. </b>
Does the use of the CO 
cause you to think that there is an 
af?liation, sponsorship, or endorsement 
of the University of Michigan (or the 
professor) by the individual who 
appears in the CO? 

</p>
<button type="button" id="yes_11" onClick="show('step12');disable('no_11')">Yes</button>
<button type="button"  id="no_11" onClick="show('step16');disable('yes_11')">No</button>
</div>

<div id="step12"  class="action"> 
<p><b>12. </b>
Send to dScribe2
for review</p>
</div>

<div id="step16"  class="copyright"> 
<p><b>16. </b>

Does the CO contain information 
indicating that it is in the public 
domain?
</p>
<button type="button" id="yes_16" onClick="show('step31');disable('no_16')">Yes</button>
<button type="button"  id="no_16" onClick="show('step18');disable('yes_16')">No</button>
</div>

<div id="step18"  class="copyright"> 
<p><b>18. </b>
Was the CO created by the instructor?
</p>
<button type="button" id="yes_18" onClick="show('step31');disable('no_18')">Yes</button>
<button type="button"  id="no_18" onClick="show('step20');disable('yes_18')">No</button>

</div>

<div id="step20"  class="copyright"> 
<p><b>20. </b>
Does the CO appear to be created by 
other UM faculty, staff or students?
</p>
<button type="button" id="yes_20" onClick="show('step21from20');disable('no_20')">Yes</button>
<button type="button"  id="no_20" onClick="show('step24');disable('yes_20')">No</button>
</div>

<div id="step21from20"  class="copyright"> 
<p><b>21. </b>
Does UM OER have permission to 
publish this CO?

</p>
<button type="button" id="yes_21from20" onClick="show('step31');disable('no_21from20')">Yes</button>
<button type="button"  id="no_21from20" onClick="show('step23from20');disable('yes_21from20')">No</button>
</div>

<div id="step23from20" class="action"> 
<p><b>23. </b>
Recommend an
action: Permission
</p><p>
(UM faculty, staff, or student-created CO)
</p>
</div>

<div id="step24"  class="copyright"> 

<p><b>24. </b>
Is the CO one of the 
following: a fact, an opinion, 
an idea, concept, or principle, a 
description or representation of a process, 
[procedure, function, system, method of 
operation], or discovery, a reference, a 
citation, a quotation/excerpt?
</p>
<button type="button" id="yes_24" onClick="show('step31');disable('no_24')">Yes</button>
<button type="button"  id="no_24" onClick="show('step26');disable('yes_24')">No</button>
</div>

<div id="step26"  class="copyright"> 
<p><b>26. </b>
Does the CO contain 
a photograph, illustration, 
artwork, chart, graph, audio, or video 
bearing a copyright notice of (or 
attribution to) a person or 
company outside UM?
</p>
<button type="button" id="yes_26" onClick="show('step21from26');disable('no_26')">Yes</button>

<button type="button"  id="no_26" onClick="show('step27');disable('yes_26')">No</button>
</div>

<div id="step21from26"  class="copyright">
<p><b>21. </b>Does UM OER have permission to 
publish this CO?
</p>
<button type="button" id="yes_21from26" onClick="show('step31');disable('no_21from26')">Yes</button>
<button type="button"  id="no_21from26" onClick="show('step23from26');disable('yes_21from26')">No</button>
</div>

<div id="step23from26"  class="action">
<p><b>23. </b>Recommend an
action: Search or Create </p>

<p>(3rd party-created CO)</p>
</div>

<div id="step27"  class="copyright"> 
<p><b>27. </b>
Does the object appear to be created 
by a person or company other than the 
educator?
</p>
<button type="button" id="yes_27" onClick="show('step28');disable('no_27')">Yes</button>
<button type="button"  id="no_27" onClick="show('step29');disable('yes_27')">No/Don't Know</button>
</div>

<div id="step28"  class="action"> 
<p><b>28. </b>

Recommend an 
action: Search or Create
</p>
</div>

<div id="step29"  class="action"> 
<p><b>29. </b>
Ask Faculty about CO origin
</p>
</div>

<div id="step31"  class="privacy"> 
<p><b>31. </b>
From the information (image, 
voice, name, facts, etc.) included in the 
CO, in its context, can you identify a 
particular individual?
</p>
<button type="button" id="yes_31" onClick="show('step32');disable('no_31')">Yes</button>

<button type="button"  id="no_31" onClick="show('step39');disable('yes_31')">No</button>
</div>

<div id="step32"  class="privacy"> 
<p><b>32. </b>
From the CO, in its context, can you 
identify the individual as a patient?
</p>
<button type="button" id="yes_32" onClick="show('step33');disable('no_32')">Yes</button>
<button type="button"  id="no_32" onClick="show('step34');disable('yes_32')">No</button>
</div>

<div id="step33"  class="action"> 
<p><b>33. </b>

Send to dScribe2 
for review
</p>
</div>

<div id="step34"  class="privacy"> 
<p><b>34. </b>
From the CO, in its context, can you 
identify the individual as a UM student?
</p>
<button type="button" id="yes_34" onClick="show('step35');disable('no_34')">Yes</button>
<button type="button"  id="no_34" onClick="show('step36');disable('yes_34')">No</button>
</div>

<div id="step35"  class="action"> 
<p><b>35. </b>

Send to dScribe2 
for review
</p>
</div>

<div id="step36"  class="privacy"> 
<p><b>36. </b>
Does the individual referenced in the
 CO appear to have consented to the 
photograph, or was this taken in a public
setting?
</p>
<button type="button" id="yes_36" onClick="show('step37');disable('no_36')">Yes</button>
<button type="button"  id="no_36" onClick="show('step38');disable('yes_36')">No</button>
</div>

<div id="step37"  class="privacy"> 
<p><b>37. </b>

Does OER have permission 
(explicit or implicit) from the individual 
referenced in the CO to use their identity 
or identi?able information?
</p>
<button type="button" id="yes_37" onClick="show('step40');disable('no_37')">Yes</button>
<button type="button"  id="no_37" onClick="show('step41');disable('yes_37')">No</button>
</div>

<div id="step38"  class="action"> 
<p><b>38. </b>
Recommend an
action: Retain
</p>
<p>* If yes to 16: [Retain: Public Domain]
(CO is in the public domain)<br>
* If yes to 18 or 21: [Retain: Permission]
(OER has permission to use CO)<br>

* If yes to 24: [Retain: Copyright Analysis]
(CO may not be protected under copyright law)</p>
</div>


<div id="step39"  class="action"> 
<p><b>39. </b>
Recommend an
action: Retain
</p>
<p>* If yes to 16: [Retain: Public Domain]
(CO is in the public domain)<br>
* If yes to 18 or 21: [Retain: Permission]
(OER has permission to use CO)<br>
* If yes to 24: [Retain: Copyright Analysis]
(CO may not be protected under copyright law)</p>
</div>

<div id="step40"  class="action"> 
<p><b>40. </b>
Recommend an
action: Retain
</p>
<p>* If yes to 16: [Retain: Public Domain]
(CO is in the public domain)<br>
* If yes to 18 or 21: [Retain: Permission]
(OER has permission to use CO)<br>
* If yes to 24: [Retain: Copyright Analysis]
(CO may not be protected under copyright law)</p>
</div>

<div id="step41"  class="action"> 
<p><b>41. </b>

Recommend an 
action: 
Permission, 
Search, or Create</p>

<p>
* From 20->21->31: [Permission]
(UM faculty, staff, or student-created CO)<br>
* From 26->21->31: [Search or Create]
(3rd party-created CO)
</p>
</div>

<a name="bottom"></a>
<p>
<button type="button" onClick="undo()">Undo last action</button>
<button type="button" onClick="location.reload(true)">Reset</button>
</p>

</body>
</html>