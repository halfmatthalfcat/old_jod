<?php

	$budgetItemID = $_POST["budgetItemID"];
	$budgetItem = $_POST["budgetItem"];
	$newValue = $_POST["newValue"];

	$con = mysqli_connect(/**/);

	if(!$con) { echo "SQL Connection Error: " . mysqli_error($con); }

	$sql_u = "CALL sp_changeBudgetItem(\"" . $budgetItemID . "\",\"" . $budgetItem . "\",\"" . $newValue . "\")";

	if($q_u = mysqli_query($con, $sql_u)){
		echo "1";
	} else { echo "0"; }

	mysqli_free_result($q_u);
	mysqli_close($con);


?>
