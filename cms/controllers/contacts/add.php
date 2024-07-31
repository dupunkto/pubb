<?php
// Add contact.

if(isset($_POST['add'])) {
  $handle = strip_prefix($_POST['handle'], "@");
  $notify = cast_boolean($_POST['notify']);

  \store\put_contact(
    handle: $handle,
    domain: $_POST['domain'],
    email: $_POST['email'],
    notify: $notify,
  ) or fail("Couldn't add contact.");

  complete("Added '@" . $handle . "'.", to: "/contacts");
}

include $view;