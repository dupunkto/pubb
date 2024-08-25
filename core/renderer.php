<?php
// Transforms textual content into either HTML or plain text.
// APIs in this namespace write directly to the output buffer.

namespace renderer;

use Parsedown;

// Public APIs

function page_classes() {
  global $pages, $type, $page, $not_found;

  $classes[] = isset($pages) ? $type : "single";
  if($not_found) $classes[] = "404";

  if(isset($page)) {
    $classes[] = $page['type'];
    if(\core\is_homepage($page)) {
      $classes[] = "home";
    }
  }

  return implode(" ", $classes);
}

function info($message) {
  render_message("info", $message);
}

function warn($message) {
  render_message("warn", $message);
}

function error($message) {
  render_message("error", $message);
}

function success($message) {
  render_message("success", $message);
}

function index($pages, $type) {
  match($type) {
    "listing" => render_listing($pages),
    "feed" => render_feed($pages),
  };
}

function page($page, $level = 1) {
  ?>
    <article 
      class="h-entry" 
      <?php if($page['lang']) echo 'lang="' . esc_attr($page['lang']) . '"' ?>
    >
      <?php if($page['title']) {
        echo "<h$level class='p-name'>{$page['title']}</h$level>";
      } else if($page['type'] == "code") {
        echo "<h$level class='p-name'><code>{$page['slug']}</code></h$level>";
      } ?>

      <div class="p-summary e-content">
        <?= shift_headings(capture('\renderer\page_content', $page), $level); ?>
      </div>

      <time class="dt-published" datetime="<?= $page['published'] ?>">
        <a class="u-url" href="<?= \urls\page_url($page) ?>">
          <?= date("Y-m-d", strtotime($page['published'])) ?>
        </a>
      </time>

        <div class="p-author h-card" hidden>
          <a class="u-url" href="<?= CANONICAL ?>">
            <?php if(defined('PROFILE_PICTURE')) { ?>
              <img
                class="u-photo"
                src="<?= PROFILE_PICTURE ?>"
                alt="Profile picture"
                width="100"
              >
            <?php } ?>
            <p>
              <?php if(defined('AUTHOR_NAME')) { ?>
                <span class="p-name"><?= AUTHOR_NAME ?></span>
              <?php } if(defined('PROFILE_HANDLE')) { ?>
                <span class="p-nickname"><?= PROFILE_HANDLE ?></span>
              <?php } ?>
            </p>
          </a>
        </div>
    </article>
  <?php
}

function comment_section($page) {
  ?>
    <aside class="comment-section">
      <h2>Webmentions</h2>

      <ul>
        <?php foreach(\store\list_mentions("incoming", $page['id']) as $mention) { ?>
          <?php $url = $mention['source'] ?>
          <li><a href="<?= $url ?>"><?= parse_url($url, PHP_URL_HOST) ?></a></li>
        <?php } ?>
      </ul>

      <form action="<?= WEBMENTION_ENDPOINT ?>" method="post">
        <p>
          <p>This page accepts <a href="//webmention.net">webmentions</a>. Have you written a reply? Let me know the URL:
        </p>

        <input required name="target" type="hidden" value="<?= \urls\page_url($page) ?>">
        <input required name="source" type="url">

        <input type="submit" value="Send webmention">
      </form>
    </aside>
  <?php
}

function page_content($page) {
  match($page['type']) {
    "photo" => render_photo($page),
    "code" => render_code($page),
    "md" => render_mdn($page),
    "html" => render_html($page),
    "txt" => render_textual($page),
  };
}

function plain_text($page) {
  $contents = \store\contents($page['path']);
  return prerender_text($contents);
}

// Internal APIs

function render_listing($pages) {
  foreach (group_by($pages, 'volume') as $volume) {
    ?>
      <section class="volume">
        <?php if($volume['id']) { ?>
          <h2 id="<?= $volume['slug'] ?>">
            <?= $volume['title'] ?>
          </h2>
        <?php } ?>

        <table>
          <thead>
            <tr>
              <th class="p-name">Post<span hidden>s</span></th>
              <th>Published</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($volume['items'] as $page) { ?>
              <tr class="h-entry">
                <td class="p-title">
                  <a class="u-url" href="<?= \urls\page_url($page) ?>">
                    <?= \core\get_page_title($page) ?>
                  </a>
                </td>
                <td>
                  <time 
                    class="dt-published" 
                    datetime="<?= date("Y-m-d", strtotime($page['published'])) ?>"
                  >
                    <?= date("M j, Y", strtotime($page['published'])) ?>
                  </time>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </section>
    <?php
  }
}

function render_feed($pages) {
  foreach (group_by($pages, 'volume') as $volume) {
    ?>
      <section class="volume">
        <?php if($volume['id']) { ?>
          <h2 id="<?= $volume['slug'] ?>">
            <?= $volume['title'] ?>
          </h2>
        <?php } ?>

        <?php foreach ($volume['items'] as $page) {
          page($page, level: 3);
        } ?>
      </section>
    <?php
  }
}

function render_photo($page) {
  $path = $page['path'];
  $url = is_url($path) ? $path : \urls\photo_url($page);

  ?>
    <figure>
      <img 
        class="u-photo" 
        src="<?= $url ?>" 
        alt="<?= esc_attr($caption) ?>"
      />
      <?php if($page['caption']) { ?>
        <figcaption><?= render_caption($page['caption']) ?></figcaption>
      <?php } ?>
    </figure> 
  <?php
}

function render_code($page) {
  $code = \store\contents($page['path']);
  echo '<pre class="code"><code>' . htmlspecialchars($code) . '</code></pre>';

  if($page['caption']) {
    $caption = render_caption($page['caption']);
    echo '<p class="caption">'. $caption . '</p>';
  }
}

function render_mdn($page) {
  $contents = \store\contents($page['path']);
  $fancy = prerender($contents);

  $parser = new Parsedown();
  $parser->setSafeMode(false);
  $parser->setMarkupEscaped(false);

  echo $parser->text($fancy);
}

function render_html($page) {
  $contents = \store\contents($page['path']);
  echo prerender($contents);
}

function render_textual($page) {
  $contents = \store\contents($page['path']);
  echo '<pre>' . htmlspecialchars(render_shortcodes($contents)) . '</pre>';
}

function render_caption($caption) {
  $parser = new Parsedown();
  $caption = prerender($caption);
  return $parser->line($caption);
}

function render_message($kind, $message) {
  ?>
    <p class="<?= $kind ?>"><?= $message ?></p>
  <?php
}

function prerender($prose) {
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

function shift_headings($html, $level) {
  $base_level = 1;
  $diff = $level - $base_level;
  $pattern = '/<h([2-6])([^>]*)>(.*?)<\/h\1>/i';

  return preg_replace_callback($pattern, function ($matches) use ($diff) {
    $level = clamp(2, intval($matches[1]) + $diff, 6);
    return "<h{$level}{$matches[2]}>{$matches[3]}</h{$level}>";
  }, $html);
}

function clamp($min, $value, $max) {
  switch(true) {
    case $value < $min: return $min;
    case $value > $max: return $max;
    default: return $value;
  };
}