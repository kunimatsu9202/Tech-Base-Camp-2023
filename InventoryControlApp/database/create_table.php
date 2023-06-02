<?php
  require_once "./db_connection.php";
  
  $stmt = $dbh->prepare("CREATE DATABASE inventory_system_team2");
  $stmt->execute();

  $stmt = $dbh->prepare("CREATE TABLE products(id INT PRIMARY_KEY AUTO_INCREMENT, name VARCHAR(100) NOT NULL, stock INT NOT NULL DEFAULT 0, price INT NOT NULL DEFAULT 0");
  $stmt->execute();
