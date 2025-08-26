<?php
// Array utilities.

function assoc($term) {
  if (is_object($term)) $term = get_object_vars($term);
  if (is_array($term)) return array_map(__FUNCTION__, $term);
  return $term;
}

function flatten($separator, $array) {
  $keys = array_keys($array);
  $values = array_values($array);
  
  return array_map(function($key, $value) use ($separator) {
    return $key . $separator . $value;
  }, $keys, $values);
}

function count_by($array, $key) {
  $keys = array_column($array, $key);
  $array = array_reduce($keys, function($acc, $key) {
    if (isset($acc[$key])) $acc[$key]++;
    else $acc[$key] = 1;

    return $acc;
  }, []);

  arsort($array); // Ew, in-place mutation.
  return $array;
}

function group_by($items, $prefix) {
  $grouped = [];
  
  foreach ($items as $item) {
    $id = $item[$prefix . "_id"];

    if (!isset($grouped[$id])) {
      $grouped[$id] = array_merge(
          unprefix_keys($item, $prefix),
          ['items' => []]
      );
    }
    
    $grouped[$id]['items'][] = $item;
  }

  return $grouped;
}

function take($array, $amount) {
  return array_slice($array, 0, $amount);
}

function prefix_keys($array, $prefix) {
  return array_combine(
    array_map(fn($k) => "$prefix$k", array_keys($array)),
    array_values($array)
  );
}

function unprefix_keys($array, $prefix) {
  $filtered = [];
  $prefix = $prefix."_";
  $len = strlen($prefix);

  foreach ($array as $key => $value) {
    if (strpos($key, $prefix) === 0) {
      $filtered[substr($key, $len)] = $value; 
    }
  }

  return $filtered;
}

function drop_empty($array) {
  return array_filter($array, function($value) {
    return !in_array($value, ["", null, false]);
  });
}

function deep_contains($haystack, $needle) {
  return $needle and count(array_filter($haystack, fn($candidate) => strpos($needle, $candidate) != false)) > 0;
}
