<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class User_authentication extends WEB_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('user_model');
  }

  public function signin() {
    /* If session already exist, then redirect to the main user page */
    if ($user_id = $this->session->userdata('id')) { redirect('user/profile/'.$user_id); }

    $this->load->library('form_validation');
    $view = array();

    if (($email = $this->input->post('email')) && ($password = $this->input->post('password'))) {
      $this->form_validation->set_rules('email',    'Email',    'trim|required|max_length[255]');
      $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[255]');
      /* SHOULD ADD TURING TEST CODE */
      $this->form_validation->set_error_delimiters('<br>', '');
      if ($this->form_validation->run() == FALSE) {
        $view['warning_message'] = 'Wrong email or password, please try again!';
      } else {
        if ( ! $user = $this->user_model->user_signin(trim($email), trim($password))) {
          $view['warning_message'] = 'Wrong email or password, please try again!';
        } elseif ($user['status'] === '-1') {
          $this->user_model->user_signout();
          $view['warning_message'] = 'This account is being blacklisted, if you have any inquirement, please try to consult to the manager.';
        } else {
          redirect('user');
        }
      }
    } elseif ($this->input->post('email') === '' || $this->input->post('password')) {
      $view['warning_message'] = 'Email and password field is empty, please try it again!';
    }

    $this->output->set_template('user_auth');
    $this->load->css('/assets/user_authentication/signin.css');

    if (empty($view['warning_message'])) { $view['warning_message'] = ''; }
    $view['title'] = 'Sign In to Scrumby Board';
    $this->load->view('user_authentication/signin_view', $view);

  }

  public function signout() {
    $this->user_model->user_signout();
    redirect('user_authentication/signin');
  }

  /* FUNCTION signup() currently unavailable due to private use. */
  /*
    public function user_signup() {
  
    }
  */

}