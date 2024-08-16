<?php
// Public Pubb API.
// Mostly consists of wrappers around other namespaces in the Pubb core.

namespace core;

use Exception;

// Pages

function get_page_title($page) {
  if($page['title']) return $page['title'];
  else if($page['type'] == 'code') return $page['slug'];
  else if($page['caption']) return $page['caption'];
  else return ucfirst($page['slug']);
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
    reply_to: $reply_to,
    caption: null,
  );
}

function edit_page(
  $id, 
  $slug, 
  $type, 
  $title, 
  $prose, 
  $visibility, 
  $draft = false, 
  $reply_to = null,
  $lang = null
) {
  $now = date("Y-m-d H:i:s");
  $path = \store\write_file($prose, ".".$type);

  return \store\update_page(
    id: $id,
    slug: $slug,
    type: $type,
    title: $title,
    lang: $lang,
    updated: $now,
    path: $path,
    draft: $draft,
    visibility: $visibility,
    reply_to: $reply_to,
    caption: null,
  );
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
    visibility: 'public',
    reply_to: null,
    caption: $caption,
  );
}

function edit_gist($id, $filename, $code, $caption) {
  $now = date("Y-m-d H:i:s");
  $path = \store\write_file($code, $filename);

  return \store\update_page(
    id: $id,
    slug: $filename,
    type: 'code',
    title: null,
    lang: null,
    updated: $now,
    path: $path,
    draft: 0,
    visibility: 'public',
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
    visibility: 'public',
    reply_to: null,
    caption: $caption,
  );
}

function update_photo($id, $slug, $caption, $path) {
  $now = date("Y-m-d H:i:s");

  return \store\update_page(
    id: $id,
    slug: $slug,
    type: 'photo',
    title: null,
    lang: null,
    updated: $now,
    path: $path,
    draft: 0,
    visibility: 'public',
    reply_to: null,
    caption: $caption,
  );
}

function upload_photo($upload) {
  $ext = parse_ext($upload['name'], "jpg");
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

// @mentions

function record_mention($page, $source) { 
  $domain = parse_host($source);
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
  
  if (preg_match_all($pattern, $content, $handles)) {
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
