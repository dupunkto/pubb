<?php
// Delete asset.

if(!isset($_GET['id'])) redirect("/media");

$id = $_GET['id'];
$asset = \store\get_asset($id) or fail("Asset doesn't exist.", to: "/media");
\store\delete_asset($id) or fail("Couldn't delete asset.", to: "/media");

$duplicates = \store\all('SELECT * FROM `assets` WHERE path = ?', [$asset['path']]);

if(count($duplicates) == 0) {
  \store\delete_file($asset['path']) 
    or fail("Deleted asset, but failed to delete underlying file with reference " . filename($asset['path']) . " from data store. There might still be working URLs to this asset.");
}

complete("Deleted #{$asset['id']}.", to: "/media");
