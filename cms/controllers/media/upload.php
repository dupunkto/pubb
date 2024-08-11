<?php
// Upload new asset(s).

$now = date("Y-m-d H:i:s");

if(isset($_POST['upload'])) {
  isset($_FILES['assets']) or fail("Missing uploads in POST data.");
  $uploads = restructure_files($_FILES['assets']);

  foreach($uploads as $upload) {
    $slug = \store\unique_slug('assets', $upload['name']);
    $tmp_file = $upload['tmp_name'];

    if($upload['error'] != UPLOAD_ERR_OK) {
      $error = match($upload['error']) {
        UPLOAD_ERR_INI_SIZE => "Upload too large.",
        UPLOAD_ERR_PARTIAL => "Upload only partially uploaded.",
        UPLOAD_ERR_NO_FILE => "No file was uploaded.",
        UPLOAD_ERR_NO_TMP_DIR => "Temporary folder to write to was missing.",
        UPLOAD_ERR_CANT_WRITE => "Couldn't write to disk.",
        UPLOAD_ERR_EXTENSION => "The upload was stopped by a PHP extension."
      };

      fail("'{$upload['name']}' failed: $error");
    }

    // This checks if someone isn't maliciously trying
    // to overwrite /etc/passwd or something.
    if(!is_uploaded_file($tmp_file) or !getimagesize($tmp_file)) 
      fail("Bad photo upload. Try again.");

    $stored_at = \store\copy_file($tmp_file) 
      or fail("Copying '{$upload['name']}' over to data store failed.");

    \store\put_asset(
      slug: $slug,
      path: $stored_at,
      uploaded_as: $upload['name'],
      uploaded_at: $now,
    ) or fail("Saving '{$upload['name']}' to the database failed.");
  }

  $count = count($uploads);
  complete("Uploaded $count files.", to: "/media");
}

include $view;