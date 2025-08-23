<?php
// Delete close friend.

if(!isset($_GET['id'])) redirect("/contacts/close-friends");

$contact_id = $_GET['id'];
$contact = \store\get_contact($contact_id) or fail("Contact doesn't exist.", to: "/contacts/close-friends");
$friend = \store\get_friend($contact_id) or fail("@{$contact['handle']} is not in your close friends.", to: "/contacts/close-friends");

\store\remove_friend($contact_id) or fail("Couldn't remove friend.", to: "/contacts/close-friends");

complete("Removed @{$contact['handle']} from close friends.", to: "/contacts/close-friends");