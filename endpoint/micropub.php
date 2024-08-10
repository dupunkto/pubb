<?php
// Micropub endpoint for creating new posts.

require_once __DIR__ . "/common.php";
require_once __DIR__ . "/../core.php";

// Configuration requests don't need authentication
if($_GET["q"] == "config") {
  http_response_code(200);
  echo json_encode(["media-endpoint" => $MEDIA_ENDPOINT]);
  exit;
}

include __DIR__ . "/auth.php";

// First, perform some checks to filter out requests that use features that
// we (intentionally) don't support.

if(
  isset($_POST['repost-of']) or 
  isset($_POST['like-of']) or 
  isset($_POST['bookmark-of'])
) json_error(501, "Reposts, likes, or bookmarks are unsupported, use a reply instead.");

if(!empty($_FILES) and !isset($_FILES['photo']))
  json_error(501, "Only 'photo' uploads are supported.");

// Okey, request lookin' good, let's process it :D

$action = @$_POST['action'] ?? "create";
$allowed_actions = SUPPORTED_SCOPES;

if(!in_array($action, $allowed_actions)) {
  http_response_code(501);
  \resp\json_error("Action not supported.");
  exit;
}

include __DIR__ . "/micropub/$action.php";
