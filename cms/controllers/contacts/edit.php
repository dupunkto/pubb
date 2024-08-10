<?php
// Edit contact.

if(!isset($_GET['id'])) redirect("/contacts");

$id = $_GET['id'];
$contact = \store\get_contact($id) or redirect("/contacts");

if(isset($_POST['edit'])) {
  $handle = strip_prefix($_POST['handle'], "@");

  \store\update_contact(
    id: cast($_POST['id']),
    handle: cast($handle),
    domain: cast($_POST['domain']),
    email: cast($_POST['email']),
    notify: cast($_POST['notify']),
  ) or fail("Couldn't update contact.");

  complete("Updated '@" . $handle . "'.", to: "/contacts");
}

include $view;