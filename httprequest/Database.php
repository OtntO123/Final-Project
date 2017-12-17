<?php	namespace httprequest;

class Database {

	static private $conn;

	static public function connect() {	//check whether $conn instantiated and instantiate it
		if(!self::$conn){
			new Database();
		}
		return self::$conn;
	}

	function __construct() {	//Set PDO object and Test connectivity
		try {
			self::$conn = new \PDO(databasesoftware . ':host=' . hostwebsite .';dbname=' . database, username, password);
			self::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
			//echo 'Connection Successful To Database.<hr>';
		}
		catch (PDOException $e) {
			echo "Connection Error To Database: " . $e->getMessage() . "<hr>";
		}
	}
}
