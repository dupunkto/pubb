<?php
// SQL-based store.

namespace store;

require __DIR__ . "/store/sqlite.php";
#require __DIR__ . "/store/mysql.php";

define('DBH', establish_connection());

// Pages

// A page type defines the content type for the 
// referenced file in the `path` column.
define('TYPES', ["md", "html", "txt", "photo", "code"]);

// The visibility of a page determines where it'll be rendered.
define('VISIBILITY', ["public", "rss-only", "hidden"]);

function put_page(
  $slug,
  $type,
  $title,
  $published,
  $updated,
  $path,
  $draft,
  $visibility,
  $caption,
  $reply_to
) {
  in_array($type, TYPES) or die("$type does not exist");
  in_array($visibility, VISIBILITY) or die("$visibility does not exist");
  in_store($path) or die("$path does not exist");

  return exec_query('INSERT INTO `pages` (
    `slug`, 
    `type`,
    `title`,
    `reply_to`,
    `path`,
    `draft`,
    `visibility`,
    `caption`,
    `published`,
    `updated`
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
    $slug,
    $type,
    $title,
    $reply_to,
    $path,
    $draft,
    $visibility,
    $caption,
    $published,
    $updated
  ]);
}

function update_page(
  $id,
  $slug,
  $type,
  $title,
  $path,
  $draft,
  $visibility,
  $updated,
  $caption,
  $reply_to,
) {
  in_array($type, TYPES) or die("$type does not exist");
  in_array($visibility, VISIBILITY) or die("$visibility does not exist");
  in_store($path) or die("$path does not exist");

  return exec_query('UPDATE `pages` SET
    `slug` = ?,
    `type` = ?,
    `title` = ?,
    `reply_to` = ?,
    `path` = ?,
    `draft` = ?,
    `visibility` = ?,
    `caption` = ?,
    `updated` = ?
  WHERE id = ?', [
    $slug,
    $type,
    $title,
    $reply_to,
    $path,
    $draft,
    $visibility,
    $caption,
    $updated,
    $id
  ]);
}

function get_page($id) {
  return one('SELECT * FROM `pages` WHERE `id` = ?', [$id]);
}

function get_page_by_slug($slug) {
  return one('SELECT * FROM `pages` WHERE `slug` = ?', [$slug]);
}

function delete_page($id) {
  return exec_query('DELETE FROM `pages` WHERE id = ? ', [$id]);
}

function pages_query() {
  return 'SELECT
    pages.id,
    pages.slug,
    pages.type,
    pages.title,
    pages.reply_to,
    pages.path,
    pages.draft,
    pages.visibility,
    pages.caption,
    pages.published,
    pages.updated,
    volumes.id AS volume_id,
    volumes.slug AS volume_slug,
    volumes.title AS volume_title,
    volumes.description AS volume_description,
    volumes.start_at AS volume_start_at,
    volumes.end_at AS volume_end_at
  FROM 
    pages
  LEFT JOIN
    volumes ON pages.published BETWEEN volumes.start_at AND volumes.end_at
  ORDER BY 
    pages.updated DESC,
    pages.id
  ';
}

function list_all_pages() {
  return all(pages_query());
}

function list_public_pages() {
  $pages = pages_query();
  return all("SELECT * FROM ($pages) WHERE `draft` != 1 AND `visibility` = 'public'");
}

function list_rss_pages() {
  $pages = pages_query();
  return all("SELECT * FROM ($pages) WHERE `draft` != 1 AND `visibility` != 'hidden'");
}

function list_regular_pages() {
  $pages = pages_query();
  return all("SELECT * FROM ($pages) WHERE `type` IN ('md', 'html', 'txt')");
}

function list_gists() {
  $pages = pages_query();
  return all("SELECT * FROM ($pages) WHERE `type` = 'code'");
}

function list_photos() {
  $pages = pages_query();
  return all("SELECT * FROM ($pages) WHERE `type` = 'photo'");
}

function last_updated() {
  $latest_page = one('SELECT `updated` FROM `pages` ORDER BY `updated` DESC', []);
  return strtotime($latest_page['updated']);
}

// Assets

function put_asset($slug, $path, $uploaded_as, $uploaded_at) {
  return exec_query('INSERT INTO `assets` (`slug`, `path`, `uploaded_as`, `uploaded_at`) 
    VALUES (?, ?, ?, ?)', [$slug, $path, $uploaded_as, $uploaded_at]);
}

function update_asset($id, $slug) {
  return exec_query('UPDATE `assets` SET `slug` = ? WHERE id = ?', [$slug, $id]);
}

function get_asset($id) {
  return one('SELECT * FROM `assets` WHERE `id` = ?', [$id]);
}

function get_asset_by_slug($slug) {
  return one('SELECT * FROM `assets` WHERE `slug` = ?', [$slug]);
}

function delete_asset($id) {
  return exec_query('DELETE FROM `assets` WHERE id = ?', [$id]);
}

function list_assets() {
  return all('SELECT * FROM `assets` ORDER BY `uploaded_at` DESC');
}

function linked_pages($asset) {
  return all('SELECT * FROM `pages` WHERE `path` = ?', [$asset['path']]);
}

// Volumes

function put_volume($slug, $title, $description, $start, $end) {  
  return exec_query('INSERT INTO `volumes`
    (`slug`, `title`, `description`, `start_at`, `end_at`)
    VALUES (?, ?, ?, ?, ?)', [$slug, $title, $description, $start, $end]);
}

function update_volume($id, $slug, $title, $description, $start, $end) {  
  return exec_query('UPDATE `volumes` 
    SET `slug` = ?, `title` = ?, `description` = ?, `start_at` = ?, `end_at` = ?
    WHERE `id` = ?', [$slug, $title, $description, $start, $end, $id]);
}

function get_volume($id) {  
  return one('SELECT * FROM `volumes` WHERE `id` = ?', [$id]);
}

function get_volume_by_slug($slug) {
  return one('SELECT * FROM `volumes` WHERE `slug` = ?', [$slug]);
}

function delete_volume($id) {
  return exec_query('DELETE FROM `volumes` WHERE id = ?', [$id]);
}

function list_volumes() {
  return all("SELECT * FROM `volumes` ORDER BY `start_at`");
}

function latest_volume() {
  return one('SELECT * FROM `volumes` ORDER BY `until` DESC', []);
}

// Contacts

function put_contact($handle, $domain, $email, $notify) {
  return exec_query('INSERT INTO `contacts` (`handle`, `domain`, `email`, `notify`) 
    VALUES (?, ?, ?, ?)', [$handle, $domain, $email, $notify]);
}

function update_contact($id, $handle, $domain, $email, $notify) {
  return exec_query('UPDATE `contacts` SET `handle` = ?, `domain` = ?, `email` = ?, `notify` = ? 
    WHERE `id` = ?', [$handle, $domain, $email, $notify, $id]);
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

function delete_contact($id) {
  return exec_query('DELETE FROM `contacts` WHERE id = ? ', [$id]);
}

function list_contacts() {
  return all('SELECT * FROM `contacts`');
}

// Mentions

define('ORIGINS', ["incoming", "outgoing"]);

function put_mention($origin, $contact_id, $page_id, $source) {
  in_array($origin, ORIGINS) or die("$origin does not exist");
  get_page($page_id)         or die("page with ID $page_id does not exist");
  get_contact($contact_id)   or die("contact with ID $contact_id does not exist");

  return exec_query('INSERT INTO `mentions` (`type`, `contact_id`, `page_id`, `source`) 
    VALUES (?, ?, ?, ?)', [$origin, $contact_id, $page_id, $source]);
}

function list_all_mentions($origin) {
  return all('SELECT 
    mention.id AS mention_id,
    mention.type AS mention_type,
    mention.contact_id,
    mention.page_id,
    mention.source,
    page.slug AS page_slug,
    page.type AS page_type,
    page.title AS page_title,
    page.reply_to AS page_reply_to,
    page.published AS page_published,
    page.updated AS page_updated,
    contact.handle AS contact_handle,
    contact.domain AS contact_domain,
    contact.email AS contact_email,
    contact.notify AS contact_notify
  FROM 
    mentions mention
  JOIN
    pages page ON mention.page_id = page.id
  LEFT JOIN 
    contacts contact ON mention.contact_id = contact.id
  WHERE
    mention.type = ?
  ORDER BY 
    page.title, mention.id
  ', [$origin]);
}

function list_mentions($origin, $page_id) { 
  return all('SELECT * FROM `mentions` WHERE 
    `page_id` = ? AND `type` = ?', [$page_id, $origin]);
}

function get_mention($origin, $page_id, $contact_id) {
  return one('SELECT * FROM `mentions` WHERE
    `type` = ? AND `page_id` = ? AND `contact_id` = ?',
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

function list_all_views() {
  return all('SELECT * FROM `views` ORDER BY `datetime` DESC');
}

// Uniqueness

function unique_slug($table, $seed) {
  $slug = slugify($seed);
  $num = 1;
  $try = $slug;
  while(slug_taken($table, $try)) $try = $slug . "-" . $num++;
  return dbg($try);
}

function slug_taken($table, $slug) {
  return !!one("SELECT slug FROM `$table` WHERE slug = ?", [$slug]);
}

// File-based storage

function write_file($contents, $ext) {
  // Writes the given text to a datetime-labeled
  // file with the given extension.

  $path = path_from_datetime($ext);
  file_put_contents($path, $contents);
  return relative_to($path, STORE);
}

function copy_file($source, $ext = null) {
  // Determine the destination filename in the store based
  // on the md5 hash of the file contents. If the file already
  // exists, return that one. Otherwise, write it.
  $path = path_from_hash($source, $ext ?? parse_ext($source));

  if(file_exists($path) or move_uploaded_file($source, $path)) {
    return relative_to($path, STORE);
  } else {
    return false;
  }
}

function in_store($path) {
  return file_exists(path_join(STORE, $path));
}

function contents($path) {
  return file_get_contents(path_join(STORE, $path));
}

function path_from_datetime($ext) {
  return STORE . "/content/" . date("Y-m-dTH:i:s") . "." . $ext;
}

function path_from_hash($source, $ext) {
  return STORE . "/uploads/" . hash_file("md5", $source) . "." . $ext;
}

// SQL helpers

function one($sql, $params) {
  return exec_query("$sql LIMIT 1", $params)?->fetch();
}

function all($sql, $params = []) {
  return exec_query($sql, $params)?->fetchAll();
}

function exec_query($sql, $params) {
  try {
    $query = DBH->prepare($sql);
    $query->execute($params);
    return $query;
  }
  catch(\PDOException $e) {
    trigger_error($e, E_USER_WARNING);
    return false;
  }
}

// Run migrations on the connected SQL database.
migrate(DBH);
