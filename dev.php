<?php
// Development server. Contains certain routes that
// the production server doesn't need, as they're configured in Apache.

require_once __DIR__ . "/core.php";
require_once __DIR__ . "/router.php";

$_NODE = getenv("NODE");
  
switch(true) {
  case is_file(__DIR__ . $path) and is_builtin():
    // Serve file as-is. Only applies to the development server,
    // in production this will be handled by Apache directly.
    return false;

  // Serve RSS feeds. Again, only in development.
  case route('@/rss.xml$@'): include __DIR__ . "/feeds/rss.php"; exit;
  case route('@/atom.xml$@'): include __DIR__ . "/feeds/atom.php"; exit;
  case route('@/feed.json$@'): include __DIR__ . "/feeds/json.php"; exit;

  case route('@/endpoint/(\w+)$@'):
    // Here be dragons.
    // But it's development, so who cares. Nobody is gonna
    // run the dev server in prod anyway. Right... right???!
  
    include __DIR__ . "/endpoint/{$params[1]}.php";
    exit;

  default:
    // Depending on the environment, run either the 
    // site or CMS router.

    include __DIR__ . "/$_NODE/index.php";
    exit;
}
