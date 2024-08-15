<?php
// Code editor for existing gists.

if(!isset($_GET['id'])) redirect("/code");

$id = $_GET['id'];
$page = \store\get_page($id) or redirect("/code");

$filename = $page['slug'];
$caption = $page['caption'];
$code = \store\contents($page['path']);

if(isset($_POST['save'])) {
  $saved = \core\edit_gist(
    id: cast($_POST['id']),
    caption: cast($_POST['caption']),
    filename: cast($_POST['filename']),
    code: cast($_POST['code'])
  );
  
  if($saved) complete("Saved gist.", to: "/code");
  else fail("Failed to save gist.", to: "/code/edit?id={$_POST['id']}");
}

include path_join($views, "edit-code.php");
