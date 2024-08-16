<header class="bar">
  <h2>
    Edit <?= $item['type'] == 'section' ? "section" : "entry"  ?>
  </h2>
</header>

<form action="" method="post">
  <input type="hidden" name="id" value="<?= $item['id'] ?>">
  <input type="hidden" name="type" value="<?= $item['type'] ?>">

  <p>
    <label for="label">Label</label>
    <input 
      type="text" 
      name="label" 
      placeholder="Awesome thing(s)"
      value="<?= esc_attr($item['label']) ?>"
    >
  </p>

  <?php if($item['type'] == 'external') { ?>
    <p>
      <label for="ref">URL</label>

      <input 
        type="url" 
        name="ref" 
        placeholder="https://example.com"
        value="<?= esc_attr($item['ref']) ?>"
      >
    </p>
  <?php } ?>

  <?php if($item['type'] == 'listing') { ?>
    <p>
      <label for="ref">Type</label>
      <select name="ref">
        <option value="/code" <?php if($item['ref'] == "/code") echo "selected" ?>>Gists</option>
        <option value="/photos" <?php if($item['ref'] == "/photos") echo "selected" ?>>Photos</option>
      </select>
    </p>
  <?php } ?>

  <?php if($item['type'] == 'page') { ?>
    <p>
      <label for="page_id">Page</label>
      <select name="page_id">
        <?php foreach(\store\list_regular_pages() as $page) { ?>
          <option 
            value="<?= $page['id'] ?>"
            <?php if($page['id'] == $item['page_id']) echo "selected" ?>
          ><?= \core\get_page_title($page) ?></option>
        <?php } ?>
      </select>
    </p>
  <?php } ?>

  <?php if($item['type'] == 'section') { ?>
    <input type="hidden" name="order" value="<?= $item['order'] ?>">
  <?php } ?>

  <div class="bar">
    <?php 
      $type = $item['type'] == 'section' ? "section" : "item";
      if($type != 'section' or \store\can_delete_menu_section($item['id'])) { 
    ?>
      <a href="<?= CMS_CANONICAL ?>/menu/delete?type=<?= $type ?>&id=<?= $item['id'] ?>" class="button">Delete</a>
    <?php } else { ?>
      <span class="no-delete">(Section cannot be deleted; it has linked menu items)</span>
    <?php } ?>
    <div class="group">
      <a href="<?= CMS_CANONICAL ?>/menu" class="button">Cancel</a>
      <input type="submit" name="edit" value="Save">
    </div>
  </div>
</form>