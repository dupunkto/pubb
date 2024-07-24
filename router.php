<?php
// Pretty decent routing based on regexes.
//
// This files exposes two globals:
//
//   $path (string) is the request path, after applying normalization.
//   $params (array) contains capture groups from the route regex.
//

$path = $_SERVER['REQUEST_URI'];
$path = explode("?", $path)[0];
$path = "/" . trim($path, "/");

$params = [];

function route($pattern) {
  global $path, $params;
  return preg_match($pattern, $path, $params);
}

function is_builtin() {
  return php_sapi_name() == "cli-server";
}

function is_https() {
  return !empty($_SERVER['https']) and $_SERVER['HTTPS'] !== "off";
}
