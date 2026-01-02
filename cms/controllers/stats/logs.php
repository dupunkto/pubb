<?php
// Per-page request logs.

$query = $_GET['path'] ?? null;
$pages = \store\list_all_pages();
$indexes = \core\list_indexes();

$index_paths = array_combine(
  array_map(fn($key) => '/' . $key, array_keys($indexes)),
  array_values($indexes)
);

$page_paths = array_combine(
  array_map(fn($page) => '/' . $page['slug'], $pages),
  array_map(fn($page) => \core\get_page_title($page), $pages)
);

$paths = array_merge(
  ['/' => '[root]'],
  $index_paths,
  $page_paths
);

$views = $query ? \store\list_views_by_path($query) : [];

include $view;