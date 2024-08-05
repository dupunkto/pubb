<?php
// Editor for existing posts.

if(isset($_POST['save']) || isset($_POST['publish'])) {
  $was_draft = \store\get_page($_POST['id'])['draft'];
  $draft = isset($_POST['save']);

  $updated = \core\edit_page(
    id: $_POST['id'],
    type: $_POST['type'],
    slug: $_POST['slug'],
    title: $_POST['title'],
    prose: $_POST['prose'],
    draft: $draft,
    reply_to: @$_POST['reply']
  );

  if($updated && $was_draft && !$draft) { 
   $page = \store\get_page_by_slug($_POST['slug']) 
      or die("Inserting and/or updating post in the database went wrong; couldn't lookup by slug.");

    \core\send_mentions($page);
  }
  
  if($updated) complete("Saved page.", to: "/pages");
  else fail("Failed to save page.", to: "/edit?id={$_POST['id']}");
}

if(!isset($_GET['id'])) redirect("/pages");

$id = $_GET['id'];
$page = \store\get_page($id) or redirect("/pages");

$slug = $page['slug'];
$title = $page['title'];
$prose = \store\contents($page['path']);
$draft = $page['draft'];
$reply = $page['reply_to'];

include path_join($views, "edit.php");
