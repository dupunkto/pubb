<?php
// Shitty infrastructure that is only loosely based 
// on the MVC-pattern.

$controllers = path_join(__DIR__, "controllers");
$views = path_join(__DIR__, "views");
$styles = path_join(__DIR__, "css");

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

function cast($value) {
  return match($value) {
    "true" => 1,
    "false" => 0,
    "" => null,
    default => $value
  };
}

function fail($message, $to = null) {
  die($message);
  if(!isset($to)) $to = full_path();

  put_flash("error", $message);
  redirect($to);
}

function complete($message, $to = null) {
  if(!isset($to)) $to = full_path();

  put_flash("success", $message);
  redirect($to);
}

function full_path() {
  global $path, $_SERVER;
  return "{$path}?{$_SERVER['QUERY_STRING']}";
}

function redirect($path) {
  header("Location: " . CMS_CANONICAL . $path);
  exit;
}
