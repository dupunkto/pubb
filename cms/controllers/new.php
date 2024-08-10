<?php
// Editor for new posts.

if(isset($_POST['save'])) {
  $draft = isset($_GET['draft']);

  $saved = \core\new_page(
    slug: cast($_POST['slug']),
    type: cast($_POST['type']),
    title: cast($_POST['title']),
    prose: cast($_POST['prose']),
    draft: $draft,
    visibility: cast($_POST['visibility']),
    reply_to: cast(@$_POST['reply'])
  );
  
  if($saved && !$draft) {
   $page = \store\get_page_by_slug($_POST['slug']) 
      or die("Inserting post into database went wrong; couldn't lookup by slug.");

    \core\send_pingbacks($page);
    \core\send_webmentions($page);
    \core\send_mentions($page);
  }
  
  if($saved) complete("Saved page.", to: "/pages");
  else fail("Failed to save page.");
}

$draft = true;

if(isset($_GET['reply'])) {
  $reply = $_GET['reply'];
}

include path_join($views, "edit-page.php");
