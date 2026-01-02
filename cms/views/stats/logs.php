<header class="bar">
  <h2>View logs</h2>

  <div class="group">
    <a href="<?= CMS_CANONICAL ?>/stats" class="button">
      Back to stats
    </a>
  </div>
</header>

<form method="get" action="<?= CMS_CANONICAL ?>/stats/logs">
  <label for="path">Page</label>
  <select name="path" id="path" onchange="this.form.submit()">
    <option value="">Select a page...</option>
    <?php foreach($paths as $path => $label) { ?>
      <option value="<?= $path ?>" <?= $query == $path ? 'selected' : '' ?>>
        <?= $label ?>
      </option>
    <?php } ?>
  </select>
</form>

<?php if($query && count($views) > 0) { ?>
  <table class="views-table">
    <thead>
      <tr>
        <th>Client IP</th>
        <th>Timestamp</th>
        <th>Path</th>
        <th>User Agent</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($views as $view) { ?>
        <tr>
          <td><a href="https://iplookup.flagfox.net/?ip=<?= htmlspecialchars($view['client_ip']) ?>"><?= htmlspecialchars($view['client_ip']) ?></a></td>
          <td><?= htmlspecialchars($view['datetime']) ?></td>
          <td><a href="<?= CANONICAL ?><?= htmlspecialchars($view['path']) ?>"><?= htmlspecialchars($view['path']) ?></a></td>
          <td><?= htmlspecialchars($view['agent']) ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
<?php } else if($query) { ?>
  <p class="placeholder-text">No views for this page yet.</p>
<?php } ?>
