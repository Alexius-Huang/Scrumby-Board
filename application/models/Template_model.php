<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Template_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  /* BASIC QUERY METHODS */
  function get_templates($where = array()) {
    $this->db->where($where);
    $this->db->order_by('id', 'asc');
    $query = $this->db->get('Template');
    return $query->result_array();
  }

  function get_template($id = '0') {
    if ($id === '0') {
      return FALSE;
    } else {
      $this->db->where('id', $id);
      $this->db->limit(1);
      $query = $this->db->get('Template');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    }
  }

  function get_templates_from_project($project_id = '0') {
    if ($project_id === '0') {
      return FALSE;
    } else {
      $this->db->where('project_id', $project_id);
      $this->db->order_by('order', 'asc');
      $query = $this->db->get('Template');
      return $query->result_array();
    }
  }

  /* CREATE TEMPLATE METHOD */
  function create_template($data) {
    $insert = array(
      'name'       => $data['name'],
      'order'      => $data['order'],
      'project_id' => $data['project_id'],
      'created_at' => time(),
      'updated_at' => time(),
    );
    $this->db->insert('Template', $insert);
    $id = $this->db->insert_id();
    return $id;
  }

  /* UPDATE TEMPLATE METHIOD */
  function update_template($data, $id) {
    $update = array();
    if (isset($data['name']))       { $update['name']       = $data['name'];       }
    if (isset($data['order']))      { $update['order']      = $data['order'];      }
    if (isset($data['project_id'])) { $update['project_id'] = $data['project_id']; }

    if ( ! empty($update)) {
      $update['updated_at'] = time();
      $this->db->where('id', $id);
      $this->db->update('Template', $update);
    }
  }

  /* DELETE TEMPLATE METHOD */
  function delete_template($id) {
    $this->db->where('id', $id);
    $this->db->limit(1);
    $this->db->delete('Template');
  }

}