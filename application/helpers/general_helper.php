<?php
defined('BASEPATH') OR exit('No direct script access allowed!');

if ( ! function_exists('pluralize') ) {
  function pluralize($number = '', $singular = '', $plural = '') {
    if (empty($singular)) {
      return FALSE;
    } else {
      switch (intval($number)) {
        case 1:
          return "1 {$singular}";
        default:
          if ( ! empty($plural)) {
            return "{$number} {$plural}";
          } else {
            return "{$number} {$singular}s";
          }
      }
    }
  }
}

if ( ! function_exists('add_icon') ) {
  function add_icon($icon_name = '', $additional_classes = array()) {
    if ($icon_name === '') {
      return FALSE;
    } else {      
      return '<i class="fa fa-'.$icon_name.' '.implode(' ', $additional_classes).'" aria-hidden="true"></i>';
    }
  }
}

if ( ! function_exists('react_add_icon') ) {
  function react_add_icon($icon_name = '', $additional_classes = array()) {
    if ($icon_name === '') {
      return FALSE;
    } else {      
      return '<i className="fa fa-'.$icon_name.' '.implode(' ', $additional_classes).'" aria-hidden="true"></i>';
    }
  }
}

if ( ! function_exists('truncate') ) {
  function truncate($text, $ending = '...', $chars = 100) {
    $result = $text." ";
    $result = substr($result, 0, $chars);
    $result = substr($result, 0, strrpos($result,' '));
    if (strlen($text) > $chars) { 
      $result = $result.$ending;
    }
    return $result;
  }
}

if ( ! function_exists('timestamp_to_datetime') ) {
  function timestamp_to_datetime($timestamp) {
    return date('Y-m-d H:i:s', $timestamp);
  }
}

if ( ! function_exists('newline_to_br') ) {
  function newline_to_br($input) {
    return preg_replace("/\r\n|\r|\n/", '<br/>', $input);
  }
}

if ( ! function_exists('br_to_newline') ) {
  function br_to_newline($input) {
    return str_ireplace('<br/>', '\\n', $input);
  }
}

if ( ! function_exists('random_string') ) {
  function random_string($length = 10, $mode = 'alpha') {
    switch ($mode) {
      case 'alpha':
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        break;
      case 'alphanumeral':
        $characters = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        break;
      case 'numeral':
        $characters = '1234567890';
        break;
      default:
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}

if ( ! function_exists('get_image_link') ) {
  function get_image_link($type, $file_name) {
    switch ((int)image_type_id($type)) {
      case 1:
        $ext = 'cards';
        break;
      case 2:
        $ext = 'panels';
        break;
    }
    return base_url().'assets/uploads/images/'.$ext.'/'.$file_name;
  }
}

if ( ! function_exists('youtube_base_url') ) {
  function youtube_base_url($key = "") {
    return "https://www.youtube.com/watch?v=".$key;
  }
}

if ( ! function_exists('youtube_link_to_key') ) {
  function youtube_link_to_key($link) {
    return substr(explode('&', $link)[0], strlen(youtube_base_url()));
  }
}

if ( ! function_exists('get_youtube_image_link') ) {
  function get_youtube_image_link($key, $size_type) {
    $image = NULL;
    switch((int)($size_type)) {
      case 1: $image = 'default.jpg';       break;
      case 2: $image = 'hqdefault.jpg';     break;
      case 3: $image = 'mqdefault.jpg';     break;
      case 4: $image = 'sddefault.jpg';     break;
      case 5: $image = 'maxresdefault.jpg'; break;
    }
    return 'https://img.youtube.com/vi/'.$key.'/'.$image;
  }
}