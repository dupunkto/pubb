<?php
// Add close friend.

if(isset($_GET['id'])) {
  $contact = \store\get_contact($_GET['id']) or redirect("/contacts/close-friends");

  if(\store\get_friend($contact['id'])) {
    fail("@{$contact['handle']} is already in your close friends.", to: "/contacts/close-friends");
  }
}

if(isset($_POST['add'])) {
  $token = trim($_POST['token']);
  if(empty($token)) $token = random_string(7);
  
  $contact = \store\get_contact($_POST['contact_id']) or fail("Contact not found.");
    
  if(\store\get_friend($contact['id'])) {
    fail("@{$contact['handle']} is already in your close friends.");
  }
  
  \store\put_friend(
    contact_id: $contact['id'],
    token: $token,
    clearance: 30
  ) or fail("Couldn't add friend.");

  complete("Added @{$contact['handle']} to your close friends.", to: "/contacts/edit?id={$contact['id']}");
}

include $view;