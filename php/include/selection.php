<?php
/*
 * Created on May 2, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
?>


<select name="secondaryRole">
	<? 
	if ($type) { echo "<option value=''>$type</option>";	} ?>
	
	<option value="">-- select --</option>
		<option value="">Assignments</option>
		<option value="">Discussion Group</option>

		<option value="">Exams</option>
		<option value="">Labs</option>
		<option value="">Lecture Notes</option>
		<option value="">Projects</option>
		<option value="">Readings</option>
		<option value="">Schedule</option>

		<option value="">Syllabus</option>
		<option value="">Video Lectures</option>
	</select>


