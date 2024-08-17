<header class="bar">
  <h2>
    Add <?= $type ?>
    <?php if($type == 'external') echo "URL" ?>
  </h2>
</header>

<form action="" method="post">
  <input type="hidden" name="type" value="<?= $type ?>">

  <?php if(in_array($type, ['external', 'listing', 'section'])) { ?>
    <p>
      <label for="label">Label</label>
      <input type="text" name="label" placeholder="Awesome thing(s)">
    </p>
  <?php } ?>

  <?php if($type == 'external') { ?>
    <p>
      <label for="ref">URL</label>
      <input type="url" name="ref" placeholder="https://example.com">
    </p>
  <?php } ?>

  <?php if($type == 'listing') { ?>
    <p>
      <label for="ref">Type</label>
      <select name="ref">
        <option value="/all">All</option>
        <option value="/index">Pages</option>
        <option value="/code">Gists</option>
        <option value="/photos">Photos</option>
      </select>
    </p>
  <?php } ?>

  <?php if($type == 'page') { ?>
    <p>
      <label for="page_id">Page</label>
      <select name="page_id">
        <?php foreach(\store\list_regular_pages() as $page) { ?>
          <option value="<?= $page['id'] ?>"><?= \core\get_page_title($page) ?></option>
        <?php } ?>
      </select>
    </p>
  <?php } ?>

  <div class="group">
    <a href="<?= CMS_CANONICAL ?>/menu" class="button">Cancel</a>
    <input type="submit" name="add" value="Add">
  </div>
</form>