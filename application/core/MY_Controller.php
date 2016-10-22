<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
  created by pinchieh 2014/03/12
  reference:
  http://philsturgeon.co.uk/blog/2010/02/CodeIgniter-Base-Classes-Keeping-it-DRY
  http://ellislab.com/codeigniter/user-guide/general/core_classes.html
*/
class MY_Controller extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
  }
}

class WEB_Controller extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->library('session');
    $this->load->helper('url');
  }
}
