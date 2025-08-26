<?php
// Path and URL utilities

function url_host($url) {
  return strtolower(parse_url($url, PHP_URL_HOST));
}

function url_path($url) {
  return parse_url($url, PHP_URL_PATH);
}

function path_name($path) {
  return pathinfo($path, PATHINFO_FILENAME);
}

function path_ext($path, $fallback = null) {
  return strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: $fallback);
}

function path_mime($path) {
  return @MIME_TYPES[path_ext($path)];
}

function path_join() {
  $paths = [];
  foreach (func_get_args() as $arg) {
    if ($arg !== '') $paths[] = $arg;
  }

  return preg_replace('#/+#','/',join('/', $paths));
}
