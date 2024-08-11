<header class="bar">
  <h2>Media</h2>
  <p class="button-group">
    <a href="<?= CMS_CANONICAL ?>/media/upload" class="button">Upload asset</a>
    <a href="<?= CMS_CANONICAL ?>/media/new" class="button">New post</a>
  </p>
</header>

<?php $posts = \store\list_photos() ?>

<h3>Posts</h3>

<ul>
  <?php foreach($posts as $post) { ?>
    <li>
      <a href="<?= CMS_CANONICAL ?>/media/edit?id=<?= $post['id'] ?>">
        <img src="<?= photo_url($asset) ?>" alt="#<?= asset['id'] ?>" loading="lazy">
      </a>
    </li>
  <?php } ?>
</ul>

<?php if(count($posts) <= 0) {
  ?>
    <p class="placeholder-text">No posts yet.</p>
  <?php
} ?>

<?php $assets = \store\list_assets() ?>

<h3>Assets</h3>

<ul>
  <?php foreach($assets as $asset) { ?>
    <li>
      <a href="<?= CMS_CANONICAL ?>/media/detail?id=<?= $asset['id'] ?>">
        <img src="<?= \urls\photo_url($asset) ?>" alt="#<?= $asset['id'] ?>" title="#<?= $asset['id'] ?>"  loading="lazy">
      </a>
    </li>
  <?php } ?>
</ul>

<?php if(count($assets) <= 0) {
  ?>
    <p class="placeholder-text">No assets yet.</p>
  <?php
} ?>