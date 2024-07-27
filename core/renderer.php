<?php
// Renders pages to HTML.

namespace renderer;

function render_info($message) {
  render_message("info", $message);
}

function render_warn($message) {
  render_message("warn", $message);
}

function render_error($message) {
  render_message("error", $message);
}

function render_success($message) {
  render_message("success", $message);
}

function render_message($kind, $message) {
  ?>
    <p class="<?= $kind ?>"><?= $message ?></p>
  <?php
}

function render_pages($pages) {
  $current_volume = null;

  foreach ($pages as $page) {
    if ($page['volume_id'] !== $current_volume) {
      if($current_volume) echo '</section>';
      $current_volume = $page['volume_id'];

      echo '<section class="volume">';
      echo "<h2>{$page['volume_title']}</h2>";
    }

    render_page($page, level: 3);
  }
}

function render_page($page, $level = 2) {
  ?>
    <article class="h-entry">
      <?php if($page['title']) {
        echo "<h$level class='p-name'>{$page['title']}</h$level>";
      } ?>

      <div class="p-summary e-content">
        <?php render_page_content($page) ?>
      </div>

      <time class="dt-published" datetime="<?= $page['published'] ?>">
        <a class="u-url" href="<?= \urls\page_url($page) ?>">
          <?= date("Y-m-d", strtotime($page['published'])) ?>
        </a>
      </time>

      <?php if(defined('AUTHOR_NAME') and defined('AUTHOR_PICTURE')) { ?>
        <div class="p-author h-card">
          <a class="u-url" href="<?= CANONICAL ?>">
            <img
              class="u-photo"
              src="<?= AUTHOR_PICTURE ?>"
              alt="<?= AUTHOR_NAME ?>"
              width="100"
            >
            <p class="p-name"><?= AUTHOR_NAME ?></p>
          </a>
        </div>
      <?php } ?>
    </article>
  <?php
}

function render_comment_section($page) {
  ?>
    <aside>
      <h2>Webmentions</h2>

      <ul>
        <?php foreach(\store\list_mentions($page['id']) as $mention) { ?>
          <?php $url = $mention['source'] ?>
          <li><a href="<?= $url ?>"><?= parse_url($url, PHP_URL_HOST) ?></a></li>
        <?php } ?>
      </ul>

      <form action="<?= WEBMENTION_ENDPOINT ?>" method="post">
        <p>
          This post accepts <a href="//indieweb.org/Webmention">Webmentions</a>. 
          Have you written a reply? Let me know the URL:
        </p>

        <input required name="target" type="hidden" value="<?= \urls\page_url($post) ?>">
        <input required name="source" type="url" placeholder="https://example.com/your/reply">

        <input type="submit" value="Send webmention">
    </aside>
  <?php
}

function render_page_content($page) {
  match($page['type']) {
    "photo" => render_photo($page),
    "code" => render_code($page),
    "markdown" => render_mdn($page),
    "html" => render_html($page),
  };
}

function render_photo($page) {
  $path = $page['path'];
  $url = is_url($path) ? $path : \urls\photo_url($path);
  
  $parser = new Parsedown();
  $caption = $parser->text($post['caption']);

  ?>
    <figure>
      <img 
        class="u-photo" 
        src="<?= $url ?>" 
        alt="<?= escape_attribute($caption) ?>"
      />
      <figcaption><?= $caption ?></figcaption>
    </figure> 
  <?php
}

function render_code($page) {
  $code = \store\contents($page['path']);
  echo '<pre><code>' . htmlspecialchars($code) . '</code></pre>';
}

function render_mdn($page) {
  $parser = new Parsedown();
  $content = \store\contents($page['path']);

  echo $parser->text($content);
}

function render_html($page) {
  echo \store\contents($page['path']);
}
