<?php

		$con = mysqli_connect(/**/);

		if(!$con) { echo "Connection Error: " . mysqli_error($con); }

		$sql_s = "CALL sp_getAccountsMinimal()";

		//echo $sql_s;

		if($q_s = mysqli_query($con, $sql_s)){
			$accounts = array();
			for($i = 0; $i < mysqli_num_rows($q_s); $i++){
				$row = mysqli_fetch_row($q_s);
				$accounts[$i] = array(
									"accountID" => $row[0],
									"accountName" => $row[1]);
			}
			echo json_encode($accounts);
		} else { echo "0: " . mysqli_error($con); }

		mysqli_free_result($q_s);
		mysqli_close($con);


?>
