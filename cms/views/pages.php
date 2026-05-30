<header class="bar">
  <h2>Pages</h2>
  <a href="<?= CMS_CANONICAL ?>/new" class="button">New page</a>
</header>

<?php
  $pages = isset($_GET['type']) ?
    \store\list_pages_by_category($_GET['type'], visibility: 0, draft: true) :
    \store\list_regular_pages(visibility: 0, draft: true) ?>

<ul>
  <?php foreach($pages as $page) { ?>
    <li>
      <a href="<?= CMS_CANONICAL ?>/edit?id=<?= $page['id'] ?>">
        <?php if($page['visibility'] < 50) { ?>
          <span class="level"><?= \core\level_to_visibility($page['visibility']) ?></span>
        <?php } ?>

        <?= \core\get_page_title($page) ?>
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
