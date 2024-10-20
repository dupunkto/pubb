<form method="post">
  <header>
    <input
      type="text"
      name="caption"
      placeholder="Caption"
      <?php if(isset($caption)) { ?>
        value="<?= esc_attr($caption) ?>"
      <?php } ?>
    >
    
    <div class="bar">
      <input
        type="text"
        name="filename"
        placeholder="main.c"
        <?php if(isset($filename)) { ?>
          value="<?= esc_attr($filename) ?>"
        <?php } ?>
        pattern="[a-zA-Z0-9_\-\.]+\.[a-zA-Z0-9_]+"
        required
      >

      <p class="group">
        <?php if(isset($id)) { ?>
          <input name="id" value="<?= $id ?>" type="hidden">
          <a href="<?= CMS_CANONICAL ?>/delete?type=code&return=/code&id=<?= $id ?>" class="button">rm&nbsp;&#8209;rf</a>
        <?php } ?>

        <input type="submit" name="save" value=":wq">
      </p>
    </div>
  </header>

  <textarea 
    autofocus 
    required 
    placeholder='#include <stdio.h>

int main() {
   printf("Hello, World!");
   return 0;
}' name="code"><?php if(isset($code)) echo $code ?></textarea>
</form>
