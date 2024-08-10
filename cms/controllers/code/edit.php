<?php
// Code editor for existing gists.

if(isset($_POST['save'])) {
  $saved = \core\edit_gist(
    id: $_POST['id'],
    caption: $_POST['caption'],
    filename: $_POST['filename'],
    code: $_POST['code']
  );
  
  if($saved) complete("Saved gist.", to: "/code");
  else fail("Failed to save gist.", to: "/code/edit?id={$_POST['id']}");
}

if(!isset($_GET['id'])) redirect("/code");

$id = $_GET['id'];
$page = \store\get_page($id) or redirect("/code");

$filename = $page['slug'];
$caption = $page['caption'];
$code = \store\contents($page['path']);

include path_join($views, "edit-code.php");
