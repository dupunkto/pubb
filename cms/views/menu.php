<header class="bar">
  <h2>Menu</h2>
</header>

<form action="" method="post">
  <div class="bar">
    <h3>Entries</h3>
  </div>

  <ul>
    <?php foreach($items as $item) { ?>
      <li>
        <?= $item['label'] ?>
        <span class="actions">
          <select name="<?= $item['id'] ?>_section_id">
            <option value="" <?php if(!$item['section_id']) echo "selected" ?>>Top level</option>
            <?php foreach($sections as $section) { ?>
              <option 
                value="<?= $section['id'] ?>" 
                <?php if($section['id'] == $item['section_id']) echo "selected" ?>
              >
                <?= $section['label'] ?>
              </option>
            <?php } ?>
          </select>
          <input 
            type="number" 
            name="<?= $item['id'] ?>_order" 
            value="<?= $item['order'] ?>"
          >
          <a href="<?= CMS_CANONICAL ?>/menu/edit?type=item&id=<?= $item['id'] ?>">
            Edit
          </a>
        </span>
      </li>
    <?php } ?>
  </ul>

  <?php if(count($items) <= 0) {
    ?>
      <p class="placeholder-text">No menu items yet.</p>
    <?php
  } ?>

  <div class="bar lint">
    <div class="group">
      <a href="<?= CMS_CANONICAL ?>/menu/add?type=page" class="button">Add page</a>
      <a href="<?= CMS_CANONICAL ?>/menu/add?type=external" class="button">Add external URL</a>
      <a href="<?= CMS_CANONICAL ?>/menu/add?type=index" class="button">Add index</a>
    </div>

    <?php if(count($items) > 0) { ?>
      <input type="submit" name="save-items" value="Save changes">
    <?php } ?>
  </div>
</form>

<form action="" method="post">
  <div class="bar">
    <h3>Sections</h3>
  </div>

  <ul>
    <?php foreach($sections as $section) { ?>
      <li>
        <?= $section['label'] ?>
        <span class="actions">
          <input 
            type="number" 
            name="<?= $section['id'] ?>_order" 
            value="<?= $section['order'] ?>"
          >
          <a href="<?= CMS_CANONICAL ?>/menu/edit?type=section&id=<?= $section['id'] ?>">
            Edit
          </a>
        </span>
      </li>
    <?php } ?>
  </ul>

  <?php if(count($sections) <= 0) {
    ?>
      <p class="placeholder-text">No sections yet.</p>
    <?php
  } ?>

  <div class="bar lint">
    <a href="<?= CMS_CANONICAL ?>/menu/add?type=section" class="button">Add section</a>
    <?php if(count($sections) > 0) { ?>
      <input type="submit" name="save-sections" value="Save changes">
    <?php } ?>
  </div>
</form>


