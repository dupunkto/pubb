<?php
// Renders data-based HTML partials.

// The difference between this namespace and the `partials`
// directory, is that these functions are meant to be reused,
// whereas the partials are simply duplicated across pages.

// APIs in this namespace write directly to the output buffer.

namespace partials;

function listing($pages) {
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

function feed($pages) {
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

function page($page, $level = 2) {
  ?>
    <article class="h-entry">
      <?php if($page['title']) {
        echo "<h$level class='p-name'>{$page['title']}</h$level>";
      } else if($page['type'] == "code") {
        echo "<h$level class='p-name'><code>{$page['slug']}</code></h$level>";
      } ?>

      <div class="p-summary e-content">
        <?php \renderer\page_content($page) ?>
      </div>

      <time class="dt-published" datetime="<?= $page['published'] ?>">
        <a class="u-url" href="<?= \urls\page_url($page) ?>">
          <?= date("Y-m-d", strtotime($page['published'])) ?>
        </a>
      </time>

      <?php if(defined('AUTHOR_NAME') and defined('AUTHOR_PICTURE')) { ?>
        <div class="p-author h-card" hidden>
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

function comment_section($page) {
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