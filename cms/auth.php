<?php
// Authenticates the user using IndieAuth.

include __DIR__ . "/../endpoint/common.php";

session_start() or die("Failed to start session");

function is_authenticated() {
    // The development server can't handle concurrent requests,
    // so the CURL request below will fail. Therefore, I decided
    // to turn authenticated of when running locally.
    return !!@$_SESSION['authenticated'] or is_builtin();
}

// Proceed if the user is logged in.
if(is_authenticated()) {
    // NOTE(robin): I reserved this block to do certain checks later.
    // For now, we'll leave it empty.
}

// Create a new authentication request.
elseif(!isset($_GET['code'])) {
    if(isset($_SESSION['state'])) {
        die("Detected another authentication session already running in this browser session. 
        Please clear your cookies and try again.");
    }

    $_SESSION['state'] = random_string();
    $_SESSION['code_verifier'] = random_string(44);
    
    $code_challenge = 
        base64_url_encode(hash('sha256', $_SESSION['code_verifier']));
        
    $scopes = implode(" ", SUPPORTED_SCOPES);
    $query = http_build_query([
        "client_id" => CLIENT_ID,
        "scope" => $scopes,
        "redirect_uri" => REDIRECT_URI,
        "state" => $_SESSION['state'],
        "code_challenge" => $code_challenge,
        "code_challenge_method" => "S256",
    ]);

    header("Location: " . AUTH_ENDPOINT . "?$query");
}

// Validate authentication response and request access token.
else {
    if(!isset($_GET['state'])) {
        json_error(400, "Missing 'state' parameter.");
    }

    if($_GET['state'] !== @$_SESSION['state']) {
        json_error(401, "Mismatched 'state'. This can possibly be caused by running multiple authentication requests in parallel.");
    }

    if(!isset($_SESSION['code_verifier'])) {
        json_error(403, "Either you're a hackerboy or I'm a dumb idiot. Or both.");
    }

    if($_GET['iss'] !== ISSUER) {
        json_error(401, "Mismatched issuer ('iss').");
    }

    $response = \http\post(AUTH_ENDPOINT, [
        "grant_type" => "authorization_code",
        "code" => $_GET['code'],
        "client_id" => CLIENT_ID,
        "redirect_uri" => REDIRECT_URI,
        "code_verifier" => $_SESSION['code_verifier'],
    ]);

    if($response['state'] == "failed") {
        json_error(500, "Failed to verify authorization code. Got: " . $response['body'] . ".");
    }

    $body = json_decode($response['body'], associative: true);

    if(@$body['me'] !== AUTHOR_SITE) {
        json_error(401, "You're not 'me'. So either you're a crazy hackerboy or I'm a dumd idiot. Or both.");
    }

    unset($_SESSION['state']);
    unset($_SESSION['code_verifier']);

    // Authenticate the user.
    $_SESSION['authenticated'] = true;
    header("Location: /");
}
