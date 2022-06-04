# Gym-website
A website to manage a gym, with the possibility to create custom courses. It uses a PostgreSQL database to save all the information required and from where to get them to build the various web pages. 

It supports 3 kinds of users: admin, registered users, and not registered ones. They have a different level of freedom: the admin can create a new course, add other admins, post news on the bulletin board, and see the number of users registered for each course on the specific page. A not registered user can only see 6 courses, while a registered one can see and subscribe to all of them if there are any places left. Once someone has logged in he can access his profile, where he can find the information that he put during the registration.

As of today, the website doesn't permit to cancel or edit users, courses, and admins.

The credentials that the site uses to access the DB are user 'www' with password 'tsw2022', while the DB name is 'tsw' and is run in localhost. To change their values you have to edit the file "connect db.php". By default the DB is empty so you can create at least an admin using the page "admin.php", not reachable by normal navigation on the site and is totally unsafe to use, so use it once and delete it. 

Watch out for the 'cf' field on the login and registration page, it is set to be used in Italy and all the checks are specific to this region.  

This website was made as a project for the exam "Tecnologie software per il web" at the University of Salerno (Unisa), in software engineering degree course.
