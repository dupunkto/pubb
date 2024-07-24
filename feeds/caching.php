<?php
// Handles caching via conditional requests.

// We want to only serve the request if the feed has changed, 
// which we determine based on the `last_updated` property.
$last_updated = \store\last_updated();

$_HEADERS = [];
foreach(getallheaders() as $name => $value) {
    $_HEADERS[$name] = $value;
}

function equal($name, $value) {
  global $_HEADERS;
  return !empty($_HEADERS[$name]) and $_HEADERS[$name] == $value;
}

$last_modified = date("r", strtotime($last_updated));
$etag = md5($last_modified);

header("Last-Modified: $last_modified");
header("ETag: $etag");

$stale = false;

if(!equal("If-Modified-Since", $last_modified)) $stale = true;
if(!equal("If-None-Match", $etag)) $stale = true;

if(!$stale) {
  http_response_code(304);
  exit;
}
