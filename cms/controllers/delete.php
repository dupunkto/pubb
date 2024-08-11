<?php
// Delete a page/gist/post.

$type = @$_GET['type'] ?? "page";
$return = @$_GET['return'] ?? "/pages";

if(!isset($_GET['id'])) redirect($return);

$id = $_GET['id'];
$page = \store\get_page($id) or fail(ucfirst($type) . " doesn't exist.", to: $return);
\store\delete_page($id) or fail("Couldn't delete $type.", to: $return);

$title = \html\page_title($page);
complete("Deleted '{$title}'.", to: $return);
