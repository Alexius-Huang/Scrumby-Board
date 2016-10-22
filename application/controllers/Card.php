<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Card extends WEB_Controller {

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
    $this->load->model('card_model');
    $this->load->model('gantt_chart_model');

    /* Set default template */
    $this->output->set_template('project_dashboard');

    /* Set default view parameters */
    $view = array('current_user' => $this->current_user);
  }

  public function index() {
    redirect('user');
  }

  public function new_card($template_id = '0') {
    if ($template_id === '0') {
      redirect('user');
    } else {
      $project_id = $this->template_model->get_template($template_id)['project_id'];
      $project = $this->project_model->get_project($project_id);
      if (( ! $this->project_model->get_project_member($project_id, $this->current_user['id'])) AND ($project['manager_id'] != $this->current_user['id'])) {
        redirect('user');
      }
    }

    if ($this->input->post('submitted')) {
      $post = $this->input->post();
      $this->load->library('form_validation');

      /* Form Validation Process */
      $this->form_validation->set_rules('title', 'Card Title', 'trim|required|max_length[20]');
      $this->form_validation->set_rules('content', 'Card Content', 'trim|required|max_length[200]');
      $this->form_validation->set_rules('card_type', 'Card Type', 'required');
      if ($post['gantt_chart_task'] == 1) {
        /* Append Gantt Chart Rule */
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('end_date', 'End Date', 'required');
        $this->form_validation->set_rules('percentage', 'Percentage', 'trim|required|numeric|is_natural|greater_than[-1]|less_than[101]');
      }
      switch ((int)$post['card_type']) {
        case 2:
          /* Append Image Content */
          /* REQUIRED NEW VALIDATION SET */
          break;
        case 3:
          /* Append Youtube Content */
          $this->form_validation->set_rules('youtube_link', 'YouTube Link', 'trim|required');
          break;
      }

      if ($this->form_validation->run() == FALSE) {
        /* Should the field(s) is(are) empty */
        if ($post['title'] == '' OR $post['content'] == '') {
          $view['warning_message'] = 'Card title or content is empty, please fill in these fields!';
        } elseif ($post['gantt_chart_task'] == 1) {
          if ((empty($post['start_date'])) || (empty($post['end_date']))) {
            $view['warning_message'] = 'Empty value in start date or end date field, please try again!';
          } else {
            /* Wrong format of the field */
            $view['warning_message'] = 'Wrong format in creating card, please check and try again!';
          }
        } elseif (($post['card_type'] == 2) /* && (empty($post['upload_image'])) */) {
          $view['warning_message'] = 'Please upload image file and try again!';
        } elseif (($post['card_type'] == 3) && (empty($post['youtube_link']))) {
          $view['warning_message'] = 'Embed YouTube Video link is empty! Please try again!';
        } else {
          /* Wrong format of the field */
          $view['warning_message'] = 'Wrong format in creating card, please check and try again!';
        }
      } elseif (($post['card_type'] == 3) AND ( ! preg_match('/^https:\/\/www.youtube.com\/watch\?v\=/', $post['youtube_link']))) {
        $view['warning_message'] = "Wrong format of YouTube link, please check and try again!";
      } else {
        /* Create Card */
        $create_card = array(
          'type_id'     => $post['card_type'],
          'title'       => $post['title'],
          'content'     => newline_to_br($post['content']),
          'order'       => count($this->card_model->get_cards_by_template($template_id)) + 1,
          'user_id'     => $this->current_user['id'],
          'template_id' => $template_id
        );
        $card_id = $this->card_model->create_card($create_card);
        $project_id = $this->template_model->get_template($template_id)['project_id'];

        if ($post['gantt_chart_task'] == 1) {
          /* Append Data */
          $gantt_ch_id = $this->gantt_chart_model->get_gantt_chart_by_project_id($project_id)['id'];
          $create_task = array(
            'card_id'     => $card_id,
            'gantt_ch_id' => $gantt_ch_id,
            'start_date'  => $post['start_date'],
            'end_date'    => $post['end_date'],
            'percentage'  => $post['percentage']
          );
          $this->gantt_chart_model->create_gantt_chart_task($create_task);
        }

        switch((int)$post['card_type']) {
          case 2:
            /* Create Image */
            $this->upload_image($card_id);
            break;
          case 3:
            /* Create YouTube Video */
            $this->load->model('youtube_model');
            $create_youtube = array(
              'key'      => youtube_link_to_key($post['youtube_link']),
              'ref_type' => youtube_type_id('Card'),
              'ref_id'   => $card_id
            );
            $this->youtube_model->create_youtube_link($create_youtube);
            break;
        }

        redirect('project/detail/'.$project_id);
      }
    }
    
    if ( ! isset($view['warning_message'])) { $view['warning_message'] = ''; }
    $view['title'] = "Create New Card";
    $view['template_id'] = $template_id;
    $this->load->css('/assets/card/new_card.css');
    $this->load->view('card/new_card_view', $view);
  }

  public function detail($id = '0') {
    $view = array('current_user' => $this->current_user);
    if ($id === '0') {
      redirect('user');
    }
    $card = $this->card_model->get_card($id);
    $card['type'] = card_type($card['type_id']);
    $card_opener = $this->user_model->get_user($card['user_id'])['username'];
    $template = $this->template_model->get_template($card['template_id']);
    $project = $this->project_model->get_project($template['project_id']);
    $manager = $this->user_model->get_user($project['manager_id'])['username'];

    switch ((int)$card['type_id']) {
      case 2:
        /* Image Content */
        $this->load->model('image_model');
        $image = $this->image_model->get_image_file_by_references('Card', $id);
        $image['file_path'] = get_image_link('Card', $image['file_name']);
        $view['image'] = $image;
        break;
      case 3:
        /* Youtube Content */
        $this->load->model('youtube_model');
        $youtube = $this->youtube_model->get_youtube_link_by_references('Card', $id);
        $youtube['base_url'] = youtube_base_url($youtube['key']);
        for ($i = 1; $i <= 5; $i ++) {
          $youtube['image_link'][$i] = get_youtube_image_link($youtube['key'], $i);
        }
        $view['youtube'] = $youtube;
        break;
    }

    $view['card'] = $card;
    $view['card_opener'] = $card_opener;
    $view['template'] = $template;
    $view['project'] = $project;
    $view['manager'] = $manager;
    if ( ! isset($view['youtube'])) { $view['youtube'] = ''; }
    $this->load->css('/assets/card/detail.css');
    $this->load->view('card/detail_view', $view);
  }

  public function setting($id = '0') {
    if ($id === '0') {
      redirect('user');
    }

    $card = $this->card_model->get_card($id);
    $template = $this->template_model->get_template($card['template_id']);
    $project = $this->project_model->get_project($template['project_id']);
    switch((int)$card['type_id']) {
      case 2:
        /* Image Content */
        $this->load->model('image_model');
        $image = $this->image_model->get_image_file_by_references('Card', $id);
        $image['file_path'] = get_image_link('Card', $image['file_name']);
        $view['image'] = $image;
        break;
      case 3:
        /* Youtube Content */
        $this->load->model('youtube_model');
        $youtube =  $this->youtube_model->get_youtube_link_by_references('Card', $id);
        $youtube['base_url'] = youtube_base_url($youtube['key']);
        $youtube['image_link'] = get_youtube_image_link($youtube['key'], 4);
        $view['youtube'] = $youtube;
        break;
    }

    if ($this->input->post('submitted')) {
      $post = $this->input->post();
      $this->load->library('form_validation');

      /* Form Validation Process */
      $this->form_validation->set_rules('title', 'Card Title', 'trim|required|max_length[20]');
      $this->form_validation->set_rules('content', 'Card Content', 'trim|required|max_length[200]');
      if ($post['gantt_task_enabled'] == 1) {
        $this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
        $this->form_validation->set_rules('percentage', 'Percentage', 'trim|required|numeric|is_natural|greater_than[-1]|less_than[101]');
      }
      switch((int)$post['card_type']) {
        case 2:
          /* Image Content */
          if ($post['origin_type'] != '2') {
            /* REQUIRED NEW VALIDATION SET */
          }
          break;
        case 3:
          /* Youtube Content */
          $this->form_validation->set_rules('youtube_link', 'Youtube Link', 'trim|required');
          break;
      }

      if ($this->form_validation->run() == FALSE) {
        if (empty($post['title']) OR empty($post['content'])) {
          $view['warning_message'] = 'Card title or content field is empty, please fill in these field and try again!';
        } elseif ($post['gantt_task_enabled'] == 1) {
          if (empty($post['start_date']) OR empty($post['end_date'])) {
            $view['warning_message'] = "Empty value in start date or end date field, please try again!";
          } else {
            $view['warning_message'] = 'Wrong format in card setting form, please check and try again!';
          }
        } elseif (($post['card_type'] == 2) AND ($post['origin_type'] != 2) /* AND  empty($post['upload_image']) */) {
          $view['warning_message'] = 'Please upload image file and try again!';
        } elseif (($post['card_type'] == 3) AND empty($post['youtube_link'])) {
          $view['warning_message'] = 'Embed YouTube Video link is empty! Please try again!';
        } else {
          $view['warning_message'] = 'Wrong format in card setting form, please check and try again!';
        }
      } else {
        /* Update Card Content */
        $update = array(
          'title'     => $post['title'],
          'content'   => newline_to_br($post['content']),
          'type_id'   => $post['card_type'] 
        );
        $this->card_model->update_card($update, $id);

        if ($post['card_type'] == $post['origin_type']) {
          switch((int)$post['card_type']) {
            case 2:
              if ( ! empty($_FILES['upload_image']['name'])) {
                /* Delete Previous Image Content */
                if ( ! isset($this->image_model)) { $this->load->model('image_model'); }
                $image = $this->image_model->get_image_file_by_references('Card', $id);
                $this->image_model->delete_image_asset_file('Card', $image['file_name']);
                $this->image_model->delete_image_file($image['id']);
                /* Create New Image Content */
                $this->upload_image($id);
              }
              break;
            case 3:
              /* Update Youtube Content */
              if ( ! empty($this->youtube_model)) { 
              $this->load->model('youtube_model'); }
              $youtube = $this->youtube_model->get_youtube_link_by_references('Card', $id);
              $update_youtube = array(
                'key'      => youtube_link_to_key($post['youtube_link']),
                'ref_type' => youtube_type_id('Card'),
                'ref_id'   => $id
              );
              $this->youtube_model->update_youtube_link($update_youtube, $youtube['id']);
              break;
          }
        } else {
          /* Delete Origin Content */
          switch((int)$post['origin_type']) {
            case 2:
              /* Delete Image Content */
              if ( ! isset($this->image_model)) { $this->load->model('image_model'); }
              $image = $this->image_model->get_image_file_by_references('Card', $id);
              $this->image_model->delete_image_asset_file('Card', $image['file_name']);
              $this->image_model->delete_image_file($image['id']);
              break;
            case 3:
              /* Delete Youtube Content */
              if ( ! isset($this->youtube_model)) { $this->load->model('youtube_model'); }
              $youtube = $this->youtube_model->get_youtube_link_by_references('Card', $id);
              $this->youtube_model->delete_youtube_link($youtube['id']);
              break;
          }
          /* Create New Content */
          switch((int)$post['card_type']) {
            case 2:
              /* Create Image Content */
              if ( ! isset($this->image_model)) { $this->load->model('image_model'); }
              $this->upload_image($id);
              break;
            case 3:
              /* Create Youtube Content */
              if ( ! isset($this->youtube_model)) { $this->load->model('youtube_model'); }
              $create_youtube = array(
                'key'      => youtube_link_to_key($post['youtube_link']),
                'ref_type' => youtube_type_id('Card'),
                'ref_id'   => $id
              );
              $this->youtube_model->create_youtube_link($create_youtube);
              break;
          }
        }

        /* Update Gantt Chart Task */
        $gantt_ch_task_id = $post['had_gantt_task_already'];
        $gantt_chart = $this->gantt_chart_model->get_gantt_chart_by_project_id($project['id']);
        if (($post['gantt_task_enabled'] == 1) AND ($post['had_gantt_task_already'] != 0)) {
          /* Already have gantt chart task and enabled it */
          $update_gantt_task = array(
            'start_date' => $post['start_date'],
            'end_date'   => $post['end_date'],
            'percentage' => $post['percentage']
          );
          $this->gantt_chart_model->update_gantt_chart_task($update_gantt_task, $gantt_ch_task_id);
        } elseif (($post['gantt_task_enabled'] == 1) AND ($post['had_gantt_task_already'] == 0)) {
          /* Enabled gantt task, but didn't have it yet */
          $create_task = array(
            'card_id'     => $id,
            'gantt_ch_id' => $gantt_chart['id'],
            'start_date'  => $post['start_date'],
            'end_date'    => $post['end_date'],
            'percentage'  => $post['percentage']
          );
          $this->gantt_chart_model->create_gantt_chart_task($create_task);
          /* Update Gantt Chart Rows */
          $this->gantt_chart_model->update_gantt_chart(array('rows' => $gantt_chart['rows'] + 1), $gantt_chart['id']);
        } elseif (($post['gantt_task_enabled'] == 0) AND ($post['had_gantt_task_already'] != 0)) {
          /* Disable gantt task and delete the original one */
          $this->gantt_chart_model->delete_gantt_chart_task($gantt_ch_task_id);
          /* Update Gantt Chart Rows */
          $this->gantt_chart_model->update_gantt_chart(array('rows' => $gantt_chart['rows'] - 1), $gantt_chart['id']);
        }

        redirect('project/detail/'.$project['id']);
      }
    }

    if (empty($view['warning_message'])) { $view['warning_message'] = ''; }
    $view['card'] = $card;
    $view['template'] = $template;
    $view['project'] = $project;
    if ($gantt_task = $this->gantt_chart_model->get_gantt_chart_task_by_card_id($id)) {
      $view['gantt_task_enabled'] = 'true';
      $view['gantt_task'] = $gantt_task;
    } else {
      $view['gantt_task_enabled'] = 'false';
      $view['gantt_task'] = null;
    }
    if ( ! isset($view['youtube'])) { $view['youtube'] = ''; }
    $this->load->css('/assets/card/setting.css');
    $this->load->view('card/setting_view', $view);

  }

  function upload_image(
    $card_id = '0',
    $field_name = "upload_image",
    $upload_path = 'assets/uploads/images/cards',
    $allowed_types = 'jpg|png|gif'
  ) {
    if ($card_id == '0') { return FALSE; }
    $config['upload_path'] = $upload_path;
    $config['allowed_types'] = $allowed_types;
    $config['file_name'] = $card_id.'_'.random_string(10, 'alphanumeral');
    $this->load->library('upload', $config);
    if ($this->upload->do_upload($field_name)) {
      $upload_data = $this->upload->data();
      if ( ! isset($this->image_model)) { $this->load->model('image_model'); }
      $create_image_file = array(
        'file_name' => $upload_data['file_name'],
        'ref_type'  => image_type_id('Card'),
        'ref_id'    => $card_id
      );
      $this->image_model->create_image_file($create_image_file);
    } else {
      $view['warning_message'] = 'There is something wrong with uploading files.';
    }
  }

}