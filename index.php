<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech World - Home</title>
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
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
            <form class="search-bar" action="products.php" method="GET">
                <input type="text" name="search" placeholder="Search products...">
                <button type="submit">Search</button>
            </form>
        </div>
    </header>
    <main>
        <section class="hero">
            <h2>Discover the Latest Electronics</h2>
            <p>Find the best deals on the latest gadgets and accessories.</p>
            <img src="images/lo.jpeg" alt="Latest Electronics">
            <div>
                <a href="products.php" class="btn">Shop Now</a>
            </div>
        </section>
        <section class="categories">
            <h2>Explore Products</h2>
            <div class="category-grid">
                <div class="category"><img src="images/5.png" alt="True wireless"><a href="products.php?category=true_wireless">True wireless</a></div>
                <div class="category"><img src="images/3.jpeg" alt="Smart Watches"><a href="products.php?category=smartwatches">Smart Watches</a></div>
                <div class="category"><img src="images/q.jpg" alt="Headphones"><a href="products.php?category=headphones">Headphones</a></div>
                <div class="category"><img src="images/rtt.jpeg" alt="Speakers"><a href="products.php?category=speakers">Speakers</a></div>
            </div>  
        </section>
    </main>
    
    <footer>
        <p>&copy; 2024 Tech World. All rights reserved.</p>
        <div style="margin-top: 1rem; color: var(--text-secondary); font-size: 0.9rem;">
            <p>123 Tech Street, Tech City, TX 12345</p>
            <p>Email: support@techworld.com | Phone: (123) 456-7890</p>
        </div>
    </footer>
</body>
</html>
