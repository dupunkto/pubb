<?php
// TOML schemas for extending the CMS with custom entities.

// Dear reader, I would rather not be reminded of the existance of
// the following code. Leave me alone. Here be dragons.

namespace schemas;

define('SCHEMAS', STORE . "/schemas");
define('FIELD_TYPES', ["text", "textarea", "number", "boolean", "select", "date", "time", "datetime", "email", "url"]);

function get_schema($name) {
  $path = SCHEMAS . "/{$name}.toml";
  $definition = parse_schema($path);
  
  return [
    'name' => $name,
    'path' => $path,
    'fields' => $definition,
    'rev' => md5_file($path)
  ];
}

function parse_schema($path) {  
  return assoc(\TOML::parseFile($path));
}

function list_schemas() {
  $schemas = [];
  
  foreach(glob(SCHEMAS . "/*.toml") as $path) {
    $definition = parse_schema($path);
    $name = basename($path, '.toml');
    
    $schemas[$name] = [
      'name' => $name,
      'path' => $path,
      'fields' => $definition,
      'rev' => md5_file($path)
    ];
  }
  
  return $schemas;
}

function scan_schemas() {  
  foreach(list_schemas() as $name => $schema) {
    \store\migrate_schema($name, $schema);
  }
}

// Validation helpers

function validate_schema($fields) {
  $errors = [];
  
  foreach($fields as $field => $definition) {
    if(!is_array($definition)) {
      $errors[] = "Field '{$field}' must be a section";
      continue;
    }
    
    if(!isset($definition['type'])) {
      $errors[] = "Field '{$field}' missing required 'type' property";
      continue;
    }
    
    if(!in_array($definition['type'], FIELD_TYPES)) {
      $errors[] = "Field '{$field}' has unsupported type '{$definition['type']}'";
    }
    
    if($definition['type'] === 'select' && !isset($definition['options'])) {
      $errors[] = "Field '{$field}' of type 'select' must have 'options' property";
    }
    
    if(isset($definition['required']) && !is_bool($definition['required'])) {
      $errors[] = "Field '{$field}' 'required' property must be boolean";
    }
    
    if(isset($definition['min']) && !is_numeric($definition['min'])) {
      $errors[] = "Field '{$field}' 'min' property must be numeric";
    }
    
    if(isset($definition['max']) && !is_numeric($definition['max'])) {
      $errors[] = "Field '{$field}' 'max' property must be numeric";
    }
  }
  
  return $errors;
}

function validate_record($schema, $data) {
  $errors = [];
  
  foreach($schema['fields'] as $field => $definition) {
    $value = $data[$field] ?? null;
    
    if(($definition['required'] ?? false) && empty($value)) {
      $errors[$field] = "This field is required";
      continue;
    }
    
    // Skip validation if field is empty and not required
    if(empty($value)) continue;
    
    switch($definition['type']) {
      case 'number':
        if(!is_numeric($value)) {
          $errors[$field] = "Must be a number";
        } else {
          $num = (float)$value;
          if(isset($definition['min']) && $num < $definition['min']) {
            $errors[$field] = "Must be at least {$definition['min']}";
          }
          if(isset($definition['max']) && $num > $definition['max']) {
            $errors[$field] = "Must be at most {$definition['max']}";
          }
        }
        break;
        
      case 'email':
        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $errors[$field] = "Must be a valid email address";
        }
        break;
        
      case 'url':
        if(!filter_var($value, FILTER_VALIDATE_URL)) {
          $errors[$field] = "Must be a valid URL";
        }
        break;
        
      case 'select':
        if(!in_array($value, $definition['options'])) {
          $errors[$field] = "Must be one of: " . implode(', ', $definition['options']);
        }
        break;
        
      case 'text':
      case 'textarea':
        if(isset($definition['min']) && strlen($value) < $definition['min']) {
          $errors[$field] = "Must be at least {$definition['min']} characters";
        }
        if(isset($definition['max']) && strlen($value) > $definition['max']) {
          $errors[$field] = "Must be at most {$definition['max']} characters";
        }
        break;
    }
  }
  
  return $errors;
}

// Uncomment the following line to run migrations.
// scan_schemas();