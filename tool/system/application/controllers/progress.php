<?php

class Progress extends Controller {

  public function __contstruct()
  {
    parent::Controller();
  }

  public function index()
  {
    $this->freakauth_light->check();

    $this->load->library('oer_progbar');
    $this->load->model('ocw_user');
    $this->load->model('material');
    
    $data = array('title' => 'Progress', 
                  'breadcrumb' => $this->breadcrumb(),
                  'role' => getUserProperty('role'));

    if ($data['role'] != 'dscribe1') {
      $this->layout->buildPage('notdscribe1', $data);
      
    } else if ($data['role'] == 'dscribe1') {
      $data['id'] = getUserProperty('id');
      $data['name'] = getUserProperty('name');
      $data['courses'] = $this->ocw_user->get_courses_simple($data['id']);
      foreach ($data['courses'] as $key => &$value) {
        $value['image'] = $this->oer_progbar;
        $value['num']['total'] = $this->material->get_co_count($value['id']);
        $value['num']['done'] = $this->material->get_done_count($value['id']);
        $value['num']['ask'] = $this->material->get_ask_count($value['id']);
        $value['num']['rem'] = $this->material->get_rem_count($value['id']);
      }
      $this->layout->buildPage('progress', $data);
    }
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