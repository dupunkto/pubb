<header class="bar">
  <h2>Your home on the Web.</h2>
</header>

<p>Welcome to pebble, a simple but powerful tool to publish words on the Web.</p>

<p><br></p>

<h3>Quick actions</h3>

<ul>
  <li><a href="<?= CMS_CANONICAL ?>/pages">Pages</a></li>
  <li><a href="<?= CMS_CANONICAL ?>/media">Media</a></li>

  <?php if(defined('SCHEMAS')) {
    foreach(\schemas\list_schemas() as $name => $schema) { ?>
      <li><a href="<?= CMS_CANONICAL ?>/schemas?name=<?= $name ?>"><?= ucfirst($name) ?></a></li>
    <?php }
  } ?>

  <li><a href="<?= CMS_CANONICAL ?>/code">Gists</a></li>
  <li><a href="<?= CMS_CANONICAL ?>/comments">Comments</a></li>
  <li><a href="<?= CMS_CANONICAL ?>/contacts">Contacts</a></li>
  <li><a href="<?= CMS_CANONICAL ?>/volumes">Volumes</a></li>
  <li><a href="<?= CMS_CANONICAL ?>/menu">Menu</a></li>
  <li><a href="<?= CMS_CANONICAL ?>/stats">Statistics</a></li>
</ul>
