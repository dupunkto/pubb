<?php
// Delete volume.

if(!isset($_GET['id'])) redirect("/volumes");

$id = $_GET['id'];
$volume = \store\get_volume($id) or fail("Volume doesn't exist.", to: "/volumes");
\store\delete_volume($id) or fail("Couldn't delete volume.", to: "/volumes");

complete("Deleted '{$volume['title']}'.", to: "/volumes");
