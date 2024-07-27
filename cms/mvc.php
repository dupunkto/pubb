<?php
// Shitty infrastructure that is only loosely based 
// on the MVC-pattern.

function put_flash($level, $message) {
  $_SESSION['flash'] = [$level, $message];
}

function clear_flash() {
  unset($_SESSION['flash']);
}

function render_flash() {
  if($flash = @$_SESSION['flash']) {
    clear_flash();
    [$level, $message] = $flash;
    ?><p class="<?= $level ?>" onclick="this.remove()">
        <?= $message ?>
    </p><?php
  }
}

function fail($message) {
  put_flash("error", $message);
  complete();
}

function complete() {
  global $path;
  header("Location: " . CANONICAL.CMS.$path);
  exit;
}
