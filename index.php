<?php
// Application entrypoint. Can serve as a catch-all for running the
// builtin PHP webserver, with the bundled .htaccess file in production.

if(getenv("ENV") == 'dev') {
  error_reporting(E_ALL & ~E_DEPRECATED);
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
}

require_once __DIR__ . "/core.php";
require_once __DIR__ . "/router.php";

$_NODE = match($_SERVER['HTTP_HOST']) {
  CMS_HOST => "cms",
  HOST => "site",
  default => die("Unknown host!")
};

function serve_file($path) {
  $mime_type = path_mime($path) ?? "text/html";

  header("Content-Type: {$mime_type}");
  include $path;
  exit;
}

$requested_file = path_join(__DIR__, $_NODE, $path);
$requested_upload = path_join(STORE, $path);

switch(true) {
  case is_file($requested_file) and is_builtin():
    // Serve file as-is. Only applies to the development server,
    // in production this will be handled by Apache directly.

    serve_file($requested_file);

  case is_file($requested_upload) and is_builtin():
    // Serve (image) file as-is. Only applies to the development server,
    // in production this will be handled by Apache directly.

    serve_file($requested_upload);

  // Serve RSS feeds and endpoints. Again, only in development.
  // (In production this is yet again handled via Apache directly.)
  case route('/rss.xml$'): include __DIR__ . "/feeds/rss.php"; exit;
  case route('/atom.xml$'): include __DIR__ . "/feeds/atom.php"; exit;
  case route('/feed.json$'): include __DIR__ . "/feeds/json.php"; exit;
  case route('/sitemap.xml$'): include __DIR__ . "/feeds/sitemap.php"; exit;
  case route('/robots.txt$'): include __DIR__ . "/feeds/robots.php"; exit;
  case route('/endpoint/(\w+)$'): include __DIR__ . "/endpoint/{$params[1]}.php"; exit;

  // Depending on the environment, run either the site or CMS router.
  default:
    include __DIR__ . "/$_NODE/index.php";
    exit;
}
