<header class="bar">
  <h2>Add volume</h2>
</header>

<form action="" method="post">
  <p>
    <label for="slug">Slug</label>
    <input
      type="text" 
      name="slug" 
      placeholder="superficial-awesomeness"
      required
    >
  </p>

  <p>
    <label for="slug">Title</label>
    <input
      type="text" 
      name="title" 
      placeholder="Superficial Awesomeness"
      required
    >
  </p>

  <p>
    <label for="slug">Description</label>
    <textarea
      name="description"
      required
    ></textarea>
  </p>

  <p>
    <label for="start_at">Starts at</label>
    <input
      type="date" 
      name="start_at" 
      required
    >
  </p>

  <p>
    <label for="end_at">Ends at</label>
    <input
      type="date" 
      name="end_at" 
      required
    >
  </p>

  <div class="group">
    <a href="<?= CMS_CANONICAL ?>/volumes" class="button">Cancel</a>
    <input type="submit" name="add" value="Add">
  </div>
</form>