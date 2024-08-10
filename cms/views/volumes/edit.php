<header class="bar">
  <h2>Edit volume</h2>
</header>

<form action="" method="post">
  <input type="hidden" name="id" value="<?= $id ?>">

  <p>
    <label for="slug">Slug</label>
    <input
      type="text" 
      name="slug" 
      placeholder="superficial-awesomeness"
      value="<?= escape_attribute($volume['slug']) ?>"
      required
    >
  </p>

  <p>
    <label for="slug">Title</label>
    <input
      type="text" 
      name="title" 
      placeholder="Superficial Awesomeness"
      value="<?= escape_attribute($volume['title']) ?>"
      required
    >
  </p>

  <p>
    <label for="slug">Description</label>
    <textarea
      name="description"
      required
    ><?= htmlspecialchars($volume['description'] ?? "") ?></textarea>
  </p>

  <p>
    <label for="start_at">Starts at</label>
    <input
      type="date" 
      name="start_at"
      value="<?= escape_attribute($volume['start_at']) ?>"
      required
    >
  </p>

  <p>
    <label for="end_at">Ends at</label>
    <input
      type="date" 
      name="end_at"
      value="<?= escape_attribute($volume['end_at']) ?>"
      required
    >
  </p>

  <div class="bar">
    <a href="<?= CMS_CANONICAL ?>/volumes/delete?id=<?= $id ?>" class="button">Delete</a>
    <div class="button-group">
      <a href="<?= CMS_CANONICAL ?>/volumes" class="button">Cancel</a>
      <input type="submit" name="edit" value="Save">
    </div>
  </div>
</form>