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

  // Merge keys into the map.
  $changes = array_combine($normalized_keys, $changes);

  // If a new passphrase was provided, we need to rehash it,
  // and change the password.
  if(isset($changes['passphrase']) and $changes['passphrase'] != "") {
    if($changes['passphrase'] !== @$changes['confirm'])
      fail("The passphrase did not match the confirmation.");

    $passphrase = $changes['passphrase'];
    $hashed = \crypto\hash_passphrase($passphrase);

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

  // Drop any empty values. (Optional, but makes the config
  // look nicer.)
  $new = array_filter($new, function($value) {
    return $value !== "";
  });

  file_put_contents(CONFIG, json_encode($new)) 
    or fail("Failed to save settings.");

  complete("Saved.");
}

function value($key) {
  $key = normalize_key($key);
  return defined($key) ? constant($key) : null;
}

function canonical_value($key) {
  $key = normalize_key($key);

  // Pre-escaping the value like this is dirty, but I cannot be bothered
  // to explicitly escape it everywhere. Sowwy!
  return is_fallback($key) ? null : esc_attr(value($key));
}

include $view;
