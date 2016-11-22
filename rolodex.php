<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" type="text/css" href="main.css" />
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
<script>

	$(document).ready(function(){
		$.getJSON("getRolodex.php", function(data){
			$.each(data, function(index, o){
				var option = $("<option/>").attr("value", o.rolodexID).text(o.email);		
				$("#rolodex_list").append(option);	
			});	
		});
	});
	
</script>

</head>

<body>

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
                            <a href="rolodex.php">Rolodex</a>
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
            <div id="rolodex_wrapper">
            	<select id="rolodex_list" size="6">
                </select>
            </div>
        </div>
    </div>
</div>
</body>
</html>
