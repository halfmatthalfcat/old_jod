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

	$accountID = NewGuid();
	$accountName = $_POST["accountName"];
	$firstName = $_POST["firstName"];
	$lastName = $_POST["lastName"];
	$streetNumber = $_POST["streetNumber"];
	$streetName = $_POST["streetName"];
	$city = $_POST["city"];
	$state = $_POST["state"];
	$zipCode = $_POST["zipCode"];
	$homePhone =  $_POST["homePhone"];
	$cellPhone = $_POST["cellPhone"];
	$email = $_POST["email"];
	$notes = $_POST["notes"];
				
	$con = mysqli_connect(/**/);
				
	if(!$con){ echo "SQL Connection Error: " . mysqli_error($con); }
				
	$sql_a = "call sp_addAccount(\"" . $accountID . "\",\"" . $accountName . "\",\"" . $firstName . "\",\"" . $lastName . "\",\"" . $streetNumber . "\",\"" . $streetName . "\",\"" . $city . "\",\"" . $state . "\",\"" . $zipCode . "\",\"" . $homePhone . "\",\"" . $cellPhone . "\",\"" . $email . "\",\"" . $notes . "\")";
	
	//echo $sql_a;
	
	if($q_a = mysqli_query($con, $sql_a)){
		echo "1";
	} else { echo "0:" . mysqli_error($con); }

?>