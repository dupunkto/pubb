<?php
// String utilities

function is_url($str) {
  return str_starts_with($str, "http://") 
  	or str_starts_with($str, "https://");
}

function is_email($str) {
  return substr_count($str, '@') == 1;
}

function is_whitespace($c) {
  return in_array($c, array(" ", "\t", "\n", "\r", "\0", "\x0B"));
}

function strip_prefix($str, $prefix) {
  return replace_prefix($str, $prefix, "");
}

function replace_prefix($str, $old, $new) {
  if(str_starts_with($str, $old)) {
    return $new . substr($str, strlen($old));
  } else {
    return $str;
  }
}

function strip_suffix($str, $suffix) {
  return replace_suffix($str, $suffix, "");
}

function replace_suffix($str, $old, $new) {
  if (str_ends_with($str, $old)) {
    return substr($str, 0, strlen($str) - strlen($old)) . $new;
  } else {
    return $str;
  }
}

function slugify($text, $length = null) {
  $text = strtr($text, UNICODE_TABLE);
  $text = preg_replace('~[^\pL\d.]+~u', '-', $text);
  $text = preg_replace('~[^-\w.]+~', '-', $text);
  $text = trim($text, '-');
  $text = preg_replace('~-+~', '-', $text);
  $text = strtolower($text);

  if (isset($length) and $length < strlen($text))
    $text = rtrim(substr($text, 0, $length), '-');

  return $text;
}
