<?php
// Delete schema record.

$name = $_GET['name'] ?? redirect("/");
$id = $_GET['id'] ?? redirect("/schemas?name={$name}");

$schema = \schemas\get_schema($name);
$record = \store\get_record($name, $id);

if(!$schema) fail("Schema '{$name}' not found.");
if(!$record) fail("Record not found.");

\store\delete_record($name, $id) 
  or fail("Couldn't delete {$name}.");

$title = \core\get_record_title($schema, $record);
complete("Deleted '{$title}'.", to: "/schemas?name={$name}");