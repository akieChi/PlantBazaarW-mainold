<?php
include '../conn.php';

header('Content-Type: application/json'); // Set header for JSON response

// SQL query to fetch products with seller email
$sql = "
    SELECT p.*, u.email AS seller_email
    FROM product p
    INNER JOIN sellers s ON p.added_by = s.seller_id
    INNER JOIN users u ON s.user_id = u.id
    WHERE p.listing_status = 1
    ORDER BY p.createdAt DESC
    LIMIT 12
"; 

$result = $conn->query($sql);

$product = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product[] = $row;  // Fetch all columns from the row, including seller_email
    }
} else {
    // Return an error message if no products found
    echo json_encode(['error' => 'No products found or query failed']);
    exit();
}

// Send the data back as JSON
echo json_encode($product);
?>
