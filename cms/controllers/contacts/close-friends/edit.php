<?php
// Edit close friend.

if(!isset($_GET['id'])) redirect("/contacts/close-friends");

$contact_id = $_GET['id'];
$contact = \store\get_contact($contact_id) or redirect("/contacts/close-friends");
$friend = \store\get_friend($contact_id) or redirect("/contacts/close-friends");

if(isset($_POST['edit'])) {
  $token = trim($_POST['token']);
  if(empty($token)) $token = random_string(7);

  \store\update_token(
    contact_id: cast($contact_id),
    token: $token,
  ) or fail("Couldn't update token.");

  complete("Updated token for @{$contact['handle']}.", to: "/contacts/close-friends");
}

include $view;