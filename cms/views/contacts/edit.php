<header class="bar">
  <h2>Edit contact</h2>
</header>

<form action="" method="post">
  <input type="hidden" name="id" value="<?= $id ?>">

  <p>
    <label for="handle">Handle</label>
    <input
      type="text" 
      name="handle" 
      placeholder="@dreamwastaken"
      value="<?= escape_attribute($contact['handle']) ?>"
      required
    >
  </p>

  <p>
    <label for="domain">Domain</label>
    <input 
      type="text" 
      name="domain" 
      placeholder="example.com"
      value="<?= escape_attribute($contact['domain']) ?>"
      required
    >
  </p>

  <p>
    <label for="email">Email</label>
    <input 
      type="text" 
      name="email" 
      placeholder="dream@example.com"
      value="<?= escape_attribute($contact['email']) ?>"
      required
    >
  </p>

  <p>
    <!-- Needed because browsers are stupid and don't send the checkbox if unchecked -->
    <input type="hidden" name="notify" value="false" />

    <label>
      <input
        type="checkbox"
        name="notify"
        <?php if($contact['notify']) echo "checked" ?>
        value="true"
      >
      <span>Send them an email when I @mention them.</span>
    </label>
  </p>

  <div class="bar">
    <a href="<?= CMS_CANONICAL ?>/contacts/delete?id=<?= $id ?>" class="button">Delete</a>
    <div class="button-group">
      <a href="<?= CMS_CANONICAL ?>/contacts" class="button">Cancel</a>
      <input type="submit" name="edit" value="Save">
    </div>
  </div>
</form>