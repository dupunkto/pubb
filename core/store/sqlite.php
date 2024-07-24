<?php
// Store adapter for SQLite databases.

function establish_connection() {
  $database = STORE . "/data.sqlite";
  $dsn = "mysql:$database;charset=utf8mb4";

  $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  try {
    return new PDO($dsn, options: $options);
  }
  catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
  }
}
