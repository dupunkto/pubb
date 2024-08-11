<header class="bar">
  <h2>
    Asset details
    <small>(#<?= $asset['id'] ?>)</small>
  </h2>

  <a href="<?= CMS_CANONICAL ?>/media/new?from=<?= $asset['id'] ?>" class="button">New post</a>
</header>

<h3>Preview</h3>

<img src="<?= \urls\photo_url($asset) ?>">

<h3>Metadata</h3>

<form action="" method="post">
  <input type="hidden" name="id" value="<?= $asset['id'] ?>">

  <table>
    <tbody>
      <tr>
        <td>Slug</td>
        <td>
          <span onclick="editSlug()" class="slug" hidden><?= $asset['slug'] ?></span>
          <input onblur="blurSlug()" type="text" name="slug" value="<?= $asset['slug'] ?>">
        </td>
      </tr>
      <tr>
        <td>Original filename</td>
        <td>
          <?= $asset['uploaded_as'] ?? '<span class="missing">Unknown</span>' ?>
        </td>
      </tr>
      <tr>
        <td>Internal reference</td>
        <td>
          <code><?= filename($asset['path']) ?></code>
        </td>
      </tr>
      <tr>
        <td>Uploaded at</td>
        <td><?= $asset['uploaded_at'] ?></td>
      </tr>
      <?php if($duplicate) { ?>
        <tr>
          <td>Duplicate of</td>
          <td>
            <a href="<?= CMS_CANONICAL ?>/media/detail?id=<?= $duplicate['id'] ?>">
              <cite>#<?= $duplicate['id'] ?></cite>
            </a>
          </td>
        </tr>
      <?php } elseif(count($duplicates) > 0) { ?>
        <tr>
          <td>Duplicates</td>
          <td>
            <?php foreach($duplicates as $duplicate) { ?>
              <a href="<?= CMS_CANONICAL ?>/media/detail?id=<?= $duplicate['id'] ?>">
                <cite>#<?= $duplicate['id'] ?></cite>
              </a>
            <?php } ?>
          </td>
        </tr>
      <?php } if(count($linked) != 0) { ?>
        <tr>
          <td>Linked posts</td>
          <td>
            <?php foreach($linked as $post) { ?>
              <a href="<?= CMS_CANONICAL ?>/media/edit?id=<?= $post['id'] ?>">
                <cite>*<?= $post['slug'] ?></cite>
              </a>
            <?php } ?>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <div class="bar">
    <a 
      class="button" 
      href="/media/delete?id=<?= $asset['id'] ?>"
      <?php if(count($duplicates) > 0) { ?>
        data-confirm="Please note that deleting this asset will not stop existing links to the image from working, as there are duplicates."
      <?php } else { ?>
        data-confirm="Are you sure? All links to this asset will stop working, immediately. This action cannot be reversed."
      <?php } ?>
    >
      Delete
    </a>
    <input type="submit" name="save" value="Save">
  </div>
</form>

<script>
  const slug = document.querySelector(".slug");
  const input = document.querySelector("[name='slug']");

  function editSlug() {
    slug.style.display = "none";
    input.style.display = "block";
    input.focus();
    input.selectionStart = input.selectionEnd = input.value.length;
  }

  function blurSlug() {
    if(input.value.length == 0) return;
    slug.textContent = input.value;
    slug.style.display = "block";
    input.style.display = "none";
  }

  blurSlug();
</script>