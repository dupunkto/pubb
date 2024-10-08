<form action="" method="post">
  <h3>Site details</h3>

  <p class="favicon">
    <label for="site.favicon">Icon</label>
    <input 
      type="text"
      name="site.favicon"
      placeholder="✨"
      pattern="^\X$"
      value="<?= canonical_value("site.favicon") ?>"
    >
  </p>

  <p>
    <label for="site.title">Title</label>
    <input 
      type="text" 
      name="site.title" 
      placeholder="Oblivously"
      value="<?= canonical_value("site.title") ?>"
      required
    >
  </p>

  <p>
    <label for="site.description">Description</label>
    <input
      type="text"
      name="site.description"
      placeholder="Because fuck social norms."
      value="<?= canonical_value("site.description") ?>"
      required
    >
  </p>

  <p>
    <label for="site.lang">Language</label>

    <select name="site.lang">
      <?php foreach(LANGUAGE_CODES as $code => $language) { ?>
        <option 
          value="<?= $code ?>" 
          <?php if($code == SITE_LANG) echo "selected" ?>
        >
          <?= $language ?>
        </option>
      <?php } ?>
    </select>
  </p>

  <p>
    <label for="site.copyright">Copyright notice</label>
    <input 
      type="text" 
      name="site.copyright"
      placeholder="♡ Copying is an act of love. Love is not subject to law. Please copy."
      value="<?= canonical_value("site.copyright") ?>"
    >
  </p>

  <h3>Profile</h3>

  <p>
    <label for="profile.handle">Handle</label>
    <input 
      type="text" 
      name="profile.handle"
      placeholder="@dreamwastaken"
      value="<?= canonical_value("profile.handle") ?>"
    >
  </p>

  <p>
    <label for="profile.bio">Biography</label>
    <textarea
      name="profile.bio"
      placeholder="Influcener or something. I don't know, who cares anyway??"
      rows="3"
    ><?= canonical_value("profile.bio") ?></textarea>
  </p>

  <table>
    <tr>
      <td>
        <label for="profile.status">Status</label>
      </td>
      <td>
        <input 
          type="text" 
          name="profile.status"
          placeholder="reading, chilling, studying, sleeping, ..."
          value="<?= canonical_value("profile.status") ?>">
      </td>
    </tr>
    <tr>
      <td>
        <label for="profile.mood">Mood</label>
      </td>
      <td>
        <input 
          type="text" 
          name="profile.mood"
          placeholder="busy, bored, happy, sad, ..."
          value="<?= canonical_value("profile.mood") ?>">
      </td>
    </tr>
  </table>

  <h3>Personal details</h3>

  <p>
    <label for="author.name">Display name</label>
    <input 
      type="text" 
      name="author.name"
      placeholder="Charlie Spring"
      value="<?= canonical_value("author.name") ?>"
    >
  </p>

  <p>
    <label for="author.email">Email address</label>
    <span>The public email address that will be printed on your profile, in feeds and in your contact details.</span>

    <input 
      type="email" 
      name="author.email"
      placeholder="dummy@<?= HOST ?>"
      value="<?= canonical_value("author.email") ?>"
    >
  </p>

  <p>
    <label for="author.site">Primary site</label>
    <span>The website that will be printed on your profile, in feeds and in your contact details. (leave empty to use this site)</span>

    <input 
      type="url" 
      name="author.site"
      placeholder="<?= CANONICAL ?>"
      value="<?= canonical_value("author.site") ?>"
    >
  </p>

  <h3>Notifications</h3>

  <p>
    <label for="notifications.admin">Receiving address</label>
    <span>
      The email address to which incoming notifications will be sent.
    </span>

    <input 
      type="email" 
      name="notifications.admin"
      placeholder="dummy@<?= HOST ?>"
      value="<?= canonical_value("notifications.admin") ?>"
    >
  </p>

  <p>
    <label for="notifications.sender">Sending address</label>
    <span>The email address from which outgoing notifications should be sent.</span>

    <input 
      type="email" 
      name="notifications.sender"
      placeholder="noreply@<?= HOST ?>"
      value="<?= canonical_value("notifications.sender") ?>"
    >
  </p>

  <p>
    <!-- Needed because browsers are stupid and don't send the checkbox if unchecked -->
    <input type="hidden" name="notifications.webmention" value="false" />

    <label>
      <input 
        type="checkbox"
        name="notifications.webmention"
        <?php if(NOTIFICATIONS_WEBMENTION) echo "checked" ?>
        value="true"
      >
      <span>Send me an email when someone mentions one of my pages.</span>
    </label>
  </p>

  <h3>Blocking</h3>

  <p>
    <label>Non-commercial</label>
    <span>Blocks big corporations from profiting of of the contents of this site.</span>

    <!-- Needed because browsers are stupid and don't send the checkbox if unchecked -->
    <input type="hidden" name="noncommercial" value="false" />
    <input type="hidden" name="nonai" value="false" />
  </p>

  <p>
    <label>
      <input 
        type="checkbox"
        name="noncommercial"
        <?php if(NONCOMMERCIAL) echo "checked" ?>
        value="true"
      >
      <span>Block big corporations from crawling this site.</span>
    </label><br>

    <label>
      <input 
        type="checkbox"
        name="nonai"
        <?php if(NONAI) echo "checked" ?>
        value="true"
      >
      <span>Block unwanted AI bots from accessing this site.</span>
    </label>
  </p>

  <h3>License</h3>

  <p>
    <label for="license">License</label>
    <span>If you've licensed your work under (for example) a Creative Commons license.</span>

    <input 
      type="text" 
      name="license"
      placeholder="CC0 1.0"
      value="<?= canonical_value("license") ?>"
    >
  </p>

  <p>
    <label for="license.uri">License URI</label>
    <span>A stable reference documenting the license terms.</span>

    <input 
      type="url" 
      name="license.uri"
      placeholder="https://creativecommons.org/publicdomain/zero/1.0/"
      value="<?= canonical_value("license.uri") ?>"
    >
  </p>

  <h3>Personalisation</h3>

  <p>
    <label>Skin</label>

    <?php \forms\options("layout.skin", [
      "hummingbird" => "Hummingbird", 
      "traditional" => "Traditional",
      "spacebook" => "Spacebook",
      "twitter-like" => "Twitter-like",
      "neopunk" => "neopunk",
      "guthib" => "GutHib",
      "baked" => "Baked",
      "nightspire" => "Night Spire",
    ], LAYOUT_SKIN) ?>
  </p>

  <?php $indexes = ["all" => "All", "index" => "Pages", "code" => "Gists", "photos" => "Photos"] ?>

  <p>
    <label for="layout.homepage">Homepage</label>

    <select name="layout.homepage">
      <?php foreach($indexes as $value => $label) { ?>
        <option 
          value="/<?= $value ?>" 
          <?php if(LAYOUT_HOMEPAGE == "/$value") echo "selected" ?>>
          <?= $label ?>
        </option>
      <?php } ?>

      <?php foreach(\store\list_regular_pages() as $page) { ?>
        <option 
          value="<?= $page['id'] ?>"
          <?php if(\core\is_homepage($page)) echo "selected" ?>>
          <?= \core\get_page_title($page) ?>
        </option>
      <?php } ?>
    </select>
  </p>

  <p>
    <label>Layout</label>
    <span>Determines whether these pages render a feed of full-text posts, or a simplified listing linking to the pages themselves.</span>
  </p>

  <table>
    <?php foreach($indexes as $value => $label) { ?>
      <tr>
        <td>
          <label for="layout.<?= $value ?>"><?= $label ?></label>
        </td>
        <td>
          <?php \forms\options("layout.$value", 
            ["listing", "feed"], 
            constant("LAYOUT_" . strtoupper($value))
          ) ?>
        </td>
      </tr>
    <?php } ?>
  </table>

  <h3>Advanced</h3>

  <p>
    <label>Sidebars</label>
    <span>All HTML is supported. Depending on the skin, the sidebars may or may not be visible. (leave empty to hide sidebars)</span>
  </p>

  <table>
    <thead>
      <tr>
        <td><label for="layout.left-sidebar">Left sidebar</label></td>
        <td><label for="layout.right-sidebar">Right sidebar</label></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <textarea
            name="layout.left-sidebar"
            placeholder="<p>Hello, World!</p>"
            rows="6"
          ><?= canonical_value("layout.left-sidebar") ?></textarea>
        </td>
        <td>
          <textarea
            name="layout.right-sidebar"
            placeholder="<p>Hello, Computer!</p>"
            rows="6"
          ><?= canonical_value("layout.right-sidebar") ?></textarea>
        </td>
      </tr>
    </tbody>
  </table>

  <p>
    <label for="layout.custom-css">Custom CSS</label>
    <textarea
      name="layout.custom-css"
      placeholder="body {
  display: flex;
}"
      rows="4"
    ><?= canonical_value("layout.custom-css") ?></textarea>
  </p>

  <h3>Security</h3>

  <p>
    <label for="passphrase">Passphrase</label>
    <span>If you want to change the passphrase, please enter a new one and then confirm it by typing it again. (leave empty to keep current passphrase)</span>

    <input 
      type="password" 
      name="passphrase"
      placeholder="Enter a passphrase..."
    >

    <input 
      type="password" 
      name="confirm"
      placeholder="Confirm passphrase..."
    >
  </p>
  
  <p>
    <!-- Needed because browsers are stupid and don't send the checkbox if unchecked -->
    <input type="hidden" name="force-https" value="false" />

    <label>
      <input 
        type="checkbox"
        name="force-https"
        <?php if(FORCE_HTTPS) echo "checked" ?>
        value="true"
      >
      <span>
        Force HTTP to HTTPS redirect. 
        (<a href="https://wiki.dupunkto.org/HTTPSIsOverrated">Why is this off by default?</a>)
      </span>
    </label>
  </p>

  <input type="submit" name="save" value="Save">
</form>
