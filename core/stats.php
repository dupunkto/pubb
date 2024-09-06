<?php
// Basic statistics: logs which URLs where hit at which time,
// and stores the referer.

namespace stats;

function record_view($path) {
  \store\put_view(
    path: $path, 
    referer: @$_SERVER['HTTP_REFERER'],
    agent: @$_SERVER['HTTP_USER_AGENT'],
    datetime: date("Y-m-d H:i:s")
  );
}
