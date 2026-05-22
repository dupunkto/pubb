<form method="post">
  <header>
    <input
      type="text"
      name="slug"
      placeholder="Slug"
      <?php if(isset($slug)) { ?>
        value="<?= esc_attr($slug) ?>"
      <?php } ?>
      pattern="[@~]?[a-z0-9](-?[a-z0-9])*"
      required
    >

    <div class="bar">
      <input
        type="text"
        name="title"
        placeholder="Title"
        <?php if(isset($title)) { ?>
          value="<?= esc_attr($title) ?>"
        <?php } ?>
      >

      <p class="group">
        <input
          type="submit"
          name="save"
          formaction="?draft"
          <?php if(isset($draft) and $draft) { ?>
            value="Save"
            title="Save as draft"
          <?php } else { ?>
            value="Save as draft"
            title="Convert to draft and save changes"
            data-confirm="Are you sure? This will unpublish your page and save any changes you made as draft. All links to this page will stop working too."
          <?php } ?>
        >

        <input
          type="submit"
          name="save"
          formaction="?publish"
          <?php if(isset($draft) and $draft) { ?>
            value="Publish"
            data-confirm="Are you sure? This will publicly publish your page, and notify all contacts that you've tagged."
          <?php } else { ?>
            value="Save"
          <?php } ?>
        >
      </p>
    </div>

    <?php if(FEATURES_SHOW_SONG) { ?>
      <input
        type="text"
        name="song"
        placeholder="Song"
        <?php if(isset($song)) { ?>
          value="<?= esc_attr($song) ?>"
        <?php } ?>
      >
    <?php } ?>

    <?php if(isset($reply) and $reply) { ?>
      <input type="hidden" name="reply" value="<?= esc_attr($reply) ?>">

      <p>
        In reply to
        <a 
          href="<?= $reply ?>"
          target="_blank"
          class="reply"
        >
          <?= url_host($reply) ?>
        </a>
      </p>
    <?php } ?>
  </header>

  <textarea
    autofocus 
    required 
    placeholder="Write anything. Write everything." 
    name="prose"><?php if(isset($prose)) echo htmlspecialchars($prose) ?></textarea>

  <?php if(isset($id)) { ?>
    <p class="views"><?= \store\view_count($id) ?> views</p>
  <?php } ?>

  <p class="options">
    <?php if(isset($id)) { ?>
      <input name="id" value="<?= $id ?>" type="hidden">
      <a href="<?= CMS_CANONICAL ?>/delete?id=<?= $id ?>" class="button">Delete</a>
    <?php } ?>

    <label for="type">Visibility:</label>

    <?php \forms\options("visibility", \core\list_visibilities(), @$visibility, flat: true, reverse: !EDITOR_REVERSE_VISIBILITIES) ?>

    <label for="type">Format:</label>

    <?php \forms\options("type", [
      "md" => "Markdown", 
      "html" => "HTML",
      "txt" => "Plain text"
    ], @$type) ?>

    <?php if(count(\core\list_categories()) > 1) { ?>
      <?php \forms\options("category", \core\list_categories(), @$category) ?>
    <?php } ?>
  </p>
</form>
