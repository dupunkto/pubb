<?php
// Access management for individual pages and feeds.

namespace access;

define('COOKIE', 'friend_token');
define('EXPIRY',  30 * 24 * 3600); // 30 days

function token() {
  return @$_COOKIE[COOKIE] ?? @$_GET['token'];
}

function clearance($default = 'public') {
  $token = token();

  if($token) {
    set_cookie($token);
    $friend = \store\get_friend_by_token($token);
    if($friend) return $friend['clearance'];    
  }

  return \core\visibility_to_level($default);
}

function set_cookie($token) {
  setcookie(
    COOKIE, 
    $token, 
    time() + EXPIRY,
    '/',
    '',
    SECURE,
    true
  );
}

function clear_cookie() {
  setcookie(COOKIE, '', time() - 3600, '/');
}