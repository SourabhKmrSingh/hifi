<?php

class Database

{

	private $db_host = "localhost";

	private $db_user = "root";

	private $db_pass = "";

	private $db_name = "hifihospitality";

	

	// private $db_host = "localhost";

	// private $db_user = "skrealtech_user";

	// private $db_pass = '~GM0}6I)Jb)Z';

	// private $db_name = "skrealtech_main";

	

	private $connection;

	private static $query;

	

	public static function myConnect()

	{

		if(!self::$query)

		{

			self::$query = new self();

		}

		return self::$query;

	}

	

	private function __construct()

	{

		$this->connection = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

		if(mysqli_connect_error())

		{

			trigger_error("Failed to connect to MySQL: " . mysql_connect_error(), E_USER_ERROR);

		}

	}

	private function __clone() { }

	public function getConnection()

	{

		return $this->connection;

	}

}



$db = Database::myConnect();

$connect = $db->getConnection();

//$result = $connect->query($regQuery);

?>