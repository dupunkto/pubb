<?php
// Public Pubb API.
// Mostly consists of wrappers around other namespaces in the Pubb core.

namespace core;

// Method to debug the micropub endpoint. It crashes the 
// endpoint and logs the request to log.json
function debug_endpoint() {
    http_response_code(400);
    echo "You sent:\n";
    print_r($_POST);

    file_put_contents(__DIR__ . "/log.json", json_encode($_POST));

    exit;
}
