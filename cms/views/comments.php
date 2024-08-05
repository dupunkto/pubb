<header class="bar">
  <h2>Comments</h2>
</header>

<?php 
  $mentions = \store\list_all_mentions('incoming');
  $pages = group_by($mentions, 'page');
?>

<h3>Incoming</h3>

<ul class="pages-list">
  <?php foreach($pages as $page) {
      ?>
      <h4>
        Comments on
        <a href="<?= \urls\page_url($page) ?>">
          <cite>
            <?= $page['title'] ?? $page['published'] ?>
          </cite>
        </a>
      </h4>

      <?php foreach($page['items'] as $mention) { 
        $url = htmlspecialchars($mention['source'])
      ?>
        <li>
          <a href="<?= $url ?>"><?= parse_host($url) ?></a>

          <?php if($mention['contact_id']) { ?>
            <a href="<?= $mention['contact_domain'] ?>">
              @<?= $mention['contact_handle'] ?>
            </a>
          <?php } ?>

          <span class="actions">
            <a href="<?= CMS_CANONICAL ?>/new?reply=<?= urlencode($url) ?>">
              Reply
            </a>
          </span>
        </li>
      <?php  }
  } ?>
</ul>

<?php if(count($mentions) <= 0) {
  ?>
    <p class="placeholder-text">No comments yet.</p>
  <?php
} ?>

<h3>Outgoing</h3>

<p class="placeholder-text">Not implemented yet.</p>
