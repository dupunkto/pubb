<?php
// Edit menu item or section.

if(!isset($_GET['id'])) redirect("/menu");
if(!isset($_GET['type'])) redirect("/menu");

$id = $_GET['id'];
$type = $_GET['type'];

$item = match($type) {
  "item" => \store\get_menu_item($id),
  "section" => \store\get_menu_section($id),
  default => false
} or redirect("/menu");

if(isset($_POST['edit']) and $_POST['type'] == 'page') {
  \store\update_menu_item(
    id: cast($_POST['id']),
    label: cast($_POST['label']),
    page_id: cast($_POST['page_id']),
    ref: null
  ) or fail("Couldn't update menu item.");

  complete("Updated '{$_POST['label']}'.", to: "/menu");
}

if(isset($_POST['edit']) and in_array($_POST['type'], ['external', 'index'])) {
  \store\update_menu_item(
    id: cast($_POST['id']),
    label: cast($_POST['label']),
    page_id: null,
    ref: cast($_POST['ref'])
  ) or fail("Couldn't update menu item.");

  complete("Updated '{$_POST['label']}'.", to: "/menu");
}

if(isset($_POST['edit']) and $_POST['type'] == 'section') {
  \store\update_menu_section(
    id: cast($_POST['id']),
    label: cast($_POST['label']),
    order: $_POST['order']
  ) or fail("Couldn't update section.");

  complete("Updated '{$_POST['label']}'.", to: "/menu");
}

include $view;