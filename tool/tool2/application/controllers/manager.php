<?php
/**
* CodeIgniter
*
* An open source application development framework for PHP 4.3.2 or newer
*
* @package        Scaffolding Manager Helper
* @author         Hubert Bernaciak
* @copyright      Copyright (c) 2007, Hubert Bernaciak.
* @link           http://www.hubi.pl
* @since          Version 1.0
*
*/

// ------------------------------------------------------------------------
class Manager extends Controller {

       function Manager()
       {
            parent::Controller();
            if(!$this->uri->segment(3) == '')
            {
            $this->load->scaffolding($this->uri->segment(3));
            }
       }

       function index()
       {
            $result = mysql_list_tables('ocw');
            while ($row = mysql_fetch_row($result))
            {
				$table = $row[0];
				$table = preg_replace('/^ocw_/','',$table);
            	echo '<a href="'.site_url("manager/ocwpass/{$table}").'">'.$row[0].'</a><br />';
            }
       
       }
}
?> 
