<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['ADMIN_NAME']) || empty($_SESSION['ADMIN_NAME']))
	{
		Header("Location: admin_start.html");
	}
?>
<!DOCTYPE html>
<html lang="cmn-Hans">
<head>
<meta charset="utf-8">
<title>系统设置</title>
<link rel="stylesheet" href="../css/inner_style.css">
</head>

<body>
<div>
	<ul class="menu_list">
		<li><a href="admin_adminpwd.php">修改自己的密码</a></li>
		<li><a href="admin_userpwd.php">修改用户密码</a></li>
		<li><a href="staff/index.php">员工账号管理</a></li>
		<li><a href="admin_end.php">安全退出</a></li>
	</ul>
</div>

</body>
</html>
