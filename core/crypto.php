<?php
// Cryptography APIs for handling authorization.

namespace crypto;

define('RANDOM_BYTE_COUNT', 32);

function generate_encryption_key() {
  $bytes = random_bytes(RANDOM_BYTE_COUNT);
  return bin2hex($bytes);
}

function hash_passphrase($passphrase) {
  $host = parse_url(AUTHOR_SITE, PHP_URL_HOST);
  $hash = md5($host . $passphrase . ENCRYPTION_KEY);

  return $hash;
}

function verify_passphrase($passphrase) {
  $hash = hash_passphrase($passphrase);
  return hash_equals(HASHED_PASSPHRASE, $hash);
}

// URL safe base64 encoding per 
// https://tools.ietf.org/html/rfc7515#appendix-C

function base64_url_encode($string) {
  $string = base64_encode($string);
  $string = rtrim($string, '=');
  $string = strtr($string, '+/', '-_');
  return $string;
}

function base64_url_decode($string) {
  $string = strtr($string, '-_', '+/');
  $padding = strlen($string) % 4;
  if($padding !== 0) {
    $string .= str_repeat('=', 4 - $padding);
  }
  $string = base64_decode($string);
  return $string;
}

// Signed codes always have a time-to-live, by default 1 year (31536000 seconds).
// The appended data rides inside the code and is covered by the signature.
function create_signed_code($key, $message, $ttl = 31536000, $appended_data = "") {
  $expires = time() + $ttl;
  $body = $message . $expires . $appended_data;
  $signature = hash_hmac("sha256", $body, $key);
  return dechex($expires) . ":" . $signature . ":" . base64_url_encode($appended_data);
}

function verify_signed_code($key, $message, $code) {
  $code_parts = explode(":", $code, 3);
  if(count($code_parts) !== 3) {
    return false;
  }

  $expires = hexdec($code_parts[0]);
  if(time() > $expires) {
    return false;
  }

  $body = $message . $expires . base64_url_decode($code_parts[2]);
  $signature = hash_hmac("sha256", $body, $key);
  return hash_equals($signature, $code_parts[1]);
}
