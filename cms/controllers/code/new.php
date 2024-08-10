<?php
// Code editor for new gists.

if(isset($_POST['save'])) {
  $saved = \core\new_gist(
    caption: $_POST['caption'],
    filename: $_POST['filename'],
    code: $_POST['code']
  );
  
  if($saved) complete("Saved gist.", to: "/code");
  else fail("Failed to save gist.");
}

include path_join($views, "edit-code.php");
