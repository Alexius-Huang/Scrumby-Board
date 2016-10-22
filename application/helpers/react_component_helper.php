<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

if ( ! function_exists('import_react_component') ) {
  function import_react_component($name) {
    return 'common/react_components/'.$name;
  }
}