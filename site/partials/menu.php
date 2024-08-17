<?php
  $menu = \store\list_menu_items();
  $sections = group_by($menu, 'section');
?>

<details open>
  <summary><h2>Menu</h2></summary>
  
  <?php
    foreach($sections as $section) {
      if($section['label']) {
        echo "<h3>{$section['label']}</h3>";
      }

      ?>
        <ul>
          <?php 
            foreach($section['items'] as $item) { 
              if($item['type'] == 'page') {
                $page = \store\get_page($item['page_id']);
                $url = \urls\page_url($page);
              } else {
                $url = $item['ref'];
              }

              ?>
                <li>
                  <a href="<?= $url ?>" data-title="<?= esc_attr($item['label']) ?>">
                    <?= $item['label'] ?>
                  </a>
                </li>
              <?php 
            } 
          ?>
        </ul>
      <?php
    }
  ?>
</details>
