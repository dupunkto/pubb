<?php
// Public facing rendering engine.

require_once __DIR__ . "/../core.php";
require_once __DIR__ . "/../router.php";

include __DIR__ . "/headers.php";

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
    $pages = \store\list_pages_by_type($type, visibility: 'public');

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
    <main <?php if(isset($pages)) echo 'class="h-feed"' ?>>
      <?php

        switch(true) {
          case $not_found:
            include "partials/404.php";
            break;

          case isset($page):
            \html\render_page($page);
            \html\render_comment_section($page);
            
            break;

          case isset($pages) and count($pages) > 0:
            \html\render_pages($pages);
            break;

          default:
            \html\render_info("Nothing here. (anymore?)");
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
