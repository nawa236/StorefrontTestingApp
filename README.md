# StorefrontTestingApp
Team 4 Project for CS499 Fall 2019

Our project was to design a mock e-commerce website with the ability for an admin to toggle the occurance of intentional bugs. These bugs can be assigned on a per-user basis meaning that the expierence of all register users can be entirely unique. This was to allow this program to be used a training, evaluating or demonstration tool related to software quality assurance.

This code is designed to be run on a LAMP Stack (Linux,Apache,MySql,PHP)

<b>Getting Started</b><br>
Our project was developed for use on a LAMP stack (Linux, Apache, MySQL, PHP). Assuming that this requirement has been met, simply navigate to the directory that you wish to use and from the terminal type “sudo git clone https://github.com/nawa236/StorefrontTestingApp.git”. This will download all of the required files for execution and you may continue to the next step.

<b>Database Initialization</b><br>
This project relies on the existence of a mysql database accessible by the server where the code is executing. In order to function, the database connection attribute will need to be updated as appropriate for your environment. Both in the file “dbConnect.php” and “database.php” are variable definitions for servername, username, password and database name. These variable will need to be manually changed to match the appropriate login credential for your mysql. Once the values have been altered, navigate to <Your_url prefix>/StorefrontTestingApp/index.php. Assuming that the message “Welcome to the Employee Store.” is displayed, the database settings have been properly provided and the initialization of the database for use is complete.

Access to any internal page requires a login so the first page to be visited should be  <Your_url prefix>/StorefrontTestingApp/register.php. Email verification is used, but will need configured, additional instructions are present in the Manual.pdf, but to quickly verfiy the account it is possible to login to the MySQL database and change the attribute "status" in the authentication table to 2 (Database name is EmployeeTraining), in order to quickly ensure the application is functioning as expected.

<b>For more information about this project please review the materials in the "Project Documentation" folder with special attention to the Manual.pdf.</b>
