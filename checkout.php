<?php
session_start();

// Function to display the cart items in the checkout
function display_checkout_cart() {
    if (!empty($_SESSION['cart'])) {
        echo "<div style='display: grid; gap: 1rem; margin-bottom: 2rem;'>";
        foreach ($_SESSION['cart'] as $item) {
            echo "<div style='display: flex; align-items: center; gap: 1rem; background: var(--bg-card); padding: 1rem; border-radius: 12px; border: 1px solid var(--border);'>";
            echo "<img src='" . htmlspecialchars($item['image']) . "' alt='" . htmlspecialchars($item['name']) . "' style='width: 80px; height: 80px; object-fit: contain; background: #fff; padding: 0.5rem; border-radius: 8px;'>";
            echo "<div style='flex: 1;'>";
            echo "<h3 style='margin: 0 0 0.5rem 0; font-size: 1.1rem;'>" . htmlspecialchars($item['name']) . "</h3>";
            echo "<p style='margin: 0; color: var(--text-secondary); font-size: 0.9rem;'>" . htmlspecialchars($item['description']) . "</p>";
            echo "</div>";
            echo "<p style='font-weight: bold; color: var(--accent); margin: 0;'>$" . htmlspecialchars($item['price']) . "</p>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }
}

// Initialize variables for purchase status and delivery date
$order_successful = false;
$delivery_date = '';

// Process the checkout form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_SESSION['cart'])) {
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $payment_method = $_POST['payment_method'];
    $upi_id = $_POST['upi_id'] ?? null;
    $total = array_sum(array_column($_SESSION['cart'], 'price'));
    $delivery_date = date('Y-m-d', strtotime('+5 days')); // Delivery date is 5 days from now

    // Save the order to the database (adjust to fit your database schema)
    // Clear the cart after checkout
    $_SESSION['cart'] = array();

    // Set order successful status to true
    $order_successful = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Tech World</title>
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
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="checkout-container">
        <?php if ($order_successful): ?>
            <div style="text-align: center; padding: 4rem 2rem; background: var(--bg-card); border-radius: 16px; border: 1px solid var(--border); max-width: 600px; margin: 0 auto;">
                <h2 style="color: #10b981; font-size: 2.5rem; margin-bottom: 1rem;">Order Successful!</h2>
                <p style="font-size: 1.1rem; color: var(--text-secondary); margin-bottom: 2rem;">Thank you for your purchase. Your order will be delivered by <strong style="color: var(--text-primary);"><?php echo htmlspecialchars($delivery_date); ?></strong>.</p>
                <a href="index.php" class="btn">Return to Home</a>
            </div>
        <?php else: ?>
            <h2 style="text-align: center; margin-bottom: 2rem;">Review Your Order</h2>
            <div class="cart-items" style="max-width: 800px; margin: 0 auto;">
                <?php display_checkout_cart(); ?>
                
                <?php if (!empty($_SESSION['cart'])): ?>
                <div style="text-align: right; margin-bottom: 2rem; padding-right: 1rem;">
                    <h3 style="font-size: 1.5rem; color: var(--text-primary);">Total: <span style="color: var(--accent);">$<?php echo array_sum(array_column($_SESSION['cart'], 'price')); ?></span></h3>
                </div>
                
                <form method="POST" action="checkout.php" class="checkout-form">
                    <h3 style="margin-top: 0; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Shipping Information</h3>
                    <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" name="fullname" placeholder="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Delivery Address</label>
                        <input type="text" id="address" name="address" placeholder="123 Main St, City, Country" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="john@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="Phone">Phone Number</label>
                            <input type="tel" id="Phone" name="Phone" placeholder="+1 234 567 8900" required>
                        </div>
                    </div>
                    
                    <h3 style="margin-top: 1.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Payment Method</h3>
                    <div class="form-group">
                        <label for="payment_method">Select Payment Option</label>
                        <select id="payment_method" name="payment_method" required onchange="toggleUPIFields()">
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="upi">UPI</option>
                        </select>
                    </div>
                    <div class="form-group" id="upi_fields" style="display: none;">
                        <label for="upi_id">Enter UPI ID</label>
                        <input type="text" id="upi_id" name="upi_id" placeholder="username@upi">
                    </div>
                    <button type="submit" class="btn" style="width: 100%; margin-top: 1rem; padding: 1rem; font-size: 1.1rem;">Confirm Purchase</button>
                </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>
    
    <script>
        function toggleUPIFields() {
            var paymentMethod = document.getElementById('payment_method').value;
            var upiFields = document.getElementById('upi_fields');
            if (paymentMethod === 'upi') {
                upiFields.style.display = 'block';
            } else {
                upiFields.style.display = 'none';
            }
        }
    </script>
    
    <footer>
        <p>&copy; 2024 Tech World. All rights reserved.</p>
    </footer>
</body>
</html>
