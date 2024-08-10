<header class="bar">
  <h2>Gists</h2>
  <a href="<?= CMS_CANONICAL ?>/code/new" class="button">New gist</a>
</header>

<?php $pages = \store\list_pages_by_type("code", include_drafts: true) ?>

<ul>
  <?php foreach($pages as $page) { ?>
    <li>
      <a href="<?= CMS_CANONICAL ?>/code/edit?id=<?= $page['id'] ?>">
        <?= $page['slug'] ?>
      </a>
    </li>
  <?php } ?>
</ul>

<?php if(count($pages) <= 0) {
  ?>
    <p class="placeholder-text">No gists yet.</p>
  <?php
} ?>
