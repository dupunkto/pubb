<?php
// IndieAuth token endpoint.

// Issues and verifies access tokens for the Micropub and media endpoints.

require_once __DIR__ . "/common.php";
require_once __DIR__ . "/../core.php";

define('TOKEN_CONTEXT', AUTHOR_SITE . "access_token");
define('TOKEN_EXPIRY', 365 * 24 * 3600);

// Exchange an authorization code for an access token.
if(@$_POST['grant_type'] == "authorization_code") {
  $client_id = filter_input(INPUT_POST, "client_id", FILTER_VALIDATE_URL);

  $params = [
    "grant_type" => "authorization_code",
    "code" => filter_input(INPUT_POST, "code"),
    "client_id" => $client_id,
    "redirect_uri" => filter_input(INPUT_POST, "redirect_uri"),
  ];

  $verifier = filter_input(INPUT_POST, "code_verifier", FILTER_UNSAFE_RAW);
  if(is_string($verifier)) $params['code_verifier'] = $verifier;

  $response = \http\post(AUTH_ENDPOINT, http_build_query($params), [
    "Content-Type" => "application/x-www-form-urlencoded",
    "Accept" => "application/json",
  ]);

  $auth = json_decode($response['body'], true);

  if($response['status'] >= 400 or !is_array($auth) or !isset($auth['me'])) {
    json_error(400, ["error" => "invalid_grant", "error_description" => "The authorization endpoint rejected the code."]);
  }

  $me = $auth['me'];
  $scope = $auth['scope'] ?? null;

  // An access token without scope grants nothing; a scopeless exchange is pure
  // authentication and should have stopped at the authorization endpoint.
  if($scope == null or $scope === "") {
    json_error(400, ["error" => "invalid_scope", "error_description" => "An access token requires at least one scope."]);
  }

  $token = \crypto\create_signed_code(
    ENCRYPTION_KEY,
    TOKEN_CONTEXT,
    TOKEN_EXPIRY,
    json_encode(['me' => $me, 'scope' => $scope, 'client_id' => $client_id])
  );

  json_data([
    "access_token" => $token,
    "token_type" => "Bearer",
    "scope" => $scope,
    "me" => $me,
  ]);
}

// Otherwise: resolve a bearer token into its grant.

$auth = "";
foreach(getallheaders() as $name => $value) {
  if(strtolower($name) == "authorization") {
    $auth = $value;
    break;
  }
}

$token = null;
if(preg_match('/^Bearer\s+(.+)$/i', trim($auth), $matches)) {
  $token = $matches[1];
} else {
  $posted = filter_input(INPUT_POST, "access_token");
  if(is_string($posted)) $token = $posted;
}

if($token == null or !\crypto\verify_signed_code(ENCRYPTION_KEY, TOKEN_CONTEXT, $token)) {
  json_error(401, ["error" => "invalid_token", "error_description" => "The access token is invalid or expired."]);
}

$payload = json_decode(\crypto\base64_url_decode(explode(":", $token, 3)[2]), true);

$grant = [
  "me" => $payload['me'],
  "client_id" => $payload['client_id'] ?? "",
  "scope" => $payload['scope'] ?? "",
];

// Our own Micropub endpoint expects a form-encoded body, because it
// is easy to parse in PHP. Also honour explicit Accept for other clients.
if(str_contains($_SERVER['HTTP_ACCEPT'] ?? "", "application/json")) {
  json_data($grant);
}

header("Content-Type: application/x-www-form-urlencoded");
echo http_build_query($grant);
exit;
