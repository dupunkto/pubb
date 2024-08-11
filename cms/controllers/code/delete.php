<?php
// Delete a gist.

if(!isset($_GET['id'])) redirect("/code");

$id = $_GET['id'];
$page = \store\get_page($id) or fail("Gist doesn't exist.", to: "/code");
\store\delete_page($id) or fail("Couldn't delete gist.", to: "/code");

complete("Deleted {$page['slug']}.", to: "/code");
