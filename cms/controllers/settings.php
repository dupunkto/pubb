<?php
// The settings screen.

if(isset($_POST['save'])) {
  $changes = $_POST;

  // PHP is transforming the site.title that I passed from
  // the client to site_title somewhere in transit. This changes
  // it back.
  $normalized_keys = array_map(function($key) {
    return str_replace("_", ".", $key);
  }, array_keys($changes));

  // Merge keys into the map and drop any empty values.
  $changes = array_combine($normalized_keys, $changes);
  $changes = array_filter($changes, function($value) {
    return $value !== "";
  });

  // If a new passphrase was provided, we need to rehash it,
  // and change the password.
  if(isset($changes['passphrase'])) {
    if($changes['passphrase'] !== @$changes['confirm'])
      fail("The passphrase did not match the confirmation.", 400);

    $passphrase = $changes['passphrase'];
    $hashed = \auth\hash_passphrase($passphrase);

    $changes['hashed_passphrase'] = $hashed;
  }

  // Make sure we're not dumping junk data into the config.
  // (Or worse: unencrypted passwords...)
  if(isset($changes['save'])) unset($changes['save']);
  if(isset($changes['passphrase'])) unset($changes['passphrase']);
  if(isset($changes['confirm'])) unset($changes['confirm']);

  $existing = json_decode(file_get_contents(CONFIG), true)
    or fail("Failed to read existing config.");

  $new = array_merge($existing, $changes);

  file_put_contents(CONFIG, json_encode($new)) 
    or fail("Failed to save settings.");

  put_flash("success", "Saved.");
  complete();
}

function value($key) {
  $key = normalize_key($key);
  return defined($key) ? constant($key) : null;
}

function canonical_value($key) {
  $key = normalize_key($key);
  return is_fallback($key) ? null : value($key);
}

include $view;
