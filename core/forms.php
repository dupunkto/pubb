<?php
// Collection of shorthands for rendering common form components.

namespace forms;

function options($name, $options, $selected) {
  ?>
    <select name="<?= $name ?>">
      <?php foreach($options as $value => $label) { ?>
        <option value="<?= $value ?>" <?php if($selected == $value) echo "selected" ?>>
          <?= $label ?>
        </option>
      <?php } ?>
    </select>
  <?php
}
