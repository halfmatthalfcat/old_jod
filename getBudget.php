<?php
	
	$accountID = $_POST["accountID"];

	$con = mysqli_connect(/**/);

	if(!$con) { echo "Connection Error: " . mysqli_error($con); }
	
	$sql_s = "CALL sp_getBudgetItems(\"" . $accountID . "\")";
	
	//echo $sql_s;
	
	if($q_s = mysqli_query($con, $sql_s)){
		$table_array = array(
							array("name" => "budgetItemID", "label" => "ID", "datatype" => "string", "editable" => false),
							array("name" => "date", "label" => "Date", "datatype" => "date", "editable" => true),
							array("name" => "description", "label" => "Description", "datatype" => "string", "editable" => true),
							array("name" => "purpose", "label" => "Purpose", "datatype" => "string", "editable" => true, "values" => 
								array("Credit", "Debit", "Time Logged", "Budget")),
							array("name" => "quantity", "label" => "Quantity", "datatype" => "integer", "editable" => true),
							array("name" => "unitPrice", "label" => "Unit Price", "datatype" => "double($, 2, dot, comma, 1)", "editable" => true),
							array("name" => "totalPrice", "label" => "Total", "datatype" => "double($, 2, dot, comma, 1)", "editable" => false),
							array("name" => "invoice", "label" => "Invoice", "datatype" => "boolean", "editable" => true),
							array("name" => "action", "label" => "Actions", "datatype" => "html", "editable" => false)
							);
		$budget_array = array();
		for($i = 0; $i < mysqli_num_rows($q_s); $i++){
			$row = mysqli_fetch_row($q_s);
			$budget_array[$i] = array("id" => ($i + 1), "values" =>
									array(
										"budgetItemID" => $row[0] == null ? "" : $row[0],
										"accountID" => $row[1] == null ? "" : $row[1],
										"date" => $row[2] == null ? "" : $row[2],
										"quantity" => $row[3] == null ? "" : $row[3],
										"description" => $row[4] == null ? "" : $row[4],
										"unitPrice" => $row[5] == null ? "" : $row[5],
										"totalPrice" => $row[6] == null ? "" : $row[6],
										"purpose" => $row[7] == null ? "" : $row[7]));										
		}
		echo json_encode(array("metadata" => $table_array, "data" => $budget_array));
	} else { echo "0"; }

	mysqli_free_result($q_s);
	mysqli_close($con);

?>