<?php
// Delete a page.

require_once __DIR__ . "/../common.php";

if(!isset($action)) 
  json_error(403, "Nice try, hackerboy.");

if(!isset($_POST['url']))
  json_error(400, "Missing 'url' parameter.");

$url = normalize_url($_POST['url']);
$page = \core\get_page_by_url($url)
  or json_error(404, "The post you're trying to remove appears to be gone already?");

\store\delete_post($page['id']);

header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
