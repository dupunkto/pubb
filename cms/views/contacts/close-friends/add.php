<header class="bar">
  <h2>Add friend</h2>
</header>

<form action="" method="post">
  <input type="hidden" name="contact_id" value="<?= $contact['id'] ?>">

  <p>
    <label for="handle">Handle</label>
    <input 
      type="text"
      name="handle"
      value="@<?= esc_attr($contact['handle']) ?>"
      disabled
    >
  </p>

  <p>
    <label for="token">Token</label>
    <input
      type="text" 
      name="token" 
      placeholder="Very secret password (leave blank to autogenerate)"
    >
  </p>

  <div class="group">
    <a href="<?= CMS_CANONICAL ?>/contacts/close-friends" class="button">Cancel</a>
    <input type="submit" name="add" value="Add">
  </div>
</form>