<?php
// Store adapter for MySQL databases.

required("db.host");
required("db.user");
required("db.pass");
required("db.name");

function establish_connection() {
  $host = DB_HOST;
  $user = DB_USER;
  $pass = DB_PASS;
  $name = DB_NAME;

  $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4";

  try {
    return new PDO($dsn, $user, $pass, $options);
  }
  catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
  }
}

function migrate() {
  $migrations = __DIR__ . "/migrations.sql";

  $sql = file_get_contents($migrations);
  $queries = explode(';', $sql);

  foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) DBH->exec($query);
  }
}
