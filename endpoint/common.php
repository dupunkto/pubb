<?php
// Common helper functions used accross endpoints.

function json_error($status, $message) {
  http_response_code($status);
  json_data(["error" => $message]);
}

function json_message($message) {
  json_data(["message" => $message]);
}

function json_data($data) { 
  header("Content-Type: application/json; charset=UTF-8");
  echo json_encode($data);
  exit;
}