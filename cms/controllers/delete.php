<?php
// Delete a post.

if(!isset($_GET['id'])) redirect("/pages");

$id = $_GET['id'];
$page = \store\get_page($id) or fail("Page doesn't exist.", to: "/pages");
\store\delete_page($id) or fail("Couldn't delete page.", to: "/pages");

complete("Deleted " . $page['title'] . ".", to: "/pages");
