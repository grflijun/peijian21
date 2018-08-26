<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!session_is_registered("user_name") || $_SESSION['user_name'] == "")
	{
		Header("Location: login.html");
	}
?>
<!DOCTYPE html>
<html lang="cmn-Hans">
<head>
<meta charset="utf-8">
<title>删除用户留言</title>
<link rel="stylesheet" href="../css/inner_style.css">
</head>

<body>

<?php
	if($_POST['items'] != "")
	{
		include("../include/linkopen.php");

		$sql = "delete from guest_msg";

		if($_POST['items'] != "0")
		{
			$sql = $sql . " where no in(" . $_POST['items'] .")";
		}

		$rs = mysql_query($sql, $link);

		include("../include/linkclose.php");

		Header("Location: msglist.php");
	}
?>

</body>
</html>
