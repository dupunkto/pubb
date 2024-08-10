<?php
// Upload new asset(s).

$now = date("Y-m-d H:i:s");

if(isset($_POST['upload'])) {
  isset($_FILES['assets']) or fail("Missing uploads in POST data.");
  $uploads = restructure_files($_FILES['assets']);

  foreach($uploads as $upload) {
    $slug = unique_slug('assets', $upload['name']);
    $tmp_file = $upload['tmp_name'];

    dbg($upload['error']) == UPLOAD_ERR_OK 
      or fail("Uploading '{$upload['name']}' failed.");

    // This checks if someone isn't maliciously trying
    // to overwrite /etc/passwd or something.
    if(!is_uploaded_file($tmp_file) or !getimagesize($tmp_file)) 
      fail("Bad photo upload. Try again.");

    $path = \store\copy_file($tmp_file) 
      or fail("Copying '{$upload['name']}' over to data store failed.");

    \store\put_asset(
      slug: $slug,
      path: $path,
      uploaded_at: $now,
    );
  }

  $count = count($uploads);
  complete("Uploaded $count files.", to: "/media");
}

include $view;