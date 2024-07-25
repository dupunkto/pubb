<?php
// Cryptography APIs for handling authorization.

namespace crypto;

define('RANDOM_BYTE_COUNT', 32);

function generate_encryption_key() {
  $bytes = random_bytes(RANDOM_BYTE_COUNT);
  return bin2hex($bytes);
}

function hash_passphrase($passphrase) {
  $host = parse_url(AUTHOR_MAIN_SITE, PHP_URL_HOST);
  $hash = md5($host . $passphrase . ENCRYPTION_KEY);

  return $hash;
}

function verify_passphrase($passphrase) {
  $hash = hash_passphrase($passphrase);
  return hash_equals(HASHED_PASSPHRASE, $hash);
}
