<?php
// Adapter for MySQL databases.

namespace adapter;
use PDO;

function establish_connection() {
  global $_DATABASE;

  $host = $_DATABASE['host'];
  $user = $_DATABASE['user'];
  $pass = $_DATABASE['pass'];
  $port = $_DATABASE['port'] ?? 5432;
  $name = ltrim($_DATABASE['path'], "/");

  $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  $dsn = "pgsql:host=$host;port=$port;dbname=$name;charset=utf8mb4";

  try {
    return new PDO($dsn, $user, $pass, $options);
  }
  catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
  }
}

function initial_run() {
  return !table_exists('migrations');
}

function execute($path) {
  $sql = file_get_contents($path);
  $queries = explode(';', $sql);

  foreach ($queries as $query) {
    $query = trim($query);
    if(!empty($query)) DBH->exec($query) !== false 
      or die("Couldn't execute query '$query'.");
  }
}

function table_exists($table_name) {
  $stmt = DBH->prepare("SELECT table_name
    FROM information_schema.tables
    WHERE table_catalog = current_database()
      AND table_schema = 'public'
      AND table_name = ?");

  $stmt->execute([$table_name]);
  return $stmt->fetch() != false;
}
