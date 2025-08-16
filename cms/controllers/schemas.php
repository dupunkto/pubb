<?php
// List schema records.

$name = $_GET['name'] ?? redirect("/");
$schema = \schemas\get_schema($name);

if(!$schema) fail("Schema '{$name}' not found.");
$records = \store\list_records($name);

include $view;