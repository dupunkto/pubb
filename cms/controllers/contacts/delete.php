<?php
// Delete contact.

if(!isset($_GET['id'])) redirect("/contacts");

$id = $_GET['id'];
$contact = \store\get_contact($id) or fail("Contact doesn't exist.", to: "/contacts");
\store\delete_contact($id) or fail("Couldn't delete contact.", to: "/contacts");

complete("Deleted '@" . $contact['handle'] . "'.", to: "/contacts");
