<?php
include 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST': // CREATE or UPDATE
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['farmer_id'])) {
            // Update
            $id = $data['farmer_id'];
            $name = $data['name'];
            $location = $data['location'];
            $phone = $data['phone'];
            $sql = "UPDATE farmers SET name='$name', location='$location', phone='$phone' WHERE farmer_id=$id";
            $conn->query($sql);
            echo json_encode(["message" => "Farmer updated successfully."]);
        } else {
            // Insert
            $name = $data['name'];
            $location = $data['location'];
            $phone = $data['phone'];
            $sql = "INSERT INTO farmers (name, location, phone) VALUES ('$name', '$location', '$phone')";
            $conn->query($sql);
            echo json_encode(["message" => "Farmer added successfully."]);
        }
        break;

    case 'GET': // READ
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $farmer = $conn->query("SELECT * FROM farmers WHERE farmer_id=$id")->fetch_assoc();
            $produce = $conn->query("SELECT * FROM produce WHERE farmer_id=$id")->fetch_all(MYSQLI_ASSOC);
            echo json_encode(["farmer" => $farmer, "produce" => $produce]);
        } else {
            $farmers = $conn->query("SELECT * FROM farmers")->fetch_all(MYSQLI_ASSOC);
            echo json_encode($farmers);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $id = $data['id'];
        $conn->query("DELETE FROM farmers WHERE farmer_id=$id");
        echo json_encode(["message" => "Farmer deleted successfully."]);
        break;
}
$conn->close();
?>