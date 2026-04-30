CREATE DATABASE edusync;
USE edusync;

CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  label VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(50) NOT NULL,
  lastname VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE classes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  classroom_number VARCHAR(20) NOT NULL
);

CREATE TABLE courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  description TEXT,
  total_hours INT NOT NULL,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dateofbirth DATE NOT NULL,
  student_number VARCHAR(50) NOT NULL UNIQUE,
  user_id INT NOT NULL UNIQUE,
  class_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (class_id) REFERENCES classes(id)
);

CREATE TABLE enrollments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  course_id INT NOT NULL,
  enrolled_at DATE NOT NULL,
  status ENUM('Actif', 'Terminé') DEFAULT 'Actif',
  UNIQUE (student_id, course_id),
  FOREIGN KEY (student_id) REFERENCES students(id),
  FOREIGN KEY (course_id) REFERENCES courses(id)
);

INSERT INTO roles (label)
VALUES ('Admin'), ('Prof'), ('Student');

INSERT INTO users (firstname, lastname, email, password, role_id)
VALUES
('Ali', 'Admin', 'ali@admin.com', '123456', 1),
('Sara', 'Prof', 'sara@prof.com', '123456', 2),
('Yassine', 'Student', 'yassine@student.com', '123456', 3),
('Fatima', 'Student', 'fatima@student.com', '123456', 3),
('Omar', 'Student', 'omar@student.com', '123456', 3);
INSERT INTO users (firstname, lastname, email, password, role_id)
VALUES
('Hamza', 'Student', 'hamza@student.com', '123456', 3),
('Salma', 'Student', 'salma@student.com', '123456', 3),
('Karim', 'Student', 'karim@student.com', '123456', 3);
INSERT INTO classes (name, classroom_number)
VALUES
('Développeur Web 2026', 'A1'),
('Data Science 2026', 'B1'),
('Réseau 2026', 'C1');

INSERT INTO classes (name , classroom_number)
VALUES
('data structure 2025' , 'B2'),
('data analyse 2026' , 'C1'),
('Telecomunication 2026' , 'G2');
INSERT INTO courses (title, description, total_hours, user_id)
VALUES
('HTML/CSS', 'Intro web', 40, 2),
('JavaScript', 'JS programming', 60, 2),
('Database', 'SQL', 50, 2);
 
INSERT INTO courses (title, description, total_hours, user_id)
VALUES ('PHP', 'Backend', 50, 6);  
INSERT INTO students (dateofbirth, student_number, user_id, class_id)
VALUES
('2000-01-10', 'STU001', 3, 1),
('2001-02-15', 'STU002', 4, 1),
('2002-03-20', 'STU003', 5, 2);

INSERT INTO students (dateofbirth, student_number, user_id, class_id)
VALUES 
('2002-02-12','STU007',9,3),
('2002-04-23','STU008',7,4),
('2004-10-13','STU009',8,5);
INSERT INTO enrollments (student_id, course_id, enrolled_at, status)
VALUES
(1, 1, '2026-04-10', 'Actif'),
(2, 1, '2026-04-10', 'Actif'),
(3, 2, '2026-04-11', 'Actif');

INSERT INTO enrollments (student_id, course_id, enrolled_at, status)
VALUES
(24,1,'2026-04-12','Actif'),
(25,2,'2026-04-12','Actif'),
(26,3,'2026-04-12','Actif');
-- INSERT IGNORE INTO roles (label)
-- VALUES 
-- ('Admin'),
-- ('Prof'),
-- ('Student');

-- INSERT INTO users (firstname, lastname, email, password, role_id)
-- VALUES
-- ('Ali', 'Admin', 'ali2@admin.com', '123456', 1),
-- ('Sara', 'Prof', 'sara2@prof.com', '123456', 2),
-- ('Yassine', 'Student', 'yassine2@student.com', '123456', 3);

-- INSERT INTO classes (name, classroom_number)
-- VALUES
-- ('Développeur Web 2026', 'A1'),
-- ('Data Science 2026', 'B1'),
-- ('Réseau 2026', 'C1');


-- INSERT INTO courses (title, description, total_hours, user_id)
-- VALUES
-- ('HTML/CSS', 'Introduction au web', 40, 4),
-- ('JavaScript', 'Programmation JS', 60, 4),
-- ('Base de données', 'SQL et modélisation', 50, 4);

-- INSERT INTO users (firstname, lastname, email, password, role_id)
-- VALUES
-- ('Fatima', 'Student', 'fatima3@student.com', '123456', 2),
-- ('Omar', 'Prof', 'omar3@student.com', '123456', 2),
-- ('Amin', 'Prof', 'amin2@gmail.com', '2345', 2);


-- INSERT INTO students (dateofbirth, student_number, user_id, class_id)
-- VALUES
-- ('2000-01-10', 'STU0011', 1, 1),
-- ('2001-02-15', 'STU0021', 2, 2),
-- ('2002-03-20', 'STU0031', 3, 3);

-- INSERT INTO enrollments (student_id, course_id, enrolled_at, status)
-- VALUES
-- (1, 1, '2026-04-10', 'Actif'),
-- (2, 2, '2026-04-11', 'Actif'),
-- (3, 3, '2026-04-12', 'Terminé');


