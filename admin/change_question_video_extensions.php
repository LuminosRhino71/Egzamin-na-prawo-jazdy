<?php
require_once '../dbconnect.php';
try {
    $connection->query('UPDATE pytania_egzaminacyjne SET Media = REPLACE(Media, ".wmv", ".mp4") WHERE Media LIKE "%.wmv"');
} catch (PDOException $error) {
    header("Location: ./");
    exit('Database error');
}
header("Location: ./");