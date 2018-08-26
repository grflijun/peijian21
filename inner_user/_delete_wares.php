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
<title>删除商品信息</title>
<link rel="stylesheet" href="../css/inner_style.css">
</head>

<body>

<?php
	if($_POST['items'] != "")
	{
		include("../include/linkopen.php");

		$link_back = "Location: user_config.php";

		$sql = "delete from ";
		if($_POST['ware_type'] == "big")
		{
			$sql = $sql."WareBigType";
			$link_back = "Location: bigtype.php";
		}
		else if($_POST['ware_type'] == "tiny")
		{
			$sql = $sql."WareTinyType";
			$link_back = "Location: tinytype.php";
		}
		else if($_POST['ware_type'] == "ware")
		{
			$sql = $sql."Wares";
			$link_back = "Location: wareunit.php";
		}
		else
		{
			echo("<h3>参数错误</h3>\n");
			exit();
		}

		if($_POST['items'] != "0")
		{
			$sql = $sql . " where no in(" . $_POST['items'] .")";
		}

		$rs = mysql_query($sql, $link);

		include("../include/linkclose.php");

		Header($link_back);
	}
?>

</body>
</html>
