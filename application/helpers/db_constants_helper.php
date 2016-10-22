<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

if ( ! function_exists('card_type') ) {
  function card_type($type_id = '0') {
    $type_list = array(
      '1' => 'Plain Text',
      '2' => 'Image with Text',
      '3' => 'YouTube with Text',
      '9' => 'Other Files'
    );
    if ($type_id === '0') {
      return $type_list;
    } elseif ( ! empty($type_list[$type_id])) {
      return $type_list[$type_id];
    } else {
      return FALSE;
    }
  }
}

if ( ! function_exists('youtube_type_id') ) {
  function youtube_type_id($type = '') {
    $type_list = array(
      'Card' => '1',
      'Panel' => '2'
    );
    if ($type === '') {
      return $type_list;
    } elseif (isset($type_list[$type])) {
      return $type_list[$type];
    } else {
      return FALSE;
    } 
  }
}

if ( ! function_exists('image_type_id') ) {
  function image_type_id($type = '') {
    return youtube_type_id($type);
  }
}