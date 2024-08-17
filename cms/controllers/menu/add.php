<?php
// Add menu item or section.

if(isset($_POST['add']) and $_POST['type'] == 'page') {
  $page = \store\get_page($_POST['page_id']) 
    or fail("Page doesn't seem to exist.", to: "/menu");

  $title = \core\get_page_title($page);

  \store\put_menu_item(
    type: $_POST['type'],
    label: cast($title),
    page_id: cast($_POST['page_id']),
    ref: null,
    section_id: null
  ) or fail("Couldn't save menu item.");

  complete("Added menu item '{$title}'.", to: "/menu");
}

if(isset($_POST['add']) and in_array($_POST['type'], ['external', 'index'])) {
  \store\put_menu_item(
    type: $_POST['type'],
    label: cast($_POST['label']),
    page_id: null,
    ref: cast($_POST['ref']),
    section_id: null
  ) or fail("Couldn't save menu item.");

  complete("Added menu item '{$_POST['label']}'.", to: "/menu");
}

if(isset($_POST['add']) and $_POST['type'] == 'section') {
  \store\put_menu_section(
    label: cast($_POST['label']),
  ) or fail("Couldn't save section.");

  complete("Added section '{$_POST['label']}'.", to: "/menu");
}

if(!isset($_GET['type'])) redirect("/menu");

$type = $_GET['type'];
$types = ["page", "external", "index", "section"];

if(!in_array($type, $types)) redirect("/menu");

include $view;