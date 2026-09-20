<?php

require_once __DIR__ . '/../config/bootstrap.php';

use App\Infrastructure\Database\Database;

$database = new Database();

$connection = $database->getConnection();

echo 'Database connection successful!';