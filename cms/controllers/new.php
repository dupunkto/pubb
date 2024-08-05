<?php
// Editor for new posts.

$type = $type ?? "md";
$volume = \store\latest_volume()['id'];
$draft = true;
$reply = $_GET['reply'];

if(isset($_POST['save']) || isset($_POST['publish'])) {
  $saved = \core\new_page(
    slug: $_POST['slug'],
    type: $_POST['type'],
    volume: $_POST['volume'],
    title: $_POST['title'],
    prose: $_POST['prose'],
    draft: isset($_POST['save']),
    reply_to: $_POST['reply']
  );
  
  if($saved && isset($_POST['publish'])) {
   $page = \store\get_page_by_slug($_POST['slug']) or die("Inserting post into database went wrong; couldn't lookup by slug.");

    \core\send_mentions($page);
  } 
  elseif ($saved) {
    complete("Saved page.", to: "/pages");
  } 
  else {
    save_session();
    complete("Failed to save page.", to: "/new"); 
  }
}

include path_join($views, "edit.php");
