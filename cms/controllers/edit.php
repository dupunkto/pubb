<?php
// Editor for existing posts.

if(isset($_POST['save'])) {
  $draft = isset($_GET['draft']);

  $updated = \core\edit_page(
    id: cast($_POST['id']),
    type: cast($_POST['type']),
    slug: cast($_POST['slug']),
    title: cast($_POST['title']),
    prose: cast($_POST['prose']),
    draft: $draft,
    visibility: cast($_POST['visibility']),
    reply_to: cast(@$_POST['reply'])
  );

  if($updated and !$draft) { 
   $page = \store\get_page_by_slug($_POST['slug']) 
      or die("Inserting and/or updating post in the database went wrong; couldn't lookup by slug.");

    \core\send_pingbacks($page);
    \core\send_webmentions($page);
    \core\send_mentions($page);
  }
  
  if($updated) complete("Saved page.", to: "/pages");
  else fail("Failed to save page.", to: "/edit?id={$_POST['id']}");
}

if(!isset($_GET['id'])) redirect("/pages");

$id = $_GET['id'];
$page = \store\get_page($id) or redirect("/pages");

$slug = $page['slug'];
$type = $page['type'];
$title = $page['title'];
$prose = \store\contents($page['path']);
$draft = $page['draft'];
$visibility = $page['visibility'];
$reply = $page['reply_to'];

include path_join($views, "edit-page.php");
