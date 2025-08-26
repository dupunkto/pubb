<?php
// HTML utilities.

function wrap($element, $str) {
  $escaped = htmlspecialchars($str);
  echo "<{$element}>{$escaped}</{$element}>";
}

function esc_attr($str) {
  return htmlspecialchars(
    string: $str, 
    flags: ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5,
    double_encode: false
  );
}

function esc_inner($str) {
  return htmlspecialchars($str);
}

function strip_comments($body) {
  return preg_replace('/<!--(.*)-->/Us', "", $body);
}
