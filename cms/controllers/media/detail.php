<?php
// Asset details

if(isset($_POST['save'])) {
  \store\update_asset(
    id: cast($_POST['id']), 
    slug: cast($_POST['slug'])
  ) or fail("Couldn't update #{$_POST['id']}.", to: "/media");

  complete("Updated #{$_POST['id']}.", to: "/media");
}

if(!isset($_GET['id'])) redirect("/media");

$id = $_GET['id'];
$asset = \store\get_asset($id) or redirect("/media");

include $view;