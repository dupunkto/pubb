<?php
// Bare-bones implementation of WebFinger protocol.

require_once __DIR__ . "/../core.php";

header("Content-Type: application/jrd+json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

if(!isset($_GET['resource'])) {
  http_response_code(400);
  exit;
}

$identities = [CANONICAL];

if(defined('AUTHOR_EMAIL')) {
  $identities[] = "mailto:" . AUTHOR_EMAIL;
}

if(defined('PROFILE_HANDLE')) {
  $identities[] = "acct:" . strip_prefix(PROFILE_HANDLE, '@') . "@" . HOST;
}

if(!in_array($_GET['resource'], $identities)) {
  http_response_code(404);
  exit;
}

$subject = array_pop($identities);
$links = [];
$aliases = $identities;

if(AUTHOR_SITE != CANONICAL) {
  $links[] = [
    "rel"  => "me",
    "href" => AUTHOR_SITE
  ];
}

$links[] = [
  "rel"  => "http://webfinger.net/rel/profile-page",
  "type" => "text/html",
  "href" => \urls\homepage_url()
];

if(defined('PROFILE_PICTURE')) {
  $links[] = [
    "rel"  => "http://webfinger.net/rel/avatar",
    "href" => PROFILE_PICTURE,
  ];
}

$links = array_merge($links, [
  [
    "rel"  => "authorization_endpoint",
    "href" => AUTH_ENDPOINT,
  ],
  [
    "rel"  => "token_endpoint",
    "href" => TOKEN_ENDPOINT,
  ],
  [
    "rel"  => "micropub",
    "href" => MICROPUB_ENDPOINT,
  ],
  [
    "rel"  => "webmention",
    "href" => WEBMENTION_ENDPOINT,
  ],
]);

$links = array_merge($links, [
  [
    "rel" => "alternate",
    "type" => "application/rss+xml", 
    "href" => CANONICAL . "/rss.xml"
  ],
  [
    "rel" => "alternate",
    "type" => "application/atom+xml",
    "href" => CANONICAL . "/atom.xml"
  ],
  [
    "rel" => "alternate",
    "type" => "application/json",
    "href" => CANONICAL . "/feed.json"
  ]
]);

echo json_encode([
  "subject" => $subject,
  "links" => $links,
  "aliases" => $aliases
]);
