<?php

	print_r($_POST);

	switch($_POST["submit"]){
		case "New":
			header("Location: accounts.php");
			break;
		case "Save":
			if(!isset($_POST["accountID"])){
				$accountID = com_create_guid();
				$accountName = $_POST["accountName"];
				$firstName = $_POST["firstName"];
				$lastName = $_POST["lastName"];
				$streetNumber = $_POST["streetNumber"];
				$streetName = $_POST["streetName"];
				$city = $_POST["city"];
				$state = $_POST["state"];
				$zipCode = $_POST["zipCode"];
				$homePhone = $_POST["homePHone"];
				$cellPhone = $_POST["cellPhone"];
				$email = $_POST["email"];
				$notes = $_POST["notes"];

				$con = mysqli_connect(/**/);

				if(!$con){ echo "SQL Connection Error: " . mysqli_error($con); }

				$sql_a = "call sp_addAccount(" . $accountID . "," . $accountName . "," . $firstName . "," . $lastName . "," . $streetNumber . "," . $streetName . "," . $city . "," . $state . "," . $zipCode . "," . $homePhone . "," . $cellPhone . "," . $email . "," . $notes . ")";

				echo $sql_a;

			} else {
				$accountID = $_POST["accountID"];
				$accountName = $_POST["accountName"];
				$firstName = $_POST["firstName"];
				$lastName = $_POST["lastName"];
				$streetNumber = $_POST["streetNumber"];
				$streetName = $_POST["streetName"];
				$city = $_POST["city"];
				$state = $_POST["state"];
				$zipCode = $_POST["zipCode"];
				$homePhone = $_POST["homePHone"];
				$cellPhone = $_POST["cellPhone"];
				$email = $_POST["email"];
				$notes = $_POST["notes"];

				$con = mysqli_connect(/**/);

				if(!$con){ echo "SQL Connection Error: " . mysqli_error($con); }

				$sql_u = "call sp_addAccount(" . $accountID . "," . $accountName . "," . $firstName . "," . $lastName . "," . $streetNumber . "," . $streetName . "," . $city . "," . $state . "," . $zipCode . "," . $homePhone . "," . $cellPhone . "," . $email . "," . $notes . ")";

				echo $sql_u;
			}
			break;
		case "Delete":
			break;
	}


?>
