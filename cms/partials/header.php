<header>
  <h1><a href="<?= CMS_CANONICAL ?>">Pebble</a></h1>

  <?php if(is_authenticated()) { ?>
    <div class="group">
      <a href="<?= CANONICAL ?>" target="_blank">View site →</a>
      <a href="<?= CMS_CANONICAL ?>/settings">Settings</a>
    </div>
  <?php } ?>
</header>
