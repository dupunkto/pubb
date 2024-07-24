<?php
// Basic statistics: logs which URLs where hit at which time,
// and stores the referer.

namespace stats;

function record_view($page) {
  \store\put_view(
    page_id: $page['id'], 
    referer: $_SERVER['HTTP_REFERER'],
    agent: $_SERVER['HTTP_USER_AGENT'],
    datetime: date("Y-m-d H:i:s")
  );
}
