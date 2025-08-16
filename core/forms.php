<?php
// Collection of shorthands for rendering common form components.

namespace forms;

function options($name, $options, $selected, $ints = false) {
  ?>
    <select name="<?= $name ?>">
      <?php 
        foreach($options as $value => $label) {
          if (is_int($value) and !$ints) {
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

function schema_form($schema, $data = []) {
  foreach($schema['fields'] as $field => $definition) {
    ?>
      <p>
        <label for="<?= $field ?>"><?= $definition['label'] ?? ucfirst($field) ?></label>
        <?php schema_field($field, $definition, $data[$field] ?? '') ?>
      </p>
    <?php
  }
}

function schema_field($field, $definition, $value = '') {
  $type = $definition['type'];
  $required = $definition['required'] ?? false;
  $min = @$definition['min'];
  $max = @$definition['max'];
  $default = $definition['default'] ?? '';

  // Use default value if no value provided
  if(empty($value)) $value = $default;
  
  if($type == 'datetime') {
    $type = 'datetime-local';

    if($value && strpos($value, 'T') == false) {
      $value = str_replace(' ', 'T', $value);
    }
  }
    
  switch($type) {
    case 'textarea':
      ?>
        <textarea name="<?= $field ?>" <?php
          if($required) echo "required";
          if($min) echo " minlength='{$min}'";
          if($max) echo " maxlength='{$max}'";
        ?>><?= htmlspecialchars($value) ?></textarea>
      <?php
      break;
      
    case 'boolean':
      ?>
        <input type="checkbox" name="<?= $field ?>" <?php if($value) echo "checked" ?>>
      <?php
      break;
      
    case 'select':
      ?>
        <select name="<?= $field ?>" <?php if($required) echo "required" ?>>
          <?php if(!$required): ?><option value="">None</option><?php endif; ?>
          <?php foreach($definition['options'] ?? [] as $option) { ?>
            <option value="<?= esc_attr($option) ?>" <?php if($value == $option) echo "selected"?>>
              <?= $option ?>
            </option>
          <?php } ?>
        </select>
      <?php
      break;

    case 'number':
      ?>
        <input type="<?= $type ?>" name="<?= $field ?>" value="<?= esc_attr($value) ?>" <?php
          if($required) echo "required";
          if($min) echo " min='{$min}'";
          if($max) echo " max='{$max}'";
        ?>>
      <?php
      break;

    default:
      ?>
        <input type="<?= $type ?>" name="<?= $field ?>" value="<?= esc_attr($value) ?>" <?php
          if($required) echo "required";
          if($min) echo " minlength='{$min}'";
          if($max) echo " maxlength='{$max}'";
        ?>>
      <?php
      break;
  }
}
