<?php
// Statistics overview.

function goto_month($year, $month) {
  $query = http_build_query(["y" => $year, "m" => $month]);
  redirect("/stats?$query");
}

function month_query($year, $month, $diff) {
  $date = DateTime::createFromFormat('Y-m', "$year-$month");
  $date->modify($diff);

  return http_build_query([
    "y" => $date->format('Y'),
    "m" => $date->format('m')
  ]);
}

$span = isset($_GET['total']) ? "total" : "monthly";

if($span == "monthly") {
  if(!isset($_GET['y']) and !isset($_GET['m'])) goto_month(date("Y"), date("m"));
  else if(!isset($_GET['y'])) goto_month(date("Y"), $_GET['m']);
  else if(!isset($_GET['m'])) goto_month($_GET['y'], "01");

  $year = $_GET['y'];
  $month = $_GET['m'];

  $prev = CMS_CANONICAL . "/stats?" . month_query($year, $month, '-1 month');
  $next = CMS_CANONICAL . "/stats?" . month_query($year, $month, '+1 month');
}

$views = match($span) {
  "total" => \store\list_all_views(),
  "monthly" => \store\list_views($year, $month),
};

$paths = count_by($views, 'path');
$data = json_encode($paths);

$pages = array_reduce(array_keys($paths), function($acc, $path) use ($paths) {
  $slug = strip_prefix($path, "/");
  $page = \store\get_page_by_slug($slug);

  if($page) {
    $page['views'] = $paths[$path];
    $acc[$page['id']] = $page;
  }

  return $acc;
}, []);

include $view;
