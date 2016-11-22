<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="editablegrid-2.0.1.css" media="screen"/>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css"><head>
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
<script src="jquery.base64.js"></script>
<script src="jquery-ui.js"></script>
<script src="jquery.form.js"></script>
<script src="editablegrid-2.0.1.js"></script>
<script src="FileSaver.js"></script>
<script src="jspdf.js"></script>
<script src="jspdf.plugin.addimage.js"></script>
<script src="logo.js"></script>
<script src="budget.js"></script>
<script>

$(function(){
	var description = $("#description"),
		purpose = $("#purpose"),
		quantity = $("#quantity"),
		unitPrice = $("#unitPrice"),
		allFields = $([]).add(description).add(purpose).add(quantity).add(unitPrice);
		
	$("#new_budget_item_dialog").dialog({
		autoOpen: false,
		height: 400,
		width: 400,
		modal: false,
		buttons: {
			"Create Budget Item": function() {
				var bValid = true;
				$("#accountID").val($("#account_select").val());
				//alert("accountID: " + accountID + ", drop val: " + $("#account_select").val());
				allFields.removeClass("ui-state-error");
				
				bValid = bValid && validateField(description, "description");
				bValid = bValid && validateField(purpose, "purpose");
				bValid = bValid && $.isNumeric(quantity.val());
				bValid = bValid && $.isNumeric(unitPrice.val());
				
				if(bValid){
					$("#newBudgetItemForm").ajaxSubmit({
						url: "add_budget_item.php",
						type: "post",
						success: function(responseText, statusText, xhr, $form){
							if(responseText == "1"){
								reloadBudget($("#accountID").val());
								$("#new_budget_item_dialog").dialog("close");
							} else {
								alert(responseText);
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
	
	$("#budget_item").click(function(){
		var sel = $("#account_select").val();
		if(sel != ""){
			$("#new_budget_item_dialog").dialog("open");
		} else { alert("Select an account before adding a budget item."); }
	});
	$("#invoice_item").click(function(){
		startInvoice(true);
	});
	$("#create_budget").click(function(){
		startInvoice(false);
	});

});

</script>

</head>

<body>

<div id="new_budget_item_dialog" title="Create a new budget item">
	<p class="validate_tips">All fields are required</p>
    <form id="newBudgetItemForm">
    	<fieldset class="dialog_fieldset">
        	<input type="hidden" name="accountID" id="accountID" />
        	<label for="description" class="block dialog_label">Description</label>
            <input type="text" name="description" id="description" class="text ui-widget-content ui-corner-all block" />
            <label for="purpose" class="block dialog_label">Purpose</label>
            <select name="purpose" id="purpose" class="block">
            	<option value="0">Credit</option>
                <option value="1">Debit</option>
                <option value="2">Time Logged</option>
                <option value="3">Budget</option>
            </select>
            <label for="quantity" class="block dialog_label">Quantity</label>
            <input type="text" name="quantity" id="quantity" class="text ui-widget-content ui-corner-all block" />
            <label for="unitPrice" class="block dialog_label">Unit Price</label>
            <input type="text" name="unitPrice" id="unitPrice" class="text ui-widget-content ui-corner-all block"  />
        </fieldset>
    </form>
</div>
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
                </tr>
        </table>
    </div>
    
    <div id="content">
        <table id="budget_bar">
            <tr>
                <td>Accounts: <select id="account_select"><option value="" selected="selected"></option></select></td>
            </tr>
            <tr>
                <td id="message"></td>
                <td id="budget_item">New Budget Item</td>
                <td id="invoice_item">Create Invoice</td>
                <td id="create_budget">Create Budget</td>
            </tr>
        </table>
        <div id="budget_wrapper"></div>
    </div>
</div>
</body>
</html>
