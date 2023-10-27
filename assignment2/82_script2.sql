-- ---------
-- Part 1 SQL Updates
UPDATE hasworkedon ...

SELECT * FROM course;

-- Update course title for courses named 'Multimedia'
UPDATE course
SET title = 'Multimedia and Communications'
WHERE title = 'Multimedia';

SELECT * FROM ta;
SELECT * FROM hasworkedon;

-- Update 'hasworkedon' table for TAs whose first names start with 'R'
UPDATE hasworkedon
SET hours_worked = 200
WHERE ta_id IN (SELECT id FROM ta WHERE first_name LIKE 'R%');

SELECT * FROM course;
SELECT * FROM hasworkedon;

-- Part 2 SQL Inserts
-- Insert a new course
INSERT INTO course (course_id, title, department, year_offered) VALUES ('CS3319', 'Introduction to Databases', 'Computer Science', 2023);

-- Insert new offerings for the course
INSERT INTO course_offering (offering_id, course_id, year, term) VALUES ('01', 'CS3319', 2020, 'Spring'), ('02','CS3319',2021,'Winter'),('03','CS3319',2019,'Fall');

-- Insert a new TA
INSERT INTO ta (student_number, user_id, first_name, last_name, degree) VALUES ('930204933','1234567','Nancy','Ajram','PhD');

-- Insert worked hours for the new TA
INSERT INTO hasworkedon (ta_id, offering_id, hours_worked) VALUES ('930204933','01',200),('930204933','02',200),('930204933','03',200);

-- Display the  inserted records to verify they were added
SELECT * FROM course WHERE course_id = 'CS3319';
SELECT * FROM course_offering WHERE course_id = 'CS3319';
SELECT * FROM ta WHERE student_number = '930204933';
SELECT * FROM hasworkedon WHERE ta_id = '930204933';

-- Part 3 SQL Queries

-- Query 1
-- Display last names of all TAs
SELECT last_name FROM ta;

-- Query 2
-- Display distinct last names from TAs
SELECT DISTINCT last_name FROM ta;

-- Query 3
-- Display all TAs sorted by first name in ascending order
SELECT * FROM ta ORDER BY first_name ASC;

-- Query 4
-- Display all TAs first and last name  doing a masters
SELECT first_name, last_name, user_id FROM ta WHERE degree = 'Masters';

-- Query 5
-- Display details of all Database courses
SELECT offering_id, term, year, course_id FROM course_offering WHERE course_id IN (SELECT course_id FROM course WHERE title LIKE '%Database%');

-- Query 6
-- Display course offerings that ran  before the course was created
SELECT * FROM course_offering co INNER JOIN course c ON co.course_id = c.course_id WHERE co.year < c.year_offered;

-- Query 7
-- Display course details for courses that are loved by anyone with the last name 'Geller'
SELECT c.title, c.course_id FROM course c INNER JOIN hasworkedon hw ON c.course_id = hw.course_id INNER JOIN ta t ON hw.ta_id = t.student_number WHERE t.last_name = 'Geller';

-- Query 8
-- Display the number of students who took CS1033
SELECT SUM(students_enrolled), 'CS1033', c.title FROM course_offering co INNER JOIN course c ON co.course_id = c.course_id WHERE c.course_id = 'CS1033';

-- Query 9
-- Display UNIQUE details of TAs who have been assigned to 1st year courses
SELECT DISTINCT t.first_name, t.last_name, c.course_id FROM ta t INNER JOIN hasworkedon hw ON t.student_number = hw.ta_id INNER JOIN course c ON hw.course_id = c.course_id WHERE c.level = 1;

-- Query 10
-- Display the TA who has worked the most hours for a course offering
SELECT t.first_name, t.last_name, hw.hours_worked, c.course_id FROM hasworkedon hw INNER JOIN ta t ON hw.ta_id = t.student_number INNER JOIN course c ON hw.course_id = c.course_id ORDER BY hw.hours_worked DESC LIMIT 1;

-- Query 11
-- Display courses that are not loved nor hated by any TA
SELECT c.title, c.course_id FROM course c WHERE c.course_id NOT IN (SELECT course_id FROM hasworkedon WHERE love = 1 OR hate = 1);

-- Query 12
-- Display the TAs who are assigned to more than 1 course offering
SELECT t.first_name, t.last_name, COUNT(hw.offering_id) FROM ta t INNER JOIN hasworkedon hw ON t.student_number = hw.ta_id GROUP BY hw.ta_id HAVING COUNT(hw.offering_id) > 1;

-- Query 13
-- Display unique details for TAs who love the courses they worked on
SELECT DISTINCT t.first_name, t.last_name, c.course_id, c.title FROM ta t INNER JOIN hasworkedon hw ON t.student_number = hw.ta_id INNER JOIN course c ON hw.course_id = c.course_id WHERE hw.love = 1;

-- Query 14
-- Create a view to find out what course has been offered the most in the Fall term
CREATE VIEW FallCourses AS SELECT course_id, COUNT(*) AS num_offers FROM course_offering WHERE term = 'Fall' GROUP BY course_id;
SELECT c.course_id, c.title, fc.num_offers FROM course c INNER JOIN FallCourses fc ON c.course_id = fc.course_id ORDER BY fc.num_offers DESC LIMIT 1;

-- Query 15
-- Display first names of TAs in alphabetical order for those whose names contain letter 'e'
SELECT first_name FROM ta WHERE first_name LIKE '%e%' ORDER BY first_name;

-- Part 4 Views/Deletes
-- Create a view to display TAs who hate certain courses sorted by level
CREATE VIEW viewhatedcourses AS
SELECT t.first_name, t.last_name, t.user_id, c.course_id, c.title
FROM ta t
INNER JOIN hates h ON t.student_number = h.ta_id
INNER JOIN course c ON h.course_id = c.course_id
ORDER BY c.level;

SELECT * FROM viewhatedcourses;

-- Display unique first name, last name, and course_id for TAs who work on courses they hate
SELECT DISTINCT t.first_name, t.last_name, c.course_id
FROM viewhatedcourses t
INNER JOIN hasworkedon hw ON hw.ta_id = t.user_id
INNER JOIN course c ON hw.course_id = c.course_id;

SELECT * FROM ta;

SELECT * FROM hates;

-- Delete the TA with user_id 'pbing'
DELETE FROM ta WHERE user_id = 'pbing';
-- Verify it has been deleted
SELECT * FROM ta WHERE user_id = 'pbing';

SELECT * FROM hates;

DELETE FROM ta WHERE user_id = 'mgeller';

-- Alter ta table to add a column named 'image' of type VARCHAR(200)
ALTER TABLE ta ADD COLUMN image VARCHAR(200);

SELECT * FROM ta;

-- Update the image for the TA with user_id 'mgeller' to set a specific image URL
UPDATE ta SET image = 'https://i.pinimg.com/originals/bf/85/8d/bf858d262ce992754e2b78042c9e0fe8.gif' WHERE user_id = 'mgeller';

SELECT * FROM ta WHERE user_id = 'mgeller';

