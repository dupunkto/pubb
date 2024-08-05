<form action="" method="post">
  <header>
    <input
      type="text"
      name="slug"
      placeholder="Slug"
      value="<?= @$slug ?>"
      pattern="[a-z0-9](-?[a-z0-9])*"
      required
    >
    
    <div class="bar">
      <input
        type="text"
        name="title"
        placeholder="Title"
        value="<?= @$title ?>"
        required
      >

      <p class="button-group">
        <?php if(isset($id)) { ?>
          <input name="id" value="<?= $id ?>" type="hidden">
          <a href="<?= CMS_CANONICAL ?>/delete?id=<?= $id ?>" class="button">Delete</a>
        <?php } ?>

        <input 
          type="submit"
          name="save"
          <?php if($draft) { ?>
            value="Save"
            title="Save as draft"
          <?php } else { ?>
            value="Save as draft"
            title="Convert to draft and save changes"
            data-confirm="Are you sure? This will unpublish your page and 
            save any changes you made as draft. All links to this page will
            stop working too."
          <?php } ?>
        >

        <input
          type="submit"
          name="publish"
          <?php if($draft) { ?>
            value="Publish"
            data-confirm="Are you sure? This will publicly publish your
            page, and notify all contacts that you've tagged."
          <?php } else { ?>
            value="Save"
          <?php } ?>
        >
      </p>
    </div>

    <?php if(@$reply) { ?>
      <p>
        In reply to 
        <a 
          href="<?= $reply ?>"
          target="_blank"
          style="font-weight: bold"
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
    name="prose"><?= @$prose ?></textarea>
</form>
