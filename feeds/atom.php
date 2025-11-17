<?php
// Atom feed (which is RSS but different).

require_once __DIR__ . "/../core.php";

include __DIR__ . "/caching.php";

if(FEEDS_ENSURE_COMPATIBILITY) header("Content-Type: text/xml; charset=UTF-8");
else header("Content-Type: application/atom+xml; charset=UTF-8");

echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';

?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title><?= SITE_TITLE ?></title>
  <subtitle><?= SITE_DESCRIPTION ?></subtitle>
  <language><?= SITE_LANG ?></language>
  <id><?= CANONICAL ?></id>
  <updated><?= date(DATE_RFC3339, \store\last_updated()) ?></updated>
  <link rel="self" href="<?= CANONICAL ?>/atom.xml" type="application/rss+xml" />

  <author>
    <name><?= AUTHOR_NAME ?></name>
    <uri><?= CANONICAL ?></uri>
    <?php if(defined('AUTHOR_EMAIL')) echo wrap("email", AUTHOR_EMAIL); ?>
  </author>

  <?php if(defined('SITE_COPYRIGHT')) echo wrap("rights", SITE_COPYRIGHT) ?>

  <generator uri="https://dupunkto.org/pubb" version="<?= PUBB_VERSION ?>">
    Pubb
  </generator>

  <?php
    $clearance = \access\clearance('rss-only');
    $pages = \store\list_pages($clearance);

    foreach(\core\filter_feeds($pages) as $page) {
      ?>
        <entry>
          <?php 
            if($page['title']) wrap("title", $page['title']);
            elseif($page['type'] == "code") wrap("title", $page['slug']);
          ?>
          <id><?= $page['id'] ?></id>
          <updated><?= date(DATE_RFC3339, strtotime($page['updated'])) ?></updated>
          <link rel="alternate" href="<?= \urls\page_url($page) ?>"/>
          <content>
            <![CDATA[ <?php \renderer\page_content($page); ?> ]]>
          </content>
        </entry>
      <?php
    }
  ?>
</feed>
