<script src="<?= CMS_CANONICAL ?>/vendor/chart.min.js"></script>

<header class="bar">
  <h2>Statistics
    <small>— 
      <?= match($span) {
        "total" => "all",
        "monthly" => "{$month}/{$year}"
      } ?>
    </small>
  </h2>

  <div class="group">
    <?php if($span == "monthly") { ?>
      <a href="<?= $prev ?>" class="button">
        Prev
      </a>
      <a href="<?= $next ?>" class="button">
        Next
      </a>
      <a href="<?= CMS_CANONICAL . "/stats?total" ?>" class="button">
        View all
      </a>
    <?php } else { ?>
      <a href="<?= CMS_CANONICAL . "/stats" ?>" class="button">
        View monthly
      </a>
    <?php } ?>
  </div>
</header>

<section>
  <hgroup>
    <h3>Hits</h3>
    <p><?= array_sum($counts) ?></p>
  </hgroup>

  <hgroup>
    <h3>Pages</h3>
    <p><?= count(\store\list_all_pages()) ?></p>
  </hgroup>

  <hgroup>
    <h3>Comments</h3>
    <p><?= count(\store\list_all_mentions('incoming')) ?></p>
  </hgroup>
</section>

<?php if(count($views) == 0) { ?>
  <p class="placeholder-text">
    <?= match($span) {
    "monthly" => "No views this month",
    "total" => "No views yet"
    } ?>.
  </p>
<?php } else { ?>
  <canvas class="chart">
    <p>Your browser doesn't seem to support rendering to a canvas.</p>

    <noscript>
      Please enable JS for my fancy diagram to work.
    </noscript>

    <code>
      <?= $data ?>
    </code>
  </canvas>

  <script defer>
    String.prototype.toGrayScale = function() {
      let hash = 0;
      for (let i = 0; i < this.length; i++) {
        hash = this.charCodeAt(i) + ((hash << 5) - hash);
      }

      const grayness = (hash & 0xFF);
      return `rgb(${grayness}, ${grayness}, ${grayness})`;
    };
    
    const json = JSON.parse(`<?= $data ?>`);
    const ctx = document.querySelector('.chart').getContext('2d');

    let paths = [];
    let views = [];
    let colors = [];

    Object.keys(json).forEach(path => {
      paths.push(path.substring(1));
      views.push(json[path]);
      colors.push(path.toGrayScale());
    });

    const data = {
      labels: paths,
      datasets: [{
        data: views,
        backgroundColor: colors,
        hoverOffset: 4
      }]
    };

    new Chart(ctx, {
      type: 'pie',
      data: data,
      responsive: true,
      options: {
        plugins: {
          legend: {
            display: false,
            position: 'bottom'
          }
        }
      }
    });
  </script>

  <h3>Top pages</h3>

  <ul>
    <?php foreach(take($pages, 7) as $id => $page) { ?>
      <?php if(!\core\is_homepage($page)) { ?>
        <li><?= \core\get_page_title($page) ?> <span><?= $page['views'] ?></span></li>
      <?php } ?>
    <?php } ?>
  </ul>

  <h3>Top agents</h3>

  <ul>
    <?php foreach(take($agents, 5) as $agent => $amount) { ?>
      <li><?= $agent ?> <span><?= $amount ?></span></li>
    <?php } ?>
  </ul>

  <h3>Misses <small>(<?= array_sum($misses) ?>)</small></h3>

  <ul>
    <?php foreach($misses as $path => $amount) { ?>
      <li><?= $path ?> <span><?= $amount ?></span></li>
    <?php } ?>
  </ul>
<?php } ?>