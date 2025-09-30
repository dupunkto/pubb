<?php
// Various utility functions.

// URL utilities

function normalize_url($url) {
  $url = replace_prefix($url, "http://", "https://");
  $url = strtolower($url);
  
  if(!str_ends_with($url, "/")) {
    return $url .= "/";
  } else {
    return $url;
  }
}

// TODO(robin): decide of canonical URL form and
// implement this API.
function canonicalize_url($url) {
  return $url;
}

// Path utilities

function relative_to($path, $parent) {
  $absolute = realpath($path);
  $parent = realpath($parent);

  if(strpos($absolute, $parent) !== 0) return false;

  $relative = substr($absolute, strlen($parent));
  $relative = ltrim($relative, DIRECTORY_SEPARATOR);
  return $relative;
}

// File handling

function restructure_files($data) {
  $restructured = array();
  $count = count($data['name']);
  $keys = array_keys($data);

  for ($i = 0; $i < $count; $i++)
    foreach ($keys as $key) 
      $restructured[$i][$key] = $data[$key][$i];

  return $restructured;
}

// Crypto

function random_string($length = 12) {
  $x = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  return substr(str_shuffle(str_repeat($x, ceil($length/strlen($x)))), 1, $length);
}
