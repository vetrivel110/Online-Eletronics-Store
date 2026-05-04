# Online Electronics Store

A responsive website using  PHP + MySQL ecommerce project for electronics products.

This app (branded as Tech World in the UI) includes product browsing, search, cart, checkout flow, user signup/login, and order history pages.

## Features

- Home page with featured categories
- Product listing from database
- Search and category filter
- Session-based shopping cart
- Buy Now and checkout flow
- User signup with password hashing
- User login with password verification
- Order placement and order history pages
- Environment-based database configuration via `.env`

## Tech Stack

- PHP (procedural style)
- MySQL
- HTML + CSS
- Sessions for authentication/cart state

## Project Structure

```text
osp/
|- index.php
|- products.php
|- cart.php
|- checkout.php
|- login.php
|- login_process.php
|- signup.php
|- signup_process.php
|- order.php
|- order_history.php
|- order_details.php
|- config.php
|- .env.example
|- style.css
|- auth.css
`- images/
```


## Database Setup

Run this SQL in phpMyAdmin or MySQL CLI:

```sql
CREATE DATABASE IF NOT EXISTS tech_world_db;
USE tech_world_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date DATETIME NOT NULL,
    total_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

Optional sample products:

```sql
INSERT INTO products (name, description, price, image) VALUES
('Wireless Earbuds', 'Bluetooth earbuds with charging case', 49.99, 'images/5.png'),
('Smart Watch', 'Fitness tracking smartwatch', 89.99, 'images/3.jpeg'),
('Over-Ear Headphones', 'Noise-isolating wired headphones', 59.99, 'images/q.jpg'),
('Portable Speaker', 'Compact wireless speaker', 39.99, 'images/rtt.jpeg');
```

## Local Run (XAMPP)

1. Place project in `C:\xampp\htdocs\osp`
2. Start Apache and MySQL in XAMPP Control Panel
3. Create DB + tables using the SQL above
4. Open:

```text
http://localhost/osp/index.php
```

## GitHub Push (Manual)

If your repo is already initialized:

```bash
git add .
git commit -m "Update README and project setup docs"
git push
```

If this is a new repo:

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/<your-username>/<your-repo>.git
git push -u origin main
```

## Security Notes

- `.env` is ignored by git (`.gitignore`) and should never be committed.
- Passwords are hashed on signup (`password_hash`) and verified on login (`password_verify`).
- Use strong DB credentials in production.

## Known Limitations

- Some flows assume `$_SESSION['user_id']` exists, but current login stores only `$_SESSION['username']`.
- `checkout.php` currently confirms purchase in UI but does not persist order data by itself.
- `signup_process.php` uses direct SQL string interpolation; prepared statements are recommended.

## License

This project is for learning and demo purposes.
