<?php
	session_start();

	if(isset($_POST['btn_ok']))
	{
		include("../include/linkopen.php");

		$usr_name = mysqli_real_escape_string($link, trim($_POST['user_name']));
		$usr_pass = mysqli_real_escape_string($link, $_POST['passwd']);

		if(!empty($usr_name) && !empty($usr_pass))
		{
			$sql = "select staff_id from staffs where out_use=0 and login_name='$usr_name' and login_pwd=SHA('$usr_pass')";
			$result = mysqli_query($link, $sql);

	    	if(mysqli_num_rows($result) == 1)
	    	{
				$row = mysqli_fetch_array($result);
				$_SESSION['STAFF_ID'] = $row[0];

				Header("Location: user_config.php");
	    	}
	    	else
	    	{
				Header("Location: error_log.html");
	    	}
		}
		else
		{
			Header("Location: error_log.html");
		}

		include("../include/linkclose.php");
	}
	else
	{
		Header("Location: error_log.html");
	}
?>
