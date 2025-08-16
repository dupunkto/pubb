<?php
// Edit schema record.

$name = $_GET['name'] ?? redirect("/");
$id = $_GET['id'] ?? redirect("/schemas?name={$name}");

$schema = \schemas\get_schema($name);
$record = \store\get_record($name, $id);

if(!$schema) fail("Schema '{$name}' not found.");
if(!$record) fail("Record not found.");

if(isset($_POST['edit'])) {
  $data = [];
  
  foreach($schema['fields'] as $field => $definition) {
    $data[$field] = cast($_POST[$field] ?? '');
  }
  
  $errors = \schemas\validate_record($schema, $data);
  
  if(empty($errors)) {
    \store\update_record($name, $id, $data) 
      or fail("Couldn't update {$name}.");

    $title = \core\get_record_title($schema, $record);
    complete("Updated '{$title}'.", to: "/schemas?name={$name}");
  }
}

include $view;