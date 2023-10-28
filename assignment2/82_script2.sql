-- ---------
-- Part 1 SQL Updates
USE assign2db;

SELECT * FROM course;

-- Update course title for courses named 'Multimedia'
UPDATE course
SET coursename = 'Multimedia and Communications'
WHERE coursename = 'Multimedia';

SELECT * FROM ta;
SELECT * FROM hasworkedon;

-- Update 'hasworkedon' table for TAs whose first names start with 'R'
UPDATE hasworkedon
SET hours = 200
WHERE tauserid IN (SELECT tauserid FROM ta WHERE firstname LIKE 'R%');

SELECT * FROM course;
SELECT * FROM hasworkedon;

-- Part 2 SQL Inserts
-- Insert a new course
ALTER TABLE course MODIFY coursename VARCHAR(100);
INSERT INTO course (coursenum, coursename, level, year) VALUES ('2208', 'Computer Organization & Architecture', 2, 2023);

-- Insert new offerings for the course
INSERT INTO courseoffer (coid, numstudent, term, year, whichcourse)
VALUES ('01', 30, 'Spring', 2020, 'CS3319'), ('02', 40, 'Winter', 2021, 'CS3319'), ('03', 25, 'Fall', 2019, 'CS3319');

-- Insert a new TA
INSERT INTO ta (tauserid, firstname, lastname, studentnum, degreetype) VALUES ('93020463','Nancy','Ajram','1234567','PhD');

-- Insert worked hours for the new TA
INSERT INTO loves (tauserid, lcoursenum) VALUES ('93020463','CS3319');
SELECT * FROM course WHERE coursenum = 'CS3319';
INSERT INTO hasworkedon (tauserid, coid, hours) VALUES ('93020463', '01', 40);

-- Display the  inserted records to verify they were added
SELECT * FROM course WHERE coursenum = 'CS3319';
SELECT * FROM courseoffer WHERE whichcourse = 'CS3319';
SELECT * FROM ta WHERE tauserid = '93020463';
SELECT * FROM loves WHERE ltauserid = '93020463';

-- Part 3 SQL Queries

-- Query 1
-- Display last names of all TAs
SELECT lastname FROM ta;

-- Query 2
-- Display distinct last names from TAs
SELECT DISTINCT lastname FROM ta;

-- Query 3
-- Display all TAs sorted by first name in ascending order
SELECT * FROM ta ORDER BY firstname ASC;

-- Query 4
-- Display all TAs first and last name  doing a masters
SELECT firstname, lastname, tauserid FROM ta WHERE degreetype = 'Masters';

-- Query 5
-- Display details of all Database courses
SELECT coid, term, year, whichcourse FROM courseoffer INNER JOIN course ON courseoffer.whichcourse = course.coursenum
WHERE coursename LIKE '%Database%';

-- Query 6
-- Display course offerings that ran  before the course was created
SELECT * FROM courseoffer INNER JOIN course ON courseoffer.whichcourse = course.coursenum
WHERE courseoffer.year < course.year;

-- Query 7
-- Display course details for courses that are loved by anyone with the last name 'Geller'
SELECT course.coursename, course.coursenum FROM loves INNER JOIN ta ON loves.ltauserid = ta.tauserid
INNER JOIN course ON loves.lcoursenum = course.coursenum WHERE ta.lastname = 'Geller';

-- Query 8
-- Display the number of students who took CS1033
SELECT SUM(numstudent), course.coursename, course.coursenum FROM courseoffer INNER JOIN course ON courseoffer.whichcourse = course.coursenum
WHERE course.coursenum = 'CS1033';

-- Query 9
-- Display UNIQUE details of TAs who have been assigned to 1st year courses
SELECT DISTINCT ta.firstname, ta.lastname, course.coursenum FROM hasworkedon INNER JOIN ta ON hasworkedon.tauserid = ta.tauserid
INNER JOIN courseoffer ON hasworkedon.coid = courseoffer.coid INNER JOIN course ON courseoffer.whichcourse = course.coursenum WHERE course.level = 1;

-- Query 10
-- Display the TA who has worked the most hours for a course offering
SELECT ta.firstname, ta.lastname, MAX(hasworkedon.hours) AS MaxHours FROM hasworkedon INNER JOIN ta ON hasworkedon.tauserid = ta.tauserid
GROUP BY hasworkedon.tauserid ORDER BY MaxHours DESC LIMIT 1;

-- Query 11
-- Display courses that are not loved nor hated by any TA
SELECT coursenum FROM course WHERE coursenum NOT IN (SELECT lcoursenum FROM loves UNION SELECT hcoursenum FROM hates);

-- Query 12
-- Display the TAs who are assigned to more than 1 course offering
SELECT ta.firstname, ta.lastname, COUNT(hasworkedon.coid) FROM ta INNER JOIN hasworkedon ON ta.tauserid = hasworkedon.tauserid
GROUP BY ta.tauserid HAVING COUNT(hasworkedon.coid) > 1;

-- Query 13
-- Display unique details for TAs who love the courses they worked on
SELECT DISTINCT ta.firstname, ta.lastname, course.coursenum, course.coursename 
FROM ta 
INNER JOIN hasworkedon ON ta.tauserid = hasworkedon.tauserid 
INNER JOIN courseoffer ON hasworkedon.coid = courseoffer.coid
INNER JOIN course ON courseoffer.whichcourse = course.coursenum
INNER JOIN loves ON ta.tauserid = loves.tauserid
WHERE loves.coursenum = course.coursenum;

-- Query 14
-- Create a view to find out what course has been offered the most in the Fall term
DROP VIEW IF EXISTS FallCourses;
CREATE VIEW FallCourses AS SELECT whichcourse, COUNT(*) AS num_offers FROM courseoffer WHERE term = 'Fall' GROUP BY whichcourse;

SELECT course.coursenum, course.coursename, FallCourses.num_offers FROM course INNER JOIN FallCourses ON course.coursenum = FallCourses.whichcourse ORDER BY num_offers DESC LIMIT 1;

-- Query 15
-- Display first names of TAs in alphabetical order for those whose names contain letter 'e'
SELECT firstname FROM ta WHERE firstname LIKE '%e%' ORDER BY firstname;

-- Part 4 Views/Deletes
-- Create a view to display TAs who hate certain courses sorted by level
DROP VIEW IF EXISTS viewHatedCourses;
CREATE VIEW viewHatedCourses AS SELECT ta.firstname, ta.lastname, ta.tauserid, course.coursenum, course.coursename
FROM ta
INNER JOIN hates ON ta.tauserid = hates.htauserid
INNER JOIN course ON hates.hcoursenum = course.coursenum
ORDER BY course.level;

SELECT * FROM viewHatedCourses;

-- Display unique first name, last name, and courseid for TAs who work on courses they hate
SELECT DISTINCT ta.firstname, ta.lastname, courseoffer.coid FROM viewHatedCourses INNER JOIN hasworkedon ON hasworkedon.tauserid = viewHatedCourses.tauserid 
INNER JOIN courseoffer ON hasworkedon.coid = courseoffer.coid;

SELECT * FROM ta;

SELECT * FROM hates;

SET foreign_key_checks = 0;
-- Delete the TA with user id 'pbing'
DELETE FROM ta WHERE tauserid = 'pbing';

-- Verify it has been deleted
SELECT * FROM ta WHERE tauserid = 'pbing';

SELECT * FROM hates;

DELETE FROM ta WHERE tauserid = 'mgeller';

SET foreign_key_checks = 1;
-- Alter ta table to add a column named 'image' of type VARCHAR(200)
ALTER TABLE ta ADD image VARCHAR(200);

SELECT * FROM ta;

-- Update the image for the TA with user id 'mgeller' to set a specific image URL
UPDATE ta SET image = 'https://i.pinimg.com/originals/bf/85/8d/bf858d262ce992754e2b78042c9e0fe8.gif' WHERE tauserid = 'mgeller';

SELECT * FROM ta WHERE tauserid = 'mgeller';
