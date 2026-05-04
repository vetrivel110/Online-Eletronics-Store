<?php
session_start();
require_once __DIR__ . '/config.php';

$conn = db_connect();

// Fetch orders for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Tech World</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Your Order History</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="login.php">Login</a>
            <a href="order_history.php">Order History</a>
        </nav>
    </header>
    <main>
        <h2>Order History</h2>
        <ul>
            <?php while ($order = $orders->fetch_assoc()): ?>
                <li>
                    <h3>Order ID: <?php echo htmlspecialchars($order['id']); ?></h3>
                    <p>Order Date: <?php echo htmlspecialchars($order['order_date']); ?></p>
                    <p>Total Price: $<?php echo htmlspecialchars($order['total_price']); ?></p>
                    <a href="order_details.php?order_id=<?php echo htmlspecialchars($order['id']); ?>">View Details</a>
                </li>
            <?php endwhile; ?>
        </ul>
    </main>
</body>
</html>
