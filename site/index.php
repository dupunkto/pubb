<?php
// Public facing rendering engine.

require_once __DIR__ . "../core.php";
require_once __DIR__ . "../router.php";

$not_found = false;

// Maps URL type -> page type
$page_types = array(
  "photos" => "photo",
  "code" => "code"
);

switch(true) {
  case !is_https() and FORCE_HTTPS:
    http_response_code(301);
    header("Location: https://" . HOST . $_SERVER['REQUEST_URI']);
    exit;
  
  case $path == "/":
    $pages = \store\list_pages();
    break;

  case route('@/(photos|code)$@'):
    $type = $page_types[$params[1]];
    $pages = \store\list_pages_by_type($type);

    break;

  case route('@/(.*)$@'):
    $slug = $params[1];
    $page = \store\get_page_by_slug($slug);

    if(!$page) $not_found = true;
    break;

  default:
    $not_found = true;
    break;
}

if($not_found) {
  http_response_code(404);
} else {
  \stats\record_view($path);
}

?><!DOCTYPE html>
<html lang="<?= SITE_LANG ?>">
  <head>
    <?php include "partials/head.php" ?>
    <title><?= SITE_TITLE ?></title>
  </head>
  <body>
    <header>
      <?php include "partials/header.php" ?>
    </header>
    <main <?php if(isset($posts)) echo 'class="h-feed"' ?>>
      <?php

        switch(true) {
          case $not_found:
            include "partials/404.php";
            break;

          case isset($page):
            \renderer\render_page($post);
            \renderer\render_comment_section($post);
            
            break;

          case isset($pages) and count($pages) > 0:
            \renderer\render_pages($pages);
            break;

          default:
            \renderer\render_info("Nothing here. (anymore?)");
            break;
        }
        
      ?>
    </main>
    <aside>
      <?php include "partials/menu.php" ?>
    </aside>
    <footer>
      <?php include "partials/footer.php" ?>
    </footer>
  </body>
</html>
