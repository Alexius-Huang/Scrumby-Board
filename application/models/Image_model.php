<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class Image_model extends CI_Model {
  
  function __construct() {
    parent::__construct();
  }

  /* BASIC QUERY METHODS */
  function get_image_files($where = array()) {
    $this->db->where($where);
    $this->db->order_by('id', 'asc');
    $query = $this->db->get('ImageFile');
    return $query->result_array();
  }

  function get_image_file($id = '0') {
    if ($id === '0') {
      return FALSE;
    } else {
      $this->db->where('id', $id);
      $this->db->limit(1);
      $query = $this->db->get('ImageFile');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    }
  }

  function get_image_files_by_ref_type($ref_type = '') {
    if ($ref_type === '') {
      return FALSE;
    } elseif ($type_id = youtube_type_id($ref_type)) {
      $this->db->where('ref_type', $type_id);
      $this->db->order_by('id', 'asc');
      $query = $this->db->get('ImageFile');
      return $query->result_array();
    } else {
      return FALSE;
    }
  }

  function get_image_file_by_references($ref_type = '', $ref_id = '0') {
    if (($ref_type === '') OR ($ref_id === '0')) {
      return FALSE;
    } elseif ($type_id = youtube_type_id($ref_type)) {
      $this->db->where(array(
        'ref_type' => $type_id,
        'ref_id'   => $ref_id
      ));
      $this->db->limit(1);
      $query = $this->db->get('ImageFile');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  /* CREATE IMAGE FILE METHOD */
  function create_image_file($data) {
    $insert = array(
      'file_name'  => $data['file_name'],
      'ref_type'   => $data['ref_type'],
      'ref_id'     => $data['ref_id'],
      'created_at' => time(),
      'updated_at' => time(),
    );
    $this->db->insert('ImageFile', $insert);
    $id = $this->db->insert_id();
    return $id;
  }

  /* UPDATE IMAGE FILE METHOD */
  function update_image_file($data, $id) {
    $update = array();
    if (isset($data['file_name'])) { $update['file_name'] = $data['file_name']; }
    if (isset($data['ref_type']))  { $update['ref_type']  = $data['ref_type'];  }
    if (isset($data['ref_id']))    { $update['ref_id']    = $data['ref_id'];    }

    if ( ! empty($update)) {
      $update['updated_at'] = time();
      $this->db->where('id', $id);
      $this->db->update('ImageFile', $update);
    }
  }

  /* DELETE IMAGE FILE METHOD */
  function delete_image_file($id) {
    $this->db->where('id', $id);
    $this->db->limit(1);
    $this->db->delete('ImageFile');
  }

  function delete_image_asset_file($type, $file_name) {
    switch($type) {
      case 'Card':
        $image_url = FCPATH."assets/uploads/images/cards/".$file_name;
        break;
      case 'Panel':
        break;
    }
    unlink($image_url);
  }

}