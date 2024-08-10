<?php
// Updates an existing post.

require_once __DIR__ . "/../common.php";

if(!isset($action)) 
  json_error(403, "Nice try, hackerboy.");

if(!isset($_POST['url']))
  json_error(400, "Missing 'url' parameter.");

$url = normalize_url($_POST['url']);
$page = \core\get_page_by_url($url)
  or json_error(404, "The post you're trying to edit appears to be non-existant.");
  
if(isset($_GET['replace'])) {
  $keys = $_GET['replace'];

  if(isset($keys['name'])) {
    $title = cast($keys['name']);

    \store\exec_query('UPDATE `pages` SET title = ?', [$title])
      or json_error(500, "Failed to update entry in the database. Try again (later?)");
  }

  if(isset($keys['summary'])) {
    if($page['type'] == 'photo') {
      $caption = cast($keys['summary']);

      \store\exec_query('UPDATE `pages` SET caption = ?', [$caption])
        or json_error(500, "Failed to update entry in the database. Try again (later?)");
    } else {
      $path = \store\write_file($keys['summary'], "{$page['slug']}.md");

      \store\exec_query('UPDATE `pages` SET path = ?', [$path])
        or json_error(500, "Failed to update entry in the database. Try again (later?)");
    }
  }

  if(isset($keys['content'])) {
    if($page['type'] == 'photo') {
      $caption = cast($keys['content']);

      \store\exec_query('UPDATE `pages` SET caption = ?', [$caption])
        or json_error(500, "Failed to update entry in the database. Try again (later?)");
    } else {
      $path = \store\write_file($keys['content'], "{$page['slug']}.md");

      \store\exec_query('UPDATE `pages` SET path = ?', [$path])
        or json_error(500, "Failed to update entry in the database. Try again (later?)");
    }
  }
}

$page = \store\get_page($_POST['id']) 
  or json_error(500, "Inserting and/or updating post in the database went horribly wrong; couldn't lookup by ID anymore.");

header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
header("Location: " . \urls\page_url($page));
