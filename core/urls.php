<?php
// Generates site URLs.

namespace urls;

function page_url($page) {
  return CANONICAL . "/" . $page['slug'];
}

function photo_url($asset) {
  str_starts_with($asset['path'], "uploads") or die("Illegal store path for asset.");
  return CANONICAL . "/" . $asset['path'];
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
