<?php
// Code editor for new gists.

if(isset($_POST['save'])) {
  $saved = \core\new_gist(
    caption: cast($_POST['caption']),
    filename: cast($_POST['filename']),
    code: cast($_POST['code'])
  );
  
  if($saved) complete("Saved gist.", to: "/code");
  else fail("Failed to save gist.");
}

include path_join($views, "edit-code.php");
