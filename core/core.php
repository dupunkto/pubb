<?php
// Public Pubb API.
// Mostly consists of wrappers around other namespaces in the Pubb core.

namespace core;

use Exception;

// Indexes

function list_indexes() {
  return array_merge(
    ["all" => "All"],
    \core\list_categories(),
    ["code" => "Gists"],
    ["photos" => "Photos"]
  );
}

function list_index_pages($index) {
  $c = \access\clearance('public');

  return match($index) {
    "all" => \store\list_pages($c),
    "code" => \store\list_gists(),
    "photos" => \store\list_photos(),
  };
}

function get_index_title($index) {
  return match($index) {
    "all" => "All",
    "index" => "Pages",
    "code" => "Code",
    "photos" => "Photos",
  };
}

function get_index_type($index) {
  return match($index) {
    "all" => LAYOUT_ALL,
    "index" => LAYOUT_INDEX,
    "code" => LAYOUT_CODE,
    "photos" => LAYOUT_PHOTOS,
  };
}

function list_category_pages($slug) {
  $c = \access\clearance('public');
  return \store\list_pages_by_category($slug, $c);
}

function get_category_title($slug) {
  $categories = list_categories();
  return $categories[$slug] ?? ucfirst($slug);
}

function get_category_type($slug) {
  return constant("LAYOUT_" . strtoupper($slug));
}

// Categories

function list_categories() {
  $categories = PAGES_CATEGORIES;
  
  $titles = array_map('ucfirst', $categories);
  $slugs = array_map('strtolower', $categories);

  return array_combine($slugs, $titles);
}

// Pages

function is_homepage($page) {
  if(str_starts_with(LAYOUT_HOMEPAGE, "/")) {
    return false;
  } else {
    return $page['id'] == (int)LAYOUT_HOMEPAGE;
  }
}

function get_page_title($page) {
  if($page['title']) return $page['title'];
  else if($page['type'] == 'code') return $page['slug'];
  else if($page['caption']) return $page['caption'];
  else return str_replace("-", " ", $page['slug']);
}

function get_page_by_url($url) {
  $slug = \urls\parse($url);
  if($slug) return \store\get_page_by_slug($slug);
}

function new_page(
  $slug,
  $type,
  $title,
  $prose,
  $visibility,
  $category = null,
  $draft = false,
  $reply_to = null,
  $lang = null
) {
  $now = date("Y-m-d H:i:s");
  $path = \store\write_file($prose, ".".$type);

  return \store\put_page(
    slug: $slug,
    type: $type,
    title: $title,
    lang: $lang,
    published: $now,
    updated: $now,
    path: $path,
    draft: $draft,
    visibility:  $visibility,
    category: $category,
    reply_to: $reply_to,
    caption: null
  );
}

function edit_page(
  $id,
  $slug,
  $type,
  $title,
  $prose,
  $visibility,
  $category = null,
  $draft = false,
  $reply_to = null,
  $lang = null
) {
  $updated = \store\page_changed($id, $prose);
  $published = \store\page_published($id, $draft);
  $path = \store\write_file($prose, ".".$type);

  return \store\update_page(
    id: $id,
    slug: $slug,
    type: $type,
    title: $title,
    lang: $lang,
    updated: $updated,
    published: $published,
    path: $path,
    draft: $draft,
    visibility: $visibility,
    category: $category,
    reply_to: $reply_to,
    caption: null
  );
}

// Visibility levels

function list_visibilities() {
  return [
    10 => "Private",
    20 => "Hidden", 
    30 => "Close Friends",
    40 => "RSS-Only",
    50 => "Public"
  ];
}

function visibility_to_level($str) {
  $normalize = fn($x) => str_replace([' ', '_'], '-', strtolower($x));
  $labels = array_map($normalize, list_visibilities());
  return array_search($normalize($str), $labels, true);
}

function level_to_visibility($lvl) {
  return list_visibilities()[$lvl];
}

function filter_feeds($pages) {
  return array_filter($pages, fn($p) => in_array($p['category'], FEEDS_CATEGORIES));
}

// Gists

function new_gist($filename, $code, $caption) {
  $now = date("Y-m-d H:i:s");
  $path = \store\write_file($code, $filename);

  return \store\put_page(
    slug: $filename,
    type: 'code',
    title: null,
    lang: null,
    published: $now,
    updated: $now,
    path: $path,
    draft: 0,
    visibility: 50,
    category: null,
    reply_to: null,
    caption: $caption
  );
}

function edit_gist($id, $filename, $code, $caption) {
  $updated = \store\page_changed($id, $code);
  $published = \store\page_published($id, false);
  $path = \store\write_file($code, $filename);

  return \store\update_page(
    id: $id,
    slug: $filename,
    type: 'code',
    title: null,
    lang: null,
    updated: $updated,
    published: $published,
    path: $path,
    draft: 0,
    visibility: 50,
    category: null,
    reply_to: null,
    caption: $caption,
  );
}

// Assets

function new_photo($slug, $caption, $path) {
  $now = date("Y-m-d H:i:s");

  return \store\put_page(
    slug: $slug,
    type: 'photo',
    title: null,
    lang: null,
    published: $now,
    updated: $now,
    path: $path,
    draft: 0,
    visibility: 50,
    category: null,
    reply_to: null,
    caption: $caption,
  );
}

function update_photo($id, $slug, $caption, $path) {
  $now = date("Y-m-d H:i:s");
  $published = \store\page_published($id, false);

  return \store\update_page(
    id: $id,
    slug: $slug,
    type: 'photo',
    title: null,
    lang: null,
    updated: $now,
    published: $published,
    path: $path,
    draft: 0,
    visibility: 50,
    category: null,
    reply_to: null,
    caption: $caption,
  );
}

function upload_photo($upload) {
  $ext = path_ext($upload['name'], "jpg");
  $tmp_file = $upload['tmp_name'];

  if($upload['error'] != UPLOAD_ERR_OK) {
    $error = match($upload['error']) {
      UPLOAD_ERR_INI_SIZE => "Upload too large.",
      UPLOAD_ERR_PARTIAL => "Upload only partially uploaded.",
      UPLOAD_ERR_NO_FILE => "No file was uploaded.",
      UPLOAD_ERR_NO_TMP_DIR => "Temporary folder to write to was missing.",
      UPLOAD_ERR_CANT_WRITE => "Couldn't write to disk.",
      UPLOAD_ERR_EXTENSION => "The upload was stopped by a PHP extension."
    };

    throw new Exception("'{$upload['name']}' failed: $error");
  }

  // This checks if someone isn't maliciously trying
  // to overwrite /etc/passwd or something.
  if(!is_uploaded_file($tmp_file) or !getimagesize($tmp_file))
    throw new Exception("Bad photo upload. Try again.");

  $path = \store\copy_file($tmp_file, $ext) 
    or throw new Exception("Copying '{$upload['name']}' over to data store failed.");

  return $path;
}

// Records

function get_record_title($schema, $record) {
  // Assumes the first field of the schema contains the title/name
  // and otherwise falls back to the 'id' field (which always exists).
  return $record[array_keys($schema['fields'])[0] ?? 'id'];
}

// @mentions

function record_mention($page, $source) { 
  $domain = url_host($source);
  $contact = \store\get_contact_by_domain($domain);

  \store\put_mention(
    origin: 'incoming',
    page_id: $page['id'],
    contact_id: $contact ? $contact['id'] : null,
    source: normalize_url($source),
  );

  \mailer\new_webmention(
    target: \urls\page_url($page),
    source: $source
  );
}

function send_mentions($page) {
  $pattern = '/@([a-zA-Z0-9]+)/';
  $content = \store\contents($page['path']);
  
  if(preg_match_all($pattern, $content, $handles)) {
    foreach($handles[1] as $handle) 
      send_mention($page, $handle);
  }
}

function send_mention($page, $handle) {
  $contact = \store\get_contact_by_handle($handle);

  if(!$contact) return false;
  if(get_sent_mention($page, $contact)) return true;

  if(\mailer\send_mention($page, $contact)) { 
    \store\put_mention(
      origin: 'outgoing',
      page_id: $page['id'],
      contact_id: $contact['id'],
      source: \urls\page_url($page),
    ) or die("Something went wrong."); 
  }
}

function get_sent_mention($page, $contact) {
  return \store\get_mention(
    origin: 'outgoing',
    page_id: $page['id'],
    contact_id: $contact['id'],
  );
}

// Webmentions & pingbacks

function send_webmentions($page) {
  $source_url = \urls\page_url($page);
  $targets = []; // TODO(robin): get all URLs from page.

  foreach($targets as $target_url) {
    \webmentions\send_webmention($source_url, $target_url);
  }
}

function send_pingbacks($page) {
  $source_url = \urls\page_url($page);
  $targets = []; // TODO(robin): get all URLs from page.

  foreach($targets as $target_url) {
    \pingbacks\send_pingback($source_url, $target_url);
  }
}

// Method to debug the micropub endpoint. It crashes the 
// endpoint and logs the request to log.json
function debug_endpoint() {
  http_response_code(400);
  echo "You sent:\n";
  print_r($_POST);

  file_put_contents(__DIR__ . "/log.json", json_encode($_POST));

  exit;
}
