<header class="bar">
  <h2>Add friend</h2>
</header>

<form action="" method="post">
  <p>
    <label for="handle">Handle</label>
    <input 
      type="text"
      name="handle"
      <?php if(isset($contact)) { ?>
        value="@<?= esc_attr($contact['handle']) ?>"
        disabled
      <?php } else { ?>
        placeholder="@dreamwastaken"
        required
      <?php } ?>
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


  <?php if(isset($contact)) { ?>
    <input type="hidden" name="contact_id" value="<?= $contact['id'] ?>">
  <?php } else { ?>
    <p>
      <label for="domain">Domain</label>
      <input 
        type="text" 
        name="domain" 
        placeholder="example.com"
        required
      >
    </p>

    <p>
      <label for="email">Email</label>
      <input 
        type="text" 
        name="email" 
        placeholder="dream@example.com"
        required
      >
    </p>

    <p>
      <!-- Needed because browsers are stupid and don't send the checkbox if unchecked -->
      <input type="hidden" name="notify" value="false" />

      <label>
        <input type="checkbox" name="notify" value="true">
        <span>Send them an email when I @mention them.</span>
      </label>
    </p>
  <?php } ?>

  <div class="group">
    <a href="<?= CMS_CANONICAL ?>/contacts/close-friends" class="button">Cancel</a>
    <input type="submit" name="add" value="Add">
  </div>
</form>