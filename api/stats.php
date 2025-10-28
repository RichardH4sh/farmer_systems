<?php
include 'db.php';

$totalFarmers = $conn->query("SELECT COUNT(*) AS total FROM farmers")->fetch_assoc()['total'];
$totalProduce = $conn->query("SELECT COUNT(*) AS total FROM produce")->fetch_assoc()['total'];
$totalQuantity = $conn->query("SELECT IFNULL(SUM(quantity),0) AS total FROM produce")->fetch_assoc()['total'];
$totalValue = $conn->query("SELECT IFNULL(SUM(quantity * price_per_kg),0) AS total FROM produce")->fetch_assoc()['total'];

echo json_encode([
  "totalFarmers" => $totalFarmers,
  "totalProduce" => $totalProduce,
  "totalQuantity" => $totalQuantity,
  "totalValue" => number_format($totalValue, 2)
]);

$conn->close();
?>