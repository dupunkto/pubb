<?php
// Add schema record.

$name = $_GET['name'] ?? redirect("/");
$schema = \schemas\get_schema($name);

if(!$schema) fail("Schema '{$name}' not found.");

if(isset($_POST['add'])) {
  $data = [];

  foreach($schema['fields'] as $field => $definition) {
    $data[$field] = cast($_POST[$field] ?? '');
  }

  $errors = \schemas\validate_record($schema, $data);
  
  if(empty($errors)) {
    \store\create_record($name, $data) 
      or fail("Couldn't add {$name}.");

    $title = \core\get_record_title($schema, $data);
    complete("Added '{$title}'.", to: "/schemas?name={$name}");
  }
}

include $view;