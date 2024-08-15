<?php
// Edit volume.

if(!isset($_GET['id'])) redirect("/volumes");

$id = $_GET['id'];
$volume = \store\get_volume($id) or redirect("/volumes");

if(isset($_POST['edit'])) {
  \store\update_volume(
    id: cast($_POST['id']),
    slug: cast($_POST['slug']),
    title: cast($_POST['title']),
    description: cast(@$_POST['description']),
    start: cast($_POST['start_at']),
    end: cast($_POST['end_at'])
  ) or fail("Couldn't update volume.");

  complete("Updated '{$_POST['title']}'.", to: "/volumes");
}

include $view;