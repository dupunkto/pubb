<header>
  <h1><a href="<?= CMS_CANONICAL ?>">Pebble</a></h1>

  <?php if(is_authenticated()) { ?>
    <a href="<?= CMS_CANONICAL ?>/settings">Settings</a>
  <?php } ?>
</header>
