<?php
// Store adapter for SQLite databases.

function establish_connection() {
  $database = STORE . "/data.db";
  $dsn = "sqlite:$database";

  $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  try {
    if(!file_exists($database)) {
      define('INITIAL_RUN', true);
      define('MIGRATIONS_STALE', true);
    }

    return new PDO($dsn, options: $options);
  }
  catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
  }
}

function execute($path) {
  $sql = str_replace(
    ["int(11)", "NOT NULL AUTO_INCREMENT", ",\n  PRIMARY KEY (`id`)"],
    ["INTEGER", "PRIMARY KEY AUTOINCREMENT", ""],
    file_get_contents($path)
  );

  $queries = explode(';', $sql);
  
  foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) DBH->exec($query);
  }
}