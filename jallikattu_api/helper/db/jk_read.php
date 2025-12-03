<?php

$read_hostname = '10.236.250.21';
$read_database = 'jallikattu';
$read_username = 'postgres';
$read_password = 'postgres';
$read_port = 5432;

try {
	$jk_read_db = new PDO("pgsql:host=$read_hostname;port=$read_port;dbname=$read_database", $read_username, $read_password);
} catch (PDOException $e) {
	die("Coluldn't able to connect to Read Database $read_database because of " . $e->getMessage());
}