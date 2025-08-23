<header class="bar">
  <h2>Close Friends</h2>
  <a href="<?= CMS_CANONICAL ?>/contacts" class="back">Back to contacts</a>
</header>

<?php $friends = \store\list_friends() ?>

<ul>
  <?php foreach($friends as $friend) { ?>
    <li>
      <strong>@<?= $friend['handle'] ?></strong>
      <code class="token"><?= $friend['token'] ?></code>
      
      <span class="actions">
        <a href="<?= CMS_CANONICAL ?>/contacts/close-friends/edit?id=<?= $friend['contact_id'] ?>">
          Edit
        </a>
      </span>
    </li>
  <?php } ?>
</ul>

<?php if(count($friends) <= 0) { ?>
  <p class="placeholder-text">No close friends yet.</p>
<?php } ?>
