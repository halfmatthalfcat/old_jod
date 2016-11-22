<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="editablegrid-2.0.1.css" media="screen"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>JOliverDecor</title>

<?php
	session_start();
	//echo time() - $_SESSION["login_time"];
	if(session_id() == "" | !isset($_SESSION["user"]) | !isset($_SESSION["login_time"])){
		echo "not logged in";
		header("Location: index.php");
	} else if (time() - $_SESSION["login_time"] >= 1800){
    	echo "session expired";
		echo time() - $_SESSION["login_time"];
        session_destroy();
		header("Location: index.php");
    }
?>

<script src="jquery-2.0.3.js"></script>
<script src="jquery-ui.js"></script>
<script src="jquery.form.js"></script>
<script src="editablegrid-2.0.1.js"></script>
<script src="accounts.js"></script>

<script>
$(function(){
	var accountName = $("#accountName"),
		email = $("#email"),
		allFields = $([]).add(accountName).add(email);
		
	$("#new_account_dialog").dialog({
		autoOpen: false,
		height: 500,
		width: 400,
		modal: true,
		buttons: {
			"Create Account": function (){
				var bValid = true;
				allFields.removeClass("ui-state-error");
				
				bValid = bValid && validateField(accountName, "accountName");
				bValid = bValid && validateField(email, "email");
				
				if(bValid){
					$("#newAccountForm").ajaxSubmit({
						url: "add_account.php",
						type: "post",
						success: function(responseText, statusText, xhr, $form){
							if(responseText == "1"){
								location.reload(true);
							}
						}
					});
				}
				else {}
			},
			Cancel: function(){ $(this).dialog("close"); }
		}
	});
	
	function validateField(o, n){
		if(o.val() == ""){ 
			o.addClass("ui-state-error");
			return false; 
		}
		else { return true; }
	}
	
	$("#account_item").click(function(){
		$("#new_account_dialog").dialog("open");	
	});
});
</script>

</head>

<body>

<div id="new_account_dialog" title="Create a new account">
	<p class="validate_tips">Account name and email required</p>
    
    <form id="newAccountForm">
    	<fieldset>
        	<label for="accountName" class="block">Account Name</label>
            <input type="text" name="accountName" id="accountName" class="text ui-widget-content ui-corner-all block" />
            <label for="email" class="block">Email</label>
            <input type="text" name="email" id="email" class="text ui-widget-content ui-corner-all block"  />
            <label for="firstName" class="block">First Name</label>
            <input type="text" name="firstName" id="firstName" class="text ui-widget-content ui-corner-all block"  />
            <label for="lastName" class="block">Last Name</label>
            <input type="text" name="lastName" id="lastName" class="text ui-widget-content ui-corner-all block"  />
            <label for="streetNumber" class="block">Street Number</label>
            <input type="text" name="streetNumber" id="streetNumber" class="text ui-widget-content ui-corner-all block"  />
            <label for="streetName" class="block">Street Name</label>
            <input type="text" name="streetName" id="streetName" class="text ui-widget-content ui-corner-all block"  />
            <label for="city" class="block">City</label>
            <input type="text" name="city" id="city" class="text ui-widget-content ui-corner-all block"  />
            <label for="state" class="block">State</label>
            <input type="text" name="state" id="state" class="text ui-widget-content ui-corner-all block"  />
            <label for="zipCode" class="block">Zip Code</label>
            <input type="text" name="zipCode" id="zipCode" class="text ui-widget-content ui-corner-all block"  />
            <label for="phoneNumber" class="block">Phone Number</label>
            <input type="text" name="phoneNumber" id="phoneNumber" class="text ui-widget-content ui-corner-all block"  />
            <label for="cellPhone" class="block">Cell Phone</label>
            <input type="texxt" name="cellPhone" id="cellPhone" class="text ui-widget-content ui-corner-all block"  />
        </fieldset>
    </form>
</div>

<div id="whole_container">
    <div id="head_container">
        <div id="status_bar">
            <?php
            
                if(isset($_SESSION["user"])){
                    echo "<div id=\"logged_in\">";
                    echo "<form action=\"logout.php\">";
                    echo "Welcome, " . $_SESSION["user"];
                    echo " | ";
                    echo "<input id=\"logout_button\" type=\"submit\" value=\"Logout\"\\>";
                    echo "</form>";
                    echo "</div>";
                }
            
            ?>
        </div>
        <div id="logo_container">
          <img id="logo" src="logo.png" />
        </div>
        <div id="nav_container">
          <table id="nav_table">
                    <tr>
                        <td id="nav_item">
                        	<a href="home.php">Home</a>
                        </td>
                        <td id="nav_item">
                        	<a href="gallery.php">Gallery</a>
                        </td>
                        <td id="nav_item">
                            <a href="accounts.php">Accounts</a>
                        </td>
                        <td id="nav_item">
                            <a href="budget.php">Budgets</a>
                        </td>
                        <td id="nav_item">
                            <a href="invoice.php">Invoices</a>
                        </td>
                    </tr>
            </table>
        </div>
        
        <div id="content">
        	<table id="account_bar">
            	<tr>
                	<td id="message"></td>
                    <td id="account_item">New Account</td>
                </tr>
            </table>
            <div id="accounts_wrapper"></div>
        </div>
    </div>
</div>
</body>
</html>
