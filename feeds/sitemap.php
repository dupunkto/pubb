<?php
// XML sitemap, because it's kinda nice ig

require_once __DIR__ . "/../core.php";
include_once __DIR__ . "/caching.php";

header("Content-Type: application/xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';

$pages = \store\list_public_pages();

?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc><?= CANONICAL ?>/</loc>
    <lastmod><?= date("c", \store\last_updated()) ?></lastmod>
  </url>
  <url>
    <loc><?= CANONICAL ?>/all</loc>
    <lastmod><?= date("c", \store\last_updated()) ?></lastmod>
  </url>
  <url>
    <loc><?= CANONICAL ?>/code</loc>
    <lastmod><?= date("c", \store\last_updated()) ?></lastmod>
  </url>
  <url>
    <loc><?= CANONICAL ?>/photos</loc>
    <lastmod><?= date("c", \store\last_updated()) ?></lastmod>
  </url>
  <?php foreach($pages as $page) { ?>
    <url>
      <loc><?= \urls\page_url($page) ?></loc>
      <lastmod><?= date("c", strtotime($page['updated'])) ?></lastmod>
    </url>
  <?php } ?>
</urlset>