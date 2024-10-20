<?php
// Collection of shorthands for rendering common form components.

namespace forms;

function options($name, $options, $selected) {
  ?>
    <select name="<?= $name ?>">
      <?php 
        foreach($options as $value => $label) {
          if (is_int($value)) {
            $value = $label;
            $label = ucfirst($label);
          }
      ?>
        <option value="<?= esc_attr($value) ?>" <?php if($selected == $value) echo "selected" ?>>
          <?= $label ?>
        </option>
      <?php } ?>
    </select>
  <?php
}
