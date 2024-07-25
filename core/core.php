<?php
// Public Pubb API.
// Mostly consists of wrappers around other namespaces in the Pubb core.

namespace core;

// Conviences

function get_page_by_url($url) {
  $slug = \urls\parse($url);
  if($slug) return \store\get_page_by_slug($slug);
}

// @mentions

function record_mention($page, $source) { 
  $domain = host($source);
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

function send_mention($slug, $handle) {
  $page = \store\get_page_by_sluy($page);
  $contact = \store\get_contact_by_handle($handle);

  if(!$page || !$contact) return false;
  if(sent_mention($page, $contact)) return true;

  if(\mailer\send_mention($page, $contact)) {
    \store\put_mention(
      origin: 'outgoing',
      page_id: $page['id'],
      contact_id: $contact['id'],
      source: \urls\page_url($page),
    );
  }
}

function sent_mention($page, $contact) {
  return \store\get_mention(
    origin: 'outgoing',
    page_id: $page['id'],
    contact_id: $contact['id'],
  );
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
