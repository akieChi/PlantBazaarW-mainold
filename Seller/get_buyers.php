<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "plantbazaardb"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the plantid from POST request
$plantId = $_POST['plantid'];

// Fetch users who have sent or received messages related to the plant
$sql = "SELECT  DISTINCT u.id, u.email, u.firstname, u.lastname, u.proflePicture
        FROM users u
        JOIN messages m ON (m.sender_id = u.id )
        WHERE m.plantidfk = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $plantId); // Bind the plant ID
$stmt->execute();
$result = $stmt->get_result();

// Fetch the buyers

$buyers = [];
while ($row = $result->fetch_assoc()) {
    $buyers[] = $row;
}

// Return the buyers as JSON
echo json_encode($buyers);

$conn->close();
?>
