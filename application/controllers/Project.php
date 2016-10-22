<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

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
    $this->load->model('template_model');

    /* Set default template */
    $this->output->set_template('project_dashboard');

    /* Set default view parameters */
    $view = array('current_user' => $this->current_user);
  }

  public function index() {
    redirect('user/profile/'.$this->current_user['id']);
  }

  public function new_project() {
    
    if ($this->input->post('submitted')) {
      $post = $this->input->post();
      $this->load->library('form_validation');

      /* Form validation process */
      $this->form_validation->set_rules('title', 'Project Name', 'trim|required|max_length[32]');
      $this->form_validation->set_rules('description', 'Project Description', 'trim|required');
      if ($this->form_validation->run() == FALSE) {
        /* Should the field(s) is(are) empty */
        if ($post['title'] == '' OR $post['description'] == '') {
          $view['warning_message'] = 'Project name or description is empty, please fill in these field!';
        } else {
          /* Wrong format of the field */
          $view['warning_message'] = 'Wrong format in project name or description, please try again!';
        }
      } elseif (count($post['template_order']) === 0) {
        /* There must exist at least one template */
        $view['warning_message'] = 'Project should exist at least one template!';
      } else {
        /* Create project / Arrange templates / Add project member */
        $create_project = array(
          'title'       => $post['title'],
          'description' => newline_to_br($post['description']),
          'templates'   => count($post['template_order']),
          'manager_id'  => $this->current_user['id']
        );
        $project_id = $this->project_model->create_project($create_project);
        foreach ($post['template_order'] as $order_id => $template_name) {
          $create_template = array(
            'name'       => $template_name,
            'order'      => $order_id + 1,
            'project_id' => $project_id
          );
          $this->template_model->create_template($create_template);
        }
        foreach ($post['member'] as $member_id) {
          $create_member = array(
            'project_id' => $project_id,
            'user_id'    => $member_id,
            'status'     => 1
          );
          $this->project_model->add_project_member($create_member);
        }

        /* Whenever project created -> Create Gantt Chart */
        $this->load->model('gantt_chart_model');
        $create_gantt_chart = array(
          'rows' => 0,
          'project_id' => $project_id
        );
        $this->gantt_chart_model->create_gantt_chart($create_gantt_chart);

        redirect('user');
      }
    }

    if ( ! isset($view['warning_message'])) { $view['warning_message'] = ''; }
    $view['url'] = '/project/new_project';
    $view['members'] = $this->user_model->get_users(array('id !=' => $this->current_user['id']));
    $view['default_templates'] = array(
      1 => 'Ideas',
      2 => 'Processing',
      3 => 'Complete'
    );
    $view['templates_count'] = count($view['default_templates']);
    $this->load->css('/assets/project/new_project.css');
    $this->load->view('project/new_project_view', $view);
  }

  public function detail($id = '0') {
    if ($id === '0') {
      redirect('user');
    } else {
      $project = $this->project_model->get_project($id);
      if (( ! $this->project_model->get_project_member($id, $this->current_user['id'])) AND ($project['manager_id'] != $this->current_user['id'])) {
        redirect('user');
      }
    }
    $this->load->model('card_model');
    $this->load->model('gantt_chart_model');

    /* POST ACTIONS */
    if ($this->input->post('delete')) {
      /* Delete card */
      $card_id = $this->input->post('card_id');
      $card = $this->card_model->get_card($card_id);
      $template_id = $card['template_id'];
      $this->card_model->delete_card($card_id);

      /* Delete following gantt chart task if exist! */
      if ($task = $this->gantt_chart_model->get_gantt_chart_task_by_card_id($card['id'])) {
        $this->gantt_chart_model->delete_gantt_chart_task($task['id']);  
      }
      /* Delete following embedded item if exist */
      switch ((int)$card['type_id']) {
        case 2:
          /* Delete Image Content */
          $this->load->model('image_model');
          $image = $this->image_model->get_image_file_by_references('Card', $card['id']);
          $this->image_model->delete_image_asset_file('Card', $image['file_name']);
          $this->image_model->delete_image_file($image['id']);
          break;
        case 3:
          /* Delete Youtube Content */
          $this->load->model('youtube_model');
          $youtube = $this->youtube_model->get_youtube_link_by_references('Card', $card['id']);
          $this->youtube_model->delete_youtube_link($youtube['id']);
          break;
      }
    }
    
    if ($this->input->post('new_template')) {
      $template_name = $this->input->post('template_name');
      $create_template = array(
        'name'       => $template_name,
        'order'      => count($this->template_model->get_templates_from_project($id)) + 1,
        'project_id' => $id
      );
      $this->template_model->create_template($create_template);
      redirect('project/detail/'.$id);
    }

    $project = $this->project_model->get_project($id);
    $project['members'] = $this->project_model->get_project_members_by_project($id);
    $status = $this->project_model->get_project_member($id, $this->current_user['id'])['status'];
    $templates = $this->template_model->get_templates_from_project($id);
    
    $template_card_table = array();
    foreach ($templates as $template) {
      $template_card_table[$template['id']] = $this->card_model->get_cards_by_template($template['id']);
    }

    $gantt_chart = $this->gantt_chart_model->get_gantt_chart_by_project_id($id);
    $gantt_chart_data = array();
    if ($gantt_chart_tasks = $this->gantt_chart_model->get_gantt_chart_tasks_by_gantt_ch_id($gantt_chart['id'])) {
      foreach ($gantt_chart_tasks as $task) {
        $card = $this->card_model->get_card($task['card_id']);
        $template = $this->template_model->get_template($card['template_id']);
        $gantt_chart_data[] = array(
          'task_id'    => $task['id'],
          'task_name'  => $card['title'],
          'resource'   => $template['name'],
          'start_date' => $task['start_date'],
          'end_date'   => $task['end_date'],
          'percentage' => $task['percentage']
        );
      }
    }

    $view['url'] = '/project/detail/'.$id;
    $view['project'] = $project;
    $view['status'] = $status;
    $view['templates'] = $templates;
    $view['template_card_table'] = $template_card_table;
    $view['gantt_chart_data'] = $gantt_chart_data;
    $this->load->model('youtube_model');
    $view['youtube_model'] = $this->youtube_model;
    $this->load->model('image_model');
    $view['image_model'] = $this->image_model;
    $this->load->css('/assets/project/detail.css');
    $this->load->view('project/detail_view', $view);
  }

  public function manage($id = '0') {
    $project = $this->project_model->get_project($id);
    $project_members = $this->project_model->get_project_members_by_project($id);
    $templates = $this->template_model->get_templates_from_project($id);    

    if ($this->input->post('submitted')) {
      $post = $this->input->post();
      $this->load->library('form_validation');

      /* Form Validation Process */
      $this->form_validation->set_rules('title', 'Project Title', 'trim|required');
      $this->form_validation->set_rules('content', 'Project Content', 'trim|required');
      if ($this->form_validation->run() == FALSE) {
        $view['warning_message'] = 'Wrong Format in Project Title or Project Description';
      } else {
        /* Update Project */
        $update_project = array(
          'title'       => $post['title'],
          'description' => newline_to_br($post['content']),
          'templates'   => count($post['template_ids'])
        );
        $this->project_model->update_project($update_project, $id);

        /* Update Project Members */
        foreach ($project_members as $member) {
          $this->project_model->remove_project_member($id, $member['id']);
        }
        foreach ($post['member_ids'] as $member_id) {
          $create_member = array(
            'project_id' => $id,
            'user_id'    => $member_id,
            'status'     => 1
          );
          $this->project_model->add_project_member($create_member);
        }
        $project_members = $this->project_model->get_project_members_by_project($id);

        /* Update Template */
        if (isset($post['remove_template_ids'])) {
          /* Should the template being deleted the following cards should be deleted */
          $this->load->model('card_model');
          foreach ($post['remove_template_ids'] as $template_id) {
            $cards = $this->card_model->get_cards_by_template($template_id);
            foreach ($cards as $card) {
              $this->card_model->delete_card($card['id']);
            }
            $this->template_model->delete_template($template_id);
          }
        }
        $order_count = 0;
        foreach (array_combine($post['template_ids'], $post['template_name']) as $template_id => $template_name) {
          $order_count++;
          if (preg_match('/new-([0-9]+)/', $template_id)) {
            /* Create new template */
            $create_template = array(
              'name'       => $template_name,
              'order'      => $order_count,
              'project_id' => $id
            );
            $this->template_model->create_template($create_template);
          } else {
            /* Update template */
            $update_template = array(
              'name'  => $template_name,
              'order' => $order_count
            );
            $this->template_model->update_template($update_template, $template_id);
          }
        }

        redirect('project/detail/'.$id);
      }
    }

    if ($this->input->post('delete_project')) {
      /* Delete all the cards in this project */
      $this->load->model('card_model');
      foreach ($templates as $template) {
        $cards = $this->card_model->get_cards_by_template($template['id']);
        foreach ($cards as $card) {
          $this->card_model->delete_card($card['id']);
        }
        /* Delete template as well */
        $this->template_model->delete_template($template['id']);
      }

      /* Delete Gantt Chart Associated Data */
      $this->load->model('gantt_chart_model');
      $gantt_chart = $this->gantt_chart_model->get_gantt_chart_by_project_id($id);
      $gantt_tasks = $this->gantt_chart_model->get_gantt_chart_tasks_by_gantt_ch_id($gantt_chart['id']);
      foreach ($gantt_tasks as $task) {
        $this->gantt_chart_model->delete_gantt_chart_task($task['id']);
      }
      $this->gantt_chart_model->delete_gantt_chart($gantt_chart['id']);

      /* Delete Project Members and Project */
      foreach ($project_members as $member) {
        $this->project_model->remove_project_member($id, $member['id']);
      }
      $this->project_model->delete_project($project['id']);
      redirect('user');
    }

    if ( ! isset($view['warning_message'])) { $view['warning_message'] = ''; }
    $view['current_user'] = $this->current_user;
    $view['project'] = $project;
    $view['manager'] = $this->user_model->get_user($project['manager_id']);
    $view['members'] = $project_members;
    $view['templates'] = $templates;
    $view['url'] = '/project/manage/'.$id;
    $this->load->css('/assets/project/manage.css');
    $this->load->view('project/manage_view', $view);
  }

  public function history($id = '0') {
    $view['url'] = '/project/history/'.$id;
    $this->load->css('/assets/project/history.css');
    $this->load->view('project/history_view', $view);
  }

}