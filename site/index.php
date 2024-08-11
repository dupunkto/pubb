<?php
// Public facing rendering engine.

require_once __DIR__ . "/../core.php";
require_once __DIR__ . "/../router.php";

include __DIR__ . "/caching.php";
include __DIR__ . "/headers.php";

$not_found = false;

switch(true) {
  case !is_https() and FORCE_HTTPS:
    http_response_code(301);
    header("Location: https://" . HOST . $_SERVER['REQUEST_URI']);
    exit;
  
  case $path == "/":
    http_response_code(301);
    header("Location: " . CANONICAL . "/all");
    exit;

  case $path == "/all":
    $pages = \store\list_public_pages();
    $title = "All pages";

    break;

  case $path == "/code":
    $pages = \store\list_gists();
    $title = "Code";

    break;

  case $path == "/photos":
    $pages = \store\list_photos();
    $title = "Photos";

    break;

  case route('@/(.*)$@'):
    $slug = $params[1];
    $page = \store\get_page_by_slug($slug);

    if($page) {
      $title = \core\get_page_title($page);
      break;
    }

  default:
    http_response_code(404);
    $title = "Whoops, a 404!";
    $not_found = true;

    break;
}

\stats\record_view($path);

if(@$page['type'] == 'txt') {
  header("Content-Type: text/plain; charset=UTF-8");
  echo \renderer\plain_text($page);
  exit;
}

?><!DOCTYPE html>
<html lang="<?= SITE_LANG ?>">
  <head>
    <?php include "partials/head.php" ?>
    <title><?= $title ?></title>
  </head>
  <body class="twitter-like">
    <header>
      <?php include "partials/header.php" ?>
    </header>
    <main <?php if(isset($pages)) echo 'class="h-feed"' ?>>
      <?php

        switch(true) {
          case $not_found:
            include "partials/404.php";
            break;

          case isset($page):
            \partials\page($page);
            \partials\comment_section($page);
            
            break;

          case isset($pages) and count($pages) > 0:
            \partials\listing($pages);
            break;

          default:
            \renderer\info("Nothing here. (anymore?)");
            break;
        }
        
      ?>
    </main>
    <nav>
      <details open>
        <summary>Menu</summary>
        <?php include "partials/menu.php" ?>
      </details>
    </nav>
    <footer>
      <?php include "partials/footer.php" ?>
    </footer>
  </body>
</html>
