<?php
// Add contact.

if(isset($_POST['add'])) {
  $handle = strip_prefix($_POST['handle'], "@");

  \store\put_contact(
    handle: $handle,
    domain: cast($_POST['domain']),
    email: cast($_POST['email']),
    notify: cast($_POST['notify']),
  ) or fail("Couldn't add contact.");

  complete("Added '@" . $handle . "'.", to: "/contacts");
}

include $view;