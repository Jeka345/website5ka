<?
	$host = 'localhost';
	$user = 'root';
	$password = '';
	$database = '5ka';
 	$base = mysqli_connect($host, $user, $password, $database);
	$select_base = mysqli_select_db($base, $database);
?>