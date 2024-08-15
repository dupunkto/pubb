<?php
// Create photo post.

$title = "New post";

if(isset($_GET['from'])) {
  $asset = \store\get_asset($_GET['from']) or redirect("/media");
  $store_path = $asset['path'];
}

if(isset($_POST['save'])) {
  if(isset($_POST['path'])) {
    $stored_at = cast(dbg($_POST['path']));
  } 
  else if(isset($_FILES['photo'])) {
    try {
      dbg($_FILES['photo']);
      $stored_at = dbg(\core\upload_photo($_FILES['photo']));
    } 
    catch(Exception $e) {
      fail($e->getMessage());
    }
  } else {
    redirect("/media");
  }

  \core\new_photo(
    slug: cast($_POST['slug']),
    caption: cast($_POST['caption']),
    path: $stored_at,
  ) or fail("Failed to save post.");

  complete("Saved post.", to: "/media");
}

include path_join($views, "edit-photo.php");
