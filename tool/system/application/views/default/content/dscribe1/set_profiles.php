<?php $this->load->view(property('app_views_path').'/dscribe1/dscribe1_header.php', $data); ?>
<?php
echo form_open('dscribe1/setprofile', '', '');
?>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td width="40%" style="font-weight: bold; text-align: left; border:1px solid #ccc;padding: 5px;  ">
		Edit Course
    </td>
 </tr>
 <tr>
	<td style="font-weight: bold; text-align: left; border:1px solid #ccc;padding: 5px;  ">
		<h4>Title (required) </h4>
       <?php echo form_input('title', $courseTitle); ?>
	</td>
 </tr>
 <tr>
	<td style="font-weight: bold; text-align: left; border:1px solid #ccc;padding: 5px;  ">
		<h4>Description</h4>A short summary of the content<br />
        <?php echo form_textarea('description', ''); ?>
	</td>
 </tr>
  <tr>
	<td style="font-weight: bold; text-align: left; border:1px solid #ccc;padding: 5px;  ">
		<h4>Course Id </h4>Enter the Course ID. The course ID, if exists, will be displayed with the course's title on the course home page, as well as on the course listing page.<br />
        <?php echo form_input('id', $courseId); ?>
	</td>
 </tr>
  <tr>
	<td style="font-weight: bold; text-align: left; border:1px solid #ccc;padding: 5px;  ">
		<h4>Term</h4> Enter the term, typically the sememster or quarter and year, in which the course was taught, e.g. Fall 2007<br />
        <?php echo form_input('term', ''); ?>
	</td>
 </tr>
</table>
<?php
		echo form_submit('savebutton', 'Save');
		echo form_submit('cancelbutton', 'Cancel');
		$string = "</div></div>";
		echo form_close($string);
?>
<?php $this->load->view(property('app_views_path').'/dscribe1/dscribe1_footer.php', $data); ?>
