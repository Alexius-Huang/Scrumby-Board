<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

class User_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  /* BASIC QUERY METHODS */
  function get_users($where = array()) {
    $this->db->where($where);
    $this->db->order_by('id', 'asc');
    $query = $this->db->get('User');
    return $query->result_array();
  }

  function get_user($id = '0') {
    if ($id == '0') {
      return FALSE;
    } else {
      $this->db->where('id', $id);
      $this->db->limit(1);
      $query = $this->db->get('User');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    }
  }

  function get_user_by_email($email = '') {
    if ($email == '') {
      return FALSE;
    } else {
      $this->db->where('email', $email);
      $this->db->limit(1);
      $query = $this->db->get('User');
      if ($query->num_rows() == 1) {
        return $query->row_array();
      } else {
        return FALSE;
      }
    }
  }

  /* CREATE USER METHOD - CURRENTLY WE DO NOT IMPLEMENT DUE TO PRIVATE USE */
  /* ALIAS TO user_signup() METHOD */
  /*
      function create_user($data) {
    
      }
  */

  /* UPDATE USER METHOD */
  function update_user($data, $id) {
    $update = array();
    if (isset($data['email']))    { $update['email']    = $data['email'];          }
    if (isset($data['password'])) { $update['password'] = sha1($data['password']); }
    if (isset($data['username'])) { $update['username'] = $data['username'];       }
    if (isset($data['status']))   { $update['status']   = $data['status'];         }
    
    if ( ! empty($update)) {
      $update['updated_at'] = time();
      $this->db->where('id', $id);
      $this->db->update('User', $update);

      /* Update Session */
      $this->session->set_userdata(array(
        'email'    => isset($update['email'])    ? $update['email']          : $this->session->userdata['email'],
        'password' => isset($update['password']) ? sha1($update['password']) : $this->session->userdata['password'],
        'username' => isset($update['username']) ? $update['username']       : $this->session->userdata['username'],
        'status'   => isset($update['status'])   ? $update['status']         : $this->session->userdata['status']
      ));
    } 
  }

  /* DELETE USER METHOD */
  function delete_user($id) {
    $this->db->where('id', $id);
    $this->db->delete('User');
  }

  /* OTHER ACTIONS */
  function user_signin($email = '', $password = '') {
    /* GET USER BY EMAIL */
    $this->db->where('email', $email);
    $this->db->limit(1);
    $query = $this->db->get('User');
    if ($query->num_rows() == 1) {
      /* CHECK USER'S PASSWORD */
      $user = $query->row_array();
      if ($user['password'] === sha1($password)) {
        /* SHOULD LOAD USER SESSION */
        $user_session = array(
          'id'          => $user['id'],
          'email'       => $user['email'],
          'username'    => $user['username'],
          'status'      => $user['status'],
          'last_signin' => time()
        );
        $this->session->set_userdata($user_session);
        $this->db->where('id', $user['id']);
        $this->db->update('User', array('last_signin' => time()));
        return $user_session;
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  function user_signout() {
    /* SHOULD DROP USER SESSION */
    $this->session->sess_destroy();
  }

}