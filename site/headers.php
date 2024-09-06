<?php
// Sets response headers.

header("Content-Language: " . SITE_LANG);

// Security & Privacy related headers.
header("Referrer-Policy: no-referrer");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: deny");
header("Cross-Origin-Opener-Policy: same-origin");

// See https://ncase.me/nutshell/#CORSForWebDevs
header("Access-Control-Allow-Origin: *");

// Makes IndieAuth work on sites with *weird* discovery
header('Link: <' . AUTH_ENDPOINT . '>; rel="authorization_endpoint"', replace: false);
header('Link: <' . TOKEN_ENDPOINT . '>; rel="token_endpoint"', replace: false);
header('Link: <' . MICROPUB_ENDPOINT . '>; rel="micropub"', replace: false);
header('Link: <' . CANONICAL . '>; rel="self"', replace: false);

// Shameless self-promotion
header("X-Powered-By: Pubb (v" . PUBB_VERSION . ")");
