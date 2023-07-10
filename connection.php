<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
  $conn = new PDO("mysql:host=$servername;dbname=tbl_tpk;charset=utf8", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->exec('SET NAMES utf8');
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
    date_default_timezone_set('Asia/Bangkok');
?>