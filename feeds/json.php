<?php
// JSON feed, because. it. is. so. much. simpler.
// (Who tf thought XML was a good idea?!?)

require_once __DIR__ . "/../core.php";

include __DIR__ . "/caching.php";

header("Content-Type: application/feed+json; charset=UTF-8");

$pages = \store\list_rss_pages();
$entries = [];

foreach($pages as $page) {
  $entry = [
    "id" => $page['id'],
    "url" => \urls\page_url($page),
    "content_html" => page_content($page),
    "date_published" => $page['published'] . "Z",
    "date_modified" => $page['updated'] . "Z"
  ];

  if($page['title']) $entry['title'] = $page['title'];
  elseif($page['type'] == "code") $entry['title'] = $page['slug'];

  $entries[] = $entry;
}

function page_content($page) {
  return capture('\renderer\page_content', $page);
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
