<?php
include '../conn.php';
session_start();

if (isset($_POST['plantid'])) {
    $plantId = $_POST['plantid'];
    $fullname = $_POST['userFullname'];
    // Update the product's status to 2 (Sold)
    $sql = "UPDATE product SET listing_status = 2, sold_to = ? WHERE plantid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si',$fullname, $plantId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
}
$conn->close();
?>
