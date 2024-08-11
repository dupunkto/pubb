<?php
// Handles formatting and delivering emails.

namespace mailer;

function new_webmention($source, $target) {
  if(!NOTIFICATIONS_WEBMENTION) return;

  $subject = "New webmention from $source";
  $message = "Your page $target was mentioned on $source!";

  send_to_webmaster($subject, $message);
}

function send_mention($page, $contact) {
  $subject = "I mentioned you!";
  $title = \core\get_page_title($page);
  $url = \urls\page_url($page);

  $message  = "Hello, {$contact['handle']}!\n\n";
  $message .= "I mentioned you on this page: \n";
  $message .= "{$title} ({$url})\n\n";

  send_to_contact($contact, $subject, $message);
}

function send_to_webmaster($subject, $message) {
  if(!defined('NOTIFICATIONS_ADMIN')) return;

  $message .= "\r\n\r\n";
  $message .= "This is an automated notification. ";
  $message .= "If you don't want to receive these anymore, you can disable them in the CMS. ";

  deliver(NOTIFICATIONS_ADMIN, "[" . HOST . "] $subject", $message);
}

function send_to_contact($contact, $subject, $message) {
  if($contact['notify'] == 0) return;

  $message .= "If you'd rather not receive emails when I @mention you on my website,\n";
  $message .= "please reply to this email, and I'll never send you notifications again.\n\n";
  $message .= "Cheers!\n";
  $message .= AUTHOR_NAME;

  deliver($contact['email'], $subject, $message);
}

function deliver($to, $subject, $message) {
  $headers = [
    "From" => NOTIFICATIONS_SENDER,
    "Content-Type" => "text/plain; charset=utf-8",
    "X-Powered-By" => "Pubb (v" . PUBB_VERSION . ")",
  ];

  mail($to, $subject, $message, $headers);
}
