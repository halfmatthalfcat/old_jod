<?php

		$con = mysqli_connect(/**/);

		if(!$con) { echo "Connection Error: " . mysqli_error($con); }
		
		$sql_s = "CALL sp_getRolodex()";
		
		if($q_s = mysqli_query($con, $sql_s)){
			$rolodex_array = array();
			for($i = 0; $i < mysqli_num_rows($q_s); $i++){
				$row = mysqli_fetch_row($q_s);
				$rolodex_array[$i] = array(
											"rolodexID" => $row[0] == null ? "" : $row[0],
											"password" => $row[1] == null ? "" : $row[1],
											"firstName" => $row[2] == null ? "" : $row[2],
											"lastName" => $row[3] == null ? "" : $row[3],
											"streetNumber" => $row[4] == null ? "" : $row[4],
											"streetName" => $row[5] == null ? "" : $row[5],
											"city" => $row[6] == null ? "" : $row[6],
											"state" => $row[7] == null ? "" : $row[7],
											"zipCode" => $row[8] == null ? "" : $row[8],
											"homePhone" => $row[9] == null ? "" : $row[9],
											"cellPhone" => $row[10] = null ? "" : $row[10],
											"email" => $row[11] == null ? "" : $row[11],
											"notes" => $row[12] == null ? "" : $row[12]);
			}
			echo json_encode($rolodex_array);
		} else { echo "0"; }
		
		mysqli_free_result($q_s);
		mysqli_close($con);

?>