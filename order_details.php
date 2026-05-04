<?php
session_start();
require_once __DIR__ . '/config.php';

$conn = db_connect();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;
$order = null;
$order_items = [];

if ($order_id > 0) {
    $order_sql = 'SELECT id, order_date, total_price FROM orders WHERE id = ? AND user_id = ?';
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param('ii', $order_id, $user_id);
    $order_stmt->execute();
    $order = $order_stmt->get_result()->fetch_assoc();
    $order_stmt->close();

    if ($order) {
        $items_sql = 'SELECT od.quantity, od.price, p.name, p.image FROM order_details od LEFT JOIN products p ON p.id = od.product_id WHERE od.order_id = ?';
        $items_stmt = $conn->prepare($items_sql);
        $items_stmt->bind_param('i', $order_id);
        $items_stmt->execute();
        $order_items = $items_stmt->get_result();
        $items_stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Tech World</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Order Details</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="login.php">Login</a>
            <a href="order_history.php">Order History</a>
        </nav>
    </header>

    <main>
        <?php if (!$order): ?>
            <h2>Order not found</h2>
            <p>We could not find this order for your account.</p>
        <?php else: ?>
            <h2>Order #<?php echo htmlspecialchars((string) $order['id']); ?></h2>
            <p>Order Date: <?php echo htmlspecialchars((string) $order['order_date']); ?></p>
            <p>Total Price: $<?php echo htmlspecialchars((string) $order['total_price']); ?></p>

            <h3>Items</h3>
            <ul>
                <?php if ($order_items && $order_items->num_rows > 0): ?>
                    <?php while ($item = $order_items->fetch_assoc()): ?>
                        <li>
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?php echo htmlspecialchars((string) $item['image']); ?>" alt="<?php echo htmlspecialchars((string) $item['name']); ?>" style="width:80px;height:80px;object-fit:contain;">
                            <?php endif; ?>
                            <p>Name: <?php echo htmlspecialchars((string) $item['name']); ?></p>
                            <p>Quantity: <?php echo htmlspecialchars((string) $item['quantity']); ?></p>
                            <p>Price: $<?php echo htmlspecialchars((string) $item['price']); ?></p>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>No items found for this order.</li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
    </main>
</body>
</html>
