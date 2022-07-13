# php_bug_tracker
A basic bug tracker / project management application. Using PHP for the backend, MySQL for the database, and Svelte for the frontend.

## Features:
- User accounts, authentication and authorization
- As a project leader, create and manage projects, add/remove bugs, add/remove posts, add/remove members
- As a project member, submit bugs with different severities, submit posts, announce that you are fixing a bug, announce that you have fixed a bug
- Dashboard showing most recent bugs and posts from your projects
- Post page with filtering based on project
- Bugs page with filtering based on project, severity and status
- Graph showing how many bugs a project has, and of what status they are (submitted / being worked on / fixed)

If you would like to try this app, run `npm init` in the app folder, 
then copy the contents of "mysql database data" to `[wherever your MySQL install is]/mysql/data/bug_tracker`.

This app was made to function with XAMPP, and due to using strict rather than relative pathing, will only work with XAMPP unless the file paths within the code are changed to suit whatever software package you intend to use (WAMP, LAMP, MAMP etc.)
