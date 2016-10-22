<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Card_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  /* BASIC QUERY METHODS */
  function get_cards($where = array()) {
    $this->db->where($where);
    $this->db->order_by('id', 'asc');
    $query = $this->db->get('Card');
    return $query->result_array();
  }

  function get_card($id = '0') {
    if ($id === '0') {
      return FALSE;
    } else {
      $this->db->where('id', $id);
      $this->db->limit(1);
      $query = $this->db->get('Card');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    }
  }

  function get_cards_by_template($template_id = '0') {
    if ($template_id === '0') {
      return FALSE;
    } else {
      $this->db->where('template_id', $template_id);
      $this->db->order_by('order', 'asc');
      $query = $this->db->get('Card');
      return $query->result_array();
    }
  }

  function get_cards_by_user($user_id = '0') {
    if ($user_id === '0') {
      return FALSE;
    } else {
      $this->db->where('user_id', $user_id);
      $this->db->order_by('id', 'asc');
      $query = $this->db->get('Card');
      return $query->result_array();
    }
  }

  /* CREATE CARD */
  function create_card($data) {
    $insert = array(
      'type_id'     => $data['type_id'],
      'title'       => $data['title'],
      'content'     => $data['content'],
      'order'       => $data['order'],
      'user_id'     => $data['user_id'],
      'template_id' => $data['template_id'],
      'created_at'  => time(),
      'updated_at'  => time()
    );
    $this->db->insert('Card', $insert);
    $id = $this->db->insert_id();
    return $id;
  }

  /* UPDATE CARD METHOD */
  function update_card($data, $id) {
    $update = array();
    if (isset($data['type_id'])) { $update['type_id'] = $data['type_id']; }
    if (isset($data['title']))   { $update['title']   = $data['title']; }
    if (isset($data['content'])) { $update['content'] = $data['content']; }
    if (isset($data['order']))   { $update['order']   = $data['order'];   }
    // if (isset($data['user_id']))
    // if (isset($data['template_id']))

    if ( ! empty($update)) {
      $update['updated_at'] = time();
      $this->db->where('id', $id);
      $this->db->update('Card', $update);
    }
  }

  /* DELETE CARD METHOD */
  function delete_card($id) {
    $this->db->where('id', $id);
    $this->db->limit(1);
    $this->db->delete('Card');
  }

}