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
    http_response_code(307);
    header("Location: " . \urls\homepage_url());
    exit;

  case route('@/(all|index|code|photos)$@'):
    $index = $params[1];
    $pages = \core\list_pages($index);
    $title = \core\get_index_title($index);
    $type = \core\get_index_type($index);

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

if(\agents\is_blocked()) {
  http_response_code(418);
  echo "I do not like you. I asked you to leave me alone nicely in /robots.txt, 
  but you're clearly not interested in anything resembling ethical behaviour; 
  that's exactly why you're now blocked. Fuck you and your techno-capitalist mindset.";
  exit;
}

?><!DOCTYPE html>
<html lang="<?= SITE_LANG ?>">
  <head>
    <?php include "partials/head.php" ?>
    <title><?= $title ?></title>
  </head>
  <body>
    <header>
      <?php include "partials/header.php" ?>
    </header>
    <main class="
      <?= isset($pages) ? "h-feed $type" : "single" ?>
      <?php if(isset($page)) echo $page['type'] ?>
    ">
      <?php

        switch(true) {
          case $not_found:
            include "partials/404.php";
            break;

          case isset($page):
            \renderer\page($page);
            \renderer\comment_section($page);
            break;

          case isset($pages) and count($pages) > 0:
            \renderer\index($pages, $type);
            break;

          default:
            \renderer\info("Nothing here. (anymore?)");
            break;
        }
        
      ?>
    </main>
    <nav>
      <?php include "partials/menu.php" ?>
    </nav>
    <footer>
      <?php include "partials/footer.php" ?>
    </footer>
  </body>
</html>
