-- Create database
CREATE DATABASE IF NOT EXISTS kpr_college;
USE kpr_college;

-- Create students table
CREATE TABLE IF NOT EXISTS students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    rollno VARCHAR(50) UNIQUE NOT NULL,
    department VARCHAR(100),
    year INT,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create certificates table
CREATE TABLE IF NOT EXISTS certificates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    certificate_path VARCHAR(500) NOT NULL,
    download_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Create email logs table
CREATE TABLE IF NOT EXISTS email_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    recipient_email VARCHAR(255),
    subject VARCHAR(255),
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('sent', 'failed', 'bounced') DEFAULT 'sent',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Create download logs table
CREATE TABLE IF NOT EXISTS download_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    certificate_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(500),
    downloaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (certificate_id) REFERENCES certificates(id) ON DELETE CASCADE
);

-- Sample data (replace with actual student data)
INSERT INTO students (name, email, rollno, department, year) VALUES
('John Doe', 'john@example.com', 'BCA001', 'Computer Applications', 1),
('Jane Smith', 'jane@example.com', 'BCA002', 'Computer Applications', 1),
('Alex Johnson', 'alex@example.com', 'BCA003', 'Computer Applications', 1);

-- Create indexes for faster queries
CREATE INDEX idx_email ON students(email);
CREATE INDEX idx_rollno ON students(rollno);
CREATE INDEX idx_student_id ON certificates(student_id);
