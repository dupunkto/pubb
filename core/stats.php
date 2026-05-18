<?php
// Basic statistics: logs which URLs where hit at which time,
// and stores the referer.

namespace stats;

function record_view($path) {
  if(!allowed_to_track()) return;

  \store\create_view(
    path: $path, 
    referer: @$_SERVER['HTTP_REFERER'],
    agent: @$_SERVER['HTTP_USER_AGENT'],
    client_ip: @$_SERVER['REMOTE_ADDR'],
    datetime: date("Y-m-d H:i:s")
  );
}

function allowed_to_track() {
   return !has_do_not_track() && !has_global_privacy_control();
}

function has_do_not_track() {
  return isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] == 1;
}

function has_global_privacy_control() {
  return isset($_SERVER['HTTP_SEC_GPC']) && $_SERVER['HTTP_SEC_GPC'] == 1;
}
