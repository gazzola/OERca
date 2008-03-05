<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * OER_Loader File a modification of
 * Loader File
 *
 * @package		YATS -- The Layout Library
 * @subpackage	Views
 * @category	Template
 * @author		Mario Mariani
 * @copyright	Copyright (c) 2006-2007, mariomariani.net All rights reserved.
 * @license		http://svn.mariomariani.net/yats/trunk/license.txt
 * modified by Ali Asad Lotia
 * for the OER Tool
 * @date      March 03 2008
 */
$this->load->view($data['settings']['views'] . $data['settings']['commons'] . 
  $data['cust_header'], $data);
$this->load->view($data['settings']['views'] . $data['settings']['content'] . 
  $view, $data);
$this->load->view($data['settings']['views'] . $data['settings']['commons'] .
  $data['cust_footer'], $data);
?>
