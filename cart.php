<?php
session_start();
require_once __DIR__ . '/config.php';

$conn = db_connect();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add items to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $_SESSION['cart'][] = $product;
    }

    $stmt->close();
    
    // Redirect to checkout if Buy Now was clicked
    if (isset($_POST['buy_now']) && $_POST['buy_now'] == '1') {
        header("Location: checkout.php");
        exit();
    }
}

// Function to display the cart items
function display_cart() {
    if (!empty($_SESSION['cart'])) {
        echo "<div class='products-list'>";
        foreach ($_SESSION['cart'] as $index => $item) {
            echo "<div class='product-card'>";
            echo "<img src='" . htmlspecialchars($item['image']) . "' alt='" . htmlspecialchars($item['name']) . "'>";
            echo "<h3>" . htmlspecialchars($item['name']) . "</h3>";
            echo "<p class='product-desc'>" . htmlspecialchars($item['description']) . "</p>";
            echo "<p class='product-price'>$" . htmlspecialchars($item['price']) . "</p>";
            echo "<form method='POST' action='cart.php' class='product-actions' style='margin:0; padding:0; border:none; background:transparent; box-shadow:none; max-width:100%;'>";
            echo "<input type='hidden' name='remove' value='$index'>";
            echo "<button type='submit' class='btn-add-cart' style='color:#ef4444;'>Remove</button>"; 
            echo "<button type='button' class='btn-buy' onclick=\"window.location.href='checkout.php'\">Proceed to Buy</button>";
            echo "</form>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p style='text-align:center; padding: 3rem; color: var(--text-secondary);'>Your cart is empty.</p>";
    }
}

// Remove item from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove'])) {
    $remove_index = $_POST['remove'];
    unset($_SESSION['cart'][$remove_index]);
    // Reindex the cart array to prevent gaps
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Tech World</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <h1>Tech World</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="cart.php" class="active">Cart</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <h2 style="text-align:center; margin-bottom: 2rem;">Items in Your Cart</h2>
        <?php display_cart(); ?>
    </main>
    <footer>
        <p>&copy; 2024 Tech World. All rights reserved.</p>
    </footer>
</body>
</html>
