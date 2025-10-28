<?php
include 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST': // CREATE or UPDATE
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['produce_id'])) {
            $id = $data['produce_id'];
            $name = $data['produce_name'];
            $qty = $data['quantity'];
            $price = $data['price_per_kg'];
            $sql = "UPDATE produce SET produce_name='$name', quantity=$qty, price_per_kg=$price WHERE produce_id=$id";
            $conn->query($sql);
            echo json_encode(["message" => "Produce updated successfully."]);
        } else {
            $farmer_id = $data['farmer_id'];
            $name = $data['produce_name'];
            $qty = $data['quantity'];
            $price = $data['price_per_kg'];
            $sql = "INSERT INTO produce (farmer_id, produce_name, quantity, price_per_kg)
                    VALUES ($farmer_id, '$name', $qty, $price)";
            $conn->query($sql);
            echo json_encode(["message" => "Produce added successfully."]);
        }
        break;

    case 'GET': // READ
        if (isset($_GET['name'])) {
            $name = $_GET['name'];
            $sql = "SELECT p.*, f.name AS farmer_name 
                    FROM produce p JOIN farmers f ON p.farmer_id = f.farmer_id 
                    WHERE p.produce_name LIKE '%$name%'";
            $result = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
            echo json_encode($result);
        } else {
            $result = $conn->query("SELECT * FROM produce")->fetch_all(MYSQLI_ASSOC);
            echo json_encode($result);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $id = $data['id'];
        $conn->query("DELETE FROM produce WHERE produce_id=$id");
        echo json_encode(["message" => "Produce deleted successfully."]);
        break;
}
$conn->close();
?>