<?php
// Generates URLs.

function page_url($page) {
  return CANONICAL . "/" . $page['slug'];
}

function is_url($str) {
  return str_starts_with($str, "http://") 
  	or str_starts_with($str, "https://");
}
