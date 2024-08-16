<script defer src="<?= CMS_CANONICAL ?>/vendor/uploads.min.js"></script>

<form enctype="multipart/form-data" action="" method="post">
  <header class="bar">
    <h2><?= $title ?></h2>

    <div class="group">
      <?php if(isset($id)) { ?>
        <input name="id" value="<?= $id ?>" type="hidden">
        <a href="<?= CMS_CANONICAL ?>/delete?type=post&return=/media&id=<?= $id ?>" class="button">delete</a>
      <?php } ?>
      <input type="submit" name="save" value="Save">
    </div>
  </header>

  <input
    type="text"
    name="slug"
    placeholder="Slug"
    <?php if(isset($slug)) { ?>
      value="<?= $slug ?>"
    <?php } ?>
    required
  >

  <input 
    type="text"
    name="caption"
    placeholder="Caption"
    <?php if(isset($caption)) { ?>
      value="<?= $caption ?>"
    <?php } ?>
  >

  <?php if(isset($store_path)) { ?>
    <input type="hidden" name="path" value="<?= $store_path ?>">
    <img src="<?= \urls\photo_url(["path" => $store_path]) ?>">
  <?php } else { ?>
    <input type="file" name="photo" accept="image/*" required>
  <?php } ?>
</form>