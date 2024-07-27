<?php
// Dynamic configuration based on the JSON store.

define('STORE', __DIR__ . "/data");
define('CONFIG', STORE . "/config.json");

$_DEFAULTS = [];

if($json = @json_decode(file_get_contents(CONFIG))) {
  foreach($json as $key => $value) {
    define(normalize_key($key), normalize_value($value));
  }
} else {
  die("Failed to read config file.");
}

required('host');
required('site.lang');
required('site.title');
required('site.description');
optional('site.copyright');

fallback('force-https', false);
fallback('prefered-proto', FORCE_HTTPS ? "https" : "http");
fallback('canonical', PREFERED_PROTO . "://" . HOST);

required('author.name');
optional('author.email');
optional('author.picture');
fallback('author.site', CANONICAL);

optional('notifications.admin');
fallback('notifications.sender', "noreply@" . HOST);
fallback('notifications.webmention', true);

fallback('micropub-endpoint', CANONICAL . "/endpoint/micropub");
fallback('media-endpoint', CANONICAL . "/endpoint/media");
fallback('webmention-endpoint', CANONICAL . "/endpoint/webmention");
fallback('auth-endpoint', CANONICAL . "/endpoint/indieauth");
fallback('token-endpoint', "https://tokens.indieauth.com/token");
fallback('pingback-endpoint',
  "https://webmention.io/webmention?forward=" . WEBMENTION_ENDPOINT);

fallback('cms.host', "cms." . HOST);
fallback('cms.canonical', PREFERED_PROTO . "://" . CMS_HOST);

resolute('issuer', CANONICAL . "/");
resolute('client-id', ISSUER);
resolute('redirect-uri', CMS_CANONICAL . "/auth");
resolute('supported-scopes', ["create", "update", "delete", "media"]);

// Helpers

function required($key) {
  if(!defined(normalize_key($key))) {
    die("Missing required key '$key' in config.");
  }
}

function fallback($key, $value) {
  global $_DEFAULTS;
  $key = normalize_key($key);
  
  if(!defined($key)) {
    $_DEFAULTS[$key] = $value;
    define($key, $value);
  }
}

function resolute($key, $value) {
  $key = normalize_key($key);
  
  if(defined($key)) {
    die("Resolute key '$key' cannot be manually assigned.");
  } else {
    define($key, $value);
  }
}

function optional($key) {
  // Do nothing, this is just here to document
  // what configuration is available.
}

function is_fallback($key) {
  global $_DEFAULTS;
  $key = normalize_key($key);
  return isset($_DEFAULTS[$key]);
}

function normalize_key($key) {
  $key = str_replace("-", "_", $key);
  $key = str_replace(".", "_", $key);

  return strtoupper($key);
}

function normalize_value($value) {
  return match($value) {
    "true", "on", "yes" => true,
    "false", "off", "no" => false,
    default => $value
  };
}
