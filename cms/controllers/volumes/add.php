<?php
// Add volume.

if(isset($_POST['add'])) {
  \store\put_volume(
    slug: $_POST['slug'],
    title: $_POST['title'],
    description: @$_POST['description'],
    start: $_POST['start_at'],
    end: $_POST['end_at']
  ) or fail("Couldn't add volume.");

  complete("Added '" . $_POST['title'] . "'.", to: "/volumes");
}

include $view;