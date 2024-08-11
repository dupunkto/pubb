<?php if(defined('SITE_COPYRIGHT')) { ?>
  <p>
    <b><?= SITE_TITLE ?></b>
    <small>
      <?= SITE_COPYRIGHT ?>
    </small>
    <?php if(defined('LICENSE') and defined('LICENSE_URI')) { ?>
      — <a href="<?= esc_attr(LICENSE_URI) ?>" rel="license" target="_blank">
        <?= LICENSE ?>
      </a>
    <?php } ?>
  </p>
<?php } ?>

<p class="powered-by">
  Powered by Pubb v<?= PUBB_VERSION ?>, a
  <a href="//dupunkto.org">{du}punkto</a> project.<br>
</p>
