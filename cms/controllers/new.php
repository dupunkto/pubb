<?php
// Editor for new posts.

$draft = true;

if(isset($_GET['reply'])) {
  $reply = $_GET['reply'];
}

if(isset($_POST['save']) || isset($_POST['publish'])) {
  $draft = isset($_POST['save']);

  $saved = \core\new_page(
    slug: $_POST['slug'],
    type: $_POST['type'],
    title: $_POST['title'],
    prose: $_POST['prose'],
    draft: $draft,
    reply_to: @$_POST['reply']
  );
  
  if($saved && !$draft) {
   $page = \store\get_page_by_slug($_POST['slug']) 
      or die("Inserting post into database went wrong; couldn't lookup by slug.");

    \core\send_mentions($page);
  } 
  
  if($saved) complete("Saved page.", to: "/pages");
  else complete("Failed to save page.", to: "/new");
}

include path_join($views, "edit.php");
