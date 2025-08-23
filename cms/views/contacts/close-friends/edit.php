<header class="bar">
  <h2>Edit token</h2>
</header>

<form action="" method="post">
  <input type="hidden" name="contact_id" value="<?= $contact_id ?>">

  <p>
    <label for="handle">Handle</label>
    <input 
      type="text" 
      value="@<?= esc_attr($contact['handle']) ?>" 
      disabled
    >
  </p>

  <p>
    <label for="token">Token</label>
    <input
      type="text" 
      name="token" 
      placeholder="Super secret password (leave blank to autogenerate)"
      value="<?= esc_attr($friend['token']) ?>"
    >
  </p>

  <div class="bar">
    <a href="<?= CMS_CANONICAL ?>/contacts/close-friends/delete?id=<?= $contact_id ?>" class="button">Revoke access</a>
    <div class="group">
      <a href="<?= CMS_CANONICAL ?>/contacts/close-friends" class="button">Cancel</a>
      <input type="submit" name="edit" value="Save">
    </div>
  </div>
</form>