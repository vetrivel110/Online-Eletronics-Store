<?php
session_start();
require_once __DIR__ . '/config.php';
$conn = db_connect();

// Fetch products from the database with optional search or category filter
$query = "SELECT * FROM products";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $query = "SELECT * FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
} elseif (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = $conn->real_escape_string($_GET['category']);
    // Assuming there is a category column, or mapping category to search.
    // If there is no category column, we can simulate by searching name/description.
    $query = "SELECT * FROM products WHERE name LIKE '%$category%' OR description LIKE '%$category%'";
}
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Tech World</title>
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
                    <li><a href="products.php" class="active">Products</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
            <form class="search-bar" action="products.php" method="GET">
                <input type="text" name="search" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">Search</button>
            </form>
        </div>
    </header>
    <main>
        <h2>Our Products</h2>
        <ul class="products-list">
            <?php if($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="product-card">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p class="product-desc"><?php echo htmlspecialchars($row['description']); ?></p>
                    <p class="product-price">$<?php echo htmlspecialchars($row['price']); ?></p>
                    <form method="POST" action="cart.php" class="product-actions" style="margin:0; padding:0; border:none; background:transparent; box-shadow:none; max-width:100%;">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn-add-cart">Add to Cart</button>
                        <button type="submit" name="buy_now" value="1" class="btn-buy">Buy Now</button>
                    </form>
                </li>
            <?php endwhile; ?>
            <?php else: ?>
                <p style="color: var(--text-secondary); text-align: center; grid-column: 1 / -1;">No products found.</p>
            <?php endif; ?>
        </ul>
    </main>
    <footer>
        <p>&copy; 2024 Tech World. All rights reserved.</p>
    </footer>
</body>
</html>
