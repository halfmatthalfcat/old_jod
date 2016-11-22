<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" type="text/css" href="main.css" />
<link rel="stylesheet" type="text/css" href="folio/galleria.folio.css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>JOliverDecor</title>

<?php
	session_start();
	//echo time() - $_SESSION["login_time"];
	if(session_id() == "" | !isset($_SESSION["user"]) | !isset($_SESSION["login_time"])){
		//echo "not logged in";
		//header("Location: index.php");
	} else if (time() - $_SESSION["login_time"] >= 1800){
    	//echo "session expired";
		//echo time() - $_SESSION["login_time"];
        //session_destroy();
		//header("Location: index.php");
    }
?>

<script src="jquery-2.0.3.js"></script>
<script src="galleria-1.3.5.js"></script>
<script src="folio/galleria.folio.min.js"></script>
<script src="galleria.picasa.js"></script>
<script>

	Galleria.loadTheme("folio/galleria.folio.min.js");
	Galleria.run(".galleria",
		{ picasa: "useralbum:joliverdecor/joliverdecor",
		  height: .9 });

</script>

</head>

<body>
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
            } else {
                echo "<div id=\"status_bar\">";
                echo "<div id=\"login_container\">";
                echo "<form name=\"login\" action=\"login.php\" method=\"post\">";
                echo "<table id=\"login_table\">";
                echo "<tr>";
                echo "<td>Email:</td>";
                echo "<td><input type=\"text\" name=\"username\"/></td>";
                echo "<td>Password:</td>";
                echo "<td><input type=\"password\" name=\"password\"/></td>";
                echo "<td><input id=\"login_button\" type=\"submit\" value=\"Login\" /></td>";
                echo "</tr>";
                echo "</table>";
                echo "</form>";
                echo "</div>";
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
                    <?php
						if(isset($_SESSION["user"])){
							echo "<td id=\"nav_item\">";
							echo "<a href=\"accounts.php\">Accounts</a>";
							echo "</td>";
							echo "<td id=\"nav_item\">";
							echo "<a href=\"budget.php\">Budgets</a>";
							echo "</td>";
						}
					?>
                </tr>
        </table>
    </div>
    
    <div id="content">
        <div id="galleria_container">
            <div class="galleria"></div>
        </div>
    </div>
</div>
</body>
</html>
