================================================================
****************************************************************
USER MANUAL
****************************************************************
================================================================

================================
********************************
OVERVIEW
********************************
================================

================================
********************************
SETUP
********************************
================================

Test server setup steps:

1. Make a MySQL database and user with access to this database.
2. git clone  to a folder on server.
3. Set the constants in db.php for PHP to access the database.
   * DATABASE_PATH = the database server
   * DATABASE_USERNAME = database user
   * DATABASE_PASSWORD = database user password
   * DATABASE_NAME = database name
   * DATABASE_TYPE = 'mysql'
4. Run the TARS.sql script with the database user.
5. Optionally, run the TARS-testdata.sql script with the database user to fill the database with test users and data we've been testing with:
   * All the test users created have the password of their user type ("student", "professor", or "staff").
6. Make sure the webserver can read files (for me,  chmod go+r  to files and  chmod go+rx  to directories).

Pull when necessary to update. Recommended steps (to keep the credentials in db.php):

1. git stash
2. git pull
3. git stash pop
4. Changes to TARS.sql may require a fresh set of tables. If so, re-run TARS.sql.

