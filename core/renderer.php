<?php
// Transforms textual content into either HTML or plain text.
// APIs in this namespace write directly to the output buffer.

namespace renderer;

use Parsedown;

// Public APIs

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

function render_mdn($page) {
  $contents = \store\contents($page['path']);
  $fancy = prerender($contents);

  $parser = new Parsedown();
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
