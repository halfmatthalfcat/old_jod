<?php

	$con = mysqli_connect(/**/);

	$username = $_POST["username"];
	$pw = $_POST["password"];

	//echo $username . ":" . $pw;
	$sql_s = "call sp_validateLogin(\"" . $username . "\",\"" . $pw . "\")";
	//echo $sql_s;
	//call login check
	if(!$con){
		echo "connecion failed";
	}
	
	$q_a = mysqli_query($con, $sql_s);
	
	if(!$q_a) { echo "query failed: " . mysqli_error($con); }
	else { $row = mysqli_fetch_row($q_a); }

	if($row[0] == 0){
		echo "incorrect username/password (" . $row[0] . ")";
	} else {
		echo "login successful";
		session_start();
		if(session_id() != "" & isset($_SESSION["user"]) & $_SESSION["user"] == $username & time() - $_SESSION["login_time"] < 1800){
			echo "continuing session";
			//echo time() - $_SESSION["login_time"];
			header("Location: home.php");
		} else {
			echo "new session";
			$_SESSION["user"] = $username;
			$_SESSION["login_time"] = time();
			header("Location: home.php");
		}
	}

	

?>
