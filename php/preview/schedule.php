<?php
/*
 * Created on Apr 13, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
?>

<?php

$TOOL_NAME="Instructor";
$PAGE_NAME="Review and Export";

?>
<link href="../include/ocw_tool.css" rel="stylesheet" type="text/css"/>
<div id="topmenu">&nbsp;&nbsp;&nbsp; <a href="../dscribe/index.php">dScribe Home</a>&nbsp;| &nbsp;<a href="../dscribe/profile.php">Course &amp; Instructor Profiles</a>&nbsp; | &nbsp;<a href="../dscribe/copyright.php">Set Default Copyright</a>&nbsp; | &nbsp;<a href="../dscribe/materials.php">Prepare Course Materials</a> &nbsp; | &nbsp;Review for Export</div>
		<br/>

<div id="tool_content"><div style="text-align:left; margin-bottom:20px;  ">
	<div id="submenu" style="font-weight:normal; color:#929292">
		<div class="tab "><a href="course.php">Course Home</a></div><div class="tab">&nbsp;&nbsp;&nbsp;<a href="review_prof.php">Instructor</a>&nbsp;&nbsp;&nbsp;</div><div class="tab"><a href="syllabus.php">Syllabus</a></div><div class="tab active">Schedule</div><div class="tab"><a href="readings.php">Readings</a></div><div class="tab"><a href="assignments.php">Assignments</a></div><div class="tab"><a href="lectures.php">Lectures</a></div>
		
	</div>
 </div>    <div><div style="padding-left: 20px;">
<table cellspacing="0" cellpadding="0" border="0" style="width:100%;" >
    <tbody>
        <tr>
            <td valign="top">
            <h3 style="text-align: left; color: #000000">SI 514 Schedule
            </h3>       </td>
        </tr>
        <tr><td>
     
<table  id="schedule" cellspacing="0" cellpadding="0" border="0">
 
  <tr>
    <th width="50"><strong>#</strong></th>
    <th width="90">Time</th>
    <th width="60">Event Type</th>
    <th width="300">Description</th>
  </tr>
  <tr class="odd">
    <td><strong>1</strong></td>
    <td style="white-space: nowrap; padding: 2px 5px;"><p><strong>Jan 9, 2007</strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Lecture</td>
    <td>Broad Overview  -  This lecture is about setting the context for the rest of the semester; what is this all about? The W3C semantic web layer cake; what did the Scientific American article really talk about? What are the foundation technologies and who are the contributing communities? We will take a first look at the convergence of web technologies, markup language construction and manipulation efforts over the last few years. Where do these standards come from, and what are they driving toward?
      Then, what are some easily understandable examples of the application of these technologies and standards that we can use to help organize our understanding around?
      We will begin the discussion of the MIT OCW Project, its inception, goals and progress, the emergence of an OCW Consortium, and the S-OCW Project at UM. This will be placed in the larger context of discussions about Open Educational Resources, and the potentials the web presents for their use.</td>
  </tr>
  <tr class="even">
    <td><strong>2</strong></td>
    <td><p><strong>Jan 16, 2007</strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Lecture</td>
    <td>Diving into the technologies. Overview of XML Markup and brief intro to RDF  -  annotating information, adding info to info; XML for markup, why do this, where does it fit into the semantic web ideas? What is it being used for? What are XML's strengths, limitations; is it all we need for information reuse? Semantic data is potentially more reusable, and is about things in the ‘real' world, not just things in documents or databases. What does that mean? When Tim B-L says things like: “We're talking about (real?) things: XML: A registration document contains a single license number field.   RDF: A car has a unique license number”… what is he saying?
      
      Moving into detailed understanding of the context and practices of the MIT OCW Project.
      Interview with Anne Margulies, Director of the MIT OCW Project, by VTC.
      See, e.g., http://mitworld.mit.edu/video/208/</td>
  </tr>
  <tr class="odd">
    <td><strong>3</strong></td>
    <td><p><strong>Jan 23, 2007</strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Lecture</td>
    <td>RDF, the Basics  -  what is it and why is it needed? Here we go a bit further into what RDF really is. What is semantic data? How do we go about describing relationships in data, developing markup for metadata, and the RDF data model. The next level of RDF: RDFS  -  formalizing the data model and why.
      
      Projects  -  talking about projects for class and requirements for students. Discussing OCW Project at the University of Michigan and places where tagging systems might help, where UI of tools is important and how to improve, how to manage adoption by various populations, exploring applications of semantic technologies to the whole problem space of sustainable OCW (S-OCW).</td>
  </tr>
  <tr class="even">
    <td><strong>4</strong></td>
    <td><p><strong>Jan 30, 2007 </strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Lecture</td>
    <td>Deeper into RDF/S and intro to OWL - Spending more time with fundamentals of semantic markup, the RDF/S elements and then putting more of what we have learned into practice in analyzing and extending various RDF descriptions. Investigating the DC:, MM:, FOAF: and other namespaces. 
      
      What are the typologies used by the MIT OCW Project? How are they used? 
      
      See: http://ocw.mit.edu/OcwWeb/Global/AboutOCW/technology.htm</td>
  </tr>
  <tr class="odd">
    <td><strong>5</strong></td>
    <td><p><strong>Feb 6, 2007</strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Lecture</td>
    <td>Ontologies  -  what are they and how are they alike/different from things we are familiar with, e.g., taxonomies, relational databases and relational db entity diagrams; investigating OWL in more depth. 
      
      How are the tools we've been working with expressing ontologies? Where does a vocabulary get the power to express complex relationships?
      
      The vocabularies used by the MIT OCW Project as ontologies; Piggy Bank, and MusicBrainz as ontologies.</td>
  </tr>
  <tr class="even">
    <td><strong>6</strong></td>
    <td><p><strong>Feb 13, 2007</strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Lecture</td>
    <td>Tagging systems and semantic web ideas. How do Del.icio.us, Frapper, Flicker, or other tagging systems develop folksonomies. What would an RDF representation of tags look like? How can folksologies be built? What are examples? Discussion in relation to Web 2.0 ideas and student projects.
      
      Discussion of the Open Educational Resources movement. Educommons tools and their use in developing OCW sites.  Emerging tagging tools that use relationships  -  a Del.icio.us for RDF?
      Virtual Visit from David Willey of Utah State (tentative). 
      
      See: http://opencontent.org/blog/ and http://cosl.usu.edu/projects/educommons/</td>
  </tr>
  <tr class="odd">
    <td><strong>7</strong></td>
    <td><p><strong>Feb 20, 2007 </strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Lecture</td>
    <td>Tools for Ontology construction - Allowing enriched relationship descriptions; intro to how to make them with Protégé; who is using them (business examples, google, yahoo, etc). First real intro to Protégé as a tool is part of this lecture. -  writing your own vs. using an editor. More on Protégé, a GUI for model construction; tutorial on Protege. Using existing schemas, models, ontologies; building on others' work and seeing how it contributes to interoperability. Examples, lots of examples. Extending various ontologies some more. Installing SWOOP as an ontology editor and comparing to Protégé.
      Intro to using Protégé or SWOOP to codify any S-OCW taxonomies, object descriptions, or IMS models. What is gained?
      
      N. Noy and D. McGuinness, Ontology Development 101: A guide to Creating Your First Ontology, Stanford University, www.ksl.stanford.edu/people/dlm/papers/ontology101/ontology101-noy-mcguinness.html</td>
  </tr>
  <tr class="even">
    <td><strong>8</strong></td>
    <td><p><strong>Feb 27, 2007</strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Special event</td>
    <td>No Class.  Decompress.</td>
  </tr>
  <tr class="odd">
    <td><strong>9</strong></td>
    <td><p><strong>Mar 6, 2007 </strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Lecture</td>
    <td>Building a namespace for S-OCW  -  an example of constructing a useable namespace. What are the “things” that Sakai/CTools deals with? How would we characterize them as elements of a namespace using RDF, OWL and the tools we have investigated? What could we then use this namespace for? How could it help us extend the capabilities of Sakai/CTools? How would the MIT-OCW taxonomy fit into this namespace?
      Discussions with Sakai/CTools developers. 
      Student projects update  -  finalizing student project choices of in-depth investigation of tool/project and domain of their choice, that would allow them to show the class an interesting development of class ideas. Focus here is again on S-OCW.</td>
  </tr>
  <tr class="even">
    <td><strong>10</strong></td>
    <td><p><strong>Mar 13, 2007</strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Lecture</td>
    <td>More discussion of tagging systems and Web 2.0 and semantic web overlaps. Dodds and Clark on uses of SPARQL.
      
      Look at AI history and development, and convergence with semweb ideas. This is the overview of the development of formal logics and their application to reasoning systems. Description logics, developments and applications in semweb ideas, problems. This is the view from the AI side of the house. DAML, OIL and now the OWL ontology languages have been moving us up the semantic web layer cake. Reasoners in action. Racer automated inferencer example.</td>
  </tr>
  <tr class="odd">
    <td><strong>11</strong></td>
    <td><p><strong>Mar 20, 2007 </strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Meeting</td>
    <td>Staff from the MIT OCW Project will be visiting us Tuesday and Wednesday to work with us on the dScribe ideas and brainstorm with us on software tools to support the dScribe process. </td>
  </tr>
  <tr class="even">
    <td><strong>12</strong></td>
    <td><p><strong>Mar 27, 2007 </strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Cancellation</td>
    <td>Joseph will be out of town, at the Hewlett Foundation meeting.  Work on Projects proceeds.</td>
  </tr>
  <tr class="odd">
    <td><strong>13</strong></td>
    <td><p><strong>Apr 3, 2007</strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Class section - Discussion</td>
    <td>Discuss student projects and dScribing of classes.</td>
  </tr>
  <tr class="even">
    <td><strong>14</strong></td>
    <td><p><strong>Apr 10, 2007 </strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Class section - Small Group</td>
    <td>Projects and dScribing.</td>
  </tr>
  <tr class="odd">
    <td><strong>15</strong></td>
    <td><p><strong>Apr 17, 2007</strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Class section - Small Group</td>
    <td>Projects and dScribing</td>
  </tr>
  <tr class="even">
    <td><strong>16</strong></td>
    <td><p><strong>Apr 20, 2007 </strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Deadline</td>
    <td>Assignment Class Projects is due on Apr 20, 2007 5:00 pm. </td>
  </tr>
  <tr class="odd">
    <td><strong>17</strong></td>
    <td><p><strong>Apr 24, 2007</strong></p>
      <p>9:00 am - 12:00pm</p></td>
    <td>Class section - Small Group</td>
    <td>Projects and dScribing</td>
  </tr>
</table>    </td>
   <td style="color:#333; padding: 10px; width:160px;"><div><strong> Comments</strong></div>
          	<div style="font-size:.92em;">
			 
			<a id="onComment" href="<?= $_SERVER['PHP_SELF'] ?>" onClick="showAddComment('<?= $pk ?>');return false;" title="add a comment" style="color:#09c;"><img src="../include/images/add.png" border=0 height="15" width="15"/> add a comment</a>
			<br/>


<?php
		$cline = 0;

			
		
?>
			<div id="addComment<?= $pk ?>" style="display:none;">
			<a href="<?= $_SERVER['PHP_SELF'] ?>" onClick="setAnchor('<?= $pk ?>');return false;" title="Save comments and any current votes" style="color:red;">Save New Comment</a><br/>
			<textarea name="cmnt<?= $pk ?>" cols="20" rows="4"></textarea>
			</div>
		</div>
		<div style="font-size:.92em;">
			
	

				<br/>
				<em><a href='mailto:$comment[email]' style='color:#09c;'>jmanske</a> - </em>
				<span id='' >do you want to provide links to any of the  projects mentioned in this list?    </span>
			
		</div>
		</td>  

</tr>

    </tbody>
</table>
</div>
</div></div>
</DIV>