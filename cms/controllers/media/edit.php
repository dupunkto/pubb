<?php
// Edit photo post.

if(isset($_POST['save'])) {
  \core\update_photo(
    id: cast($_POST['id']),
    slug: cast($_POST['slug']),
    caption: cast($_POST['caption']),
    path: cast($_POST['path']),
  ) or fail("Failed to save post.");

  complete("Saved post.", to: "/media");
}

if(!isset($_GET['id'])) redirect("/media");

$id = $_GET['id'];
$post = \store\get_page($id) or redirect("/media");

$title = "Edit post";
$slug = $post['slug'];
$caption = $post['caption'];
$store_path = $post['path'];

include path_join($views, "edit-photo.php");
