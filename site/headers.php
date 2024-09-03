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
header('Link: <' . AUTH_ENDPOINT . '>; rel="authorization_endpoint"');
header('Link: <' . TOKEN_ENDPOINT . '>; rel="token_endpoint"');
header('Link: <' . MICROPUB_ENDPOINT . '>; rel="micropub"');
header('Link: <' . CANONICAL . '>; rel="self"');

// Shameless self-promotion
header("X-Powered-By: Pubb (v" . PUBB_VERSION . ")");
