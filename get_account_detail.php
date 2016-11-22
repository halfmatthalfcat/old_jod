<?php

	$accountID = $_POST["accountID"];
	
	$con = mysqli_connect(/**/);

	if(!$con) { echo "Connection Error: " . mysqli_error($con); }
	
	$sql_s = "CALL sp_getAccountDetail(\"" . $accountID . "\")";
	
	if($q_s = mysqli_query($con, $sql_s)){
		$row = mysqli_fetch_row($q_s);
		echo json_encode(array(
			"accoundID" => $row[0],
			"accountName" => $row[1],
			"password" => $row[2],
			"firstName" => $row[3],
			"lastName" => $row[4],
			"streetNumber" => $row[5],
			"streetName" => $row[6],
			"city" => $row[7],
			"state" => $row[8],
			"zipCode" => $row[9],
			"homePhone" => $row[10],
			"cellPhone" => $row[11],
			"email" => $row[12]
		));
	} else { echo "0"; }
	
	mysqli_free_result($q_s);
	mysqli_close($con);


?>