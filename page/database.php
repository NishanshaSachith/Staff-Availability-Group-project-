<?php
	$server_name = "localhost";
	$username = "csc210user";
	$server_password = "CSC210!";
	$data_base = "group6";

	try{
		$conn = mysqli_connect($server_name,$username,$server_password,$data_base);
		if(!$conn){
			echo "<h1>Unable to connect</h1>";
		}
	}catch(mysqli_sql_exception){
		echo "<h1>Unknown Error</h1>";
	}
?>
