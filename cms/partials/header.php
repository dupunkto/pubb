<header>
  <?php if(defined('COCKPIT')) { ?>
    <h1><a href="<?= PROTO ?>://<?= COCKPIT ?>">Scatter</a></h1>
  <?php } else { ?>
    <h1><a href="<?= CMS_CANONICAL ?>">pebble</a></h1>
  <?php } ?>

  <?php if(function_exists('is_authenticated') and is_authenticated()) { ?>
    <div class="group">
      <a href="<?= CANONICAL ?>" target="_blank">View site →</a>
      <a href="<?= CMS_CANONICAL ?>/settings">Settings</a>
    </div>
  <?php } ?>
</header>
