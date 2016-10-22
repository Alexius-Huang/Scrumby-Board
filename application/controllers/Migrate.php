<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migrate extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    if(ENVIRONMENT != 'development'){
      if(!is_cli()){
        redirect('/');
      }
    }

    //initialize migration table if its empty
    $query = $this->db->get('migrations');
    $versions = $query->result_array();
    if (empty($versions)) {
      $insert = array(
        'version' => '0',
      );
      $this->db->insert('migrations', $insert);
    }

  }

  public function index()
  {
    //migrate to current version assiened in config/migration.php
    $this->load->library('migration');
    if($this->migration->current() === FALSE)
    {
      show_error($this->migration->error_string());
    }else{
      echo 'Migrate success.';
    }
  }

  public function latest()
  {
    //migrate to latest version in filesystem
    $this->load->library('migration');
    if($this->migration->latest() === FALSE)
    {
      show_error($this->migration->error_string());
    }else{
      echo 'Migrate success.';
    }
  }

  public function version($version = '')
  {
    //migrate to specific version
    $this->load->library('migration');
    if($this->migration->version($version) === FALSE)
    {
      show_error($this->migration->error_string());
    }else{
      echo 'Migrate success.';
    }
  }

}