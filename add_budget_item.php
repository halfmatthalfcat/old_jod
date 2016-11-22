<?php

	function NewGuid() { 
    $s = strtoupper(md5(uniqid(rand(),true))); 
    	$guidText = 
        substr($s,0,8) . '-' . 
        substr($s,8,4) . '-' . 
        substr($s,12,4). '-' . 
        substr($s,16,4). '-' . 
        substr($s,20); 
    	return $guidText;
	}
	
	$budgetItemID = NewGuid();
	$accountID = $_POST["accountID"];
	$description = $_POST["description"];
	$date = date('m/j/Y');
	$purpose = $_POST["purpose"];
	$quantity = $_POST["quantity"];
	$unitPrice = $_POST["unitPrice"];
	$totalPrice = $quantity * $unitPrice;
	
	$con = mysqli_connect(/**/);
				
	if(!$con){ echo "SQL Connection Error: " . mysqli_error($con); }
	
	$sql_a = "CALL sp_addBudgetItem(\"" . $budgetItemID . "\",\"" . $accountID . "\",\"" . $date . "\"," . $quantity . ",\"" . $description . "\"," . $unitPrice . "," . $totalPrice . ",\"" . $purpose . "\")";
	
	//echo $sql_a;
	
	if($q_a = mysqli_query($con, $sql_a)){
		echo "1";
	} else { echo "0"; }
	
	mysqli_free_result($q_a);
	mysqli_close($con);


?>
