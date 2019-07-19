<?php
date_default_timezone_set("Asia/Jakarta");

require_once 'database.php';

$mysqli = new mysqli($con['host'], $con['user'], $con['pass'], $con['db']);

if ($mysqli->connect_error) {
    die('Koneksi database gagal: ' . $mysqli->connect_error);
}
