<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" type="text/css" href="main.css" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>JOliverDecor</title>

<script src="jquery-2.0.3.js"></script>
<script>



</script>

</head>

<body>

<?php
	session_start();
	//echo time() - $_SESSION["login_time"];
	if(session_id() == "" | !isset($_SESSION["user"]) | !isset($_SESSION["login_time"])){
		//echo "not logged in";
		//header("Location: index.php");
	} else if (time() - $_SESSION["login_time"] >= 1800){
    	//echo "session expired";
		//echo time() - $_SESSION["login_time"];
        session_destroy();
		header("Location: index.php");
    }
?>

<div id="head_container">
    <div id="status_bar">
        <div id="login_container">
            <form name="login" action="login.php" method="post">
                <table id="login_table">
                    <tr>
                        <td>Email:</td>
                        <td><input type="text" name="username"/></td>
                        <td>Password:</td>
                        <td><input type="password" name="password"/></td>
                        <td><input id="login_button" type="submit" value="Login" /></td>
                     </tr>
                </table>
            </form> 	
        </div>
    </div>
    <div id="logo_container">
        <img id="logo" src="logo.png" />
    </div>
    
    <div id="nav_container">
      <table id="nav_table">
                <tr>
                    <td id="nav_item">
                        <a href="mailto:joliverdecor@gmail.com">Contact</a>
                    </td>
                    <td id="nav_item">
                        <a href="gallery.php">Gallery</a>
                    </td>
                </tr>
        </table>
    </div>

    <div id="content">

    </div>
</div>
</body>
</html>
