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
                $url = \urls\page_url(\store\get_page($item['page_id']));
                $selected = parse_path($url) == $path;
              } else {
                $url = $item['ref'];
                $selected = $url == $path;
              }

              ?>
                <li
                  <?php if($selected) echo 'class="selected"' ?>
                  ><a 
                    href="<?= $url ?>" 
                    data-title="<?= esc_attr($item['label']) ?>"
                  ><?= $item['label'] ?></a
                ></li>
              <?php 
            } 
          ?>
        </ul>
      <?php
    }
  ?>
</details>
