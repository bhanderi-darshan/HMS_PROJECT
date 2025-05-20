-- Create database
CREATE DATABASE IF NOT EXISTS luxury_hotel;
USE luxury_hotel;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('user', 'staff', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Rooms table
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    capacity INT NOT NULL,
    bed_type VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    size INT,
    description TEXT,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    booking_reference VARCHAR(20) NOT NULL UNIQUE,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    num_guests INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    special_requests TEXT,
    payment_method VARCHAR(50) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Services table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    hours VARCHAR(100),
    highlights TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Service bookings table
CREATE TABLE IF NOT EXISTS service_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    booking_reference VARCHAR(20) NOT NULL UNIQUE,
    service_date DATE NOT NULL,
    service_time TIME NOT NULL,
    num_people INT NOT NULL,
    selected_package VARCHAR(50),
    total_price DECIMAL(10, 2) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    special_requests TEXT,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tables (restaurant) table
CREATE TABLE IF NOT EXISTS tables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_number INT NOT NULL UNIQUE,
    seats INT NOT NULL,
    location VARCHAR(50) DEFAULT 'Main Dining Area',
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table reservations table
CREATE TABLE IF NOT EXISTS table_reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    table_id INT NOT NULL,
    reservation_reference VARCHAR(20) NOT NULL UNIQUE,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    num_guests INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    special_requests TEXT,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (table_id) REFERENCES tables(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menu items table
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category ENUM('starters', 'mains', 'desserts', 'beverages') NOT NULL,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'responded', 'archived') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample users
INSERT INTO users (first_name, last_name, email, password, phone, role) VALUES
('Admin', 'User', 'admin@luxuryhotel.com', '$2y$10$AAPrJwGzKxrxIZ49Gj/7h.oBxiK3Q9K6YO1wJg62K/j/0jqvfciGO', '+1234567890', 'admin'),
('Staff', 'Member', 'staff@luxuryhotel.com', '$2y$10$2CmQxnvUVAMSSSNy9QYEneKB8o8CGm3k9LyUYgAgU31gqkmUkT4zO', '+1234567891', 'staff'),
('John', 'Doe', 'john@example.com', '$2y$10$v7Clr4lZEkZve0Vw56DfreQe0iGWDBPpRTn0zjUBN7KWl3.r5bKPe', '+1234567892', 'user'),
('Sarah', 'Johnson', 'sarah@example.com', '$2y$10$v7Clr4lZEkZve0Vw56DfreQe0iGWDBPpRTn0zjUBN7KWl3.r5bKPe', '+1234567893', 'user'),
('Michael', 'Smith', 'michael@example.com', '$2y$10$v7Clr4lZEkZve0Vw56DfreQe0iGWDBPpRTn0zjUBN7KWl3.r5bKPe', '+1234567894', 'user');

-- Insert sample rooms
INSERT INTO rooms (name, type, capacity, bed_type, price, size, description, image_url, is_available, featured) VALUES
('Deluxe Room', 'deluxe', 2, 'King Bed', 199.00, 32, 'Spacious deluxe room with modern amenities and beautiful city view. Features include a luxurious bathroom, work desk, and high-speed internet access.', 'https://images.pexels.com/photos/271624/pexels-photo-271624.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', TRUE, TRUE),
('Executive Suite', 'suite', 3, 'King Bed', 299.00, 48, 'Luxurious suite with separate living area and premium amenities. Enjoy a spacious bathroom with soaking tub, 24-hour room service, and exclusive access to the Executive Lounge.', 'https://images.pexels.com/photos/271619/pexels-photo-271619.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', TRUE, TRUE),
('Presidential Suite', 'presidential', 4, 'King Bed', 499.00, 72, 'The ultimate luxury experience with panoramic views and exclusive services. Features include a separate living room, dining area, private butler service, and premium amenities.', 'https://images.pexels.com/photos/1838554/pexels-photo-1838554.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', TRUE, TRUE),
('Family Suite', 'suite', 5, '2 King Beds', 399.00, 65, 'Perfect for families, this spacious suite offers two connecting rooms with all the amenities needed for a comfortable stay. Includes a mini-kitchen and family entertainment options.', 'https://images.pexels.com/photos/210265/pexels-photo-210265.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', TRUE, FALSE),
('Standard Twin Room', 'standard', 2, 'Twin Beds', 159.00, 28, 'Comfortable room with two single beds, perfect for friends or colleagues traveling together. Includes a work desk and modern bathroom.', 'https://images.pexels.com/photos/271618/pexels-photo-271618.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1', TRUE, FALSE);

-- Insert sample services
INSERT INTO services (name, description, price, hours, highlights, image_url) VALUES
('Spa & Wellness', 'Indulge in a world of relaxation and rejuvenation at our award-winning spa. Our expert therapists combine ancient healing traditions with modern techniques to offer treatments that restore balance to body, mind, and spirit.', 85.00, 'Open daily: 9:00 AM - 8:00 PM', 'Signature massage therapies|Luxury facial treatments|Aromatherapy and body scrubs|Couples treatments|Hydrotherapy pool and steam room', 'https://images.pexels.com/photos/3757942/pexels-photo-3757942.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
('Fitness Center', 'Maintain your fitness routine in our state-of-the-art fitness center, featuring premium cardio and strength training equipment. Our certified personal trainers are available to provide customized workout programs tailored to your goals.', 45.00, 'Open 24 hours for hotel guests', 'Latest cardio and weight equipment|Personal training sessions|Yoga and pilates classes|Nutritional consultation|Fresh towels and water service', 'https://images.pexels.com/photos/3076509/pexels-photo-3076509.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
('Swimming Pool', 'Dive into relaxation in our stunning infinity pool with panoramic views of the city skyline. The pool area features private cabanas, a poolside bar, and attentive service to enhance your experience.', 65.00, 'Open daily: 7:00 AM - 10:00 PM', 'Heated infinity pool|Poolside cabanas with reserved service|Jacuzzi and whirlpool|Pool bar with signature cocktails|Children\'s splash pool', 'https://images.pexels.com/photos/261327/pexels-photo-261327.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
('Conference Facilities', 'Host impeccable meetings and events in our sophisticated conference facilities. With cutting-edge technology, versatile spaces, and dedicated event planners, we ensure your business functions run smoothly.', 500.00, 'Available upon request', 'Multiple conference rooms and ballrooms|State-of-the-art audiovisual equipment|High-speed internet access|Customized catering menus|Professional event planning assistance', 'https://images.pexels.com/photos/416320/pexels-photo-416320.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');

-- Insert sample tables
INSERT INTO tables (table_number, seats, location) VALUES
(1, 2, 'Main Dining Area'),
(2, 4, 'Main Dining Area'),
(3, 2, 'Main Dining Area'),
(4, 4, 'Main Dining Area'),
(5, 6, 'Main Dining Area'),
(6, 2, 'Terrace'),
(7, 4, 'Terrace'),
(8, 6, 'Terrace'),
(9, 8, 'Private Dining'),
(10, 2, 'Window Area'),
(11, 4, 'Window Area'),
(12, 2, 'Bar Adjacent');

-- Insert sample menu items
INSERT INTO menu_items (name, description, price, category, image_url) VALUES
('Herb-Crusted Prawns', 'Succulent prawns coated in a fragrant herb crust, served with a zesty citrus aioli and microgreens.', 18.00, 'starters', 'https://images.pexels.com/photos/1211887/pexels-photo-1211887.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
('Beet & Goat Cheese Salad', 'Roasted heirloom beets with creamy goat cheese, candied walnuts, and arugula, drizzled with honey balsamic reduction.', 14.00, 'starters', 'https://images.pexels.com/photos/1893567/pexels-photo-1893567.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
('Truffle Mushroom Soup', 'Silky wild mushroom soup infused with truffle oil, garnished with crème fraîche and chives.', 12.00, 'starters', 'https://images.pexels.com/photos/539451/pexels-photo-539451.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
('Filet Mignon', '8oz prime beef tenderloin grilled to perfection, served with truffle mashed potatoes, seasonal vegetables, and a red wine reduction.', 42.00, 'mains', 'https://images.pexels.com/photos/769289/pexels-photo-769289.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
('Pan-Seared Sea Bass', 'Crispy-skinned sea bass on a bed of saffron risotto, accompanied by asparagus and lemon beurre blanc.', 36.00, 'mains', 'https://images.pexels.com/photos/725991/pexels-photo-725991.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
('Wild Mushroom Risotto', 'Creamy Arborio rice with a medley of wild mushrooms, finished with aged Parmesan, white truffle oil, and fresh herbs.', 28.00, 'mains', 'https://images.pexels.com/photos/6896393/pexels-photo-6896393.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
('Chocolate Lava Cake', 'Warm chocolate cake with a molten center, served with vanilla bean ice cream and fresh berries.', 14.00, 'desserts', 'https://images.pexels.com/photos/3026804/pexels-photo-3026804.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
('Crème Brûlée', 'Classic vanilla custard with a caramelized sugar crust, accompanied by house-made shortbread cookies.', 12.00, 'desserts', 'https://images.pexels.com/photos/2144112/pexels-photo-2144112.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
('Signature Martini', 'Premium vodka or gin with house-infused vermouth and a twist of citrus. A classic with our special touch.', 16.00, 'beverages', 'https://images.pexels.com/photos/2775860/pexels-photo-2775860.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'),
('Artisanal Coffee Selection', 'Single-origin coffee prepared using your choice of method: French press, pour-over, or espresso. Served with petit fours.', 8.00, 'beverages', 'https://images.pexels.com/photos/2531188/pexels-photo-2531188.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');

-- Insert sample room bookings
INSERT INTO bookings (user_id, room_id, booking_reference, check_in_date, check_out_date, num_guests, total_price, first_name, last_name, email, phone, special_requests, payment_method, status) VALUES
(3, 1, 'BK-20250123-001', '2025-06-01', '2025-06-05', 2, 796.00, 'John', 'Doe', 'john@example.com', '+1234567892', 'Early check-in if possible.', 'credit_card', 'confirmed'),
(4, 2, 'BK-20250123-002', '2025-05-25', '2025-05-28', 2, 897.00, 'Sarah', 'Johnson', 'sarah@example.com', '+1234567893', 'High floor room preferred.', 'credit_card', 'confirmed'),
(5, 3, 'BK-20250123-003', '2025-07-10', '2025-07-15', 3, 2495.00, 'Michael', 'Smith', 'michael@example.com', '+1234567894', 'Celebration package for anniversary.', 'credit_card', 'pending'),
(3, 4, 'BK-20250123-004', '2025-06-15', '2025-06-20', 4, 1995.00, 'John', 'Doe', 'john@example.com', '+1234567892', 'Need extra towels and pillows.', 'paypal', 'confirmed'),
(4, 1, 'BK-20250123-005', '2025-08-05', '2025-08-10', 2, 995.00, 'Sarah', 'Johnson', 'sarah@example.com', '+1234567893', 'Late check-out requested.', 'credit_card', 'pending');

-- Insert sample service bookings
INSERT INTO service_bookings (user_id, service_id, booking_reference, service_date, service_time, num_people, selected_package, total_price, first_name, last_name, email, phone, special_requests, status) VALUES
(3, 1, 'SB-20250123-001', '2025-06-02', '14:00:00', 2, 'Couples Massage', 170.00, 'John', 'Doe', 'john@example.com', '+1234567892', 'Prefer female therapists.', 'confirmed'),
(4, 1, 'SB-20250123-002', '2025-05-26', '10:00:00', 1, 'Deluxe Facial', 85.00, 'Sarah', 'Johnson', 'sarah@example.com', '+1234567893', 'Sensitive skin, please use gentle products.', 'confirmed'),
(5, 3, 'SB-20250123-003', '2025-07-12', '11:00:00', 2, 'Pool Access with Cabana', 130.00, 'Michael', 'Smith', 'michael@example.com', '+1234567894', 'Celebration setup for anniversary.', 'pending'),
(3, 2, 'SB-20250123-004', '2025-06-16', '08:00:00', 1, 'Personal Training Session', 45.00, 'John', 'Doe', 'john@example.com', '+1234567892', 'Focus on strength training.', 'confirmed'),
(4, 4, 'SB-20250123-005', '2025-08-07', '15:00:00', 10, 'Business Meeting Package', 500.00, 'Sarah', 'Johnson', 'sarah@example.com', '+1234567893', 'Need projector and refreshments.', 'pending');

-- Insert sample table reservations
INSERT INTO table_reservations (user_id, table_id, reservation_reference, reservation_date, reservation_time, num_guests, full_name, email, phone, special_requests, status) VALUES
(3, 5, 'TR-20250123-001', '2025-06-02', '19:00:00', 6, 'John Doe', 'john@example.com', '+1234567892', 'Birthday celebration, need cake service.', 'confirmed'),
(4, 7, 'TR-20250123-002', '2025-05-27', '20:00:00', 4, 'Sarah Johnson', 'sarah@example.com', '+1234567893', 'Prefer quiet area for business discussion.', 'confirmed'),
(5, 9, 'TR-20250123-003', '2025-07-13', '18:30:00', 8, 'Michael Smith', 'michael@example.com', '+1234567894', 'Anniversary dinner, need special setup.', 'pending'),
(3, 11, 'TR-20250123-004', '2025-06-18', '19:30:00', 4, 'John Doe', 'john@example.com', '+1234567892', 'Window seating preferred.', 'confirmed'),
(4, 6, 'TR-20250123-005', '2025-08-06', '20:30:00', 2, 'Sarah Johnson', 'sarah@example.com', '+1234567893', 'Romantic dinner setup.', 'pending');

-- Insert sample contact messages
INSERT INTO contact_messages (name, email, subject, message, status) VALUES
('Robert Thompson', 'robert@example.com', 'Wedding Venue Inquiry', 'Hello, I am interested in booking your venue for a wedding reception in September 2025. Could you please provide information about your wedding packages and availability? Thank you.', 'new'),
('Emily Wilson', 'emily@example.com', 'Accessibility Information', 'Hi, I will be staying at your hotel next month and would like to know about the accessibility features available for wheelchair users. Do you have accessible rooms and what facilities are available? Thanks.', 'read'),
('David Chen', 'david@example.com', 'Lost Item Recovery', 'I believe I left my Rolex watch in room 302 during my stay last weekend (May 10-12, 2025). Can you please check if it was found by housekeeping? It has great sentimental value. Thank you.', 'responded'),
('Jennifer Lee', 'jennifer@example.com', 'Corporate Event Planning', 'Our company is planning a corporate retreat for 50 people in Q4 2025. We would need accommodation, meeting spaces, and catering. Can you send a proposal with available dates and packages?', 'new'),
('Thomas Brown', 'thomas@example.com', 'Feedback on Recent Stay', 'I recently stayed in your Executive Suite (May 1-5, 2025) and wanted to compliment your staff on the exceptional service, particularly the concierge who went above and beyond to help with dinner reservations.', 'archived');