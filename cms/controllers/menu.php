<?php
// Edit menu order.

$items = \store\list_menu_items();
$sections = \store\list_menu_sections();

if(isset($_POST['save-items'])) {
  foreach($items as $item) {
    \store\update_menu_order(
      id: $item['id'],
      order: $_POST["{$item['id']}_order"],
      section_id: $_POST["{$item['id']}_section_id"]
    ) or fail("Failed to update menu layout.");
  }

  complete("Updated menu layout.");
}

if(isset($_POST['save-sections'])) {
  foreach($sections as $section) {
    \store\update_menu_section(
      id: $section['id'],
      label: $section['label'],
      order: $_POST["{$section['id']}_order"]
    ) or fail("Failed to update section layout.");
  }

  complete("Updated section layout.");
}

include $view;