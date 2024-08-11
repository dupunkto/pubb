<?php
// Renders pages to HTML.

namespace html;

function page_title($page) {
  if($page['title']) return $page['title'];
  else return $page['slug'];
}

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
  $volumes = group_by($pages, 'volume');

  foreach ($volumes as $volume) {
    ?>
      <section class="volume">
        <?php if($volume['id']) { ?>
          <h2 id="<?= $volume['slug'] ?>">
            <?= $volume['title'] ?>
          </h2>
        <?php } ?>

        <?php foreach ($volume['items'] as $page) {
          render_page($page, level: 3);
        } ?>
      </section>
    <?php
  }
}

function render_page($page, $level = 2) {
  ?>
    <article class="h-entry">
      <?php if($page['title']) {
        echo "<h$level class='p-name'>{$page['title']}</h$level>";
      } else if($page['type'] == "code") {
        echo "<h$level class='p-name'><code>{$page['slug']}</code></h$level>";
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
        <?php foreach(\store\list_mentions("incoming", $page['id']) as $mention) { ?>
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
    "md" => render_mdn($page),
    "html" => render_html($page),
    "txt" => render_text($page),
  };
}

function render_photo($page) {
  $path = $page['path'];
  $url = is_url($path) ? $path : \urls\photo_url($page);
  $caption = render_caption($page['caption']);

  ?>
    <figure>
      <img 
        class="u-photo" 
        src="<?= $url ?>" 
        alt="<?= esc_attr($caption) ?>"
      />
      <figcaption><?= $caption ?></figcaption>
    </figure> 
  <?php
}

function render_code($page) {
  $code = \store\contents($page['path']);
  $caption = render_caption($page['caption']);

  echo '<pre class="code"><code>' . htmlspecialchars($code) . '</code></pre>';
  echo '<p class="caption">'. $caption . '</p>';
}

function render_caption($caption) {
  $parser = new Parsedown();
  $caption = rendering_pipeline($caption);
  return $parser->line($caption);
}

function render_mdn($page) {
  $contents = \store\contents($page['path']);
  $fancy = rendering_pipeline($contents);

  $parser = new Parsedown();
  echo $parser->text($fancy);
}

function render_html($page) {
  $contents = \store\contents($page['path']);
  echo rendering_pipeline($contents);
}

function render_text($page) {
  $contents = \store\contents($page['path']);
  echo '<pre>' . htmlspecialchars(render_shortcodes($contents)) . '</pre>';
}

function render_plain($page) {
  $contents = \store\contents($page['path']);
  return prerender_text($contents);
}

function rendering_pipeline($prose) {
  $prose = prerender_html($prose);
  $prose = prerender_text($prose);

  return $prose;
}

function prerender_html($prose) {
  return render_tagged_contacts($prose);
}

function prerender_text($prose) {
  return render_shortcodes($prose);
}

function render_tagged_contacts($prose) {
  $pattern = '/@([a-zA-Z0-9]+)/';
  $replacement = '<a href="$1" class="mention">$1</a>';

  $callback = function($matches) {
    $contact = \store\get_contact_by_handle($matches[1]);

    if($contact) {
      return "<a href='//{$contact['domain']}' class='mention'>@{$contact['handle']}</a>";
    } else {
      return "@" . $matches[1];
    }
  };

  return preg_replace_callback($pattern, $callback, $prose);
}

function render_shortcodes($prose) { 
  $shorts = [
    '/--/' => "—",
    '/\.\.\./' => "…",
    '/\(TM\)/' => '™',
    '/\(c\)/' => '©',
    '/:back:/' => '←',
    '/:go:/' => '→',
    '/:x:/' => '×',
    '/:love:/' => '♡',
    '/:hot:/' => '🔥',
    '/:sparkles:/' => '✨',
    '/:rocket:/' => '🚀',
    '/:email:/' => '✉️',
    '/:video:/' => '📺',
    '/:audio:/' => '🎙️',
    '/:shrug:/' => '¯\\\\_(ツ)\\\\_/¯',
    '/:dancing:/' => 'ᕕ( ᐛ )ᕗ',
    '/:fight:/' => '(ง\'̀-\'́)ง',
    '/:flex:/' => 'ᕦ(•̀‿•́ )ᕤ',
    '/:happy:/' => '(✿◠‿◠)',
    '/:cute:/' => '٩(｡•́‿•̀｡)۶',
  ];

  return preg_replace(array_keys($shorts), array_values($shorts), $prose);
}
