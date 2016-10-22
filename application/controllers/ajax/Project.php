<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends WEB_Controller {

  public function __construct() {
    parent::__construct();
    $this->output->nocache();

    /* Check if user already sign in */
    if ( ! $this->session->userdata('id')) {
      redirect('user_authentication/signin');
    }

    /* Current user can be fetched by session */
    $this->current_user = $this->session->userdata();
    
    /* Default loading model */
    $this->load->model('user_model');
    $this->load->model('project_model');
  }

  /* AJAX for project/member -> addProjectMember */
  public function get_contact_by_email() {
    if ($this->input->post('add_contact_email')) {
      $contact_email = $this->input->post('add_contact_email');
      if ($contact = $this->user_model->get_user_by_email($contact_email)) {
        echo json_encode(array('contact' => $contact));
      } else {
        echo json_encode(array('contact' => 'Not Found'));
      }
    } else {
      redirect('user');
    }
  }

}