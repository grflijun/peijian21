<?php
	session_start();

//    require_once('../include/db_def.php');
	require_once "../include/dbda.class.php";

	if(isset($_POST['btn_ok']))
	{
		$db = new DBDA();

		if($db->CreateTempDb())
		{
			$usr_name = $db->real_escape_string(trim($_POST['user_name']));
			$usr_pass = $db->real_escape_string($_POST['passwd']);

			if(!empty($usr_name) && !empty($usr_pass))
			{
				$sql_check = "select login_name from web_admin where login_name='$usr_name' and login_pwd=SHA('$usr_pass')";
				$rs_check = $db->temp_sql($sql_check);

				if($rs_check->num_rows == 1)
				{
					$_SESSION['ADMIN_NAME'] = $usr_name;

					Header("Location: admin_config.php");
				}
				else
				{
					Header("Location: error_login.html");
				}
			}
			else
			{
				echo('<script> alert("hahaha2");   </script>');
				Header("Location: error_login.html");
			}


			$db->DeleteTempDb();
		}

/*
		$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
			or die($str_inner_err . mysqli_error());

		$usr_name = mysqli_real_escape_string($link, trim($_POST['user_name']));
		$usr_pass = mysqli_real_escape_string($link, $_POST['passwd']);

		if(!empty($usr_name) && !empty($usr_pass))
		{
			$sql_sel = "select login_name from web_admin where login_name='$usr_name' and login_pwd=SHA('$usr_pass')";
			$result = mysqli_query($link, $sql_sel);

	    	if(mysqli_num_rows($result) == 1)
	    	{
				$_SESSION['ADMIN_NAME'] = $usr_name;

				Header("Location: admin_config.php");
	    	}
	    	else
	    	{
				Header("Location: error_login.html");
	    	}
		}
		else
		{
			Header("Location: error_login.html");
		}

		mysqli_close($link);
*/
	}
	else
	{
		echo('<script> alert("hahaha3");   </script>');
		Header("Location: error_login.html");
	}
?>
