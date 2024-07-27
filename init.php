<?php
// The contents of this file will be evaluated just
// before the Core API loads, on every request.

// Error reporting during development.
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

if(!defined('PHP_VERSION_ID') or PHP_VERSION_ID < 80000) {
  die("The minimum required PHP version is 8.0. Please upgrade your PHP installation to continue.");
}

// Initialize data store if it doesn't exist yet.
if(!is_dir(STORE)) {
  mkdir(STORE);
  mkdir(STORE . "/content");
  mkdir(STORE . "/uploads");
}

// Error for mismatches between CANONICAL and FORCE_HTTPS.
if(FORCE_HTTPS and str_starts_with(CANONICAL, "http://")) {
  die("You've set FORCE_HTTPS to true, but the CANONICAL still contains http:// (instead of https://). This can potentially cause mixed content warnings, and messes with the canonical URL of your site!");
}
