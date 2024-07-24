<?php
// MySQL-based store.

namespace store;

require_once __DIR__ . "/store/sqlite.php";
#require_once __DIR__ . "/store/mysql.php";

define('DBH', establish_connection());

// Pages

// A page type defines the content type for the 
// referenced file in the `type` column.
define('TYPES', ["markdown", "html", "photo", "code"]);

function put_page(
  $slug, 
  $type, 
  $volume, 
  $title, 
  $published, 
  $path, 
  $updated = null, 
  $caption = null, 
  $reply_to = null,
) {
  in_array($type, TYPES) or die("$type does not exist");
  file_exists($path)     or die("$path does not exist");
  get_volume($volume)    or die("volume with ID $volume does not exist");

  return exec_query('INSERT INTO `pages` (
    `slug`, 
    `type`,
    `volume_id`,
    `title`,
    `reply_to`,
    `path`,
    `caption`,
    `published`,
    `updated`
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', [
    $slug,
    $type,
    $volume,
    $title,
    $reply_to,
    $path,
    $caption,
    $published,
    $updated ?? $published
  ]);
}

function get_page($id) {
  return one('SELECT * FROM `pages` WHERE `id` = ?', [$id]);
}

function get_page_by_slug($slug) {
  return one('SELECT * FROM `pages` WHERE `slug` = ?', [$slug]);
}

function list_pages($volume) {
  return all('SELECT 
    pages.id,
    pages.slug,
    pages.type,
    pages.volume_id,
    pages.title,
    pages.reply_to,
    pages.path,
    pages.caption,
    pages.published,
    pages.updated,
    volumes.id AS volume_id,
    volumes.slug AS volume_slug,
    volumes.title AS volume_title,
    volumes.description,
    volumes.created_at
  FROM 
    pages
  JOIN 
    volumes ON pages.volume_id = volumes.id
  ORDER BY 
    volumes.created_at DESC,
    volumes.id DESC,
    pages.updated DESC,
    pages.id ASC
  ');
}

function list_pages_by_type($type) {
  in_array($type, TYPES) or die("$type does not exist");

  return all('SELECT 
    pages.id,
    pages.slug,
    pages.type,
    pages.volume_id,
    pages.title,
    pages.reply_to,
    pages.path,
    pages.caption,
    pages.published,
    pages.updated,
    volumes.id AS volume_id,
    volumes.slug AS volume_slug,
    volumes.title AS volume_title,
    volumes.description,
    volumes.created_at
  FROM 
    pages
  JOIN
    volumes ON pages.volume_id = volumes.id
  WHERE
    pages.type = ?
  ORDER BY 
    volumes.created_at DESC,
    volumes.id DESC,
    pages.updated DESC,
    pages.id ASC
  ', [$type]);
}

function last_updated() {
  $latest_page = one('SELECT `updated` FROM `pages` ORDER BY `updated` DESC');
  return new DateTime($latest_page['updated']);
}

// Mentions

function put_mention($page_id, $source) {
  get_page($page_id) or die("page with ID $page_id does not exist");

  return exec_query('INSERT INTO `mentions` (`page_id`, `source`) VALUES (?, ?)', 
    [$page_id, $source]);
}

function list_mentions($page_id) {
  return all('SELECT * FROM `mentions` WHERE `page_id` = ?', [$page_id]);
}

// Views

function put_view($page_id, $referer, $agent, $datetime) {
  get_page($page_id) or die("page with ID $page_id does not exist");
  
  return exec_query('INSERT INTO `views` 
    (`page_id`, `referer`, `agent`, `datetime`) VALUES (?, ?)',
    [$page_id, $referer, $agent, $datetime]);
}

function list_views($year, $month) {
  $start = "$year-$month-01 00:00:00";
  $end = date("Y-m-d H:i:s", strtotime("$year-$month-01 +1 month"));

  return all("SELECT * FROM `views` WHERE `datetime` >= ? AND `datetime` < ?", 
    [$start, $end]);
}

// File-based storage

function write_file($contents, $ext) {
  // Writes the given text to a datetime-labeled
  // file with the given extension.

  $path = path_from_datetime($ext);
  file_put_contents($path, $content);
  return $path;
}

function copy_file($source, $dest) {
  // Determine the destination filename in the store based
  // on the md5 hash of the file contents. If the file already
  // exists, return that one. Otherwise, write it.
  $path = path_from_hash($source);

  if(file_exists($path) or move_uploaded_file($tmp_file, $path)) {
    return $path;
  } else {
    return false;
  }
}

function contents($path) {
  return file_get_contents($path);
}

function path_from_datetime($ext) {
  return STORE . "/content/" . date("Y-m-dTH:i:s") . $ext;
}

function path_from_hash($source, $ext) {
  return STORE . "/uploads/" . hash_file("md5", $source) . $ext;
}

function ext($filename) {
  return "." . strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

// SQL helpers

function one($sql, $params) {
  return exec_query("$sql LIMIT 1", $params)->fetch();
}

function all($sql, $params) {
  return exec_query($sql, $params)->fetchAll();
}

function exec_query($sql, $params) {
  $query = DBH->prepare($sql);
  $query->execute($params);
  return $query;
}

function migrate() {
  $migrations = __DIR__ . "/migrations.sql";

  $sql = file_get_contents($migrations);
  $queries = explode(';', $sql);

  foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) DBH->exec($query);
  }
}

// Run migrations on the connected SQL database.
migrate(DBH);
