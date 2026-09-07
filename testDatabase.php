<?php

require_once "Database.php";

$database = new Database();
$connection = $database->connect();

echo "Database connection successful.";

?>