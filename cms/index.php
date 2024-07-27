<?php
// Pebble is the best CMS in the universe.
// (Sorry Pluto, there can only be one...)

require_once __DIR__ . "/../core.php";
require_once __DIR__ . "/../router.php";

include __DIR__ . "/auth.php";

$method = $_SERVER['REQUEST_METHOD'];
if($path == "") $path = "home";

// This is dangerous. But the user has already been
// authenticated at this point, so technically we can trust them.
// So let's leave it in. I like living on the edge.

$view = __DIR__ . "/views/$path.php";
$controller = __DIR__ . "/controllers/$path.php";
$stylesheet = __DIR__ . "/css/$path.css";

include __DIR__ . "/mvc.php";

?><!DOCTYPE html>
<html lang="en">
  <head>
    <?php include __DIR__ . "/partials/head.php" ?>
    <title>Pebble</title>
    <style><?php include $stylesheet ?></style>
  </head>
  <body>
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
  </body>
</html>
