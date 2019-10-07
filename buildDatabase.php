<?php

// set error handling
ini_set('display_errors',1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = 'localhost';
$username = 'root';
$password = 'TriBugApp'; // use your own username and password for the server.

$dbversion = 0.1;
$connection = new mysqli($servername, $username, $password);

if($connection -> connect_error){
	echo "Error connecting to database - " + $connection->connect_error;
}

// check to see if database exists, if not create the database
$query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'EmployeeTraining'";
if($result = mysqli_query($connection, $query)){
	$row = mysqli_fetch_assoc($result);
	if($row == null){
		$query = "CREATE Database EmployeeTraining
				CHARACTER SET utf8
				COLLATE utf8_unicode_ci;";
		$result = mysqli_query($connection, $query);
		$dbname = 'EmployeeTraining';
		$connection = new mysqli($servername, $username, $password, $dbname);
		if($connection -> connect_error){
			echo "Error connection to database.";
		}
		buildTables($connection);
	}
}

// the following functions set the individual table code with constraints
function productTable(){
    $query = "CREATE TABLE product (
			id INT(11) NOT NULL AUTO_INCREMENT,
			name VARCHAR(50),
			description VARCHAR(255),
			sku VARCHAR(20),
			price DECIMAL(6,2),
			inventory INT(11),
			category INT(11),
			color INT(11),
			size INT(11),
			PRIMARY KEY (id),
			INDEX index_sku (sku),
			FOREIGN KEY fk_category (category) REFERENCES category(id) ON UPDATE CASCADE,
			FOREIGN KEY fk_size (size) REFERENCES size(id) ON UPDATE CASCADE,
			FOREIGN KEY fk_color (color) REFERENCES color (id) ON UPDATE CASCADE
			);";

    return $query;
}

function colorTable(){
    $query = "CREATE TABLE color (
			id INT(11) NOT NULL AUTO_INCREMENT,
			description VARCHAR(50),
			hex CHAR(6),
			rgba VARCHAR(20),
			PRIMARY KEY (id)
			);";

    return $query;
}

function sizeTable(){
    $query = "CREATE TABLE size (
			id INT(11) NOT NULL AUTO_INCREMENT,
			code VARCHAR(5),
			type VARCHAR(10),
			PRIMARY KEY (id)
			);";

    return $query;
}

function categoryTable(){
    $query = "CREATE TABLE category (
			id INT(11) NOT NULL AUTO_INCREMENT,
			name VARCHAR(50),
			PRIMARY KEY (id)
			);";

    return $query;
}

function customerTable(){
    $query = "CREATE TABLE customer (
			id INT(11) NOT NULL AUTO_INCREMENT,
			fname VARCHAR(40),
			mname VARCHAR(40),
			lname VARCHAR(40),
			address1 VARCHAR(50),
			address2 VARCHAR(50),
			city VARCHAR(30),
			state_province VARCHAR(20),
			postalcode VARCHAR(10),
			email VARCHAR(50) NOT NULL UNIQUE,
			PRIMARY KEY (id),
			UNIQUE KEY (email)
			);";

    return $query;
}

function authenticationTable(){
    $query = "CREATE TABLE authentication (
			id INT(11) NOT NULL AUTO_INCREMENT,
			password TEXT NULL,
			email VARCHAR(50) NOT NULL UNIQUE,
			type TINYINT(1),
			PRIMARY KEY (id),
			UNIQUE KEY (email)
			);";

    return $query;
}

function orderTable(){
    $query = "CREATE TABLE orders (
			id INT(11) NOT NULL AUTO_INCREMENT,
			orderDate DATE,
			products VARCHAR(255),
			status VARCHAR(10),
			custid INT(11) NOT NULL,
			PRIMARY KEY (id),
			FOREIGN KEY fk_customer (custid) REFERENCES customer(id) ON UPDATE RESTRICT
			);";

    return $query;
}

function bugTable(){
    $query = "CREATE TABLE bug (
			id INT(11) NOT NULL AUTO_INCREMENT,
			name VARCHAR(50),
			functional_area VARCHAR(50),
			description TEXT,
			codeblock TEXT,
			PRIMARY KEY (id)
			);";

    return $query;
}

function assignmentTable(){
    $query = "CREATE TABLE assignment (
			id INT(11) NOT NULL AUTO_INCREMENT,
			bugid INT(11) NOT NULL,
			userid INT(11) NOT NULL,
			adate DATE,
			PRIMARY KEY (id),
			FOREIGN KEY fk_bugid (bugid) REFERENCES bug(id) ON UPDATE CASCADE,
			FOREIGN KEY fk_userid (userid) REFERENCES authentication(id) ON UPDATE CASCADE
			);";

    return $query;
}

// build all database tables from the table functions
function buildTables($connection) {
    $functions = [ "size" => "sizeTable", "color" => "colorTable", "category" => "categoryTable",
        "product" => "productTable", "authentication" => "authenticationTable", "customer" => "customerTable", "order" => "orderTable", "bug" => "bugTable", "assignment" => "assignmentTable"];

    foreach($functions as $func=>$value){
        $sql = call_user_func($value);
        if(mysqli_query($connection, $sql) === FALSE){
            echo mysqli_error($connection);
        }
    }
}
?>