<header class="bar">
  <h2>Pages</h2>
  <a href="<?= CMS_CANONICAL ?>/new" class="button">New page</a>
</header>

<?php $pages = \store\list_pages_by_type(["md", "html", "txt"], include_drafts: true) ?>

<ul>
  <?php foreach($pages as $page) { ?>
    <li>
      <a href="<?= CMS_CANONICAL ?>/edit?id=<?= $page['id'] ?>">
        <?= \html\page_title($page) ?>
        <?php if($page['draft']) echo wrap("small", "— draft") ?>
      </a>
    </li>
  <?php } ?>
</ul>

<?php if(count($pages) <= 0) {
  ?>
    <p class="placeholder-text">No pages yet.</p>
  <?php
} ?>
