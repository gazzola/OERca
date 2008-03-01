<?php
/**
 * Progress Class
 *
 * @package OER Tool
 * @author Ali Asad Lotia <lotia@umich.edu>
 * @date 10 February 2008
 * @copyright Copyright (c) 2006, University of Michigan
 */
class Progress extends Controller {

  public function __construct()
  {
    parent::Controller();

    $this->load->library('oer_progbar');
    $this->load->model('ocw_user');
    $this->load->model('material');
  }

  public function index()
  {
    $this->freakauth_light->check();

    $data = array('title' => 'Progress', 
                  'breadcrumb' => $this->breadcrumb(),
                  'role' => getUserProperty('role'),
                  'name' => getUserProperty('name'));

    if ($data['role'] != 'dscribe1') {
      $this->layout->buildPage('homeother', $data);
      
    } else if ($data['role'] == 'dscribe1') {
      $data['id'] = getUserProperty('id');
      $data['courses'] = $this->ocw_user->get_courses_simple($data['id']);
      foreach ($data['courses'] as $key => &$value) {
        $value['num']['total'] = $this->material->get_co_count($value['id']);
        $value['num']['done'] = $this->material->get_done_count($value['id']);
        $value['num']['ask'] = $this->material->get_ask_count($value['id']);
        $value['num']['rem'] = $this->material->get_rem_count($value['id']);
      }
      $this->layout->buildPage('homedscribe1', $data);
    }
  }

  public function make_bar($total,$done,$ask,$rem)
  {
    $this->oer_progbar->build_prog_bar($total,$done,$ask,$rem);
    $this->oer_progbar->get_prog_bar();
  }

  public function breadcrumb($section='default')
  {
    $breadcrumb = array();
    
    $breadcrumb[] = array('url'=>site_url(), 'name'=>'Home');

    if ($section == 'default') {
      $breadcrumb[] = array('url'=>'', 'name'=>'Work Progress');
    }
    return $breadcrumb;
  }
}
?>
