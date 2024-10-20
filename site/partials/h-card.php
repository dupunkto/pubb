<section class="h-card">
  <h3>
    <span class="p-name"><?= AUTHOR_NAME ?></span>
    <?php if(defined('PROFILE_HANDLE')) { ?>
      <span class="p-nickname"><?= PROFILE_HANDLE ?></span>
    <?php } ?>
  </h3>
  <?php if(defined('PROFILE_PICTURE')) { ?>
    <img 
      class="u-photo" 
      src="<?= PROFILE_PICTURE ?>"
      alt="Profile picture"
    >
  <?php } ?>
  
  <?php if(defined('PROFILE_STATUS')) { ?>
    <p class="p-status"><?= PROFILE_STATUS ?></p>
  <?php } ?>
  <?php if(defined('PROFILE_MOOD')) { ?>
    <p class="p-mood"><?= PROFILE_MOOD ?></p>
  <?php } ?>

  <?php if(defined('PROFILE_BIO')) { ?>
    <p class="p-note"><?= PROFILE_BIO ?></p>
  <?php } ?>

  <?php if(defined('AUTHOR_EMAIL')) { ?>
    <p class="p-email">
      <a class="u-email" href="<?= CANONICAL ?>/email">
        <?php [$local, $host] = explode('@', AUTHOR_EMAIL) ?>
        <?= $local ?><b hidden>.fuckspambots</b>@<?= $host ?>
      </a>
    </p>

    <small hidden>
      In text-based browsers, the email above might include <q>.fuckspambots</q>.
      You probably know what to do with that part of the email.
    </small>
  <?php } ?>

  <?php if(defined('AUTHOR_IM_NICK') and defined('AUTHOR_IM_URL')) { ?>
    <p class="p-im">
      <a class="u-im" href="<?= AUTHOR_IM_URL ?>">
        <?= AUTHOR_IM_NICK ?>
      </a>
    </p>
  <?php } ?>
</section>
