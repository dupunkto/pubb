<form method="post">
  <header>
    <input
      type="text"
      name="slug"
      placeholder="Slug"
      <?php if(isset($slug)) { ?>
        value="<?= $slug ?>"
      <?php } ?>
      pattern="[a-z0-9](-?[a-z0-9])*"
      required
    >
    
    <div class="bar">
      <input
        type="text"
        name="title"
        placeholder="Title"
        <?php if(isset($title)) { ?>
          value="<?= $title ?>"
        <?php } ?>
        required
      >

      <p class="button-group">
        <input
          type="submit"
          name="save"
          formaction="?draft"
          <?php if(isset($draft) && $draft) { ?>
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
          <?php if(isset($draft) && $draft) { ?>
            value="Publish"
            data-confirm="Are you sure? This will publicly publish your page, and notify all contacts that you've tagged."
          <?php } else { ?>
            value="Save"
          <?php } ?>
        >
      </p>
    </div>

    <?php if(isset($reply) && $reply) { ?>
      <input type="hidden" name="reply" value="<?= $reply ?>">

      <p>
        In reply to
        <a 
          href="<?= $reply ?>"
          target="_blank"
          class="reply"
        >
          <?= parse_host($reply) ?>
        </a>
      </p>
    <?php } ?>
  </header>

  <textarea 
    autofocus 
    required 
    placeholder="Write anything. Write everything." 
    name="prose"><?php if(isset($prose)) echo $prose ?></textarea>

  <p class="options">
    <?php if(isset($id)) { ?>
      <input name="id" value="<?= $id ?>" type="hidden">
      <a href="<?= CMS_CANONICAL ?>/delete?id=<?= $id ?>" class="button">Delete</a>
    <?php } ?>

    <label for="type">Visibility:</label>

    <?php \forms\options("visibility", [
      "public" => "Public", 
      "rss-only" => "RSS-only", 
      "email-only" => "Email-only"
    ], @$visibility) ?>

    <label for="type">Render as:</label>

    <?php \forms\options("type", [
      "md" => "Markdown", 
      "html" => "HTML"
    ], @$type) ?>
  </p>
</form>
