<?php
// Output buffering.

function capture($function, ...$args) {
  ob_start();
  call_user_func_array($function, $args);
  return ob_get_clean();
}
