-- =============================================================
-- SEED DATA — wtech-bykov-bohuslavskyi  (Supabase / PostgreSQL)
-- Run in: Supabase Dashboard → SQL Editor
-- All user passwords: "password"
-- =============================================================

-- ─── Clean slate (wipes all existing data) ────────────────────
TRUNCATE TABLE
  payments,
  order_items,
  orders,
  cart_items,
  products_on_sales,
  item_color_size_counts,
  product_images,
  products,
  sales,
  categories,
  colors,
  users
RESTART IDENTITY CASCADE;

-- ─── Categories ───────────────────────────────────────────────
INSERT INTO categories (id, name, slug, created_at, updated_at) VALUES
  (1, 'T-Shirts',  't-shirts',  NOW(), NOW()),
  (2, 'Jeans',     'jeans',     NOW(), NOW()),
  (3, 'Hoodies',   'hoodies',   NOW(), NOW()),
  (4, 'Shirts',    'shirts',    NOW(), NOW()),
  (5, 'Sneakers',  'sneakers',  NOW(), NOW());

SELECT setval(pg_get_serial_sequence('categories','id'), MAX(id)) FROM categories;


-- ─── Colors ───────────────────────────────────────────────────
INSERT INTO colors (id, name, hex_code, created_at, updated_at) VALUES
  (1, 'Black', '#000000', NOW(), NOW()),
  (2, 'White', '#FFFFFF', NOW(), NOW()),
  (3, 'Navy',  '#1B2A4A', NOW(), NOW()),
  (4, 'Red',   '#DC2626', NOW(), NOW()),
  (5, 'Olive', '#4D5D2E', NOW(), NOW()),
  (6, 'Grey',  '#6B7280', NOW(), NOW());

SELECT setval(pg_get_serial_sequence('colors','id'), MAX(id)) FROM colors;


-- ─── Products ─────────────────────────────────────────────────
INSERT INTO products (id, name, description, price, slug, stock, category_id, rating, created_at, updated_at) VALUES
  -- T-Shirts (category 1)
  (1,  'Classic White Tee',        'A timeless wardrobe essential crafted from 100% organic cotton for all-day comfort.',              19.99, 'classic-white-tee',        50, 1, 4.5, NOW() - INTERVAL '30 days', NOW()),
  (2,  'Graphic Print Tee',        'Bold graphic print tee that makes a statement. Pre-shrunk jersey cotton.',                        24.99, 'graphic-print-tee',        35, 1, 4.0, NOW() - INTERVAL '25 days', NOW()),
  (3,  'Striped Cotton Tee',       'Classic nautical stripe in soft breathable cotton. A summer must-have.',                          22.99, 'striped-cotton-tee',       40, 1, 3.8, NOW() - INTERVAL '20 days', NOW()),
  (4,  'V-Neck Essential Tee',     'Clean V-neck silhouette in ultra-soft Pima cotton. Perfect for layering.',                       17.99, 'v-neck-essential-tee',     60, 1, 4.7, NOW() - INTERVAL '15 days', NOW()),
  -- Jeans (category 2)
  (5,  'Slim Fit Jeans',           'Modern slim fit with just enough stretch for comfort. Mid-rise waist, 5-pocket styling.',         49.99, 'slim-fit-jeans',           25, 2, 4.3, NOW() - INTERVAL '28 days', NOW()),
  (6,  'Straight Leg Jeans',       'Classic straight-leg cut in rigid denim. A versatile everyday staple.',                          54.99, 'straight-leg-jeans',       20, 2, 4.1, NOW() - INTERVAL '22 days', NOW()),
  (7,  'Skinny Stretch Jeans',     'Ultra-slim silhouette with 2% elastane for all-day comfort and movement.',                       44.99, 'skinny-stretch-jeans',     30, 2, 3.9, NOW() - INTERVAL '18 days', NOW()),
  (8,  'Relaxed Fit Jeans',        'Easy relaxed fit with a roomy seat and thigh. 100% rigid cotton denim.',                         59.99, 'relaxed-fit-jeans',        15, 2, 4.4, NOW() - INTERVAL '10 days', NOW()),
  -- Hoodies (category 3)
  (9,  'Classic Pullover Hoodie',  'The quintessential pullover hoodie in midweight fleece with kangaroo pocket.',                   39.99, 'classic-pullover-hoodie',  45, 3, 4.6, NOW() - INTERVAL '35 days', NOW()),
  (10, 'Zip-Up Hoodie',            'Full-zip hoodie in soft brushed fleece. Twin pockets and ribbed cuffs.',                         44.99, 'zip-up-hoodie',            30, 3, 4.2, NOW() - INTERVAL '27 days', NOW()),
  (11, 'Oversized Hoodie',         'Relaxed oversized fit for an effortless streetwear look. Dropped shoulders.',                    49.99, 'oversized-hoodie',         20, 3, 4.5, NOW() - INTERVAL '12 days', NOW()),
  (12, 'Fleece Hoodie',            'Cosy heavyweight fleece hoodie ideal for cold weather. Lined hood, ribbed trims.',               42.99, 'fleece-hoodie',            35, 3, 4.0, NOW() - INTERVAL '8 days',  NOW()),
  -- Shirts (category 4)
  (13, 'Oxford Button Shirt',      'Classic Oxford-weave shirt with button-down collar. Tailored fit in pure cotton.',               34.99, 'oxford-button-shirt',      25, 4, 4.3, NOW() - INTERVAL '40 days', NOW()),
  (14, 'Flannel Check Shirt',      'Warm brushed-cotton flannel in a classic check pattern. Perfect for autumn layering.',           39.99, 'flannel-check-shirt',      20, 4, 4.1, NOW() - INTERVAL '32 days', NOW()),
  (15, 'Linen Summer Shirt',       'Lightweight linen shirt with a relaxed straight fit. Ideal for warm days.',                     42.99, 'linen-summer-shirt',       30, 4, 4.4, NOW() - INTERVAL '14 days', NOW()),
  (16, 'Denim Shirt',              'Classic denim shirt in washed cotton with a relaxed fit. Chest pockets, snap fastening.',        44.99, 'denim-shirt',              15, 4, 3.9, NOW() - INTERVAL '6 days',  NOW()),
  -- Sneakers (category 5)
  (17, 'Running Sneakers',         'Lightweight performance sneaker with cushioned midsole and breathable mesh upper.',              79.99, 'running-sneakers',         40, 5, 4.8, NOW() - INTERVAL '45 days', NOW()),
  (18, 'Canvas Low-Top',           'Minimalist canvas sneaker with vulcanised rubber sole. An effortless everyday classic.',         49.99, 'canvas-low-top',           50, 5, 4.5, NOW() - INTERVAL '38 days', NOW()),
  (19, 'High-Top Sneakers',        'Iconic high-top silhouette in premium canvas with padded ankle collar for extra support.',       69.99, 'high-top-sneakers',        25, 5, 4.3, NOW() - INTERVAL '21 days', NOW()),
  (20, 'Slip-On Sneakers',         'Effortless slip-on style with elastic side gussets and a padded insole for all-day comfort.',   44.99, 'slip-on-sneakers',         35, 5, 4.1, NOW() - INTERVAL '5 days',  NOW());

SELECT setval(pg_get_serial_sequence('products','id'), MAX(id)) FROM products;


-- ─── Product Images ────────────────────────────────────────────
-- Unsplash fashion photos (stable direct links).
-- Several products have 2–3 images to showcase multiple angles/colours.
INSERT INTO product_images (product_id, image_url, size, created_at, updated_at) VALUES
  -- Product 1 — Classic White Tee (2 images)
  (1,  'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&h=800&fit=crop&q=80', 102400, NOW(), NOW()),
  (1,  'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=600&h=800&fit=crop&q=80',  98304, NOW(), NOW()),
  -- Product 2 — Graphic Print Tee (1 image)
  (2,  'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=600&h=800&fit=crop&q=80', 110592, NOW(), NOW()),
  -- Product 3 — Striped Cotton Tee (2 images)
  (3,  'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=600&h=800&fit=crop&q=80',    115712, NOW(), NOW()),
  (3,  'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=600&h=800&fit=crop&q=80',  95232, NOW(), NOW()),
  -- Product 4 — V-Neck Essential Tee (1 image)
  (4,  'https://images.unsplash.com/photo-1571945153237-4929e783af4a?w=600&h=800&fit=crop&q=80',  99328, NOW(), NOW()),
  -- Product 5 — Slim Fit Jeans (2 images)
  (5,  'https://images.unsplash.com/photo-1542272604-787c3835535d?w=600&h=800&fit=crop&q=80',    131072, NOW(), NOW()),
  (5,  'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=600&h=800&fit=crop&q=80', 125952, NOW(), NOW()),
  -- Product 6 — Straight Leg Jeans (1 image)
  (6,  'https://images.unsplash.com/photo-1475178626620-a4d074967452?w=600&h=800&fit=crop&q=80', 118784, NOW(), NOW()),
  -- Product 7 — Skinny Stretch Jeans (1 image)
  (7,  'https://images.unsplash.com/photo-1604176354204-9268737f9e64?w=600&h=800&fit=crop&q=80', 120832, NOW(), NOW()),
  -- Product 8 — Relaxed Fit Jeans (2 images)
  (8,  'https://images.unsplash.com/photo-1602293589930-45aad59ba3ab?w=600&h=800&fit=crop&q=80', 143360, NOW(), NOW()),
  (8,  'https://images.unsplash.com/photo-1475180098004-ca77a66827be?w=600&h=800&fit=crop&q=80', 138240, NOW(), NOW()),
  -- Product 9 — Classic Pullover Hoodie (3 images)
  (9,  'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=600&h=800&fit=crop&q=80',    137216, NOW(), NOW()),
  (9,  'https://images.unsplash.com/photo-1509942774463-acf339cf87d5?w=600&h=800&fit=crop&q=80', 129024, NOW(), NOW()),
  (9,  'https://images.unsplash.com/photo-1517686469429-8bdb88b9f907?w=600&h=800&fit=crop&q=80', 122880, NOW(), NOW()),
  -- Product 10 — Zip-Up Hoodie (2 images)
  (10, 'https://images.unsplash.com/photo-1620799139507-2a76f79a2f4d?w=600&h=800&fit=crop&q=80', 115712, NOW(), NOW()),
  (10, 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&h=800&fit=crop&q=80', 111616, NOW(), NOW()),
  -- Product 11 — Oversized Hoodie (1 image)
  (11, 'https://images.unsplash.com/photo-1578681994506-b8f463449011?w=600&h=800&fit=crop&q=80', 108544, NOW(), NOW()),
  -- Product 12 — Fleece Hoodie (1 image)
  (12, 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=600&h=800&fit=crop&q=80',    104448, NOW(), NOW()),
  -- Product 13 — Oxford Button Shirt (2 images)
  (13, 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600&h=800&fit=crop&q=80', 108544, NOW(), NOW()),
  (13, 'https://images.unsplash.com/photo-1603252109303-2751441dd157?w=600&h=800&fit=crop&q=80', 104448, NOW(), NOW()),
  -- Product 14 — Flannel Check Shirt (1 image)
  (14, 'https://images.unsplash.com/photo-1516257984-b1b4d707412e?w=600&h=800&fit=crop&q=80',    112640, NOW(), NOW()),
  -- Product 15 — Linen Summer Shirt (2 images)
  (15, 'https://images.unsplash.com/photo-1590548784585-a2a2a78a0c3a?w=600&h=800&fit=crop&q=80', 101376, NOW(), NOW()),
  (15, 'https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?w=600&h=800&fit=crop&q=80',  97280, NOW(), NOW()),
  -- Product 16 — Denim Shirt (1 image)
  (16, 'https://images.unsplash.com/photo-1602810316693-3667c854239a?w=600&h=800&fit=crop&q=80', 119808, NOW(), NOW()),
  -- Product 17 — Running Sneakers (3 images)
  (17, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&h=800&fit=crop&q=80',    155648, NOW(), NOW()),
  (17, 'https://images.unsplash.com/photo-1559181567-c3190952d54d?w=600&h=800&fit=crop&q=80',    147456, NOW(), NOW()),
  (17, 'https://images.unsplash.com/photo-1608231387042-66d1773d3028?w=600&h=800&fit=crop&q=80', 143360, NOW(), NOW()),
  -- Product 18 — Canvas Low-Top (2 images)
  (18, 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=600&h=800&fit=crop&q=80', 147456, NOW(), NOW()),
  (18, 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&h=800&fit=crop&q=80',    138240, NOW(), NOW()),
  -- Product 19 — High-Top Sneakers (2 images)
  (19, 'https://images.unsplash.com/photo-1600185365926-3a2ce3cdb9eb?w=600&h=800&fit=crop&q=80', 163840, NOW(), NOW()),
  (19, 'https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=600&h=800&fit=crop&q=80',    155648, NOW(), NOW()),
  -- Product 20 — Slip-On Sneakers (1 image)
  (20, 'https://images.unsplash.com/photo-1608667508764-33cf0726b13a?w=600&h=800&fit=crop&q=80', 138240, NOW(), NOW());


-- ─── Stock (item_color_size_counts) ───────────────────────────
INSERT INTO item_color_size_counts (item_id, color_id, size, count, created_at, updated_at) VALUES
  -- Product 1 — Classic White Tee: White & Black, S/M/L
  (1, 2, 'S', 10, NOW(), NOW()), (1, 2, 'M', 15, NOW(), NOW()), (1, 2, 'L', 10, NOW(), NOW()),
  (1, 1, 'S',  8, NOW(), NOW()), (1, 1, 'M', 12, NOW(), NOW()), (1, 1, 'L',  5, NOW(), NOW()),
  -- Product 2 — Graphic Print Tee: Black & Red
  (2, 1, 'S',  5, NOW(), NOW()), (2, 1, 'M', 10, NOW(), NOW()), (2, 1, 'L',  8, NOW(), NOW()),
  (2, 4, 'S',  4, NOW(), NOW()), (2, 4, 'M',  8, NOW(), NOW()),
  -- Product 3 — Striped Cotton Tee: Navy & White
  (3, 3, 'S',  8, NOW(), NOW()), (3, 3, 'M', 12, NOW(), NOW()), (3, 3, 'L',  8, NOW(), NOW()),
  (3, 2, 'S',  4, NOW(), NOW()), (3, 2, 'M',  8, NOW(), NOW()),
  -- Product 4 — V-Neck Essential Tee: Black, Grey & White
  (4, 1, 'XS', 5, NOW(), NOW()), (4, 1, 'S', 10, NOW(), NOW()), (4, 1, 'M', 15, NOW(), NOW()), (4, 1, 'L', 10, NOW(), NOW()),
  (4, 6, 'S',  8, NOW(), NOW()), (4, 6, 'M', 10, NOW(), NOW()), (4, 6, 'L',  7, NOW(), NOW()),
  (4, 2, 'S',  5, NOW(), NOW()), (4, 2, 'M',  8, NOW(), NOW()),
  -- Product 5 — Slim Fit Jeans: Black & Navy
  (5, 1, 'S',  5, NOW(), NOW()), (5, 1, 'M',  8, NOW(), NOW()), (5, 1, 'L',  5, NOW(), NOW()),
  (5, 3, 'S',  3, NOW(), NOW()), (5, 3, 'M',  7, NOW(), NOW()),
  -- Product 6 — Straight Leg Jeans: Navy & Olive
  (6, 3, 'S',  4, NOW(), NOW()), (6, 3, 'M',  8, NOW(), NOW()), (6, 3, 'L',  4, NOW(), NOW()),
  (6, 5, 'M',  6, NOW(), NOW()), (6, 5, 'L',  2, NOW(), NOW()),
  -- Product 7 — Skinny Stretch Jeans: Black & Grey
  (7, 1, 'XS', 4, NOW(), NOW()), (7, 1, 'S',  8, NOW(), NOW()), (7, 1, 'M', 10, NOW(), NOW()), (7, 1, 'L', 5, NOW(), NOW()),
  (7, 6, 'S',  3, NOW(), NOW()), (7, 6, 'M',  6, NOW(), NOW()),
  -- Product 8 — Relaxed Fit Jeans: Navy & Black
  (8, 3, 'M',  5, NOW(), NOW()), (8, 3, 'L',  5, NOW(), NOW()), (8, 3, 'XL', 3, NOW(), NOW()),
  (8, 1, 'M',  4, NOW(), NOW()), (8, 1, 'L',  3, NOW(), NOW()),
  -- Product 9 — Classic Pullover Hoodie: Black, Navy & Grey
  (9, 1, 'S',  8, NOW(), NOW()), (9, 1, 'M', 12, NOW(), NOW()), (9, 1, 'L', 10, NOW(), NOW()), (9, 1, 'XL', 5, NOW(), NOW()),
  (9, 3, 'S',  5, NOW(), NOW()), (9, 3, 'M',  8, NOW(), NOW()), (9, 3, 'L',  7, NOW(), NOW()),
  (9, 6, 'M',  5, NOW(), NOW()), (9, 6, 'L',  5, NOW(), NOW()),
  -- Product 10 — Zip-Up Hoodie: Black, Red & Grey
  (10, 1, 'S', 5, NOW(), NOW()), (10, 1, 'M', 8, NOW(), NOW()), (10, 1, 'L',  7, NOW(), NOW()),
  (10, 4, 'S', 3, NOW(), NOW()), (10, 4, 'M', 5, NOW(), NOW()),
  (10, 6, 'M', 5, NOW(), NOW()),
  -- Product 11 — Oversized Hoodie: Olive, Black & White
  (11, 5, 'S', 4, NOW(), NOW()), (11, 5, 'M', 6, NOW(), NOW()), (11, 5, 'L',  5, NOW(), NOW()),
  (11, 1, 'S', 3, NOW(), NOW()), (11, 1, 'M', 5, NOW(), NOW()),
  (11, 2, 'S', 2, NOW(), NOW()), (11, 2, 'M', 3, NOW(), NOW()),
  -- Product 12 — Fleece Hoodie: Navy & Grey
  (12, 3, 'M', 8, NOW(), NOW()), (12, 3, 'L', 8, NOW(), NOW()), (12, 3, 'XL', 5, NOW(), NOW()),
  (12, 6, 'S', 5, NOW(), NOW()), (12, 6, 'M', 7, NOW(), NOW()), (12, 6, 'L',  5, NOW(), NOW()),
  -- Product 13 — Oxford Button Shirt: White & Navy
  (13, 2, 'S', 5, NOW(), NOW()), (13, 2, 'M', 8, NOW(), NOW()), (13, 2, 'L',  5, NOW(), NOW()),
  (13, 3, 'S', 3, NOW(), NOW()), (13, 3, 'M', 5, NOW(), NOW()),
  -- Product 14 — Flannel Check Shirt: Red & Olive
  (14, 4, 'S', 5, NOW(), NOW()), (14, 4, 'M', 7, NOW(), NOW()), (14, 4, 'L',  5, NOW(), NOW()),
  (14, 5, 'S', 3, NOW(), NOW()), (14, 5, 'M', 5, NOW(), NOW()),
  -- Product 15 — Linen Summer Shirt: White & Olive
  (15, 2, 'S', 8, NOW(), NOW()), (15, 2, 'M',10, NOW(), NOW()), (15, 2, 'L',  7, NOW(), NOW()),
  (15, 5, 'S', 4, NOW(), NOW()), (15, 5, 'M', 7, NOW(), NOW()), (15, 5, 'L',  4, NOW(), NOW()),
  -- Product 16 — Denim Shirt: Navy
  (16, 3, 'S', 4, NOW(), NOW()), (16, 3, 'M', 6, NOW(), NOW()), (16, 3, 'L',  5, NOW(), NOW()),
  -- Product 17 — Running Sneakers: Black, White & Red
  (17, 1, 'S', 8, NOW(), NOW()), (17, 1, 'M',12, NOW(), NOW()), (17, 1, 'L', 10, NOW(), NOW()), (17, 1, 'XL', 5, NOW(), NOW()),
  (17, 2, 'S', 5, NOW(), NOW()), (17, 2, 'M', 8, NOW(), NOW()), (17, 2, 'L',  7, NOW(), NOW()),
  (17, 4, 'M', 5, NOW(), NOW()), (17, 4, 'L', 5, NOW(), NOW()),
  -- Product 18 — Canvas Low-Top: White, Black & Navy
  (18, 2, 'S',10, NOW(), NOW()), (18, 2, 'M',15, NOW(), NOW()), (18, 2, 'L', 10, NOW(), NOW()),
  (18, 1, 'S', 8, NOW(), NOW()), (18, 1, 'M',10, NOW(), NOW()), (18, 1, 'L',  8, NOW(), NOW()),
  (18, 3, 'M', 7, NOW(), NOW()),
  -- Product 19 — High-Top Sneakers: Black & White
  (19, 1, 'S', 5, NOW(), NOW()), (19, 1, 'M', 8, NOW(), NOW()), (19, 1, 'L',  7, NOW(), NOW()),
  (19, 2, 'S', 3, NOW(), NOW()), (19, 2, 'M', 6, NOW(), NOW()), (19, 2, 'L',  3, NOW(), NOW()),
  -- Product 20 — Slip-On Sneakers: Grey, Black & Olive
  (20, 6, 'S', 8, NOW(), NOW()), (20, 6, 'M',10, NOW(), NOW()), (20, 6, 'L',  8, NOW(), NOW()),
  (20, 1, 'S', 5, NOW(), NOW()), (20, 1, 'M', 7, NOW(), NOW()),
  (20, 5, 'M', 5, NOW(), NOW()), (20, 5, 'L', 5, NOW(), NOW());


-- ─── Sales ────────────────────────────────────────────────────
INSERT INTO sales (id, name, slug, discount, valid_from, valid_to, promo_code, created_at, updated_at) VALUES
  (1, 'Summer Sale',        'summer-sale',      20.00, NOW() - INTERVAL '7 days', NOW() + INTERVAL '30 days', NULL,    NOW(), NOW()),
  (2, 'Denim & Hood Deals', 'denim-hood-deals', 30.00, NOW() - INTERVAL '3 days', NOW() + INTERVAL '60 days', NULL,    NOW(), NOW()),
  (3, 'VIP10 Promo',        'vip10-promo',      10.00, NOW() - INTERVAL '1 day',  NOW() + INTERVAL '90 days', 'VIP10', NOW(), NOW());

SELECT setval(pg_get_serial_sequence('sales','id'), MAX(id)) FROM sales;


-- ─── Products ↔ Sales ─────────────────────────────────────────
-- Summer Sale (20%) → all T-Shirts (1–4)
-- Denim & Hood Deals (30%) → Slim Fit Jeans, Skinny Jeans, Classic Hoodie, Oversized Hoodie
INSERT INTO products_on_sales (product_id, sale_id, created_at, updated_at) VALUES
  (1,  1, NOW(), NOW()),
  (2,  1, NOW(), NOW()),
  (3,  1, NOW(), NOW()),
  (4,  1, NOW(), NOW()),
  (5,  2, NOW(), NOW()),
  (7,  2, NOW(), NOW()),
  (9,  2, NOW(), NOW()),
  (11, 2, NOW(), NOW());


-- ─── Users ────────────────────────────────────────────────────
-- Password for all accounts: "password"
INSERT INTO users (id, name, email, password, is_admin, created_at, updated_at) VALUES
  (1, 'Admin',        'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE,  NOW() - INTERVAL '60 days', NOW()),
  (2, 'John Doe',     'john@example.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FALSE, NOW() - INTERVAL '50 days', NOW()),
  (3, 'Jane Smith',   'jane@example.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FALSE, NOW() - INTERVAL '45 days', NOW()),
  (4, 'Mike Johnson', 'mike@example.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FALSE, NOW() - INTERVAL '30 days', NOW()),
  (5, 'Sarah Wilson', 'sarah@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FALSE, NOW() - INTERVAL '20 days', NOW()),
  (6, 'Tom Brown',    'tom@example.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', FALSE, NOW() - INTERVAL '10 days', NOW());

SELECT setval(pg_get_serial_sequence('users','id'), MAX(id)) FROM users;


-- ─── Orders ───────────────────────────────────────────────────
INSERT INTO orders (
  id, user_id, status, currency, promo_code,
  subtotal_before_discount, sales_discount_total, promo_discount_total, discount_total,
  subtotal, delivery_fee, total,
  shipping_full_name, shipping_email, shipping_phone,
  shipping_street, shipping_city, shipping_postal_code, shipping_country,
  paid_at, created_at, updated_at
) VALUES
  -- 1: John — delivered | 2× T-Shirts, Summer Sale 20%
  (1, 2, 'delivered',       'usd', NULL,
   37.98, 7.60, 0.00, 7.60,  30.38, 4.99, 35.37,
   'John Doe',     'john@example.com',  '+44 7700 900001', '42 Oak Avenue',  'Manchester', 'M1 1AE',  'GB',
   NOW() - INTERVAL '25 days', NOW() - INTERVAL '28 days', NOW()),

  -- 2: Jane — shipped | Slim Fit Jeans, no discount
  (2, 3, 'shipped',         'usd', NULL,
   49.99, 0.00, 0.00, 0.00,  49.99, 4.99, 54.98,
   'Jane Smith',   'jane@example.com',  '+44 7700 900002', '8 Maple Road',   'London',     'E1 6RF',  'GB',
   NOW() - INTERVAL '5 days',  NOW() - INTERVAL '7 days',  NOW()),

  -- 3: Mike — paid | Hoodie 30% + Jeans 30%
  (3, 4, 'paid',            'usd', NULL,
   84.98, 25.49, 0.00, 25.49, 59.49, 4.99, 64.48,
   'Mike Johnson', 'mike@example.com',  '+44 7700 900003', '15 Pine Street', 'Birmingham', 'B1 1BB',  'GB',
   NOW() - INTERVAL '2 days',  NOW() - INTERVAL '3 days',  NOW()),

  -- 4: Sarah — delivered | Running Sneakers, free shipping
  (4, 5, 'delivered',       'usd', NULL,
   79.99, 0.00, 0.00, 0.00,  79.99, 0.00, 79.99,
   'Sarah Wilson', 'sarah@example.com', '+44 7700 900004', '23 Cedar Lane',  'Leeds',      'LS1 1BA', 'GB',
   NOW() - INTERVAL '15 days', NOW() - INTERVAL '18 days', NOW()),

  -- 5: Tom — pending_payment | High-Top Sneakers
  (5, 6, 'pending_payment', 'usd', NULL,
   69.99, 0.00, 0.00, 0.00,  69.99, 4.99, 74.98,
   'Tom Brown',    'tom@example.com',   '+44 7700 900005', '7 Birch Close',  'Liverpool',  'L1 1AA',  'GB',
   NULL, NOW() - INTERVAL '1 day',  NOW()),

  -- 6: John — completed | Relaxed Jeans, VIP10 promo 10%
  (6, 2, 'completed',       'usd', 'VIP10',
   59.99, 0.00, 6.00, 6.00,  53.99, 4.99, 58.98,
   'John Doe',     'john@example.com',  '+44 7700 900001', '42 Oak Avenue',  'Manchester', 'M1 1AE',  'GB',
   NOW() - INTERVAL '40 days', NOW() - INTERVAL '42 days', NOW()),

  -- 7: Jane — cancelled | Oversized Hoodie 30%
  (7, 3, 'cancelled',       'usd', NULL,
   49.99, 15.00, 0.00, 15.00, 34.99, 4.99, 39.98,
   'Jane Smith',   'jane@example.com',  '+44 7700 900002', '8 Maple Road',   'London',     'E1 6RF',  'GB',
   NULL, NOW() - INTERVAL '12 days', NOW()),

  -- 8: Mike — delivered | 2× Canvas Low-Top, free shipping
  (8, 4, 'delivered',       'usd', NULL,
   99.98, 0.00, 0.00, 0.00,  99.98, 0.00, 99.98,
   'Mike Johnson', 'mike@example.com',  '+44 7700 900003', '15 Pine Street', 'Birmingham', 'B1 1BB',  'GB',
   NOW() - INTERVAL '35 days', NOW() - INTERVAL '38 days', NOW());

SELECT setval(pg_get_serial_sequence('orders','id'), MAX(id)) FROM orders;


-- ─── Order Items ──────────────────────────────────────────────
INSERT INTO order_items (
  order_id, product_id, color_id, size, quantity,
  unit_price_before_discount,
  unit_sales_discount_percent, unit_promo_discount_percent, unit_discount_percent,
  unit_price_after_discount, line_total,
  created_at, updated_at
) VALUES
  -- Order 1: Classic White Tee (Black, M) + V-Neck Tee (Grey, S)  → Summer Sale 20%
  (1, 1, 1, 'M', 1,  19.99, 20.00, 0.00, 20.00, 15.99,  15.99, NOW() - INTERVAL '28 days', NOW()),
  (1, 4, 6, 'S', 1,  17.99, 20.00, 0.00, 20.00, 14.39,  14.39, NOW() - INTERVAL '28 days', NOW()),
  -- Order 2: Slim Fit Jeans (Navy, M) → no discount
  (2, 5, 3, 'M', 1,  49.99,  0.00, 0.00,  0.00, 49.99,  49.99, NOW() - INTERVAL '7 days',  NOW()),
  -- Order 3: Classic Pullover Hoodie (Black, L) + Skinny Stretch Jeans (Black, M) → 30%
  (3, 9, 1, 'L', 1,  39.99, 30.00, 0.00, 30.00, 27.99,  27.99, NOW() - INTERVAL '3 days',  NOW()),
  (3, 7, 1, 'M', 1,  44.99, 30.00, 0.00, 30.00, 31.49,  31.49, NOW() - INTERVAL '3 days',  NOW()),
  -- Order 4: Running Sneakers (Black, L) → no discount
  (4, 17, 1, 'L', 1, 79.99,  0.00, 0.00,  0.00, 79.99,  79.99, NOW() - INTERVAL '18 days', NOW()),
  -- Order 5: High-Top Sneakers (White, M) → no discount
  (5, 19, 2, 'M', 1, 69.99,  0.00, 0.00,  0.00, 69.99,  69.99, NOW() - INTERVAL '1 day',   NOW()),
  -- Order 6: Relaxed Fit Jeans (Navy, M) → VIP10 promo 10%
  (6, 8,  3, 'M', 1, 59.99,  0.00,10.00, 10.00, 53.99,  53.99, NOW() - INTERVAL '42 days', NOW()),
  -- Order 7: Oversized Hoodie (Olive, M) → 30%
  (7, 11, 5, 'M', 1, 49.99, 30.00, 0.00, 30.00, 34.99,  34.99, NOW() - INTERVAL '12 days', NOW()),
  -- Order 8: 2× Canvas Low-Top (White, M) → no discount
  (8, 18, 2, 'M', 2, 49.99,  0.00, 0.00,  0.00, 49.99,  99.98, NOW() - INTERVAL '38 days', NOW());


-- ─── Payments ─────────────────────────────────────────────────
-- Only paid/completed/shipped/delivered orders have payment records
INSERT INTO payments (order_id, provider, provider_payment_id, status, amount, currency, payload, created_at, updated_at) VALUES
  (1, 'stripe', 'pi_seed_order_01',  'succeeded',  35.37, 'usd', '{"seed":true}', NOW() - INTERVAL '25 days', NOW()),
  (2, 'stripe', 'pi_seed_order_02',  'succeeded',  54.98, 'usd', '{"seed":true}', NOW() - INTERVAL '5 days',  NOW()),
  (3, 'stripe', 'pi_seed_order_03',  'succeeded',  64.48, 'usd', '{"seed":true}', NOW() - INTERVAL '2 days',  NOW()),
  (4, 'stripe', 'pi_seed_order_04',  'succeeded',  79.99, 'usd', '{"seed":true}', NOW() - INTERVAL '15 days', NOW()),
  (6, 'stripe', 'pi_seed_order_06',  'succeeded',  58.98, 'usd', '{"seed":true}', NOW() - INTERVAL '40 days', NOW()),
  (8, 'stripe', 'pi_seed_order_08',  'succeeded',  99.98, 'usd', '{"seed":true}', NOW() - INTERVAL '35 days', NOW());

SELECT setval(pg_get_serial_sequence('payments','id'), MAX(id)) FROM payments;

-- =============================================================
-- Done! Summary:
--   5 categories · 6 colors · 20 products · 20 images
--   2 active sales + 1 promo code (VIP10)
--   6 users (admin@example.com is admin)
--   8 orders (delivered/shipped/paid/pending/cancelled/completed)
--   10 order items · 6 payments
-- =============================================================
