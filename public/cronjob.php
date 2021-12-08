<?php //error_reporting(E_ALL);
define('DB_HOST', 'localhost');
define('DATABASE', 'dbxa9uekldxeyh');
define('USERNAME', 'uwz5iicmb8teu');
define('PASSWORD', 'ckqye6t66gkk');


$conn = mysqli_connect("localhost", USERNAME, PASSWORD, DATABASE);

//$con = new mysqli(DB_HOST,USERNAME,PASSWORD,DATABASE);

if ($conn->connect_errno) {
  echo "Failed to connect to MySQL: " . $conn->connect_error;
  exit();
}

// Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
}

define('IG_APP_ID', '142780604181049');
define('IG_APP_SECRET', 'dfa126313e78a42e3a8da2d9e1612752');
define('IG_APP_CALLBACK', 'https://instafeed.tjcg.in/callback.php');