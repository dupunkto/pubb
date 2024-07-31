<?php
// Various utility functions.

// Basically a dumping-ground for function I'd wish were
// just in the PHP standard library.

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
  echo "<{$element}>{$htmlspecialchars($str)}</{$element}>";
}

function escape_attribute($str) {
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
    return $key . $seperator . $value;
  }, $keys, $values);
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
  $relative = ltrim($relativePath, DIRECTORY_SEPARATOR);
  return $relative;
}

// Getters

function parse_host($url) {
  return strtolower(parse_url($url, PHP_URL_HOST));
}

function parse_mime_type($path) {
  $ext = strip_prefix(parse_ext($path), ".");
  return @MIME_TYPES[$ext];
}

function parse_ext($path) {
  return "." . strtolower(pathinfo($path, PATHINFO_EXTENSION));
}

// Casters

function cast_boolean($value) {
  return match ($value) {
    "true" => true,
    "false" => false,
  };
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
