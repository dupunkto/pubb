<header class="bar">
  <h2>Add contact</h2>
</header>

<form action="" method="post">
  <p>
    <label for="handle">Handle</label>
    <input
      type="text" 
      name="handle" 
      placeholder="@dreamwastaken"
      required
    >
  </p>

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

  <div class="button-group">
    <a href="<?= CMS_CANONICAL ?>/contacts" class="button">Cancel</a>
    <input type="submit" name="add" value="Add">
  </div>
</form>