<?php
session_start();
require_once __DIR__ . '/config.php';

$conn = db_connect();

// Function to place the order
function place_order($conn) {
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login
    $order_date = date("Y-m-d H:i:s");
    $total_price = array_sum(array_column($_SESSION['cart'], 'price'));

    // Insert order into orders table
    $sql = "INSERT INTO orders (user_id, order_date, total_price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isd", $user_id, $order_date, $total_price);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert order details into order_details table
    foreach ($_SESSION['cart'] as $item) {
        $sql = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $order_id, $item['id'], $quantity = 1, $item['price']);
        $stmt->execute();
        $stmt->close();
    }

    // Clear the cart
    $_SESSION['cart'] = array();

    echo "<script type='text/javascript'>alert('Order placed successfully!');</script>";
}

// Place the order if POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    place_order($conn);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Tech World</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Order Confirmation</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="login.php">Login</a>
            <a href="order_history.php">Order History</a>
        </nav>
    </header>
    <main>
        <h2>Thank you for your purchase!</h2>
        <p>Your order has been placed successfully.</p>
    </main>
</body>
</html>
