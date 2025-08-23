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
  
  // Adding existing contact as friend
  if(isset($_POST['contact_id'])) {
    $contact = \store\get_contact($_POST['contact_id']) or fail("Contact not found.");
    
    if(\store\get_friend($contact['id'])) {
      fail("@{$selected_contact['handle']} is already in your close friends.");
    }
    
    \store\put_friend(
      contact_id: $contact['id'],
      token: $token,
      clearance: 30
    ) or fail("Couldn't add friend.");

    complete("Added @{$selected_contact['handle']} to your close friends.", to: "/contacts/close-friends");
  }
  
  // Creating new contact and adding as friend
  else {
    $handle = strip_prefix($_POST['handle'], "@");
    
    \store\put_contact(
      handle: $handle,
      domain: cast($_POST['domain']),
      email: cast($_POST['email']),
      notify: cast($_POST['notify']),
    ) or fail("Couldn't create contact.");

    $contact = \store\get_contact_by_handle($handle) or
      fail("Contact @{$handle} was added but not found.");
    
    \store\put_friend(
      contact_id: $contact['id'],
      token: $token,
      clearance: 30
    ) or fail("Couldn't add friend.");

    complete("Added new contact @{$selected_contact['handle']} to your close friends.", to: "/contacts/close-friends");
  }
}

include $view;