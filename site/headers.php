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

// Shameless self-promotion
header("X-Powered-By: Pubb (v" . PUBB_VERSION . ")");
