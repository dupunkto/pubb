<?php
// Various utility functions.

// Basically a dumping-ground for function I'd wish were
// just in the PHP standard library.

// Elixir+Rust fanboy :>

function dbg($input) {
  var_dump($input);
  return $input;
}

function todo($msg) {
  die("TODO: $msg");
}

// String utilities

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

// HTML utilities

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

function strip_comments($body) {
  return preg_replace('/<!--(.*)-->/Us', "", $body);
}

// Array utilities

function flatten($separator, $array) {
  $keys = array_keys($array);
  $values = array_values($array);
  
  return array_map(function($key, $value) use ($separator) {
    return $key . $separator . $value;
  }, $keys, $values);
}

function count_by($array, $key) {
  $keys = array_column($array, $key);

  return array_reduce($keys, function($acc, $key) {
    if (isset($acc[$key])) $acc[$key]++;
    else $acc[$key] = 1;

    return $acc;
  }, []);
}

function group_by($items, $prefix) {
  $grouped = [];
  
  foreach ($items as $item) {
    $id = $item[$prefix . "_id"];

    if (!isset($grouped[$id])) {
      $grouped[$id] = array_merge(
          unprefix_keys($item, $prefix),
          ['items' => []]
      );
    }
    
    $grouped[$id]['items'][] = $item;
  }

  return $grouped;
}

function unprefix_keys($array, $prefix) {
  $filtered = [];
  $prefix = $prefix."_";
  $len = strlen($prefix);

  foreach ($array as $key => $value) {
    if (strpos($key, $prefix) === 0) {
      $filtered[substr($key, $len)] = $value; 
    }
  }

  return $filtered;
}

function drop_empty($array) {
  return array_filter($array, function($value) {
    return !in_array($value, ["", null, false]);
  });
}

function deep_contains($haystack, $needle) {
  return $needle and count(array_filter($haystack, fn($candidate) => strpos($needle, $candidate) != false)) > 0;
}

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

// TODO(robin): decide of canonical URL form
// and implement this API.
function canonicalize_url($url) {
  return $url;
}

function is_url($str) {
  return str_starts_with($str, "http://") 
  	or str_starts_with($str, "https://");
}

// Path utilities

function path_join() {
  $paths = [];
  foreach (func_get_args() as $arg) {
    if ($arg !== '') $paths[] = $arg;
  }

  return preg_replace('#/+#','/',join('/', $paths));
}

function relative_to($path, $parent) {
  $absolute = realpath($path);
  $parent = realpath($parent);

  if (strpos($absolute, $parent) !== 0) return false;

  $relative = substr($absolute, strlen($parent));
  $relative = ltrim($relative, DIRECTORY_SEPARATOR);
  return $relative;
}

function filename($path) {
  return pathinfo($path, PATHINFO_FILENAME);
}

// Getters

function parse_host($url) {
  return strtolower(parse_url($url, PHP_URL_HOST));
}

function parse_path($url) {
  return parse_url($url, PHP_URL_PATH);
}

function parse_mime_type($path) {
  return @MIME_TYPES[parse_ext($path)];
}

function parse_ext($path, $fallback = null) {
  return strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: $fallback);
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

// Output buffering

function capture($function, ...$args) {
  ob_start();
  call_user_func_array($function, $args);
  return ob_get_clean();
}

// Crypto

function random_string($length = 12) {
  $x = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  return substr(str_shuffle(str_repeat($x, ceil($length/strlen($x)))), 1, $length);
}

// URL safe base64 encoding per 
// https://tools.ietf.org/html/rfc7515#appendix-C

function base64_url_encode($string) {
  $string = base64_encode($string);
  $string = rtrim($string, '=');
  $string = strtr($string, '+/', '-_');
  return $string;
}

function base64_url_decode($string) {
  $string = strtr($string, '-_', '+/');
  $padding = strlen($string) % 4;
  if($padding !== 0) {
    $string .= str_repeat('=', 4 - $padding);
  }
  $string = base64_decode($string);
  return $string;
}
