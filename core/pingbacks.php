<?php
// Handles sending pingbacks.

namespace pinkbacks;

function send_pingback($source_url, $target_url) {
  $endpoint = discover_endpoint($target_url);
    
  if(!$endpoint) return false;

  $payload = xmlrpc_encode_request("pingback.ping", array(
    $source_url, 
    $target_url
  ));

  $response = \http\post($endpoint, $payload, [
    "Content-Type" => "application/xml"
  ]);

  // Collapse whitespace just to be safe
  $body = strtolower(preg_replace('/\s+/', "", $response['body']));

  // Check if request was successful
  if($response['status'] !== 200 or empty($body)) return false;
  if(strpos($body, "<fault>") or !strpos($body, "<string>")) return false;

  return $response['body'];
}

function discover_endpoint($target_url) {
  $response = \http\get($target_url);
  [$header] = $response['headers']['X-Pingback'];
  $body = strip_comments($response['body']);
  
  if($header) return $header;

  $rel_href = '/<(?:link|a)[ ]+href="([^"]*)"[ ]+rel="pingback"[ ]*\/?>/i';
  $href_rel = '/<(?:link|a)[ ]+rel="pingback"[ ]+href="([^"]*)"[ ]*\/?>/i';

  if(preg_match($rel_href, $body, $match) or preg_match($href_rel, $body, $match)) {
    return $match[1];
  }

  return false;
}

// Stolen from indieweb/mention-client-php, 
// dual-licensed Apache 2.0, MIT.
function xmlrpc_encode_request($method, $params) {
  $xml  = '<?xml version="1.0"?>';
  $xml .= '<methodCall>';
  $xml .= '<methodName>'.htmlspecialchars($method).'</methodName>';
  $xml .= '<params>';
  foreach($params as $param) {
    $xml .= '<param><value><string>'.htmlspecialchars($param).'</string></value></param>';
  }
  $xml .= '</params></methodCall>';

  return $xml;
}
