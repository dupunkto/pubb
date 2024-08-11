<?php
// Upload new asset(s).

$now = date("Y-m-d H:i:s");

if(isset($_POST['upload'])) {
  isset($_FILES['assets']) or fail("Missing uploads in POST data.");
  $uploads = restructure_files($_FILES['assets']);

  foreach($uploads as $upload) {
    try {
      $slug = \store\unique_slug('assets', $upload['name']);
      $stored_at = \core\upload_photo($upload);

      \store\put_asset(
        slug: $slug,
        path: $stored_at,
        uploaded_as: $upload['name'],
        uploaded_at: $now,
      ) or fail("Saving '{$upload['name']}' to the database failed.");
    } catch(Exception $e) {
      fail($e->getMessage());
    }
  }

  $count = count($uploads);
  complete("Uploaded $count files.", to: "/media");
}

include $view;