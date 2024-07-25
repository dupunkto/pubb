<?php
// Handles sending webmentions.

namespace webmentions;

function send_webmention($source_url, $target_url) {
  $endpoint = discover_endpoint($target_url);

  if(!$endpoint) return false;
    
  $payload = [
    "source" => $source_url,
    "target" => $target_url
  ];

  $headers = [
    "Content-Type" => "application/x-www-form-urlencoded",
    "Accept" => "application/json, */*;q=0.8"
  ];
  
  \http\post($endpoint, $payload, $headers);
  
  if($response['status'] < 200 or $response['status'] > 299) {
    return false;
  }

  return $response["body"];
}

function discover_endpoint($target_url) {
  $response = \http\head($target_url);
  $links = parse_link_header($response['headers']['link']);

  foreach($links as $key => $value) {
    if(str_contains($key, "webmention")) {
      return $links[$key][0]['uri'];
    }
  }

  $response = \http\get($target_url);
  $body = strip_comments($response['body']);

  $rel_href = '/<(?:link|a)[ ]+href="([^"]*)"[ ]+rel="[^" ]* ?webmention ?[^" ]*"[ ]*\/?>/i';
  $href_rel = '/<(?:link|a)[ ]+rel="[^" ]* ?webmention ?[^" ]*"[ ]+href="([^"]*)"[ ]*\/?>/i';

  if(preg_match($rel_href, $body, $match) or preg_match($href_rel, $body, $match)) {
    return $match[1];
  }

  return false;
}

// Adapted version of phpish/link_header, licensed MIT.
// I inlined it because I hate composer. Sowwy!
function parse_link_header($link_values) {
  if(is_string($link_values)) $link_values = array($link_values);

  $links = array();

  foreach($link_values as $link_value) {

    $state = "link_start";
    $link = array();
    $uri = $param_name = $param_value = "";

    $link_value = trim($link_value);

    $len = strlen($link_value);

    foreach(str_split($link_value) as $chr) {
      switch ($state) {
        case "link_start":
          if("<" == $chr) {
            $state = "uri_start";
            $uri = "";
            $link = array();
          }
          break;
        case "uri_start":
          if(">" == $chr) {
            $state = "uri_end";
            $link['uri'] = $uri;
          }
          else $uri .= $chr;
          break;
        case "uri_end":
          if(";" == $chr) $state = "param_start";
          break;
        case "param_start":
          if(!is_whitespace($chr)) {
            $state = "param_name_start";
            $param_name = $chr;
          }
          break;
        case "param_name_start":
          if("=" == $chr) $state = "param_name_end";
          else $param_name .= $chr;
          break;
        case "param_name_end":
          $param_value = "";
          if('"' == $chr) $state = "quoted_param_value_start";
          else $state = "param_value_start";
          break;
        case "quoted_param_value_start":
          if('"' == $chr) $state = "quoted_param_value_end";
          else $param_value .= $chr;
          break;
        case "quoted_param_value_end":
          if(";" == $chr) $state = "param_value_end";
          elseif("," == $chr) $state = "end_of_params";
          break;
        case "param_value_start":
          if(";" == $chr) $state = "param_value_end";
          elseif("," == $chr) $state = "end_of_params";
          else $param_value .= $chr;
          break;
        case "param_value_end":
          $state = "param_start";
          $link[$param_name] = $param_value;
          break;
        case "end_of_params":
          $state = 'link_start';
          $link[$param_name] = $param_value;
          if(isset($link['rel'])) {
            $rels = $link['rel'];
            unset($link['rel']);
            foreach(explode(' ', $rels) as $rel) $links[$rel][] = $link;
          }
          else $links[] = $link;
      }
    }

    if("link_start" != $state) {
      $link[$param_name] = $param_value;
      if(isset($link['rel'])) {
        $rels = $link['rel'];
        unset($link['rel']);
        foreach(explode(' ', $rels) as $rel) $links[$rel][] = $link;
      }
      else $links[] = $link;

    }
  }

  return $links;
}
