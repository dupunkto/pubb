<?php
// robots.txt to block unwanted visitors.

require_once __DIR__ . "/../core.php";

header("Content-Type: text/plain; charset=UTF-8");

?>
Sitemap: <?= CANONICAL ?>/sitemap.xml

<?php if(NONCOMMERCIAL) { ?>
# This site is non-commercial and doesn't want anything
# to do with the Corporate Web.
<?php foreach(CORPORATE_AGENTS as $agent) {
  echo "\nUser-agent: $agent";
} ?>
Disallow: /
<?php } ?>

<?php if(NONAI) { ?>
# This site is opposed to the unrequested, unwanted and
# unethical scraping of the Personal Web in order to "train"
# Carbon-hungry highschoolers.
<?php foreach(AI_AGENTS as $agent) {
  echo "\nUser-agent: $agent";
} ?>
Disallow: /
<?php } ?>
