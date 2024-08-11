<?php
// Add volume.

if(isset($_POST['add'])) {
  \store\put_volume(
    slug: cast($_POST['slug']),
    title: cast($_POST['title']),
    description: cast(@$_POST['description']),
    start: cast($_POST['start_at']),
    end: cast($_POST['end_at'])
  ) or fail("Couldn't add volume.");

  complete("Added '{$_POST['title']}.", to: "/volumes");
}

include $view;