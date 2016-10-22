<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class User extends WEB_Controller {

  public function __construct() {
    parent::__construct();
    $this->output->nocache();

    # Check if user already sign in
    if ( ! $this->session->userdata('id')) {
      redirect('user_authentication/signin');
    }

    # Preserve session flash data
    $this->session->keep_flashdata('success_message');

    # Current user can be fetched by session
    $this->current_user = $this->session->userdata();
    
    # Default loading model
    $this->load->model('user_model');
    $this->load->model('project_model');
    
    # Set default template
    $this->output->set_template('user_dashboard');

    # Set default view parameters
    $view = array('current_user' => $this->current_user);
  }

  public function index() {
    redirect('user/profile/'.$this->current_user['id']);
  }

  public function profile($id = '0') {
    if ($id === '0') {
      redirect('user');
    }

    $managed_projects = $this->project_model->get_projects(array('manager_id' => $this->current_user['id']));
    $managed_project_member_table = array();
    foreach ($managed_projects as $project) {
      $managed_project_member_table[$project['id']] = $this->project_model->get_project_members_by_project($project['id']);
    }

    $participated_projects = $this->project_model->get_participated_projects($this->current_user['id']);
    $participated_project_member_table = array();
    $project_manager_table = array();
    $project_status_table = array();
    foreach ($participated_projects as $project) {
      $participated_project_member_table[$project['id']] = $this->project_model->get_project_members_by_project($project['id']);
      $project_manager_table[$project['id']] = $this->user_model->get_user($project['manager_id'])['username'];
      $project_status_table[$project['id']] = $this->project_model->get_project_member($project['id'], $this->current_user['id'])['status'];
    }

    $view['url'] = '/user/profile/'.$id;
    $view['title'] = "Hello! ".$this->current_user['username'];
    $view['managed_projects'] = $managed_projects;
    $view['managed_project_member_table'] = $managed_project_member_table;
    $view['participated_projects'] = $participated_projects;
    $view['participated_project_member_table'] = $participated_project_member_table;
    $view['project_manager_table'] = $project_manager_table;
    $view['project_status_table'] = $project_status_table;
    $this->load->css('/assets/user/profile.css');
    $this->load->view('user/profile_view', $view);
  }

  public function setting($id = '0') {
    if ($id === '0') {
      redirect('user');
    }

    if ($this->input->post('submitted')) {
      $post = $this->input->post();
      $this->load->library('form_validation');

      /* Form Validation Process */
      $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
      $this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[30]');
      if ($post['password'])
      $this->form_validation->set_rules('password', 'Password', 'trim');
      $this->form_validation->set_rules('password_confirmation', 'Password Confirmation', 'trim');
      if ($this->form_validation->run() === FALSE) {
        if ($post['password'] != $post['password_confirmation']) {
          $view['warning_message'] = 'Oops, your password and password confirmation field are not the same, please check it again!';
        } elseif ($post['username'] == '' OR $post['email'] == '') {
          $view['warning_message'] = 'Your email or username is/are empty, please fill in and try again!';
        } else {
          $view['warning_message'] = 'Wrong format in email or username field, please try again!';
        }
      } elseif ($post['password'] != $post['password_confirmation']) {
        $view['warning_message'] = 'Oops, your password and password confirmation field are not the same, please check it again!';
      } else {
        /* Update user account */
        $update_account = array(
          'email'    => $post['email'],
          'username' => $post['username'],
        );
        if ( ! empty($post['password'])) {
          $update_account['password'] = $post['password'];
        }
        $this->user_model->update_user($update_account, $this->current_user['id']);
        $this->session->set_userdata('success_message', 'Your account has been updated successfully!');
        redirect('user/setting/'.$this->current_user['id']);
      }
    }

    if ( ! isset($warning_message)) { $view['warning_message'] = ''; }
    $view['url'] = '/user/setting/'.$id;
    $this->load->css('/assets/user/setting.css');
    $this->load->view('user/setting_view', $view);    
  }

  public function history($id = '0') {
    if ($id === '0') {
      redirect('user');
    }
    $view['url'] = '/user/history/'.$id;
    $this->load->css('/assets/user/history.css');
    $this->load->view('user/history_view', $view);
  }

}