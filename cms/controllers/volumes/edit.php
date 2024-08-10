<?php
// Edit volume.

if(!isset($_GET['id'])) redirect("/volumes");

$id = $_GET['id'];
$volume = \store\get_volume($id) or redirect("/volumes");

if(isset($_POST['edit'])) {
  \store\update_volume(
    id: $_POST['id'],
    slug: $_POST['slug'],
    title: $_POST['title'],
    description: @$_POST['description'],
    start: $_POST['start_at'],
    end: $_POST['end_at']
  ) or fail("Couldn't add volume.");

  complete("Updated '" . $_POST['title'] . "'.", to: "/volumes");
}

include $view;