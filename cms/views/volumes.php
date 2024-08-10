<header class="bar">
  <h2>Volumes</h2>
  <a href="<?= CMS_CANONICAL ?>/volumes/add" class="button">Add volume</a>
</header>

<?php $volumes = \store\list_volumes() ?>

<ul>
  <?php foreach($volumes as $volume) { ?>
    <li>
      <?= $volume['title'] ?>

      <span class="actions">
        <a href="<?= CMS_CANONICAL ?>/volumes/edit?id=<?= $volume['id'] ?>">
          Edit
        </a>
      </span>
    </li>
  <?php } ?>
</ul>

<?php if(count($volumes) <= 0) {
  ?>
    <p class="placeholder-text">No volumes yet.</p>
  <?php
} ?>
