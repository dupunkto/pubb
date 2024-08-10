<?php
// Creates a new page.

require_once __DIR__ . "/../common.php";

if(!isset($action))
  json_error(403, "Nice try, hackerboy.");

$now = date("Y-m-d H:i:s");

$title = cast(@$_POST['name']);
$content = cast(@$_POST['content'] ?? $_POST['summary']);
$reply_to = cast(@$_POST['in-reply-to']);

// If `published` is given, use that, otherwise,
// fall back to the current datetime.
$published = $_POST['published'] ?? $now;

if(isset($_FILES['photo'])) {
  if(isset($_POST['photo']))
    syslog(LOG_WARN, "Micropub: you provided both a photo upload and URL. The URL will be ignored.");

  $client_name = $_FILES['photo']['name'];
  $tmp_file = $_FILES['photo']['tmp_name'];

  // This checks if someone isn't maliciously trying
  // to overwrite /etc/passwd or something.
  if(!is_uploaded_file($tmp_file) or !getimagesize($tmp_file)) 
    json_error(400, "Bad photo upload. Try again.");
 
  $slug = \store\unique_slug('assets', slugify($client_name));
  $path = \store\copy_file($tmp_file) 
    or json_error(500, "Something went wrong while saving your photo.");

  \store\put_page(
    slug: $slug,
    type: 'photo',
    path: $path,
    title: $title,
    published: $published,
    updated: $published,
    path: $path,
    draft: 0,
    visibility: 'public',
    caption: $content,
    reply_to: $reply_to,
  ) or json_error(500, "Something went wrong while saving your photo.");

} else if(isset($_POST['photo'])) {
  if(is_array($_POST['photo'])) {
    syslog(LOG_WARN, "Micropub: multiple photos provided, but the endpoint only supports a single photo per post, other photos will be ignored.");

    $url = $_POST['photo'][0];
  } else {
    $url = $_POST['photo'];
  }

  $slug = \store\unique_slug('assets', 'external');

  \store\put_page(
    slug: $slug,
    type: 'photo',
    path: $path,
    title: $title,
    published: $published,
    updated: $published,
    path: $url,
    draft: 0,
    visibility: 'public',
    caption: $content,
    reply_to: $reply_to,
  ) or json_error(500, "Something went wrong while saving your photo.");

} else {
  if(!$content) json_error(400, "Missing 'content' or 'summary' value in post payload.");

  if($title) $slug = \store\unique_slug('assets', slugify($title));
  else $slug = uniqid();

  $path = \store\write_file($content, "md");

  \store\put_page(
    slug: $slug,
    type: 'photo',
    path: $path,
    title: $title,
    published: $published,
    updated: $published,
    path: $path,
    draft: 0,
    visibility: 'public',
    caption: null,
    reply_to: $reply_to,
  ) or json_error(500, "Something went wrong while saving your post.");
  
}

$page = \store\get_page_by_slug($_POST['slug']) 
  or json_error(500, "Inserting and/or updating post in the database went wrong; couldn't lookup by slug.");

\core\send_pingbacks($page);
\core\send_webmentions($page);
\core\send_mentions($page);

header($_SERVER["SERVER_PROTOCOL"] . " 201 Created");
header("Location: " . \urls\page_url($page));
