<?php
include '../conn.php';
session_start();

$email = $_SESSION['email'];

// Fetch seller ID based on email
$query = "SELECT id, email FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo json_encode(['error' => 'User not found']);
    exit;
}

$userId = $user['id'];
$sellerEmail = $user['email'];

// Get page number and number of products per page from the request
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$productsPerPage = 4; // Number of products to display per page
$offset = ($page - 1) * $productsPerPage;

// Get listing_status (1 for available, 2 for sold)
$listingStatus = isset($_GET['listing_status']) ? (int)$_GET['listing_status'] : 1; 

// Fetch products listed by this seller for the current page
$productQuery = "
    SELECT *, 
           (SELECT email FROM sellers WHERE user_id = users.id) AS seller_email 
    FROM product 
    JOIN sellers ON product.added_by = sellers.seller_id 
    JOIN users ON sellers.user_id = users.id 
    WHERE users.email = '$email' 
    AND product.listing_status = $listingStatus 
   ";

$productResult = mysqli_query($conn, $productQuery);

// Check for errors in the product query
if (!$productResult) {
    die("Query failed: " . mysqli_error($conn));
}

$products = [];
while ($product = mysqli_fetch_assoc($productResult)) {
    $products[] = $product;
}

// Get the total number of products (without pagination)
$totalQuery = "
    SELECT COUNT(*) AS total 
    FROM product 
    JOIN sellers ON product.added_by = sellers.seller_id 
    JOIN users ON sellers.user_id = users.id 
    WHERE users.email = '$email' 
    AND product.listing_status = $listingStatus";
$totalResult = mysqli_query($conn, $totalQuery);
$total = mysqli_fetch_assoc($totalResult)['total'];

// Calculate total profit (sum of all product prices for the seller)
$profitQuery = "
    SELECT SUM(price) AS total_profit
    FROM product 
    JOIN sellers ON product.added_by = sellers.seller_id 
    JOIN users ON sellers.user_id = users.id 
    WHERE users.email = '$email' 
    AND product.listing_status = $listingStatus";
$profitResult = mysqli_query($conn, $profitQuery);
$profit = mysqli_fetch_assoc($profitResult)['total_profit'];

// If no profit found, set it to 0
$profit = $profit ? $profit : 0;

echo json_encode([
    'products' => $products,
    'total' => $total,
    'total_profit' => $profit // Add the total profit to the response
]);
?>
