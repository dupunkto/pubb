<?php
// Webmention endpoint for cross-site commenting.

require_once __DIR__ . "/../core.php";

if(!isset($_POST['source'])) {
  http_response_code(400);
  echo "Missing 'source' parameter.";
  exit;
}

if(!isset($_POST['target'])) {
  http_response_code(400);
  echo "Missing 'target' parameter.";
  exit;
}

// The 'source' URL has to be a valid HTTP-fetchable page.

$source_scheme = parse_url($_POST['source'], PHP_URL_SCHEME);

if(in_array($source_scheme, ["http", "https"])) {
  http_response_code(400);
  echo "The URL scheme for the source URL is invalid.";
  exit;
}

// Make sure the 'target' page is actually on our site.

$target = normalize_url($_POST['target']);
$our_site = normalize_url(CANONICAL);

if(!str_starts_with($target, $our_site)) {
  http_response_code(400);
  echo "You can only send webmentions for URLs hosted on this site.";
  exit;
}

// Validate whether the 'target' page is a post page.

$page = \core\get_page_by_url($target);

if(!$page) {
  http_response_code(404);
  echo "The target URL (normalized: $target) does not accept webmentions.";
  exit;
}

// Validate whether the 'source' page actually 
// contains a link to 'target'.

$response = \http\get($_POST['source']);
$source = $reponse['body'];

if(stristr($source, $_POST['target'])) {
  http_response_code(400);
  echo "Your page doesn't actually mention mine.";
  exit;
}

// Everything looks good (*yay!). Let's process the Webmention!

http_response_code(202);
\core\record_mention($page, $_POST['source']);

// Redirect to target page, useful in case of sending 
// a webmention via the form in the comment section.
header('Link: ' . $target);

