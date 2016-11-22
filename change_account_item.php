<?php

	$budgetItemID = $_POST["accountID"];
	$budgetItem = $_POST["accountItem"];
	$newValue = $_POST["newValue"];

	$con = mysqli_connect(/**/);

	if(!$con) { echo "SQL Connection Error: " . mysqli_error($con); }

	$sql_u = "CALL sp_changeAccountItem(\"" . $budgetItemID . "\",\"" . $budgetItem . "\",\"" . $newValue . "\")";

	if($q_u = mysqli_query($con, $sql_u)){
		echo "1";
	} else { echo "0: " . mysqli_error($con); }

	mysqli_free_result($q_u);
	mysqli_close($con);


?>
