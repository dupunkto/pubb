<header class="bar">
  <h2>Add <?= strip_suffix($name, "s") ?></h2>
</header>

<form action="" method="post">
  <?php if(isset($errors)) { ?>
    <div class="errors">
      <?php foreach($errors as $field => $error) { ?>
        <p><strong><?= $field ?>:</strong> <?= $error ?></p>
      <?php } ?>
    </div>
  <?php } ?>

  <?php \forms\schema_form($schema, $_POST ?? []) ?>

  <div class="group">
    <a href="<?= CMS_CANONICAL ?>/schemas?name=<?= $name ?>" class="button">Cancel</a>
    <input type="submit" name="add" value="Add">
  </div>
</form>