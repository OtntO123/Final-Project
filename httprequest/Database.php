<?php	namespace httprequest;

//Using singleton to initialize PHP Data Objects for database connection
class Database {

	static private $conn;

	static public function connect() {	//check whether $conn is initialized and initialize it
		if(!self::$conn){
			new Database();
		}
		return self::$conn;
	}

	function __construct() {	//initialize PDO object and Test connectivity
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
