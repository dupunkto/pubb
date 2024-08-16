<?php
// Delete menu item or section.

if(!isset($_GET['id'])) redirect("/menu");
if(!isset($_GET['type'])) redirect("/menu");

$id = $_GET['id'];
$type = $_GET['type'];

switch($type) {
  case "item":
    $item = \store\get_menu_item($id) or fail("Menu item doesn't exist.", to: "/menu");
    \store\delete_menu_item($id) or fail("Couldn't delete menu item.", to: "/menu");
    break;

  case "section":
    $item = \store\get_menu_section($id) or fail("Section doesn't exist.", to: "/menu");
    \store\can_delete_menu_section($id) or fail("Cannot delete section because it is in use.");
    \store\delete_menu_section($id) or fail("Couldn't delete section.", to: "/menu");
    break;

  default:
    redirect("/menu");
    break;
}

complete("Deleted '{$item['label']}'.", to: "/menu");
