<?php
$servername = "localhost"; // Cambia esto si tu servidor MySQL no está en localhost
$username = "tu_usuario_de_phpmyadmin";
$password = "tu_contraseña_de_phpmyadmin";
$database = "tu_base_de_datos";

$conn = mysql_connect("localhost", "root", ,"task_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $sql = "SELECT * FROM tasks";
    $result = $conn->query($sql);
    $tasks = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
    }

    echo json_encode($tasks);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $description = $data["description"];

    if (!empty($description)) {
        $sql = "INSERT INTO tasks (description) VALUES ('$description')";

        if ($conn->query($sql) === TRUE) {
            http_response_code(200);
        } else {
            http_response_code(500);
        }
    } else {
        http_response_code(400);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    $taskId = $_GET["id"];
    $sql = "DELETE FROM tasks WHERE id=$taskId";

    if ($conn->query($sql) === TRUE) {
        http_response_code(200);
    } else {
        http_response_code(500);
    }
}

$conn->close();
?>
