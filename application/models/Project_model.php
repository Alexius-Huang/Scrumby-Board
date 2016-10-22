<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Project_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  /* BASIC QUERY METHODS */
  function get_projects($where = array()) {
    $this->db->where($where);
    $this->db->order_by('id', 'asc');
    $query = $this->db->get('Project');
    return $query->result_array();
  }

  function get_project($id = '0') {
    if ($id === '0') {
      return FALSE;
    } else {
      $this->db->where('id', $id);
      $this->db->limit(1);
      $query = $this->db->get('Project');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    }
  }

  /*
    function get_project_by_key($key = '') {

    }
  */

  function get_project_members_by_project($id = '0') {
    if ($id === '0') {
      return FALSE;
    } else {
      $this->load->model('user_model');
      $this->db->where('project_id', $id);
      $this->db->order_by('id', 'asc');
      $query = $this->db->get('ProjectMember');
      $members = array();
      foreach ($query->result_array() as $project_member_relation) {
        $members[] = $this->user_model->get_user($project_member_relation['user_id']);
      }
      return $members;
    }
  }

  function get_project_member($id = '0', $user_id = '0') {
    if ($id === '0' OR $user_id === '0') {
      return FALSE;
    } else {
      $this->db->where(array('project_id' => $id, 'user_id' => $user_id));
      $this->db->limit(1);
      $query = $this->db->get('ProjectMember');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    }
  }

  function get_participated_projects($user_id = '0') {
    if ($user_id === '0') {
      return FALSE;
    } else {
      $this->db->where('user_id', $user_id);
      $this->db->order_by('project_id', 'asc');
      $query = $this->db->get('ProjectMember');
      $projects = array();
      foreach ($query->result_array() as $project_member_relation) {
        $projects[] = $this->get_project($project_member_relation['project_id']);
      }
      return $projects;
    }
  }

  /* CREATE PROJECT, ADD PROJECT MEMBER METHOD */
  function create_project($data) {
    $insert = array(
      'key'         => random_string(),
      'title'       => $data['title'],
      'description' => $data['description'],
      'templates'   => $data['templates'],
      'manager_id'  => $data['manager_id'],
      'created_at'  => time(),
      'updated_at'  => time(),
    );
    $this->db->insert('Project', $insert);
    $id = $this->db->insert_id();
    return $id;
  }

  function add_project_member($data) {
    $insert = array(
      'project_id' => $data['project_id'],
      'user_id'    => $data['user_id'],
      'status'     => $data['status'],
      'created_at' => time(),
      'updated_at' => time()
    );
    $this->db->insert('ProjectMember', $insert);
    $id = $this->db->insert_id();
    return $id;
  }

  /* UPDATE PROJECT & PROJECT MEMBER METHOD */
  function update_project($data, $id) {
    $update = array();
    if (isset($data['title']))       { $update['title']       = $data['title'];       }
    if (isset($data['description'])) { $update['description'] = $data['description']; }
    if (isset($data['templates']))   { $update['templates']   = $data['templates'];   }
    if (isset($data['manager_id']))  { $update['manager_id']  = $data['manager_id'];  }

    if ( ! empty($update)) {
      $update['updated_at'] = time();
      $this->db->where('id', $id);
      $this->db->update('Project', $update);
    }
  }

  function update_project_member_status($project_id, $member_id, $status) {
    if (isset($status)) {
      $update = array('status' => $status, 'updated_at' => time());
      $this->db->where(array('user_id' => $member_id, 'project_id' => $project_id));
      $this->db->update('ProjectMember', $update);
    }
  }

  /* DELETE PROJECT, REMOVE PROJECT MEMBER METHOD */
  function delete_project($id) {
    $this->db->where('id', $id);
    $this->db->limit(1);
    $this->db->delete('Project');
  }

  function remove_project_member($project_id, $member_id) {
    $this->db->where(array('project_id' => $project_id, 'user_id' => $member_id));
    $this->db->limit(1);
    $this->db->delete('ProjectMember');
  }

}