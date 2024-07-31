<?php
// Edit contact.

if(!isset($_GET['id'])) redirect("/contacts");

$id = $_GET['id'];
$contact = \store\get_contact($id) or redirect("/contacts");

if(isset($_POST['edit'])) {
  $handle = strip_prefix($_POST['handle'], "@");
  $notify = cast_boolean($_POST['notify']);

  var_dump($_POST['notify']);
  var_dump($notify);

  \store\update_contact(
    id: $_POST['id'],
    handle: $handle,
    domain: $_POST['domain'],
    email: $_POST['email'],
    notify: $notify,
  ) or fail("Couldn't update contact.");

  complete("Updated '@" . $handle . "'.", to: "/contacts");
}

include $view;