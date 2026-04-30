INSERT INTO roles (label)
VALUES 
('Admin'),
('Prof'),
('Student');

INSERT INTO users (firstname, lastname, email, password, role_id)
VALUES
('Ali', 'Admin', 'ali@admin.com', '123456', 1),
('Sara', 'Prof', 'sara@prof.com', '123456', 2),
('Yassine', 'Student', 'yassine@student.com', '123456', 3);

INSERT INTO classes (name, classroom_number)
VALUES
('Développeur Web 2026', 'A1'),
('Data Science 2026', 'B1'),
('Réseau 2026', 'C1');


INSERT INTO courses (title, description, total_hours, user_id)
VALUES
('HTML/CSS', 'Introduction au web', 40, 2),
('JavaScript', 'Programmation JS', 60, 2),
('Base de données', 'SQL et modélisation', 50, 2);

INSERT INTO users (firstname, lastname, email, password, role_id)
VALUES
('Fatima', 'Student', 'fatima@student.com', '123456', 3),
('Omar', 'Student', 'omar@student.com', '123456', 3);


INSERT INTO students (dateofbirth, student_number, user_id, class_id)
VALUES
('2000-01-10', 'STU001', 3, 1),
('2001-02-15', 'STU002', 4, 2),
('2002-03-20', 'STU003', 5, 3);

INSERT INTO enrollments (student_id, course_id, enrolled_at, status)
VALUES
(1, 1, '2026-04-10', 'Actif'),
(2, 2, '2026-04-11', 'Actif'),
(3, 3, '2026-04-12', 'Terminé');
