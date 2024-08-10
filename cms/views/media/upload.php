<header class="bar">
  <h2>Upload assets</h2>
</header>

<form enctype="multipart/form-data" action="" method="post">
  <input type="file" name="assets[]" accept="image/*" multiple required>
  <input type="submit" name="upload" value="Upload">
</form>

<script>
  function containsFiles(e) {
    return [...e.dataTransfer.types ?? []].some(t => t === "Files");
  }

  const dropZone = document.querySelector("input[type='file']");
  const showDropZone = () => dropZone.style.filter = "brightness(0.95)";
  const hideDropZone = () => dropZone.style.filter = "";

  dropZone.ondragover = e => e.preventDefault();
  dropZone.ondragenter = e => {
    if (!containsFiles(e)) return;
    showDropZone();

    dropZone.ondragleave = e => hideDropZone();
    dropZone.ondrop = e => hideDropZone();
  };
</script>