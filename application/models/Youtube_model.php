<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Youtube_model extends CI_Model {
  
  function __construct() {
    parent::__construct();
  }

  /* BASIC QUERY METHODS */
  function get_youtube_links($where = array()) {
    $this->db->where($where);
    $this->db->order_by('id', 'asc');
    $query = $this->db->get('YoutubeLink');
    return $query->result_array();
  }

  function get_youtube_link($id = '0') {
    if ($id === '0') {
      return FALSE;
    } else {
      $this->db->where('id', $id);
      $this->db->limit(1);
      $query = $this->db->get('YoutubeLink');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    }
  }

  function get_youtube_links_by_ref_type($ref_type = '') {
    if ($ref_type === '') {
      return FALSE;
    } elseif ($type_id = youtube_type_id($ref_type)) {
      $this->db->where('ref_type', $type_id);
      $this->db->order_by('id', 'asc');
      $query = $this->db->get('YoutubeLink');
      return $query->result_array();
    } else {
      return FALSE;
    }
  }

  function get_youtube_link_by_references($ref_type = '', $ref_id = '0') {
    if (($ref_type === '') OR ($ref_id === '0')) {
      return FALSE;
    } elseif ($type_id = youtube_type_id($ref_type)) {
      $this->db->where(array(
        'ref_type' => $type_id,
        'ref_id'   => $ref_id
      ));
      $this->db->limit(1);
      $query = $this->db->get('YoutubeLink');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  /* CREATE youtube_link METHOD */
  function create_youtube_link($data) {
    $insert = array(
      'key'        => $data['key'],
      'ref_type'   => $data['ref_type'],
      'ref_id'     => $data['ref_id'],
      'created_at' => time(),
      'updated_at' => time(),
    );
    $this->db->insert('YoutubeLink', $insert);
    $id = $this->db->insert_id();
    return $id;
  }

  /* UPDATE youtube_link METHIOD */
  function update_youtube_link($data, $id) {
    $update = array();
    if (isset($data['key']))      { $update['key']      = $data['key'];      }
    if (isset($data['ref_type'])) { $update['ref_type'] = $data['ref_type']; }
    if (isset($data['ref_id']))   { $update['ref_id']   = $data['ref_id'];   }

    if ( ! empty($update)) {
      $update['updated_at'] = time();
      $this->db->where('id', $id);
      $this->db->update('YoutubeLink', $update);
    }
  }

  /* DELETE youtube_link METHOD */
  function delete_youtube_link($id) {
    $this->db->where('id', $id);
    $this->db->limit(1);
    $this->db->delete('YoutubeLink');
  }

}