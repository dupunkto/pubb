<?php
// JSON feed, because. it. is. so. much. simpler.
// (Who tf thought XML was a good idea?!?)

include __DIR__ . "/../core.php";
include __DIR__ . "/caching.php";

header("Content-Type: application/feed+json; charset=UTF-8");

$pages = \store\list_pages();
$entries = [];

foreach($pages as $page) {
  $entries[] = array(
    "id" => $page['id'],
    "url" => \urls\page_url($page),
    "title" => $page['title'],
    "content_html" => render_to_str($page),
    "date_published" => $page['published'] . "Z",
    "date_modified" => $page['updated'] . "Z"
  );
}

function render_to_str($page) {
  ob_start();
  \renderer\render_page_content($page);
  return ob_get_clean();
}

echo json_encode(array(
  "version" => "https://jsonfeed.org/version/1.1",
  "title" => SITE_TITLE,
  "description" => SITE_DESCRIPTION,
  "language" => SITE_LANG,
  "author" => AUTHOR_NAME,
  "home_page_url" => CANONICAL . "/",
  "feed_url" => CANONICAL . "/feed.json",
  "items" => $entries
));
