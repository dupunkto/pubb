<?php
// security.txt with contact details.

require_once __DIR__ . "/../core.php";

header("Content-Type: text/plain; charset=UTF-8");

$now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
$expiry = $now->modify('+30 days')->format('Y-m-d\TH:i:s.000\Z');

?>
Contact: <?= CANONICAL ?>/contact
Canonical: <?= CANONICAL ?>/security.txt
Expires: <?= $expiry . "\n" ?>
Preferred-Languages: <?= SITE_LANG ?>
