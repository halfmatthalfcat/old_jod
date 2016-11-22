<?php

	$budgetItemID = $_POST["budgetItemID"];
	
	$con = mysqli_connect(/**/);

	if(!$con){ echo "SQL Connection Error: " . mysqli_error($con); }

	$sql_d = "CALL sp_deleteBudgetItem(\"" . $budgetItemID . "\")";
	
	//echo $sql_d;
	
	if($s_d = mysqli_query($con, $sql_d)){
		echo "1";
	} else { echo "0:" . mysqli_error($con); }

?>