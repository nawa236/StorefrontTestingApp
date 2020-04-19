<?php

class Database{

    public $servername = 'localhost';
    public $username = 'store';
    public $password = 'finalproject'; // use your own username and password for the server.
    public $dbname = 'EmployeeTraining';

public static function openServer(){
    $db = new Database();
    // set error handling
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $dbversion = 0.1;
    $connection = new mysqli($db->servername, $db->username, $db->password);

    if($connection -> connect_error){
	    echo "Error connecting to database - " + $connection->connect_error;
    }

    return $connection;
}

public static function buildDatabase(){
    // check to see if database exists, if not create the database
    $db = new Database();
    $connection = $db->openServer();
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'EmployeeTraining'";
    if($result = mysqli_query($connection, $query)){
	    $row = mysqli_fetch_assoc($result);
	    if($row == null){
		    $query = "CREATE Database EmployeeTraining
				    CHARACTER SET utf8
				    COLLATE utf8_unicode_ci;";
		    $result = mysqli_query($connection, $query);
		    $connection = new mysqli($db->servername, $db->username, $db->password, $db->dbname);
		    if($connection -> connect_error){
			    echo "Error connection to database.";
		    }
		$db->buildTables($connection);
	    }
    $db->populateTables();
    }
}

public static function populateTables(){
    // check to see if the store databases are populated, and then populate tables
    $db = new Database();
    $tables = [ "size", "color","category","product", "bug"];

    foreach($tables as $t){
        $query = "SELECT * FROM $t;";
        $connection = new mysqli($db->servername, $db->username, $db->password, $db->dbname);
        if($connection -> connect_error){
            echo "Error connection to database.";
        }
        if($result = mysqli_query($connection, $query)){
            $row  = mysqli_num_rows($result);
            if($row < 1){
                $db->populateTable($connection, $t);
            }
        }
    }
}

// the following functions set the individual table code with constraints
public static function productTable(){
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

public static function sizeTable(){
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
            hash VARCHAR(32) NOT NULL,
            status TINYINT(1),
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
            discount VARCHAR(50) DEFAULT 0,
			PRIMARY KEY (id),
			FOREIGN KEY fk_customer (custid) REFERENCES customer(id) ON UPDATE RESTRICT
			);";

    return $query;
}

function orderProductsTable(){
    $query = "CREATE TABLE order_products(
              oid INT(11) NOT NULL,
              pid INT(11) NOT NULL,
              quantity INT(11) NOT NULL,
              FOREIGN KEY fk_order (oid) REFERENCES orders(id) ON UPDATE RESTRICT,
              FOREIGN KEY fk_product (pid) REFERENCES product(id) ON UPDATE RESTRICT
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
			sdate DATE,
            edate DATE,
			PRIMARY KEY (id),
			FOREIGN KEY fk_bugid (bugid) REFERENCES bug(id) ON UPDATE CASCADE,
			FOREIGN KEY fk_userid (userid) REFERENCES authentication(id) ON UPDATE CASCADE
			);";

    return $query;
}

function configurationTable(){
    $query = "CREATE TABLE configuration (
			id INT(11) NOT NULL AUTO_INCREMENT,
			parameter VARCHAR(50),
            value VARCHAR(50),
			PRIMARY KEY (id)
			);";

    return $query;
}

// build all database tables from the table functions
public function buildTables($connection) {
    $db = new Database();
    $functions = [ "size" => "sizeTable", "color" => "colorTable", "category" => "categoryTable",
        "product" => "productTable", "authentication" => "authenticationTable", "customer" => "customerTable", "order" => "orderTable", "orderproduct" => "orderProductsTable", "bug" => "bugTable", "assignment" => "assignmentTable"];

    foreach($functions as $func=>$value){
        $sql = call_user_func(array($db,$value));
        if(mysqli_query($connection, $sql) === FALSE){
            echo mysqli_error($connection);
        }
    }
}

public function populateTable($connection, $table){
    $schema = mysqli_query($connection, "DESCRIBE $table;");
    $sql = "INSERT INTO $table (";
    $types = "";
    foreach($schema as $field){
        if($field["Field"] != "id"){
            $sql .= $field["Field"] . ",";
            switch (substr($field["Type"],0,3)):
                case "int":
                    $types .= "i";
                    break;
                case "dec":
                    $types .= "d";
                    break;
                default:
                    $types .= "s";
            endswitch;
        }
    }
    $sql = rtrim($sql, ',') . ") VALUES (";
    $count = 1;
    if(file_exists("resources/$table.csv")){
        if(($fhandle = fopen("resources/$table.csv", "r")) !== FALSE){
            while (($data = fgetcsv($fhandle, 0, ",")) !== FALSE) {
                if($data != null){
                    $is = ($table == "product" ? 0 : 1);
                    if($count == 1){
                        for($i = $is; $i < count($data) ; $i++){
                            $sql .= "?,";
                        }
                        $sql = rtrim($sql, ',') . ");";
                        $count++;
                    }
                    $query = mysqli_prepare($connection, $sql);
                    $params = array();
                    array_push($params,$types);
                    for($i = $is; $i < count($data); $i++){
                        if($data[$i] == null) { $data[$i] = " "; }
                        $params[] = & $data[$i];
                    }
                    call_user_func_array(array($query, 'bind_param'), $params);
                    mysqli_stmt_execute($query);
                }
            }
        }
    }

}

// administrative functions
public function generateUserList(){
    $db = new Database();
    $connection = new mysqli($db->servername, $db->username, $db->password, $db->dbname);
    $query = "SELECT id, concat(lname, ', ', fname) as name FROM customer ORDER BY lname";
    $userlist = array();
    if($result = mysqli_query($connection, $query, MYSQLI_USE_RESULT)){
        while($row = mysqli_fetch_array($result)){
            array_push($userlist, $row);
        }
    }
    mysqli_close($connection);
    return $userlist;
}

public function generateBugList(){
    $db = new Database();
    $connection = new mysqli($db->servername, $db->username, $db->password, $db->dbname);
    $query = "SELECT id, concat(name, ' - ', functional_area) as name, description FROM bug ORDER BY functional_area";
    $buglist = array();
    if($result = mysqli_query($connection, $query, MYSQLI_USE_RESULT)){
        while($row = mysqli_fetch_array($result)){
            array_push($buglist, $row);
        }
    }
    mysqli_close($connection);
    return $buglist;
    }

public function runQuery($query){
    $db = new Database();
    $connection = new mysqli($db->servername, $db->username, $db->password, $db->dbname);
    if($result = mysqli_query($connection, $query)){
        $count = mysqli_num_rows($result);
        return $count;
    }else{
        return -1;
    }
}

public function runDataQuery($query){
    $db = new Database();
    $connection = new mysqli($db->servername, $db->username, $db->password, $db->dbname);
    if($result = mysqli_query($connection, $query)){
        $data = array();
        while($row = mysqli_fetch_row($result)){
            array_push($data, $row);
        }
        return $data;
    }else{
        return -1;
    }
}

public function runInsert($query){
    $db = new Database();
    $connection = new mysqli($db->servername, $db->username, $db->password, $db->dbname);
    if($result = mysqli_query($connection, $query)){
        $count = mysqli_affected_rows($connection);
        return $count;
    }else{
        return -1;
    }
}

public function runUpdate($query){
    $db = new Database();
    $connection = new mysqli($db->servername, $db->username, $db->password, $db->dbname);
    if($result = mysqli_query($connection, $query)){
        $count = mysqli_affected_rows($connection);
        return $count;
    }else{
        return -1;
    }
}
}
?>
