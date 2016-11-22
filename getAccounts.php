<?php

		$con = mysqli_connect(/**/);

		if(!$con) { echo "Connection Error: " . mysqli_error($con); }
		
		$sql_s = "CALL sp_getAccounts()";
		
		if($q_s = mysqli_query($con, $sql_s)){
			$table_array = array(
								array("name" => "accountID", "label" => "Account ID", "datatype" => "string", "editable" => false),
								array("name" => "accountName", "label" => "Account Name", "datatype" => "string", "editable" => true),
								array("name" => "firstName", "label" => "First Name", "datatype" => "string", "editable" => true),
								array("name" => "lastName", "label" => "Last Name", "datatype" => "string", "editable" => true),
								array("name" => "streetNumber", "label" => "Street Number", "datatype" => "string", "editable" => true),
								array("name" => "streetName", "label" => "Street Name", "datatype" => "string", "editable" => true),
								array("name" => "city", "label" => "City", "datatype" => "string", "editable" => true),
								array("name" => "state", "label" => "State", "datatype" => "string", "editable" => true),
								array("name" => "zipCode", "label" => "Zip Code", "datatype" => "string", "editable" => true),
								array("name" => "homePhone", "label" => "Home Phone", "datatype" => "string", "editable" => true),
								array("name" => "cellPhone", "label" => "Cell Phone", "datatype" => "string", "editable" => true),
								array("name" => "email", "label" => "Email", "datatype" => "email", "editable" => true),
								array("name" => "notes", "label" => "Notes", "datatype" => "string", "editable" => true),
								array("name" => "action", "label" => "Actions", "datatype" => "html", "editable" => false)
								);
			$rolodex_array = array();
			for($i = 0; $i < mysqli_num_rows($q_s); $i++){
				$row = mysqli_fetch_row($q_s);
				$rolodex_array[$i] = array("id" => ($i + 1), "values" =>
										array(
											"accountID" => $row[0] == null ? "" : $row[0],
											"accountName" => $row[1] == null ? "" : $row[1],
											"password" => $row[2] == null ? "" : $row[2],
											"firstName" => $row[3] == null ? "" : $row[3],
											"lastName" => $row[4] == null ? "" : $row[4],
											"streetNumber" => $row[5] == null ? "" : $row[5],
											"streetName" => $row[6] == null ? "" : $row[6],
											"city" => $row[7] == null ? "" : $row[7],
											"state" => $row[8] == null ? "" : $row[8],
											"zipCode" => $row[9] == null ? "" : $row[9],
											"homePhone" => $row[10] == null ? "" : $row[10],
											"cellPhone" => $row[11] = null ? "" : $row[11],
											"email" => $row[12] == null ? "" : $row[12],
											"notes" => $row[13] == null ? "" : $row[13]));
			}
			echo json_encode(array("metadata" => $table_array, "data" => $rolodex_array));
		} else { echo "0"; }
		
		mysqli_free_result($q_s);
		mysqli_close($con);

?>