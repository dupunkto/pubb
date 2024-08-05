<?php
// Generates site URLs.

namespace urls;

function page_url($page) {
  return CANONICAL . "/" . $page['slug'];
}

function photo_url($page) {
  return page_url($page);
}

function type_url($type) {
  return CANONICAL . match($type) {
    "photo" => "/photos",
    "code" => "/code",
    default => "/",
  };
}

function parse($url) {
  $path = parse_url($url, PHP_URL_PATH);
  return in_array($path, ["/", "/photos", "/code"]) ? false : $path;
}
