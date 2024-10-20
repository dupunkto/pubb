<?php
// Pebble is the best CMS in the universe.
// (Sorry Pluto, there can only be one...)

require_once __DIR__ . "/../core.php";
require_once __DIR__ . "/../router.php";

include __DIR__ . "/auth.php";
require __DIR__ . "/mvc.php";

$method = $_SERVER['REQUEST_METHOD'];
$slug = strip_prefix($path, "/");
if($slug == "") $slug = "home";

// This is dangerous. But the user has already been
// authenticated at this point, so technically we can trust them.
// So let's leave it in. I like living on the edge.

$view = path_join($views, "$slug.php");
$controller = path_join($controllers, "$slug.php");
$stylesheet = path_join($styles, "$slug.css");

unset($slug);

?><!DOCTYPE html>
<html lang="en">
  <head>
    <?php include __DIR__ . "/partials/head.php" ?>
    <title>pebble</title>
    <style><?php include $stylesheet ?></style>
  </head>
  <body data-instant-intensity="mousedown">
    <?php include __DIR__ . "/partials/header.php" ?>
    <main>
      <?php render_flash(); ?>
      <?php
        switch(true) {
          case file_exists($controller):
            include $controller;
            break;

          case file_exists($view):
            include $view;
            break;

          default:
            include __DIR__ . "/partials/404.php";
            break;
        }
      ?>
    </main>

    <script src="<?= CMS_CANONICAL ?>/vendor/pubb-html.min.js"></script> 
    <script src="<?= CMS_CANONICAL ?>/vendor/instant-page.min.js" type="module"></script> 
  </body>
</html>
