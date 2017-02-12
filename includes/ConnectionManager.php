<?php

class ConnectionManager {

	private $server = "localhost";
	private $username = "root";
	private $password = "";
	private $database = "angular_db";
	private $connection = null;
	private $result = null;
	private $userdb = null;

	private static $cm = null;

	private function __construct() {
		$this->connect();
	}

	public static function getInstance() {
		if(self::$cm == null) self::$cm = new ConnectionManager();
		return self::$cm;
	}

	public function connect() {
		$this->connection = mysqli_connect($this->server, $this->username, $this->password, $this->database);
		if (mysqli_errno($this->connection)) {
			echo "Failed to connect to the database server!";
			exit();
		}
		$this->userdb = mysqli_select_db($this->connection, $this->database);
		if (!$this->userdb) {
			echo "Database Error: Database not found! --> " . mysqli_errno($this->connection);
			exit();
		}
	}

	public function disconnect() {
		mysqli_close($this->connection);
	}

	public function query($query) {
		if (!($this->result = mysqli_query($this->connection, $query))) {
			echo "Database Error: Query execution failed! --> " . mysqli_error($this->connection);
		}
		return $this->result;
	}

	public function begin() {
		mysqli_query($this->connection, "BEGIN");
	}

	public function commit($check) {
		if (!$check) {
			mysqli_query($this->connection, "ROLLBACK");
		} else {
			mysqli_query($this->connection, "COMMIT");
		}
		return $check;
	}

	public function transactionQuery() {
		mysqli_query($this->connection, "BEGIN");
		$queries = func_get_args();
		$check = 1;
		foreach ($queries as $query) {
			$this->query($query);
			if (!$this->result) {
				$check = 0;
			}
		}
		if ($check == 0) {
			mysqli_query($this->connection, "ROLLBACK");
		} else {
			mysqli_query($this->connection, "COMMIT");
		}
		return $check == 0 ? false : true;
	}

	public function fetch() {
		$row = mysqli_fetch_assoc($this->result);
		return $row;
	}

	public function fetchAll() {
		while ($row = mysqli_fetch_assoc($this->result)) {
			$rows[] = $row;
		}
		$this->result->close();
		return empty($rows) ? array() : $rows;
	}

	public function count($result) {
		return mysqli_num_rows($result);
	}

	public function getDB() {
		return $this->connection;
	}

	public function getResult() {
		return $this->result;
	}

}

?>