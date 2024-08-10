<?php
// Public Pubb API.
// Mostly consists of wrappers around other namespaces in the Pubb core.

namespace core;

// Pages

function get_page_by_url($url) {
  $slug = \urls\parse($url);
  if($slug) return \store\get_page_by_slug($slug);
}

function new_page($slug, $type, $title, $prose, $visibility, $draft = false, $reply_to = null) {
  $now = date("Y-m-d H:i:s");
  $path = \store\write_file($prose, ".".$type);

  return \store\put_page(
    slug: $slug,
    type: $type,
    title: $title,
    published: $now,
    updated: $now,
    path: $path,
    draft: $draft,
    visibility:  $visibility,
    reply_to: $reply_to,
    caption: null,
  );
}

function edit_page($id, $slug, $type, $title, $prose, $visibility, $draft = false, $reply_to = null) {
  $now = date("Y-m-d H:i:s");
  $path = \store\write_file($prose, ".".$type);

  return \store\update_page(
    id: $id,
    slug: $slug,
    type: $type,
    title: $title,
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
    updated: $now,
    path: $path,
    draft: 0,
    visibility: 'public',
    reply_to: null,
    caption: $caption,
  );
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
  $source_url = page_url($page);
  $targets = []; // TODO(robin): get all URLs from page.

  foreach($targets as $target_url) {
    \webmentions\send_webmention($source_url, $target_url);
  }
}

function send_pingbacks($page) {
  $source_url = page_url($page);
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
