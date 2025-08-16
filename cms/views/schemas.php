<header class="bar">
  <h2><?= ucfirst($name) ?></h2>
  <a href="<?= CMS_CANONICAL ?>/schemas/add?name=<?= $name ?>" class="button">
    Add <?= strip_suffix($name, "s") ?>
  </a>
</header>

<ul>
  <?php foreach($records as $record) { ?>
    <li>
      <a href="<?= CMS_CANONICAL ?>/schemas/edit?name=<?= $name ?>&id=<?= $record['id'] ?>">
        <?= \core\get_record_title($schema, $record) ?>
      </a>
    </li>
  <?php } ?>
</ul>

<?php if(count($records) <= 0) {
  ?>
    <p class="placeholder-text">No <?= $name ?> yet.</p>
  <?php
} ?>
