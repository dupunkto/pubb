<script defer src="<?= CMS_CANONICAL ?>/vendor/uploads.min.js"></script>

<form enctype="multipart/form-data" action="" method="post">
  <header class="bar">
    <h2>Upload assets</h2>
    <input type="submit" name="upload" value="Upload">
  </header>

  <input type="file" name="assets[]" accept="image/*" multiple required>
</form>
