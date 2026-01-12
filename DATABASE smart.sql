CREATE DATABASE smart;
USE smart;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'organizer', 'participant') NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    organizer_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    location VARCHAR(150),
    category VARCHAR(100),
    capacity INT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (organizer_id) REFERENCES users(user_id)
        ON DELETE CASCADE
);

CREATE TABLE event_approvals (
    approval_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    admin_id INT NOT NULL,
    approval_status ENUM('approved', 'rejected') NOT NULL,
    remarks VARCHAR(255),
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (event_id) REFERENCES events(event_id)
        ON DELETE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES users(user_id)
        ON DELETE CASCADE
);

CREATE TABLE event_registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    participant_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('registered', 'cancelled') DEFAULT 'registered',

    FOREIGN KEY (event_id) REFERENCES events(event_id)
        ON DELETE CASCADE,
    FOREIGN KEY (participant_id) REFERENCES users(user_id)
        ON DELETE CASCADE,

    UNIQUE (event_id, participant_id)
);

CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    participant_id INT NOT NULL,
    attendance_status ENUM('present', 'absent') DEFAULT 'absent',
    marked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (event_id) REFERENCES events(event_id)
        ON DELETE CASCADE,
    FOREIGN KEY (participant_id) REFERENCES users(user_id)
        ON DELETE CASCADE,

    UNIQUE (event_id, participant_id)
);

CREATE TABLE feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    participant_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comments TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (event_id) REFERENCES events(event_id)
        ON DELETE CASCADE,
    FOREIGN KEY (participant_id) REFERENCES users(user_id)
        ON DELETE CASCADE
);
