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

function list_pages() {
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
  ', []);
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

// Contacts

function put_contact($handle, $domain, $email) {
  return exec_query('INSERT INTO `contacts` (`handle`, `domain`, `email`) 
    VALUES (?, ?, ?)', [$handle, $domain, $email]);
}

function get_contact($id) {
  return one('SELECT * FROM `contacts` WHERE `id` = ?', [$id]);
}

function get_contact_by_handle($handle) {
  return one('SELECT * FROM `contacts` WHERE `handle` = ?', [$handle]);
}

function get_contact_by_domain($domain) {
  return one('SELECT * FROM `contacts` WHERE `domain` = ?', [$domain]);
}

function get_contact_by_email($email) {
  return one('SELECT * FROM `contacts` WHERE `email` = ?', [$email]);
}

// Mentions

define('ORIGINS', ["incoming", "outgoing"]);

function put_mention($origin, $contact_id, $page_id, $source) {
  in_array($origin, ORIGINS) or die("$origin does not exist");
  get_page($page_id)         or die("page with ID $page_id does not exist");
  get_contact($contact_id)   or die("contact with ID $contact_id does not exist");

  return exec_query('INSERT INTO `mentions` (`origin`, `contact_id`, `page_id`, `source`) 
    VALUES (?, ?, ?, ?)', [$origin, $contact_id, $page_id, $source]);
}

function list_mentions($origin, $page_id) { 
  return all('SELECT * FROM `mentions` WHERE 
    `page_id` = ? AND `origin` = ?', [$page_id, $origin]);
}

function get_mention($origin, $page_id, $contact_id) {
  return one('SELECT * FROM `mentions` WHERE
    `origin` = ? AND `page_id` = ? AND `contact_id` = ?',
    [$origin, $page_id, $contact_id]);
}

// Views

function put_view($path, $referer, $agent, $datetime) { 
  return exec_query('INSERT INTO `views` 
    (`path`, `referer`, `agent`, `datetime`) VALUES (?, ?, ?, ?)',
    [$path, $referer, $agent, $datetime]);
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
  return relative_to($path, STORE);
}

function copy_file($source, $dest) {
  // Determine the destination filename in the store based
  // on the md5 hash of the file contents. If the file already
  // exists, return that one. Otherwise, write it.
  $path = path_from_hash($source, ext($source));

  if(file_exists($path) or move_uploaded_file($tmp_file, $path)) {
    return relative_to($path, STORE);
  } else {
    return false;
  }
}

function contents($path) {
  return file_get_contents(STORE . $path);
}

function path_from_datetime($ext) {
  return STORE . "/content/" . date("Y-m-dTH:i:s") . $ext;
}

function path_from_hash($source, $ext) {
  return STORE . "/uploads/" . hash_file("md5", $source) . $ext;
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

// Run migrations on the connected SQL database.
migrate(DBH);
