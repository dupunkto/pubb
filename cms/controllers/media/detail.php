<?php
// Preview and edit asset.

if(!isset($_GET['id'])) redirect("/media");

$id = $_GET['id'];
$asset = \store\get_asset($id) or redirect("/media");
$linked = \store\linked_pages($asset);
$duplicates = \store\duplicates($asset);
$duplicate = \store\duplicate_of($asset);

if(isset($_POST['save'])) {
  \store\update_asset(
    id: cast($_POST['id']), 
    slug: cast($_POST['slug'])
  ) or fail("Couldn't update #{$_POST['id']}.", to: "/media");

  complete("Updated #{$_POST['id']}.", to: "/media");
}

include $view;